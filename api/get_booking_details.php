<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

$ref_no = $_GET['ref_no'] ?? '';
if (empty($ref_no)) {
    echo json_encode(['success' => false, 'message' => 'Missing booking reference']);
    exit;
}

try {
    // Get booking details
    $stmt = $pdo->prepare("
        SELECT b.*, br.branch_name, 
        IF(b.booking_beautician > 0, CONCAT(s.staff_fname, ' ', s.staff_lname), 'Any Beautician') as beautician_name
        FROM oc_booking b
        LEFT JOIN oc_branch br ON b.booking_branch = br.branch_id
        LEFT JOIN oc_staff s ON b.booking_beautician = s.staff_id
        WHERE b.booking_refno = ?
        LIMIT 1
    ");
    $stmt->execute([$ref_no]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        echo json_encode(['success' => false, 'message' => 'Booking not found']);
        exit;
    }

    // Get services
    $stmtSvc = $pdo->prepare("
        SELECT bps.*, p.prdt_name as service_name
        FROM oc_booking_person_services bps
        LEFT JOIN oc_product p ON bps.service_id = p.prdt_id
        WHERE bps.booking_id = ?
    ");
    $stmtSvc->execute([$booking['booking_ID']]);
    $servicesRaw = $stmtSvc->fetchAll(PDO::FETCH_ASSOC);

    $services = [];
    foreach ($servicesRaw as $s) {
        $services[] = [
            'name' => $s['service_name'],
            'duration' => $s['service_time'],
            'price' => $s['service_price']
        ];
    }

    $paymentMode = ($booking['payment_type'] == 5) ? 'HitPay' : 'Pay at store';

    // If HitPay and status=completed, verify with HitPay API, update payment status to 1 if not already, and send email/sms
    $status = $_GET['status'] ?? '';
    $hitpay_ref = $_GET['hitpay_ref'] ?? '';
    
    if ($paymentMode === 'HitPay' && $status === 'completed' && $booking['payment_status'] == 3 && !empty($hitpay_ref)) {
        
        $hitpaySandbox = filter_var(getenv('HITPAY_SANDBOX'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
        $hitpayApiKey = getenv('HIT_PAY_API_KEY') ?: '';

        $apiUrl = $hitpaySandbox 
            ? 'https://api.sandbox.hit-pay.com/v1/payment-requests/' . $hitpay_ref
            : 'https://api.hit-pay.com/v1/payment-requests/' . $hitpay_ref;

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'X-BUSINESS-API-KEY: ' . $hitpayApiKey
            ]
        ]);
        $hitpayResRaw = curl_exec($ch);
        curl_close($ch);
        $hitpayData = json_decode($hitpayResRaw, true);

        // Only proceed if HitPay server confirms the payment is actually completed
        if (isset($hitpayData['status']) && $hitpayData['status'] === 'completed') {
            // Update to Paid and Upcoming
            $upd = $pdo->prepare("UPDATE oc_booking SET payment_status = 1, booking_status = 1, status = 1 WHERE booking_ID = ?");
            $upd->execute([$booking['booking_ID']]);

            // Auto-Generate POS Bill for HitPay
            $booking_id = $booking['booking_ID'];
            $bill_session = 'booking_' . $booking_id;
            
            // Check if bill already exists
            $stmtCheckBill = $pdo->prepare("SELECT billid FROM oc_bill WHERE booking_ID = ?");
            $stmtCheckBill->execute([$booking_id]);
            
            if ($stmtCheckBill->rowCount() == 0) {
                $branch_id = $booking['booking_branch'];
                $customer_id = $booking['customers_id'];
                $price = $booking['price'];
                $discount_amount = $booking['discount_amount'] ?? 0;
                $modeofpay = $booking['payment_type'] ?? 5; // 5 for HitPay
                $date = date('Y-m-d');
                $datetime = date('Y-m-d H:i:s');

                // Generate billrefno
                $stmtBranch = $pdo->prepare("SELECT branch_code FROM oc_branch WHERE branch_id = ?");
                $stmtBranch->execute([$branch_id]);
                $branchCode = $stmtBranch->fetchColumn() ?: 'RUP';
                $customer_initials = strtoupper(substr($branchCode, 0, 3));
                
                $check_odate = date('d');
                $check_month = date('m');
                $check_year = date('Y');
                
                $stmtLastBill = $pdo->prepare("SELECT billrefno FROM oc_bill WHERE branch_id = ? AND DATE(billdate) = DATE(?) ORDER BY billid DESC LIMIT 1");
                $stmtLastBill->execute([$branch_id, $date]);
                $lastRef = $stmtLastBill->fetchColumn();
                
                if ($lastRef) {
                    $parts = explode('-', $lastRef);
                    $seq = (int)end($parts);
                    $seq++;
                    $bill_ref_no = $customer_initials . $check_odate . $check_month . $check_year . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
                } else {
                    $bill_ref_no = $customer_initials . $check_odate . $check_month . $check_year . '-00001';
                }

                $discount_id = $booking['discount_id'] ?? 0;
                $coupon_code = '';
                if ($discount_id > 0) {
                    $stmtCoupon = $pdo->prepare("SELECT discount_code FROM oc_discounts WHERE discount_id = ?");
                    $stmtCoupon->execute([$discount_id]);
                    $coupon_code = $stmtCoupon->fetchColumn() ?: '';
                }

                // Insert into oc_bill with status=3, paid=totalamount, au_id=6
                $stmtCreateBill = $pdo->prepare("INSERT INTO oc_bill (au_id, bill_type, branch_id, billrefno, customerid, booking_ID, billqty, billdate, modeofpay, bill_session, bill_createdon, status, paid, subtotal, totalamount, coupon_code, coupon_discount, discount_amt) VALUES (6, '3', ?, ?, ?, ?, '0', ?, ?, ?, ?, '3', ?, ?, ?, ?, ?, '0')");
                $stmtCreateBill->execute([$branch_id, $bill_ref_no, $customer_id, $booking_id, $date, $modeofpay, $bill_session, $datetime, $price, $price, $price, $coupon_code, $discount_amount]);
                $bill_id = $pdo->lastInsertId();

                // Insert Items into oc_billitem
                $service_ids = explode(',', $booking['booking_services']);
                $service_prices = explode(',', $booking['booking_services_price']);
                $booking_beautician = $booking['booking_beautician'];
                
                $billqty = 0;
                $subtotal = 0;
                
                $stmtInsertItem = $pdo->prepare("INSERT INTO oc_billitem (billid, branch_id, billrefno, item_id, assign_beautician, item_price, item_quantity, billitem_createdon, billitem_session) VALUES (?, ?, ?, ?, ?, ?, '1', ?, ?)");
                
                foreach ($service_ids as $index => $item_id) {
                    $item_id = trim($item_id);
                    if (!$item_id) continue;
                    $item_price = isset($service_prices[$index]) ? trim($service_prices[$index]) : 0;
                    
                    $stmtInsertItem->execute([$bill_id, $branch_id, $bill_ref_no, $item_id, $booking_beautician, $item_price, $datetime, $bill_session]);
                    $billqty++;
                    $subtotal += $item_price;
                }
                
                $bill_total_final = $subtotal - $discount_amount;
                $stmtUpdateTotals = $pdo->prepare("UPDATE oc_bill SET billqty = ?, subtotal = ?, totalamount = ?, paid = ? WHERE billid = ?");
                $stmtUpdateTotals->execute([$billqty, $subtotal, $bill_total_final, $bill_total_final, $bill_id]);
            }

        // Send Email & SMS
        require_once 'smtp_functions.php';
        require_once 'email_template.php';

        $appointment_time_display = date('h:i A', strtotime($booking['booking_starttime']));
        $sms_msg = "Just a reminder! Your beauty appointment is coming up at {$appointment_time_display}. See you soon - Rupinis";
        $mobile = $booking['booking_phone'] ?? '';
        if (!empty($mobile)) {
            // Note: If country code wasn't saved with phone, it might be raw. 
            // In process_booking we save mobile. Assuming country code is there or we just send it.
            // For now, assume it's correct. Onewaysms expects just numbers.
            sendOnewaySms($mobile, $sms_msg);
        }

        $email = $booking['booking_email'] ?? '';
        if (!empty($email)) {
            $services_names_only = array_map(function($s) { return $s['name']; }, $services);
            $services_formatted = implode(', ', $services_names_only);
            $email_html = getAppointmentConfirmationEmailTemplate(
                $booking['booking_name'], 
                $booking['booking_date'], 
                $booking['booking_starttime'], 
                $booking['booking_endtime'], 
                $booking['branch_name'], 
                $booking['beautician_name'], 
                $services_formatted, 
                $booking['booking_refno'], 
                $booking['booking_totalprice'], 
                'HitPay (Online)'
            );
            $sender_email = getenv('SEND_FROM_EMAIL') ?: 'app.journeygenie@gmail.com';
            $sender_name = getenv('SEND_FROM_NAME') ?: 'Rupinis';

            // Send Customer Notification
            $subject = "Rupini's - Payment Successful & Appointment Confirmed";
            smtpEmailConfig($email, '', $sender_email, '', $sender_name, $subject, $email_html);
            
            // Send Admin Notification
            $admin_subject = "New Booking Alert: " . trim($booking['booking_name']);
            $admin_email = getenv('ENQUIRY_EMAIL_SENT_TO') ?: 'rupinisit@gmail.com';
            smtpEmailConfig($admin_email, '', $sender_email, '', $sender_name, $admin_subject, $email_html);
        }

        // Insert Admin DB Notification for Dashboard
        try {
            $admin_notif_title = "New Appointment Booked";
            $admin_notif_body = trim($booking['booking_name']) . " has booked an appointment for " . date('M j, Y', strtotime($booking['booking_date'])) . " at " . $appointment_time_display . " (" . $booking['branch_name'] . ")";
            $date_now = date('Y-m-d H:i:s');
            
            $notifStmt = $pdo->prepare("INSERT INTO oc_mobile_notifications (notification_target_type, notification_event_type, booking_id, contact_id, notification_title, notification_body, notification_send_at, notification_send_status, is_admin_notification, status, deleted, createdon) VALUES ('3', '1', ?, ?, ?, ?, ?, '0', '1', '1', '0', ?)");
            $notifStmt->execute([$booking['booking_ID'], $booking['customers_id'], $admin_notif_title, $admin_notif_body, $date_now, $date_now]);
        } catch (\Exception $e) {
            error_log('Notification DB Error: ' . $e->getMessage());
        }
        } // End HitPay API check
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'booking_id' => $booking['booking_ID'],
            'ref_no' => $booking['booking_refno'],
            'outlet' => $booking['branch_name'],
            'date' => $booking['booking_date'],
            'time' => date('h:i A', strtotime($booking['booking_starttime'])),
            'therapist' => trim($booking['beautician_name']),
            'payment' => $paymentMode,
            'final_price' => $booking['booking_totalprice'],
            'duration' => $booking['booking_time'],
            'services' => $services
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
