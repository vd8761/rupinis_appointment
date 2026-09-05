<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!is_array($body)) {
    $body = [];
}

$booking_beautician_raw = $body['booking_beautician'] ?? ($body['beautician_id'] ?? '');
$booking_branch = (int) ($body['booking_branch'] ?? ($body['branch_id'] ?? 0));
$booking_date = trim((string) ($body['booking_date'] ?? ($body['date'] ?? '')));

$duration_minutes = (int) ($body['duration'] ?? 30);
if ($duration_minutes < 30) $duration_minutes = 30;
$slot_minutes = 15;
$break_minutes = 15;

$booking_beauticians_array_temp = is_array($booking_beautician_raw) ? $booking_beautician_raw : explode(',', (string) $booking_beautician_raw);
$booking_beauticians_array = array_map('intval', $booking_beauticians_array_temp);
$booking_beauticians_array = array_values(array_filter($booking_beauticians_array, function ($v) {
    return $v > 0;
}));
$booking_beautician = !empty($booking_beauticians_array) ? $booking_beauticians_array[0] : 0;

$default_day_start_str = '09:30 AM';
$day_start_str = $default_day_start_str;
$day_end_str = '08:30 PM';
$slot_minutes = 30;
$break_minutes = 15;

if (!function_exists('returnBackendSlots')) {
    function returnBackendSlots($slots) {
        if (empty($slots)) {
            $slots[] = ["label" => "Slot not available", "start_time" => "", "end_time" => "", "isBooked" => true, "isFrozen" => true];
        }
        echo json_encode(["status" => true, "data" => $slots]);
        exit;
    }
}

$is_shop_closed = false;

// --- APPLY SHOP TIMING RULES ---
if ($booking_date !== '') {
    $check_date = date('Y-m-d', strtotime($booking_date));
    $w = date('w', strtotime($booking_date));
    $check_day = (int) (($w == 0) ? 7 : $w);

    $rules = [];
    $stmtRules = $pdo->query("SELECT staff_id, specific_date, end_date, day_of_week, open_time, close_time, is_closed FROM shop_timing_rules WHERE rule_type != 1 AND status = 1 AND deleted = '0'");
    if ($stmtRules) {
        $rules = $stmtRules->fetchAll(PDO::FETCH_ASSOC);
    }

    $best_rule = null;
    $best_priority = -1;

    foreach ($rules as $r) {
        $r_staff = (int) $r['staff_id'];
        $r_date = $r['specific_date'];
        $r_end_date = isset($r['end_date']) ? $r['end_date'] : null;
        $r_day = (int) $r['day_of_week'];

        $is_staff_match = ($booking_beautician > 0 && $r_staff === $booking_beautician);
        
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

        if ($is_staff_match && $is_date_match) {
            if ($best_priority < 4) {
                $best_priority = 4;
                $best_rule = $r;
            }
        } elseif ($is_staff_match && $is_day_match && (empty($r_date) || $r_date === '0000-00-00')) {
            if ($best_priority < 3) {
                $best_priority = 3;
                $best_rule = $r;
            }
        } elseif ($r_staff === 0 && $is_date_match) {
            if ($best_priority < 2) {
                $best_priority = 2;
                $best_rule = $r;
            }
        } elseif ($r_staff === 0 && $is_day_match && (empty($r_date) || $r_date === '0000-00-00')) {
            if ($best_priority < 1) {
                $best_priority = 1;
                $best_rule = $r;
            }
        }
    }

    if ($best_rule !== null) {
        if ($best_rule['is_closed'] == 1) {
            $is_shop_closed = true;
        } else {
            if (!empty($best_rule['open_time'])) {
                $day_start_str = date('h:i A', strtotime($best_rule['open_time']));
            }
            if (!empty($best_rule['close_time'])) {
                $day_end_str = date('h:i A', strtotime($best_rule['close_time']));
            }
        }
    }
}

if ($is_shop_closed) {
    returnBackendSlots([]);
}

