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
$contact_num = $_POST['contact_num'];
$faculty_unit = $_POST['faculty_unit'];
$application_date = $_POST['application_date'];
$purpose_use = $_POST['purpose_use'];
$use_date = $_POST['use_date'];
$use_time = $_POST['use_time'];
$expected_num = $_POST['expected_num'];

// Handle facilities
$facilities = isset($_POST['request_facilities']) ? $_POST['request_facilities'] : [];
$other_facility = trim($_POST['other_facility'] ?? '');

if ($other_facility !== '' && in_array("Others", $facilities)) {
    $facilities[] = "Others: " . $other_facility;
}

$final_facilities = implode(", ", $facilities);

// Check if a row exists
$check_sql = "SELECT id FROM honorarium_submissions WHERE id = ? AND user_id = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ii", $submission_id, $user_id);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    // ===== UPDATE existing record =====
    $sql = "UPDATE honorarium_submissions SET 
                contact_num = ?, faculty_unit = ?, application_date = ?, purpose_use = ?,
                use_date = ?, use_time = ?, expected_num = ?, request_facilities = ?
            WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssii",
        $contact_num, $faculty_unit, $application_date, $purpose_use,
        $use_date, $use_time, $expected_num, $final_facilities,
        $submission_id, $user_id
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Honorarium form updated successfully!');
            window.location.href='honorarium_form.php?id={$submission_id}';
        </script>";
    } else {
        echo "Error updating: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);

} else {
    // ===== INSERT new record =====
    $sql = "INSERT INTO honorarium_submissions (
                id, user_id, contact_num, faculty_unit, application_date, purpose_use,
                use_date, use_time, expected_num, request_facilities
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iissssssss",
        $submission_id, $user_id, $contact_num, $faculty_unit, $application_date,
        $purpose_use, $use_date, $use_time, $expected_num, $final_facilities
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Honorarium form submitted successfully!');
            window.location.href='honorarium_form.php?id={$submission_id}';
        </script>";
    } else {
        echo "Error inserting: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_stmt_close($check_stmt);
mysqli_close($conn);
?>
