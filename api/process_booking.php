<?php
// api/process_booking.php
require_once '../includes/db.php';

header('Content-Type: application/json');

// Receive JSON data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Process Contact (Insert or Update if exists)
    $name = $data['customer']['name'] ?? 'Unknown';
    $email = $data['customer']['email'] ?? '';
    $mobile = $data['customer']['mobile'] ?? '';
    $notes = $data['customer']['notes'] ?? '';

    // Check if contact exists by mobile
    $stmt = $pdo->prepare("SELECT contact_id FROM oc_contact WHERE contact_mobile = ? LIMIT 1");
    $stmt->execute([$mobile]);
    $contact = $stmt->fetch();
    
    $contact_id = 0;
    $date_now = date('Y-m-d H:i:s');

    if ($contact) {
        $contact_id = $contact['contact_id'];
    } else {
        // Insert new contact
        $stmt = $pdo->prepare("INSERT INTO oc_contact (contact_fname, contact_email, contact_mobile, contact_notes, createdon, contact_status) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([$name, $email, $mobile, $notes, $date_now]);
        $contact_id = $pdo->lastInsertId();
    }

    // 2. Process Booking
    $ref_no = 'RUP-' . time() . rand(10, 99);
    $branch_id = 1; // Default to Little India for now, or map from $data['outlet']
    
    $booking_date = date('Y-m-d', strtotime($data['date']));
    $booking_time = $data['time'] ?? '';
    $total_price = 0;

    foreach ($data['services'] as $service) {
        $total_price += floatval($service['price']);
    }

    $stmt = $pdo->prepare("
        INSERT INTO oc_booking (
            customers_id, booking_refno, booking_name, booking_email, booking_phone, 
            booking_branch, booking_beautician, booking_date, booking_time, 
            booking_totalprice, booking_mode, booking_status, createdon, status, deleted
        ) VALUES (
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, 
            ?, 1, 0, ?, 1, 0
        )
    ");
    
    $stmt->execute([
        $contact_id, 
        $ref_no, 
        $name, 
        $email, 
        $mobile,
        $branch_id, 
        $data['therapist'] ?? 'Any Available',
        $booking_date, 
        $booking_time, 
        $total_price, 
        $date_now
    ]);

    $booking_id = $pdo->lastInsertId();

    // 3. Process Booking Services
    $stmtService = $pdo->prepare("
        INSERT INTO oc_booking_person_services (
            booking_id, person_no, person_name, beautician_id, service_id, 
            service_price, offer_price, service_time, createdon, status, deleted
        ) VALUES (
            ?, 1, ?, 0, ?, 
            ?, ?, ?, ?, 1, 0
        )
    ");

    foreach ($data['services'] as $service) {
        // Extract a numeric ID if possible from the string "s1"
        $svc_id_num = (int) filter_var($service['id'], FILTER_SANITIZE_NUMBER_INT);
        if ($svc_id_num == 0) $svc_id_num = 1; // Fallback
        
        $price = floatval($service['price']);
        $duration = $service['duration'] . ' mins';
        
        $stmtService->execute([
            $booking_id,
            $service['name'], // Using person_name to store service name temporarily
            $svc_id_num,
            $price,
            $price, // offer_price = service_price
            $duration,
            $date_now
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Booking successfully created', 
        'ref_no' => $ref_no
    ]);

} catch (\Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
