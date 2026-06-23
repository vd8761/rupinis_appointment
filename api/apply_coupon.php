<?php
// api/apply_coupon.php
require_once '../includes/db.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$body = json_decode($input, true);

if (!is_array($body)) {
    $body = [];
}

$mobile = trim(filter_var($body['mobile'] ?? '', FILTER_SANITIZE_STRING));
$coupon_code = trim(filter_var($body['coupon_code'] ?? '', FILTER_SANITIZE_STRING));
$getprice = floatval($body['getprice'] ?? 0);

if ($coupon_code === '' || $getprice <= 0) {
    echo json_encode(["status" => false, "message" => "Coupon Code and valid Price are required", "data" => []]);
    exit;
}

// Optional: find contact_id if mobile is provided
$contact_id = 0;
if ($mobile !== '') {
    $stmt = $pdo->prepare("SELECT contact_id FROM oc_contact WHERE contact_mobile = ? AND contact_status = 1 LIMIT 1");
    $stmt->execute([$mobile]);
    if ($row = $stmt->fetch()) {
        $contact_id = $row['contact_id'];
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM oc_discounts WHERE discount_code = ? AND status = '1' AND deleted = '0' LIMIT 1");
    $stmt->execute([$coupon_code]);
    $discount = $stmt->fetch();

    if (!$discount) {
        echo json_encode(["status" => false, "message" => "Coupon Code is not valid or inactive", "data" => []]);
        exit;
    }

    $discount_id = $discount['discount_id'];
    $discount_amount = (float)$discount['discount_amount'];
    $expiry_date_start = $discount['expiry_date_start'];
    $expiry_date_end = $discount['expiry_date_end'];
    $is_first_time_user = (int)$discount['is_first_time_user'];

    $current_date = date('Y-m-d');

    // Check Expiry Start Date
    if ($expiry_date_start != '0000-00-00' && $expiry_date_start != NULL && $current_date < $expiry_date_start) {
        echo json_encode(["status" => false, "message" => "Coupon code is not yet active.", "data" => []]);
        exit;
    }

    // Check Expiry End Date
    if ($expiry_date_end != '0000-00-00' && $expiry_date_end != NULL && $current_date > $expiry_date_end) {
        echo json_encode(["status" => false, "message" => "Coupon code has expired.", "data" => []]);
        exit;
    }

    // Check First Time User (If applicable)
    if ($is_first_time_user === 1) {
        if ($contact_id > 0) {
            // Using logic from backend
            $check_used = $pdo->prepare("SELECT booking_ID FROM oc_booking WHERE customers_id = ? AND booking_date >= '2026-03-08' AND deleted = '0'");
            $check_used->execute([$contact_id]);
            if ($check_used->rowCount() > 0) {
                echo json_encode(["status" => false, "message" => "This coupon is only for first-time users.", "data" => []]);
                exit;
            }
        } else {
            echo json_encode(["status" => false, "message" => "Please enter your mobile number first to verify first-time user status.", "data" => []]);
            exit;
        }
    }

    // Check Birthday Coupon
    $is_birthday_coupon = (int)($discount['is_birthday_coupon'] ?? 0);
    if ($is_birthday_coupon === 1) {
        if ($contact_id > 0) {
            $customer_q = $pdo->prepare("SELECT contact_dob FROM oc_contact WHERE contact_id = ? AND contact_status = 1 LIMIT 1");
            $customer_q->execute([$contact_id]);
            $customer_row = $customer_q->fetch();
            
            if ($customer_row) {
                $dob = $customer_row['contact_dob'] ?? '';
                $dob_month = null;
                if (!empty($dob) && $dob !== '0000-00-00') {
                    $dob_time = strtotime($dob);
                    if ($dob_time) {
                        $dob_month = (int)date('n', $dob_time);
                    } else {
                        $parts = preg_split('/[-.\/]/', $dob);
                        if (count($parts) === 3) {
                            if (strlen($parts[0]) === 4) { // YYYY-MM-DD
                                $dob_month = (int)$parts[1];
                            } else if (strlen($parts[2]) === 4) { // DD-MM-YYYY
                                $dob_month = (int)$parts[1];
                            }
                        }
                    }
                }

                $current_month = (int)date('n');
                if ($dob_month === null || $dob_month !== $current_month) {
                    echo json_encode(["status" => false, "message" => "This birthday coupon can only be used during your birthday month.", "data" => []]);
                    exit;
                }
            } else {
                echo json_encode(["status" => false, "message" => "Unable to verify customer information for birthday coupon.", "data" => []]);
                exit;
            }

            // Check if the birthday coupon has already been used by the customer
            $check_used = $pdo->prepare("SELECT booking_ID FROM oc_booking WHERE customers_id = ? AND discount_id = ? AND deleted = '0' LIMIT 1");
            $check_used->execute([$contact_id, $discount_id]);
            
            if ($check_used->rowCount() > 0) {
                echo json_encode(["status" => false, "message" => "This birthday coupon has already been used.", "data" => []]);
                exit;
            }
        } else {
            echo json_encode(["status" => false, "message" => "Please enter your mobile number first to verify birthday coupon eligibility.", "data" => []]);
            exit;
        }
    }

    // Calculation: Percentage or Flat
    $discount_type = (int)$discount['discount_type'];
    $discount_percentage = (float)$discount['discount_percentage'];

    if ($discount_type === 2) { // Percentage
        $discount_value = round($getprice * ($discount_percentage / 100), 2);
    } else { // Flat amount
        $discount_value = $discount_amount;
    }

    $final_price = round($getprice - $discount_value, 2);
    if ($final_price < 0) {
        $final_price = 0;
        $discount_value = $getprice; // Discount cannot exceed subtotal
    }

    echo json_encode([
        "message" => "Coupon Code is valid",
        "status" => true,
        "data" => [
            "discount_id"         => $discount_id,
            "discount_percentage" => $discount_percentage,
            "discount_value"      => $discount_value,
            "discount_amount"     => $final_price,
            "coupon_code"         => $coupon_code
        ]
    ]);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'message' => 'Database error.']);
    exit;
}
