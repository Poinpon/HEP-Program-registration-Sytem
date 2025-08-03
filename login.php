<?php
session_start();

// Connect to DB
$conn = mysqli_connect("localhost", "root", "", "hep_system");

// Get login input
$user_id = $_POST['user_id'];
$password = $_POST['password'];

$sql = "SELECT id, user_id, is_admin FROM users WHERE user_id = ? AND password = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $user_id, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    $_SESSION['user_id'] = $row['id'];         
    $_SESSION['user_login'] = $row['user_id']; 
    $_SESSION['is_admin'] = (bool)$row['is_admin']; 

    // Redirect based on role
    if ($_SESSION['is_admin']) {
        header("Location: review_submissions.php"); // admin panel
    } else {
        header("Location: dashboard.php"); // normal user page
    }
    exit();
} else {
    echo "<script>alert('Invalid ID or password.'); window.location.href='login.html';</script>";
}
?>
