<?php 
require_once 'sessi.php';
require_once __DIR__ . '/fun.php';
// Handle adding new employee
if (isset($_POST['add_employee'])) {
    $name = htmlspecialchars($_POST['name']);
    $wage = (float)$_POST['wage'];
    if ($wage >= 10) {
        $_SESSION['employees'][$name] = [
            'wage' => $wage,
            'overtime_logs' => []
        ];
    }
}

// Handle logging overtime
if (isset($_POST['log_ot'])) {
    $name = $_POST['emp_name'];
    $hours = (float)$_POST['ot_hours'];
    if ($hours > 0 && $hours <= 20 && isset($_SESSION['employees'][$name])) {
        $_SESSION['employees'][$name]['overtime_logs'][] = $hours;
    }
}

$searchTerm = $_GET['search'] ?? '';
$displayList = filterEmployeesByName($_SESSION['employees'], $searchTerm);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Employee Overtime Tracker</title>
</head>
<body>
    <h1>Overtime Tracker</h1>

    <div class="forms-container">
        <form method="POST">
            <h3> ➕Add Employee</h3>
            <input type="text" name="name" placeholder="Name" required>
            <input type="number" name="wage" min="10" step="0.01" placeholder="Wage (min $10)" required>
            <button type="submit" name="add_employee">Add</button>
        </form>

        <form method="POST">
            <h3>⏰Log Overtime</h3>
            <select name="emp_name">
                <?php foreach($_SESSION['employees'] as $name => $data): ?>
                    <option value="<?= $name ?>"><?= $name ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="ot_hours" min="0" max="20" step="0.5" required>
            <button type="submit" name="log_ot">Log Hours</button>
        </form>
    </div>

    <hr>

    <form method="GET">
        <input type="text" name="search" placeholder="Search by name..." value="<?= $searchTerm ?>">
        <button type="submit">Filter</button>
    </form>

    <div class="stats">
        <p><strong>Total Payout:</strong> $<?= number_format(getTotalOvertimePayout($_SESSION['employees']), 2) ?></p>
        <p><strong>Top Worker:</strong> <?= getTopOvertimeEmployee($_SESSION['employees']) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Hourly Wage</th>
                <th>Total OT Hours</th>
                <th>OT Pay (1.5x)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            $keys = array_keys($displayList);
            while ($i < count($keys)): 
                $name = $keys[$i];
                $data = $displayList[$name];
                $totalOT = array_sum($data['overtime_logs']);
                $otPay = calculateOvertimePay($data['wage'], $totalOT);
            ?>
            <tr>
                <td><?= $name ?></td>
                <td>$<?= number_format($data['wage'], 2) ?></td>
                <td><?= $totalOT ?></td>
                <td>$<?= number_format($otPay, 2) ?></td>
            </tr>
            <?php $i++; endwhile; ?>
        </tbody>
    </table>

    <form method="POST" style="margin-top: 20px;">
        <button type="submit" name="reset_data" style="background: red; color: white;">Reset All Data</button>
    </form>
</body>
</html>