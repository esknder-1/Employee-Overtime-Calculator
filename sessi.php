<?php
session_start();

// Initialize the employee list if it doesn't exist
if (!isset($_SESSION['employees'])) {
    $_SESSION['employees'] = [];
}

// Handle the reset button
if (isset($_POST['reset_data'])) {
    $_SESSION['employees'] = [];
    header("Location: index.php");
    exit();
}
?>
