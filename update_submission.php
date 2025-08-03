<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid submission ID.");
}

$submission_id = (int)$_GET['id'];

// Redirect to C Form with submission ID as a parameter
header("Location: c_form.php?id=$submission_id");
exit();
?>
