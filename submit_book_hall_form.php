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

// Collect form data
$name = $_POST['bookhall_name'];
$program = $_POST['program_name'];
$date = $_POST['bookhall_date'];
$equipment = isset($_POST['request_equip']) ? implode(", ", $_POST['request_equip']) : '';

// Check if a row already exists
$check_sql = "SELECT id FROM book_hall_form WHERE id = ? AND user_id = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ii", $submission_id, $user_id);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    // ===== Update existing =====
    $sql = "UPDATE book_hall_form SET
                name = ?, program_name = ?, bookhall_date = ?, request_equip = ?
            WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssii", $name, $program, $date, $equipment, $submission_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Book Hall form updated successfully!');
            window.location.href='book_hall_form.php?id={$submission_id}';
        </script>";
    } else {
        echo "Error updating: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    // ===== Insert new =====
    $sql = "INSERT INTO book_hall_form (id, user_id, name, program_name, bookhall_date, request_equip)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iissss", $submission_id, $user_id, $name, $program, $date, $equipment);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Book Hall form submitted successfully!');
            window.location.href='book_hall_form.php?id={$submission_id}';
        </script>";
    } else {
        echo "Error inserting: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

mysqli_stmt_close($check_stmt);
mysqli_close($conn);
?>
