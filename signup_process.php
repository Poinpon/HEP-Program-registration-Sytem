<?php
$conn = mysqli_connect("localhost", "root", "", "hep_system");

$user_id = trim($_POST['user_id']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// Check for password match
if ($password !== $confirm) {
    echo "<script>alert('Passwords do not match.'); window.location.href='signup.php';</script>";
    exit();
}

// Check if user already exists
$check = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($check, "s", $user_id);
mysqli_stmt_execute($check);
$result = mysqli_stmt_get_result($check);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('User ID already exists.'); window.location.href='signup.php';</script>";
    exit();
}

// Insert new user
$stmt = mysqli_prepare($conn, "INSERT INTO users (user_id, password, is_admin) VALUES (?, ?, 0)");
mysqli_stmt_bind_param($stmt, "ss", $user_id, $password);
mysqli_stmt_execute($stmt);

echo "<script>alert('Account created! You may now log in.'); window.location.href='login.html';</script>";
?>