// Common/Any Beautician Logic
if ($booking_beautician <= 0) {
    $slot_date = $booking_date !== '' ? $booking_date : date('Y-m-d');
    $base_time = strtotime($slot_date . ' ' . $day_start_str);
    $slot_duration = $slot_minutes * 60;
    $results = [];
    $now = time();
    $total_person = 1;

    $beauticians = [];
    $beautician_slots = [];
    $stmtStaff = $pdo->prepare("SELECT staff_id, staff_slots FROM oc_staff WHERE branch_id = :branch_id AND staff_status = 1"); // Removing staff_role = 1 based on other scripts logic
    $stmtStaff->execute([':branch_id' => $booking_branch]);
    
    while ($row = $stmtStaff->fetch(PDO::FETCH_ASSOC)) {
        $sid = (int) $row['staff_id'];
        
        // CHECK TIMING RULES FOR THIS SPECIFIC BEAUTICIAN
        $b_is_closed = false;
        $b_open_ts = null;
        $b_close_ts = null;
        $best_b_priority = -1;
        $best_b_rule = null;

        foreach ($rules as $r) {
            $r_staff = (int) $r['staff_id'];
            if ($r_staff !== $sid) continue;

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
                if ($best_b_priority < 2) {
                    $best_b_priority = 2;
                    $best_b_rule = $r;
                }
            } elseif ($is_day_match && (empty($r_date) || $r_date === '0000-00-00')) {
                if ($best_b_priority < 1) {
                    $best_b_priority = 1;
                    $best_b_rule = $r;
                }
            }
        }

        if ($best_b_rule !== null) {
            if ($best_b_rule['is_closed'] == 1) {
                $b_is_closed = true;
            } else {
                if (!empty($best_b_rule['open_time'])) $b_open_ts = strtotime("$booking_date " . $best_b_rule['open_time']);
                if (!empty($best_b_rule['close_time'])) $b_close_ts = strtotime("$booking_date " . $best_b_rule['close_time']);
            }
        }

        if ($b_is_closed) continue; // Skip this beautician!

        $beauticians[] = $sid;
        $slotsCsv = trim((string) ($row['staff_slots'] ?? ''));
        $intervals = [];
        if ($slotsCsv !== '') {
            $slot_ids = array_map('intval', explode(',', $slotsCsv));
            $slot_date_b = $booking_date !== '' ? $booking_date : date('Y-m-d');
            $base_time_b = strtotime($slot_date_b . ' ' . $default_day_start_str);
            foreach ($slot_ids as $slotIdInt) {
                if ($slotIdInt > 0) {
                    $s_start = $base_time_b + (($slotIdInt - 1) * $slot_minutes * 60);
                    $s_end = $s_start + ($slot_minutes * 60);
                    
                    if ($b_open_ts !== null && $s_start < $b_open_ts) continue;
                    if ($b_close_ts !== null && $s_end > $b_close_ts) continue;

                    $intervals[] = ['start' => $s_start, 'end' => $s_end];
                }
            }
        }
        if (empty($intervals)) {
            $intervals = [['start' => strtotime($slot_date . ' ' . $day_start_str), 'end' => strtotime($slot_date . ' ' . $day_end_str)]];
        }
        $beautician_slots[$sid] = $intervals;
    }

    $booked_slots = [];
    $queryBooked = "SELECT booking_starttime, booking_endtime, booking_beautician, customers_id FROM oc_booking WHERE (booking_status IN (1,2) OR payment_status IN (1,3) OR (booking_hold_till IS NOT NULL AND booking_hold_till > NOW())) AND booking_branch = :booking_branch AND booking_date = :booking_date AND booking_starttime IS NOT NULL AND booking_endtime IS NOT NULL AND (deleted = 0 OR deleted IS NULL)";
    $stmtBooked = $pdo->prepare($queryBooked);
    $stmtBooked->execute([':booking_branch' => $booking_branch, ':booking_date' => $slot_date]);

    while ($r = $stmtBooked->fetch(PDO::FETCH_ASSOC)) {
        $time_s = date('H:i:s', strtotime($r['booking_starttime']));
        $time_e = date('H:i:s', strtotime($r['booking_endtime']));
        $s = strtotime("$slot_date $time_s");
        $e = strtotime("$slot_date $time_e");
        $b_beauticians = array_filter(array_map('intval', explode(',', (string) ($r['booking_beautician'] ?? ''))));
        if ($s && $e && $e > $s && !empty($b_beauticians)) {
            foreach ($b_beauticians as $bid) {
                $booked_slots[] = ['beautician_id' => $bid, 'start' => $s, 'end' => $e, 'customers_id' => $r['customers_id']];
            }
        }
    }

    $start_times = [];
    $curr_time = $base_time;
    $limit = strtotime($slot_date . ' ' . $day_end_str);

    while ($curr_time < $limit) {
        $is_conflict = false;
        $conflict_end = null;
        
        $curr_end = $curr_time + ($duration_minutes * 60) + ($break_minutes * 60);

        foreach ($booked_slots as $b) {
            $b_end_buffered = $b['end'] + ($break_minutes * 60);
            
            // Check if our potential slot overlaps with the existing booking
            if ($curr_time < $b_end_buffered && $curr_end > $b['start']) {
                $is_conflict = true;
                if ($conflict_end === null || $b_end_buffered > $conflict_end) {
                    $conflict_end = $b_end_buffered;
                }
            }
        }

        if ($is_conflict) {
            $curr_time = $conflict_end;
        } else {
            $start_times[] = $curr_time;
            $curr_time += $slot_duration;
        }
    }

    foreach ($start_times as $start_ts) {
        $end_ts = $start_ts + ($duration_minutes * 60);
        $new_booking_buffered_end = $end_ts + ($break_minutes * 60);
        $end_ts = min($limit, $start_ts + ($duration_minutes * 60));
        
        $available_beautician_ids = [];
        $available_beauticians = 0;
        foreach ($beauticians as $beautician_id) {
            $is_allowed = false;
            if (isset($beautician_slots[$beautician_id])) {
                $covered = 0;
                foreach ($beautician_slots[$beautician_id] as $interval) {
                    if ($end_ts > $interval['start'] && $start_ts < $interval['end']) {
                        $c_s = max($start_ts, $interval['start']);
                        $c_e = min($end_ts, $interval['end']);
                        $covered += ($c_e - $c_s);
                    }
                }
                if ($covered >= ($end_ts - $start_ts)) {
                    $is_allowed = true;
                }
            }
            
            if (!$is_allowed) continue;

            $is_busy = false;
            foreach ($booked_slots as $b) {
                $buffer_time = ($break_minutes * 60);
                $existing_buffered_end = $b['end'] + $buffer_time;
                
                if ($b['beautician_id'] === $beautician_id && $start_ts < $existing_buffered_end && $new_booking_buffered_end > $b['start']) {
                    $is_busy = true;
                    break;
                }
            }
            if (!$is_busy) {
                $available_beauticians++;
                $available_beautician_ids[] = $beautician_id;
            }
        }

        $isBooked = ($available_beauticians < $total_person);

        $results[] = [
            "label" => date('h:i A', $start_ts) . ' - ' . date('h:i A', $end_ts),
            "start_time" => date('H:i', $start_ts),
            "end_time" => date('H:i', $end_ts),
            "isBooked" => $isBooked,
            "isFrozen" => ($now > $start_ts),
            "available_beauticians" => $available_beautician_ids,
            "sort_ts" => $start_ts
        ];
    }
    
    // Explicitly add actual bookings so they show up as greyed-out blocked slots!
    foreach ($booked_slots as $b) {
        if ($b['start'] >= $base_time && $b['start'] < $limit) {
            $results[] = [
                "label" => date('h:i A', $b['start']) . ' - ' . date('h:i A', $b['end']),
                "start_time" => date('H:i', $b['start']),
                "end_time" => date('H:i', $b['end']),
                "isBooked" => true,
                "isFrozen" => false,
                "available_beauticians" => [],
                "sort_ts" => $b['start']
            ];
        }
    }

    usort($results, function($a, $b) {
        return $a['sort_ts'] - $b['sort_ts'];
    });

    foreach ($results as &$res) {
        unset($res['sort_ts']);
    }

    returnBackendSlots($results);
}

