<?php
include "functions.php";

// Ensure session safety
$employees = $_SESSION['employees'] ?? [];
$overtime = $_SESSION['overtime'] ?? [];

// Calculate overtime per employee
$employeeStats = [];

foreach ($employees as $i => $emp) {
    $employeeStats[$i] = [
        'name' => $emp['name'],
        'wage' => $emp['wage'],
        'regular_hours' => $emp['hours'],
        'ot_hours' => 0,
        'ot_pay' => 0
    ];
}

foreach ($overtime as $ot) {
    $empIndex = $ot['emp'];

    if (!isset($employeeStats[$empIndex])) continue;

    $employeeStats[$empIndex]['ot_hours'] += $ot['hours'];
    $employeeStats[$empIndex]['ot_pay'] += overtimePay(
        $employees[$empIndex]['wage'],
        $ot['hours']
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Summary</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<h1>📊 Employee Summary Report</h1>


<!-- Table -->
<table border="1" cellpadding="10" cellspacing="0" style="margin-top:20px; width:100%;">
    <tr>
        <th>Name</th>
        <th>Hourly Wage</th>
        <th>Regular Hours</th>
        <th>Overtime Hours</th>
        <th>Overtime Pay</th>
    </tr>

    <?php foreach ($employeeStats as $emp): ?>
    <tr>
        <td><?= htmlspecialchars($emp['name']) ?></td>
        <td>$<?= number_format($emp['wage'], 2) ?></td>
        <td><?= $emp['regular_hours'] ?></td>
        <td><?= $emp['ot_hours'] ?></td>
        <td>$<?= number_format($emp['ot_pay'], 2) ?></td>
    </tr>
    <?php endforeach; ?>

</table>

<br>

<a href="index.php">
    <button>⬅ Back to Dashboard</button>
</a>

</body>
</html>
