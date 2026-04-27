<?php
session_start();

/* ---------- INIT STORAGE ---------- */
if (!isset($_SESSION['employees'])) {
    $_SESSION['employees'] = [];
}

/* ---------- ADD EMPLOYEE ---------- */
if (isset($_POST['add_employee'])) {
    $name = $_POST['full_name'];
    $wage = floatval($_POST['hourly_wage']);
    $reg = floatval($_POST['reg_hours']);

    $_SESSION['employees'][] = [
        'name' => $name,
        'wage' => $wage,
        'reg_hours' => $reg,
        'ot_hours' => 0,
        'logs' => []
    ];
}

/* ---------- LOG OVERTIME ---------- */
if (isset($_POST['log_ot'])) {
    foreach ($_SESSION['employees'] as &$emp) {
        if ($emp['name'] == $_POST['employee_name']) {
            $hours = floatval($_POST['ot_hours']);
            $emp['ot_hours'] += $hours;
            $emp['logs'][] = $hours;
        }
    }
}

/* ---------- DELETE LAST OT ---------- */
if (isset($_POST['delete_last_ot'])) {
    foreach ($_SESSION['employees'] as &$emp) {
        if ($emp['name'] == $_POST['delete_employee']) {
            if (!empty($emp['logs'])) {
                $last = array_pop($emp['logs']);
                $emp['ot_hours'] -= $last;
            }
        }
    }
}

/* ---------- RESET ALL ---------- */
if (isset($_POST['reset_all'])) {
    $_SESSION['employees'] = [];
}

/* ---------- GET DATA ---------- */
$employees = $_SESSION['employees'];

/* ---------- STATS ---------- */
$count = count($employees);
$total = 0;
$max_ot = 0;
$top = "None";

foreach ($employees as $e) {
    $ot_pay = $e['ot_hours'] * ($e['wage'] * 1.5);
    $total += $ot_pay;

    if ($e['ot_hours'] > $max_ot) {
        $max_ot = $e['ot_hours'];
        $top = $e['name'];
    }
}

$avg = $count > 0 ? $total / $count : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Overtime Tracker</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; }
        .container { width: 90%; margin:auto; }
        h2 { text-align:center; }
        .grid { display:flex; gap:20px; margin-bottom:20px; }
        .card { background:white; padding:20px; border-radius:10px; flex:1; }
        input, select, button { width:100%; margin:5px 0; padding:8px; }
        .btn-blue { background:#3498db; color:white; border:none; }
        .btn-red { background:#e74c3c; color:white; border:none; }
        .btn-yellow { background:#f1c40f; border:none; }
        table { width:100%; border-collapse:collapse; background:white; }
        th, td { padding:10px; border:1px solid #ddd; text-align:center; }
        .stats-grid { display:flex; gap:10px; margin-bottom:20px; }
        .stat-box { flex:1; background:white; padding:15px; text-align:center; border-radius:8px; }
    </style>
</head>
<body>

<div class="container">
    <h2>⏱ Employee Overtime Tracker</h2>

    <div class="grid">
        <!-- ADD EMPLOYEE -->
        <div class="card">
            <h3>Add Employee</h3>
            <form method="POST">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="number" name="hourly_wage" placeholder="Hourly Wage" step="0.01" required>
                <input type="number" name="reg_hours" placeholder="Regular Hours" required>
                <button type="submit" name="add_employee" class="btn-blue">Add</button>
            </form>
        </div>

        <!-- LOG OT -->
        <div class="card">
            <h3>Log Overtime</h3>
            <form method="POST">
                <select name="employee_name" required>
                    <option value="">Select</option>
                    <?php foreach ($employees as $e): ?>
                        <option value="<?= $e['name'] ?>"><?= $e['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="ot_hours" step="0.1" required>
                <button type="submit" name="log_ot" class="btn-blue">Add OT</button>
            </form>
        </div>

        <!-- ACTIONS -->
        <div class="card">
            <h3>Actions</h3>
            <form method="POST">
                <button type="submit" name="reset_all" class="btn-red">Reset All</button>
            </form>

            <form method="POST">
                <select name="delete_employee" required>
                    <option value="">Select</option>
                    <?php foreach ($employees as $e): ?>
                        <option value="<?= $e['name'] ?>"><?= $e['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="delete_last_ot" class="btn-yellow">Delete Last OT</button>
            </form>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-box">Employees<br><?= $count ?></div>
        <div class="stat-box">Top OT<br><?= $top ?></div>
        <div class="stat-box">Avg Pay<br>$<?= number_format($avg,2) ?></div>
        <div class="stat-box">Total<br>$<?= number_format($total,2) ?></div>
    </div>

    <!-- TABLE -->
    <table>
        <tr>
            <th>Name</th>
            <th>Reg Hours</th>
            <th>Wage</th>
            <th>OT Hours</th>
            <th>OT Pay</th>
            <th>Total</th>
            <th>Logs</th>
        </tr>

        <?php foreach ($employees as $emp):
            $ot_pay = $emp['ot_hours'] * ($emp['wage'] * 1.5);
            $reg_pay = $emp['reg_hours'] * $emp['wage'];
        ?>
        <tr>
            <td><?= $emp['name'] ?></td>
            <td><?= $emp['reg_hours'] ?></td>
            <td>$<?= $emp['wage'] ?></td>
            <td><?= $emp['ot_hours'] ?></td>
            <td>$<?= number_format($ot_pay,2) ?></td>
            <td>$<?= number_format($reg_pay + $ot_pay,2) ?></td>
            <td>
                <?= !empty($emp['logs']) ? implode(', ', $emp['logs']) : '-' ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