// SPECIFIC BEAUTICIAN LOGIC
if ($booking_date === '' || $booking_branch <= 0) {
    $slot_date = $booking_date !== '' ? $booking_date : date('Y-m-d');
    $base_time = strtotime($slot_date . ' ' . $day_start_str);
    $slot_duration = $slot_minutes * 60;
    $results = [];
    $now = time();
    for ($i = 1; $i <= 45; $i++) {
        $start_ts = $base_time + (($i - 1) * $slot_duration);
        $end_ts = $start_ts + $slot_duration;
        $results[] = [
            "label" => date('h:i A', $start_ts) . ' - ' . date('h:i A', $end_ts),
            "start_time" => date('H:i', $start_ts),
            "end_time" => date('H:i', $end_ts),
            "isBooked" => false,
            "isFrozen" => ($now > $start_ts)
        ];
    }
    returnBackendSlots($results);
}

// 1. Fetch Bookings & Build Schedule
$bookings_list = [];
$beautician_conditions = [];
foreach ($booking_beauticians_array as $beautician_id) {
    $beautician_id = (int) $beautician_id;
    if ($beautician_id > 0) {
        $beautician_conditions[] = "FIND_IN_SET($beautician_id, booking_beautician)";
    }
}
$beautician_where = !empty($beautician_conditions) ? ("AND (" . implode(' OR ', $beautician_conditions) . ")") : "";

