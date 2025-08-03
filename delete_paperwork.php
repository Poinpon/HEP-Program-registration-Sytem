<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$submission_id = isset($_POST['submission_id']) ? (int)$_POST['submission_id'] : 0;

$sql = "SELECT file_path FROM uploaded_paperwork WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $submission_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $file_path = $row['file_path'];

    // Delete file from filesystem
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Delete record from DB
    $delete_sql = "DELETE FROM uploaded_paperwork WHERE id = ? AND user_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "ii", $submission_id, $user_id);
    mysqli_stmt_execute($delete_stmt);
    mysqli_stmt_close($delete_stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

header("Location: upload_paperwork.php?id=" . $submission_id);
exit();
?>
