<?php
// Calculate Overtime Pay: 1.5x hourly rate
function calculateOvertimePay($rate, $hours) {
    return $rate * $hours * 1.5;
}

// Get Total Overtime Payout for all employees
function getTotalOvertimePayout($employees) {
    $total = 0;
    foreach ($employees as $emp) {
        foreach ($emp['overtime_logs'] as $hours) {
            $total += calculateOvertimePay($emp['wage'], $hours);
        }
    }
    return $total;
}

// Find employee with the most overtime hours
function getTopOvertimeEmployee($employees) {
    if (empty($employees)) return "N/A";
    $maxHours = -1;
    $topName = "";
    
    foreach ($employees as $name => $data) {
        $totalHours = array_sum($data['overtime_logs']);
        if ($totalHours > $maxHours) {
            $maxHours = $totalHours;
            $topName = $name;
        }
    }
    return $topName . " (" . $maxHours . " hrs)";
}

// Filter employees by name (Case-insensitive)
function filterEmployeesByName($employees, $searchTerm) {
    if (empty($searchTerm)) return $employees;
    
    return array_filter($employees, function($key) use ($searchTerm) {
        return stripos($key, $searchTerm) !== false;
    }, ARRAY_FILTER_USE_KEY);
}
?>