$queryBooked = "SELECT booking_starttime, booking_endtime, payment_status, booking_hold_till, customers_id, booking_beautician FROM oc_booking WHERE (booking_status IN (1,2) OR payment_status IN (1,3) OR (booking_hold_till IS NOT NULL AND booking_hold_till > NOW())) $beautician_where AND booking_branch = :booking_branch AND booking_date = :booking_date AND booking_starttime IS NOT NULL AND booking_endtime IS NOT NULL AND (deleted = 0 OR deleted IS NULL) ORDER BY booking_starttime ASC";
$stmtBookedSpec = $pdo->prepare($queryBooked);
$stmtBookedSpec->execute([':booking_branch' => $booking_branch, ':booking_date' => $booking_date]);

while ($r = $stmtBookedSpec->fetch(PDO::FETCH_ASSOC)) {
    $booking_beautician_ids = array_filter(array_map('trim', explode(',', (string) ($r['booking_beautician'] ?? ''))), function ($v) {
        return ctype_digit($v) && $v > 0;
    });
    $time_s = date('H:i:s', strtotime($r['booking_starttime']));
    $time_e = date('H:i:s', strtotime($r['booking_endtime']));
    $s = strtotime("$booking_date $time_s");
    $e = strtotime("$booking_date $time_e");
    if ($s && $e && $e > $s) {
        $is_hold = false;
        if (!in_array($r['payment_status'], [1, 3])) {
            $is_hold = true;
        }
        foreach ($booking_beautician_ids as $beautician_id) {
            $bookings_list[] = [
                'beautician_id' => $beautician_id,
                'start' => $s,
                'end' => $e,
                'is_hold' => $is_hold,
                'customers_id' => $r['customers_id']
            ];
        }
    }
}

usort($bookings_list, function ($a, $b) {
    return $a['start'] - $b['start'];
});

// 2. Fetch Staff Availability
$allowed_intervals = [];
$original_day_start = strtotime("$booking_date $default_day_start_str");

$stmtStaffSpec = $pdo->prepare("SELECT staff_slots, staff_fname, staff_lname FROM oc_staff WHERE staff_id = :staff_id AND branch_id = :branch_id AND staff_status = 1 LIMIT 1");
$stmtStaffSpec->execute([':staff_id' => $booking_beautician, ':branch_id' => $booking_branch]);

if ($staffRow = $stmtStaffSpec->fetch(PDO::FETCH_ASSOC)) {
    $slotsCsv = trim((string) ($staffRow['staff_slots'] ?? ''));
    if ($slotsCsv !== '') {
        $staff_slot_ids = array_map('intval', explode(',', $slotsCsv));
        foreach ($staff_slot_ids as $sid) {
            if ($sid > 0) {
                $s_start = $original_day_start + (($sid - 1) * 30 * 60);
                $s_end = $s_start + (30 * 60);
                $allowed_intervals[] = ['start' => $s_start, 'end' => $s_end];
            }
        }
    }
}

