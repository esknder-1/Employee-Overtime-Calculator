<?php
include "functions.php";

// Handle forms
if (isset($_POST['add_employee'])) {
    addEmployee($_POST['name'], $_POST['wage'], $_POST['hours']);
}

if (isset($_POST['add_overtime'])) {
    addOvertime($_POST['employee'], $_POST['ot_hours']);
}

if (isset($_POST['delete_ot'])) {
    deleteLastOvertime($_POST['employee']);
}

if (isset($_POST['reset'])) {
    resetAll();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Employee Overtime Tracker</title>
<link rel="stylesheet" href="styles.css">
</head>

<body>

<h1>⏱ Employee Overtime Tracker</h1>
<p class="subtitle">Track overtime hours, calculate premium pay, and view statistics</p>

<div class="container">

<!-- Add Employee -->
<div class="card">
<h3>Add Employee</h3>
<form method="post">
<input name="name" placeholder="Full Name" required>
<input name="wage" type="number" placeholder="Hourly Wage" required>
<input name="hours" type="number" placeholder="Regular Hours" required>
<button class="blue" name="add_employee">Add Employee</button>
</form>
</div>

<!-- Log Overtime -->
<div class="card">
<h3>Log Overtime</h3>
<form method="post">
<select name="employee" required>
<option value="">-- Choose employee --</option>
<?php foreach ($_SESSION['employees'] as $i => $emp): ?>
<option value="<?= $i ?>"><?= $emp['name'] ?></option>
<?php endforeach; ?>
</select>

<input name="ot_hours" type="number" placeholder="Overtime Hours (0-20)" required>

<button class="blue" name="add_overtime">Add Overtime</button>
</form>
</div>

<!-- Actions -->
<div class="card">
<h3>Actions</h3>

<form method="post">
<button class="red" name="reset">Reset All Data</button>
</form>

<form method="post">
<select name="employee" required>
<option value="">-- Select employee --</option>
<?php foreach ($_SESSION['employees'] as $i => $emp): ?>
<option value="<?= $i ?>"><?= $emp['name'] ?></option>
<?php endforeach; ?>
</select>

<button class="orange" name="delete_ot">Delete Last Overtime Entry</button>
</form>

</div>

</div>

<!-- Stats -->
<div class="stats">

<div class="stat-box">
<h2><?= totalEmployees() ?></h2>
<p>Total Employees</p>
</div>

<div class="stat-box">
<h2><?= mostOvertimeEmployee() ?></h2>
<p>Most Overtime Hours</p>
</div>

<div class="stat-box">
<h2>$<?= number_format(avgOvertimePay(), 2) ?></h2>
<p>Avg Overtime Pay</p>
</div>

<div class="stat-box">
<h2>$<?= number_format(totalOvertimePayout(), 2) ?></h2>
<p>Total Overtime Payout</p>
</div>

</div>










<div style="margin-top:20px;">
      <a href="session.php">
    <button class="btn-small">View Full Employee Summary</button>
</a>



</div>
</body>
</html>
