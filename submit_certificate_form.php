<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$submission_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$submission_id) {
    echo "Error: submission ID missing.";
    exit();
}

// Collect form inputs
$association   = trim($_POST['association']);
$program_name  = trim($_POST['program_name']);
$date          = $_POST['date'];
$venue         = trim($_POST['venue']);
$report_date   = $_POST['report_date'];

// Check if a row already exists in certificate_submissions for this ID
$check_sql = "SELECT id FROM certificate_submissions WHERE id = ? AND user_id = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ii", $submission_id, $user_id);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    // ===== UPDATE existing certificate submission =====
    $sql = "UPDATE certificate_submissions SET
                association = ?, program_name = ?, date = ?, venue = ?, report_date = ?
            WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssii", 
        $association, $program_name, $date, $venue, $report_date,
        $submission_id, $user_id
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Certificate form updated successfully!');
            window.location.href='certificate_form.php?id={$submission_id}';
        </script>";
    } else {
        echo "Error updating certificate: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);

} else {
    // ===== INSERT new certificate submission =====
    $sql = "INSERT INTO certificate_submissions (
                id, user_id, association, program_name, date, venue, report_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisssss", 
        $submission_id, $user_id, $association, $program_name, $date, $venue, $report_date
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Certificate form submitted successfully!');
            window.location.href='certificate_form.php?id={$submission_id}';
        </script>";
    } else {
        echo "Error inserting certificate: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_stmt_close($check_stmt);
mysqli_close($conn);
?>
