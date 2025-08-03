<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// Collect inputs
$program_name = $_POST['program_name'];
$program_date = $_POST['program_date'];
$venue = $_POST['venue'];
$organizer = $_POST['organizer'];
$coupon = (int)$_POST['coupon'];

if (isset($_POST['submission_id'])) {
    // ===== UPDATE existing submission =====
    $submission_id = (int)$_POST['submission_id'];

    $sql = "UPDATE emerit_submissions SET
        program_name=?, program_date=?, venue=?, organizer=?, coupon=?
        WHERE id=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssiii", $program_name, $program_date, $venue, $organizer, $coupon, $submission_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('E-Merit submission updated successfully!'); window.location.href='e_merit_form.php?id={$submission_id}';</script>";
    } else {
        echo "Error updating submission: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);

} else {
    // ===== INSERT new submission =====
    $sql = "INSERT INTO emerit_submissions (user_id, program_name, program_date, venue, organizer, coupon) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issssi", $user_id, $program_name, $program_date, $venue, $organizer, $coupon);

    if (mysqli_stmt_execute($stmt)) {
        $submission_id = mysqli_insert_id($conn);
        echo "<script>alert('E-Merit submission created successfully!'); window.location.href='e_merit_form.php?id={$submission_id}';</script>";
    } else {
        echo "Error inserting submission: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
