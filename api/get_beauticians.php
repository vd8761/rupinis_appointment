<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!is_array($body)) {
    $body = [];
}
$branch_id = (int) ($body['branch_id'] ?? 0);
$date_str = trim((string) ($body['date'] ?? ''));

$is_global_closed = false;
$staff_closed_map = [];

// Apply shop timing rules if date is provided
if ($date_str !== '') {
    $check_date = date('Y-m-d', strtotime($date_str));
    $w = date('w', strtotime($date_str));
    $check_day = (int) (($w == 0) ? 7 : $w);

    $rules = [];
    $stmtRules = $pdo->query("SELECT staff_id, specific_date, end_date, day_of_week, open_time, close_time, is_closed FROM shop_timing_rules WHERE rule_type != 1 AND status = 1 AND deleted = '0'");
    if ($stmtRules) {
        $rules = $stmtRules->fetchAll(PDO::FETCH_ASSOC);
    }

    // 1. Check Global Rules First
    $best_global_priority = -1;
    $best_global_rule = null;
    
    foreach ($rules as $r) {
        $r_staff = (int) $r['staff_id'];
        if ($r_staff !== 0) continue; // Skip staff-specific rules

        $r_date = $r['specific_date'];
        $r_end_date = isset($r['end_date']) ? $r['end_date'] : null;
        $r_day = (int) $r['day_of_week'];

        $is_date_match = false;
        if (!empty($r_date) && $r_date !== '0000-00-00') {
            if (!empty($r_end_date) && $r_end_date !== '0000-00-00') {
                if ($check_date >= $r_date && $check_date <= $r_end_date) {
                    $is_date_match = true;
                }
            } else {
                if ($r_date === $check_date) {
                    $is_date_match = true;
                }
            }
        }
        $is_day_match = ($r_day === $check_day);

        if ($is_date_match) {
            if ($best_global_priority < 2) {
                $best_global_priority = 2;
                $best_global_rule = $r;
            }
        } elseif ($is_day_match && (empty($r_date) || $r_date === '0000-00-00')) {
            if ($best_global_priority < 1) {
                $best_global_priority = 1;
                $best_global_rule = $r;
            }
        }
    }

    if ($best_global_rule !== null && $best_global_rule['is_closed'] == 1) {
        $is_global_closed = true;
    }

    // 2. Map Staff Specific Rules
    // We only care about is_closed for staff here
    foreach ($rules as $r) {
        $r_staff = (int) $r['staff_id'];
        if ($r_staff === 0) continue; 

        $r_date = $r['specific_date'];
        $r_end_date = isset($r['end_date']) ? $r['end_date'] : null;
        $r_day = (int) $r['day_of_week'];

        $is_date_match = false;
        if (!empty($r_date) && $r_date !== '0000-00-00') {
            if (!empty($r_end_date) && $r_end_date !== '0000-00-00') {
                if ($check_date >= $r_date && $check_date <= $r_end_date) {
                    $is_date_match = true;
                }
            } else {
                if ($r_date === $check_date) {
                    $is_date_match = true;
                }
            }
        }
        $is_day_match = ($r_day === $check_day);

        $priority = -1;
        if ($is_date_match) {
            $priority = 4;
        } elseif ($is_day_match && (empty($r_date) || $r_date === '0000-00-00')) {
            $priority = 3;
        }

        if ($priority > -1) {
            if (!isset($staff_closed_map[$r_staff]) || $priority > $staff_closed_map[$r_staff]['priority']) {
                $staff_closed_map[$r_staff] = [
                    'priority' => $priority,
                    'is_closed' => ($r['is_closed'] == 1)
                ];
            }
        }
    }
}

$results = [];

// If the entire shop is closed globally on this day, no beauticians are available
if ($is_global_closed) {
    echo json_encode(["status" => true, "data" => []]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT staff_id, staff_fname, staff_lname, profile_img FROM oc_staff WHERE branch_id = :branch_id AND staff_status = 1 ORDER BY staff_fname ASC");
    $stmt->execute([':branch_id' => $branch_id]);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $staff_id = (int) $row['staff_id'];
        
        // Skip if this specific staff member is closed
        if (isset($staff_closed_map[$staff_id]) && $staff_closed_map[$staff_id]['is_closed']) {
            continue;
        }

        $full_name = trim(($row['staff_fname'] ?? '') . ' ' . ($row['staff_lname'] ?? ''));
        $beautician_name = ($full_name !== '') ? ucfirst(strtolower(trim($full_name))) : '';

        $STAFF_UPLOAD_REL = 'assets/staff/';
        $DEFAULT_AVATAR_REL = 'assets/staff/common_female_avatar.svg';

        $img = trim((string) ($row['profile_img'] ?? ''));
        $profile_img = ($img !== '') ? ($STAFF_UPLOAD_REL . $img) : $DEFAULT_AVATAR_REL;

        $results[] = [
            "beautician_id" => (string) $staff_id,
            "beautician_name" => (string) $beautician_name,
            "beautician_avatar" => (string) ($profile_img ?? '')
        ];
    }
    
    echo json_encode(["status" => true, "data" => $results]);
} catch (\PDOException $e) {
    echo json_encode(["status" => false, "message" => "Database error"]);
}
?>
