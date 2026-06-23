<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!is_array($body)) {
    $body = [];
}
$mobile = trim($body['mobile'] ?? '');
$country_code = trim($body['country_code'] ?? '');

if ($mobile === '') {
    echo json_encode(["status" => false, "message" => "Mobile number required"]);
    exit;
}

try {
    // First, check by mobile number only
    $stmt = $pdo->prepare("SELECT contact_id, contact_fname, contact_email, country_code FROM oc_contact WHERE contact_mobile = :mobile AND contact_status = 1 LIMIT 1");
    $stmt->execute([':mobile' => $mobile]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $db_cc = trim($row['country_code']);
        
        // If country code is null/empty in DB, update it
        if (empty($db_cc) && !empty($country_code)) {
            $update = $pdo->prepare("UPDATE oc_contact SET country_code = :cc WHERE contact_id = :id");
            $update->execute([':cc' => $country_code, ':id' => $row['contact_id']]);
            $db_cc = $country_code;
        }

        // Verify country code matches
        if ($db_cc === $country_code) {
            echo json_encode([
                "status" => true,
                "data" => [
                    "contact_fname" => $row['contact_fname'],
                    "contact_email" => $row['contact_email']
                ]
            ]);
        } else {
            // Found mobile, but country code doesn't match
            echo json_encode(["status" => false, "message" => "No customer found for this region"]);
        }
    } else {
        echo json_encode(["status" => false, "message" => "No customer found"]);
    }
} catch (\PDOException $e) {
    echo json_encode(["status" => false, "message" => "Database error"]);
}
?>