if (empty($allowed_intervals)) {
    $allowed_intervals = [['start' => strtotime("$booking_date $day_start_str"), 'end' => strtotime("$booking_date $day_end_str")]];
}

$cursor = strtotime("$booking_date $day_start_str");
$limit = strtotime("$booking_date $day_end_str");

$booking_idx = 0;
$final_slots = [];
$total_bookings = count($bookings_list);

if (!function_exists('isSlotAllowed')) {
    function isSlotAllowed($start, $end, $intervals) {
        foreach ($intervals as $int) {
            if ($start < $int['end'] && $end > $int['start']) {
                return true;
            }
        }
        return false;
    }
}

$now = time();
$slot_duration = $slot_minutes * 60;

    $start_times = [];
    $curr_time = $cursor;
    while ($curr_time < $limit) {
        $is_conflict = false;
        $conflict_end = null;
        
        $curr_end = $curr_time + ($duration_minutes * 60) + ($break_minutes * 60);

        foreach ($bookings_list as $b) {
            $b_end_buffered = $b['end'] + ($break_minutes * 60);
            
            // Check if our potential slot overlaps with the existing booking
            if ($curr_time < $b_end_buffered && $curr_end > $b['start']) {
                $is_conflict = true;
                if ($conflict_end === null || $b_end_buffered > $conflict_end) {
                    $conflict_end = $b_end_buffered;
                }
            }
        }

        if ($is_conflict) {
            $curr_time = $conflict_end;
        } else {
            $start_times[] = $curr_time;
            $curr_time += $slot_duration;
        }
    }

foreach ($start_times as $s_start) {
    $s_end = min($limit, $s_start + ($duration_minutes * 60));
    $new_booking_buffered_end = $s_end + ($break_minutes * 60);

    $isBooked = false;

    // Check if the entire duration is covered by allowed intervals
    $is_allowed = false;
    foreach ($allowed_intervals as $int) {
        if ($s_start >= $int['start'] && $s_end <= $int['end']) {
            $is_allowed = true;
            break;
        }
    }
    if (!$is_allowed) {
        $covered = 0;
        foreach ($allowed_intervals as $int) {
            if ($int['end'] > $s_start && $int['start'] < $s_end) {
                $c_start = max($s_start, $int['start']);
                $c_end = min($s_end, $int['end']);
                $covered += ($c_end - $c_start);
            }
        }
        if ($covered >= ($s_end - $s_start)) {
            $is_allowed = true;
        }
    }

    if (!$is_allowed) {
        $isBooked = true;
    } else {
        foreach ($bookings_list as $b) {
            // In frontend, we assume break is always needed because customer is not known yet
            $buffer_time = ($break_minutes * 60);
            $existing_buffered_end = $b['end'] + $buffer_time;
            
            if ($s_start < $existing_buffered_end && $new_booking_buffered_end > $b['start']) {
                $isBooked = true;
                break;
            }
        }
    }

    $final_slots[] = [
        "label" => date('h:i A', $s_start) . ' - ' . date('h:i A', $s_end),
        "start_time" => date('H:i', $s_start),
        "end_time" => date('H:i', $s_end),
        "isBooked" => $isBooked,
        "isFrozen" => ($now > $s_start),
        "sort_ts" => $s_start
    ];
}

// Explicitly add actual bookings so they show up as greyed-out blocked slots!
foreach ($bookings_list as $b) {
    if ($b['start'] >= $cursor && $b['start'] < $limit) {
        $final_slots[] = [
            "label" => date('h:i A', $b['start']) . ' - ' . date('h:i A', $b['end']),
            "start_time" => date('H:i', $b['start']),
            "end_time" => date('H:i', $b['end']),
            "isBooked" => true,
            "isFrozen" => false,
            "sort_ts" => $b['start']
        ];
    }
}

usort($final_slots, function($a, $b) {
    return $a['sort_ts'] - $b['sort_ts'];
});

// Remove sort_ts before returning
foreach ($final_slots as &$fs) {
    unset($fs['sort_ts']);
}

returnBackendSlots($final_slots);
?>
