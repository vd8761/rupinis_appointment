<?php
// api/process_booking.php
require_once '../includes/db.php';

// HitPay Configurations
if (!defined('HIT_PAY_API_KEY')) {
    define('HIT_PAY_API_KEY', getenv('HIT_PAY_API_KEY') ?: '');
}
if (!defined('HIT_PAY_SALT')) {
    define('HIT_PAY_SALT', getenv('HIT_PAY_SALT') ?: '');
}
if (!defined('HIT_PAY_CURRENCY_FORMAT')) {
    define('HIT_PAY_CURRENCY_FORMAT', getenv('HIT_PAY_CURRENCY_FORMAT') ?: 'SGD');
}
if (!defined('HITPAY_SANDBOX')) {
    define('HITPAY_SANDBOX', filter_var(getenv('HITPAY_SANDBOX'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true);
}

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['customer']) || empty($data['services']) || !is_array($data['services'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing booking data payload.']);
    exit;
}

// Basic input sanitization and validation
$name = trim(filter_var($data['customer']['name'] ?? 'Unknown', FILTER_SANITIZE_STRING));
$email = trim(filter_var($data['customer']['email'] ?? '', FILTER_SANITIZE_EMAIL));
$mobile = trim(filter_var($data['customer']['mobile'] ?? '', FILTER_SANITIZE_STRING));
$country_code = trim(filter_var($data['customer']['country_code'] ?? '+65', FILTER_SANITIZE_STRING));
$notes = trim(filter_var($data['customer']['notes'] ?? '', FILTER_SANITIZE_STRING));

if (empty($mobile)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Mobile number is required.']);
    exit;
}

$branch_id = 1; // Fallback
if (!empty($data['outlet'])) {
    $stmt_branch = $pdo->prepare("SELECT branch_id FROM oc_branch WHERE branch_name = ? LIMIT 1");
    $stmt_branch->execute([$data['outlet']]);
    if ($b = $stmt_branch->fetch()) {
        $branch_id = (int)$b['branch_id'];
    }
}

$booking_date = date('Y-m-d', strtotime($data['date']));
$booking_start_time = trim($data['backend_time'] ?? '');
if (!$booking_start_time) {
    // Fallback if backend_time isn't present
    $booking_start_time = date('H:i:s', strtotime($data['time']));
}

$booking_beautician = (int)($data['therapist_id'] ?? 0);
$booking_services = $data['services'];

try {
    $pdo->beginTransaction();

    // Past Date/Time Check
    $booking_start_ts = strtotime("$booking_date $booking_start_time");
    if ($booking_start_ts < time()) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Cannot book an appointment for a past time."]);
        exit;
    }

    // 1. Process Contact (Insert or Update if exists)
    $stmt = $pdo->prepare("SELECT `contact_id` FROM `oc_contact` WHERE `contact_mobile` = ? LIMIT 1");
    $stmt->execute([$mobile]);
    $contact = $stmt->fetch();
    
    $contact_id = 0;
    $date_now = date('Y-m-d H:i:s');

    if ($contact) {
        $contact_id = $contact['contact_id'];
        // Update the country code and email if provided
        $update_parts = ["country_code = ?"];
        $update_params = [$country_code];
        
        if (!empty($email)) {
            $update_parts[] = "contact_email = ?";
            $update_params[] = $email;
        }
        
        $update_params[] = $contact_id;
        $update_stmt = $pdo->prepare("UPDATE oc_contact SET " . implode(", ", $update_parts) . " WHERE contact_id = ?");
        $update_stmt->execute($update_params);
    } else {
        // Insert new contact
        $stmt = $pdo->prepare("INSERT INTO oc_contact (contact_fname, contact_email, country_code, contact_mobile, contact_notes, createdon, contact_status) VALUES (?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([$name, $email, $country_code, $mobile, $notes, $date_now]);
        $contact_id = $pdo->lastInsertId();
    }

    // Fetch Service Details from DB
    $total_min = 0;
    $total_price = 0;
    $service_prices = [];
    $service_ids_str = [];

    foreach ($booking_services as $service) {
        $sid = (int) filter_var($service['id'], FILTER_SANITIZE_NUMBER_INT);
        $qS = $pdo->prepare("SELECT prdt_final_price, prdt_service_time FROM oc_product WHERE prdt_id = ? LIMIT 1");
        $qS->execute([$sid]);
        if ($rS = $qS->fetch()) {
            $total_min += (int) $rS['prdt_service_time'];
            $price = (float) $rS['prdt_final_price'];
            $total_price += $price;
            $service_prices[] = $price;
            $service_ids_str[] = $sid;
        }
    }

    $discount_id = (int)($data['discount_id'] ?? 0);
    $discount_amount = (float)($data['discount_amount'] ?? 0);
    $final_price = $total_price - $discount_amount;
    if($final_price < 0) $final_price = 0;

    $booking_end_ts = $booking_start_ts + ($total_min * 60);
    $db_start_time = date('H:i:s', $booking_start_ts);
    $db_end_time = date('H:i:s', $booking_end_ts);

    $assigned_beautician_name = "Any Beautician";

    $any_assigned = (int)($data['any_assigned_beautician_id'] ?? 0);
    if ($booking_beautician == 0 && $any_assigned > 0) {
        $booking_beautician = $any_assigned;
    }

    if ($booking_beautician > 0) {
        $staff_q = $pdo->prepare("SELECT staff_fname, staff_lname FROM oc_staff WHERE staff_id = ? LIMIT 1");
        $staff_q->execute([$booking_beautician]);
        if ($staff = $staff_q->fetch()) {
            $assigned_beautician_name = trim($staff['staff_fname'] . ' ' . $staff['staff_lname']);
        }
    }

    // Conflict Check logic with 15 minute buffer (for the selected or newly assigned beautician)
    if ($booking_beautician > 0) {
        $conflict_q = $pdo->prepare("SELECT booking_id, customers_id, booking_starttime, booking_endtime FROM oc_booking WHERE booking_beautician = ? AND booking_date = ? AND payment_status IN ('1','3') AND (deleted = 0 OR deleted IS NULL)");
        $conflict_q->execute([$booking_beautician, $booking_date]);
        
        while ($row = $conflict_q->fetch()) {
            $existing_start = strtotime("$booking_date " . $row['booking_starttime']);
            $existing_end = strtotime("$booking_date " . $row['booking_endtime']);

            $buffer_time = ($row['customers_id'] == $contact_id) ? 0 : (15 * 60);
            $buffer_end = $existing_end + $buffer_time;
            
            $new_booking_buffer = ($row['customers_id'] == $contact_id) ? 0 : (15 * 60);
            $new_booking_buffered_end = $booking_end_ts + $new_booking_buffer;

            if ($booking_start_ts < $buffer_end && $new_booking_buffered_end > $existing_start) {
                $msg = ($buffer_time > 0) ? "Booking time conflicts with existing booking (including 15-minute break)" : "Booking time conflicts with existing booking";
                echo json_encode(["success" => false, "message" => $msg]);
                exit;
            }
        }
    }

    // Insert Booking
    $ref_no = 'RUP-' . time() . rand(10, 99);
    $payment = $data['payment'] ?? '';
    
    // Force Pay at Store if amount is less than 1 SGD
    if ($payment === 'HitPay' && $final_price < 1) {
        $payment = 'Pay at Store';
    }

    // Map payment type to integer based on rules
    $payment_type = 6; // Default to Pay at Store
    if (strcasecmp($payment, 'Paypal') === 0) $payment_type = 1;
    else if (strcasecmp($payment, 'Membership') === 0) $payment_type = 3;
    else if (strcasecmp($payment, 'Paynow') === 0) $payment_type = 4;
    else if (strcasecmp($payment, 'HitPay') === 0) $payment_type = 5;
    else if (strcasecmp($payment, 'Pay at Store') === 0) $payment_type = 6;
    else if (strcasecmp($payment, 'Split Payment') === 0) $payment_type = 7;

    // User requested to not update payment_status to 3 for Pay at store, 
    // but rather update booking_status to 1 (Upcoming).
    // UPDATE: Now correctly implementing Payment Status as 3 (Pending) for new bookings.
    $payment_status = 3; 
    $booking_mode = 0; // Forced to 0 for all types of bookings on this platform
    $booking_status = ($payment === 'HitPay') ? 0 : 1;
    $bookingserviceid = implode(',', $service_ids_str);
    $bookingserviceprice = implode(',', $service_prices);

    $status_val = ($payment === 'HitPay') ? 0 : 1;
    $stmt = $pdo->prepare("
        INSERT INTO oc_booking (
            customers_id, booking_type, booking_refno, booking_name, booking_email, booking_phone, 
            booking_branch, booking_beautician, booking_date, booking_starttime, booking_endtime, booking_time,
            booking_services, booking_services_price,
            price, booking_totalprice, booking_mode, payment_status, payment_type, discount_id, discount_amount, booking_status, createdon, status, deleted
        ) VALUES (
            ?, 1, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0
        )
    ");
    
    $stmt->execute([
        $contact_id, 
        $ref_no, 
        $name, 
        $email, 
        $country_code . $mobile,
        $branch_id, 
        $booking_beautician,
        $booking_date, 
        $db_start_time, 
        $db_end_time,
        $total_min,
        $bookingserviceid,
        $bookingserviceprice,
        $total_price, 
        $final_price, 
        $booking_mode,
        $payment_status,
        $payment_type,
        $discount_id,
        $discount_amount,
        $booking_status,
        $date_now,
        $status_val
    ]);

    $booking_id = $pdo->lastInsertId();
    $ref_no = 'REF' . str_pad($booking_id, 6, '0', STR_PAD_LEFT);
    $pdo->prepare("UPDATE oc_booking SET booking_refno = ? WHERE booking_id = ?")->execute([$ref_no, $booking_id]);

    // Process Booking Services
    $stmtService = $pdo->prepare("
        INSERT INTO oc_booking_person_services (
            booking_id, person_no, person_name, beautician_id, service_id, 
            service_price, offer_price, service_time, start_time, end_time, createdon, status, deleted
        ) VALUES (
            ?, 1, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, 1, 0
        )
    ");

    // Initialize current time for sequential service times
    $current_svc_time_ts = strtotime($db_start_time);

    foreach ($booking_services as $idx => $service) {
        $svc_id_num = $service_ids_str[$idx];
        $price = $service_prices[$idx];
        $duration_mins = intval($service['duration']);
        $duration_str = $duration_mins . ' mins'; 
        
        $svc_start_time = date('H:i:s', $current_svc_time_ts);
        $current_svc_time_ts += ($duration_mins * 60);
        $svc_end_time = date('H:i:s', $current_svc_time_ts);

        $stmtService->execute([
            $booking_id,
            $name,
            $booking_beautician,
            $svc_id_num,
            $price,
            $price, // offer_price
            $duration_str,
            $svc_start_time,
            $svc_end_time,
            $date_now
        ]);
    }

    $pdo->commit();

    $response = [
        'success' => true, 
        'message' => 'Booking successfully created', 
        'ref_no' => $ref_no,
        'booking_id' => $booking_id,
        'beautician_name' => $assigned_beautician_name
    ];

    // Dispatch Notifications for "Pay at Salon"
    if ($payment !== 'HitPay') {
        require_once 'smtp_functions.php';
        require_once 'email_template.php';

        // 1. Send SMS
        $appointment_time_display = date('h:i A', strtotime($db_start_time));
        $sms_msg = "Just a reminder! Your beauty appointment is coming up at {$appointment_time_display}. See you soon - Rupinis";
        $full_mobile = $country_code . $mobile;
        sendOnewaySms($full_mobile, $sms_msg);

        // Insert Admin DB Notification for Dashboard
        try {
            $branch_name = $data['outlet'] ?? 'Rupinis';
            $admin_notif_title = "New Appointment Booked";
            $admin_notif_body = trim($name) . " has booked an appointment for " . date('M j, Y', strtotime($booking_date)) . " at " . $appointment_time_display . " (" . $branch_name . ")";
            
            $notifStmt = $pdo->prepare("INSERT INTO oc_mobile_notifications (notification_target_type, notification_event_type, booking_id, contact_id, notification_title, notification_body, notification_send_at, notification_send_status, is_admin_notification, status, deleted, createdon) VALUES ('3', '1', ?, ?, ?, ?, ?, '0', '1', '1', '0', ?)");
            $notifStmt->execute([$booking_id, $contact_id, $admin_notif_title, $admin_notif_body, $date_now, $date_now]);
        } catch (\Exception $e) {
            error_log('Notification DB Error: ' . $e->getMessage());
        }

        // 2. Send Email
        if (!empty($email)) {
            $services_names_only = array_map(function($s) { return $s['name']; }, $booking_services);
            $services_formatted = implode(', ', $services_names_only);

            $email_html = getAppointmentConfirmationEmailTemplate(
                $name, 
                $booking_date, 
                $db_start_time, 
                $db_end_time, 
                $branch_name, 
                $assigned_beautician_name, 
                $services_formatted, 
                $ref_no, 
                $final_price, 
                'Pay at Salon'
            );

            $sender_email = getenv('SEND_FROM_EMAIL') ?: 'app.journeygenie@gmail.com';
            $sender_name = getenv('SEND_FROM_NAME') ?: 'Rupinis';

            // Send Customer Notification
            $subject = "Appointment Confirmation - Rupini's";
            smtpEmailConfig($email, '', $sender_email, '', $sender_name, $subject, $email_html);
            
            // Send Admin Notification
            $admin_subject = "New Booking Alert: " . trim($name);
            $admin_email = getenv('ENQUIRY_EMAIL_SENT_TO') ?: 'rupinisit@gmail.com';
            smtpEmailConfig($admin_email, '', $sender_email, '', $sender_name, $admin_subject, $email_html);
        }
    }

    // HitPay API Request (if chosen)
    if ($payment === 'HitPay' && $final_price > 0) {
        $reference_no = $contact_id . '-booking-' . date('YmdHis');
        $purpose_of_payment = "S$ $final_price paid for Rupinis Booking (Booking ID: $booking_id) on " . date('d/m/Y H:i:s') . " - [$reference_no]";

        $apiUrl = HITPAY_SANDBOX
            ? 'https://api.sandbox.hit-pay.com/v1/payment-requests'
            : 'https://api.hit-pay.com/v1/payment-requests';

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $redirectURL = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/../book.php?booking=' . $ref_no;

        $postData = [
            'amount' => $final_price,
            'currency' => HIT_PAY_CURRENCY_FORMAT,
            'name' => trim($name),
            'email' => $email,
            'phone' => $country_code . $mobile,
            'purpose' => $purpose_of_payment,
            'reference_number' => $reference_no,
            'redirect_url' => $redirectURL
        ];

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'X-BUSINESS-API-KEY: ' . HIT_PAY_API_KEY,
                'Content-Type: application/x-www-form-urlencoded'
            ],
            CURLOPT_POSTFIELDS => http_build_query($postData)
        ]);

        $responseRaw = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($responseRaw, true);

        if (isset($result['id'], $result['url'])) {
            $response['payment_url'] = $result['url'];
            
            // Save paynow_url to booking table
            $upd = $pdo->prepare("UPDATE oc_booking SET paynow_url = ? WHERE booking_ID = ?");
            $upd->execute([$result['url'], $booking_id]);
        } else {
            // Hitpay failed, return error and rollback if desired, or just return an error message
            $errorMsg = $result['message'] ?? 'Failed to generate HitPay payment request.';
            error_log("HitPay Error: " . json_encode($result));
            echo json_encode(['success' => false, 'message' => $errorMsg]);
            exit;
        }
    }

    echo json_encode($response);

} catch (\PDOException $e) {
    if($pdo->inTransaction()) $pdo->rollBack();
    error_log('Database Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again later.']);
} catch (\Exception $e) {
    if($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
}
?>
