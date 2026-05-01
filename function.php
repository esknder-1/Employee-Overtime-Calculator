<?php
session_start();

if (!isset($_SESSION['employees'])) $_SESSION['employees'] = [];
if (!isset($_SESSION['overtime'])) $_SESSION['overtime'] = [];

// Add employee
function addEmployee($name, $wage, $hours) {
    $_SESSION['employees'][] = [
        'name' => $name,
        'wage' => $wage,
        'hours' => $hours
    ];
}

// Add overtime
function addOvertime($empIndex, $hours) {
    $_SESSION['overtime'][] = [
        'emp' => $empIndex,
        'hours' => $hours
    ];
}

// Delete last overtime
function deleteLastOvertime($empIndex) {
    for ($i = count($_SESSION['overtime']) - 1; $i >= 0; $i--) {
        if ($_SESSION['overtime'][$i]['emp'] == $empIndex) {
            array_splice($_SESSION['overtime'], $i, 1);
            break;
        }
    }
}

// Reset all
function resetAll() {
    $_SESSION['employees'] = [];
    $_SESSION['overtime'] = [];
}

// Overtime pay (1.5x)
function overtimePay($wage, $hours) {
    return $wage * 1.5 * $hours;
}

// Total payout
function totalOvertimePayout() {
    $total = 0;
    foreach ($_SESSION['overtime'] as $ot) {
        $emp = $_SESSION['employees'][$ot['emp']];
        $total += overtimePay($emp['wage'], $ot['hours']);
    }
    return $total;
}

// Average overtime pay
function avgOvertimePay() {
    if (count($_SESSION['overtime']) == 0) return 0;
    return totalOvertimePayout() / count($_SESSION['overtime']);
}

// Most overtime employee
function mostOvertimeEmployee() {
    $totals = [];

    foreach ($_SESSION['overtime'] as $ot) {
        if (!isset($totals[$ot['emp']])) {
            $totals[$ot['emp']] = 0;
        }
        $totals[$ot['emp']] += $ot['hours'];
    }

    if (empty($totals)) return "None";

    $maxIndex = array_keys($totals, max($totals))[0];
    return $_SESSION['employees'][$maxIndex]['name'];
}

// Total employees
function totalEmployees() {
    return count($_SESSION['employees']);
}







?>
