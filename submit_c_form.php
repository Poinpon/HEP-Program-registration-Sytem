<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php'; 

$user_id = $_SESSION['user_id'];
$association_code = $_POST['association_code'];
$association_name = $_POST['association_name'];
$activity_name = $_POST['activity_name'];
$activity_level = $_POST['activity_level'];
$activity_category = $_POST['activity_category'];
$joint_organizer = $_POST['joint_organizer'];
$activity_venue = $_POST['activity_venue'];
$activity_date = $_POST['activity_date'];

$male = (int)$_POST['participants_male'];
$female = (int)$_POST['participants_female'];
$total = (int)$_POST['participants_total'];

$cost = floatval($_POST['estimated_cost']);
$sponsorship = floatval($_POST['estimated_sponsorship']);
$revenue = floatval($_POST['estimated_revenue']);

$impact_students = $_POST['impact_students'];
$impact_university = $_POST['impact_university'];
$impact_community = $_POST['impact_community'];

// Soft skills: join checkbox[] values as comma-separated string
$soft_skills = isset($_POST['soft_skills']) ? implode(", ", $_POST['soft_skills']) : "";

if (isset($_POST['submission_id'])) {
    // ====== UPDATE existing submission ======
    $submission_id = (int)$_POST['submission_id'];

    $sql = "UPDATE c_form_submissions SET
        association_code=?, association_name=?, activity_name=?, activity_level=?,
        activity_category=?, joint_organizer=?, activity_venue=?, activity_date=?,
        participants_male=?, participants_female=?, participants_total=?,
        estimated_cost=?, estimated_sponsorship=?, estimated_revenue=?,
        impact_students=?, impact_university=?, impact_community=?, soft_skills=? 
        WHERE id=? AND user_id=?";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) die("Prepare failed: " . mysqli_error($conn));

    mysqli_stmt_bind_param($stmt, "ssssssssiiidddssssii", 
    $association_code, $association_name, $activity_name, $activity_level,
    $activity_category, $joint_organizer, $activity_venue, $activity_date,
    $male, $female, $total, $cost, $sponsorship, $revenue,
    $impact_students, $impact_university, $impact_community, $soft_skills,
    $submission_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['form_saved'] = true;  // Set session variable to indicate the form is saved
        echo "<script>alert('Submission updated successfully!'); window.location.href='c_form.php?id={$submission_id}';</script>";
    } else {
        echo "Error updating submission: " . mysqli_error($conn);
    }

} else {
    // ====== INSERT new submission ======
    $sql = "INSERT INTO c_form_submissions (
        user_id, association_code, association_name, activity_name, activity_level,
        activity_category, joint_organizer, activity_venue, activity_date,
        participants_male, participants_female, participants_total,
        estimated_cost, estimated_sponsorship, estimated_revenue,
        impact_students, impact_university, impact_community, soft_skills
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issssssssiiidddssss", 
    $user_id, $association_code, $association_name, $activity_name, $activity_level,
    $activity_category, $joint_organizer, $activity_venue, $activity_date,
    $male, $female, $total, $cost, $sponsorship, $revenue,
    $impact_students, $impact_university, $impact_community, $soft_skills);

    if (mysqli_stmt_execute($stmt)) {
        $submission_id = mysqli_insert_id($conn);
        $_SESSION['form_saved'] = true;  // Set session variable to indicate the form is saved

        // Insert blank rows into other forms to avoid "submission not found" errors later
        $tables = [
            'emerit_submissions',
            'certificate_submissions',
            'book_hall_form',
            'honorarium_submissions'
        ];
        foreach ($tables as $table) {
            $sql_blank = "INSERT INTO {$table} (id, user_id) VALUES (?, ?)";
            $stmt_blank = mysqli_prepare($conn, $sql_blank);
            if (!$stmt_blank) {
                die("Prepare failed for {$table}: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_blank, "ii", $submission_id, $user_id);
            if (!mysqli_stmt_execute($stmt_blank)) {
                die("Error inserting blank into {$table}: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt_blank);
        }

        echo "<script>alert('C Form submitted successfully!'); window.location.href='c_form.php?id={$submission_id}';</script>";
    } else {
        echo "Error inserting submission: " . mysqli_error($conn);
    }
}

// Close the prepared statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!-- For the Next button, add this check to prevent moving forward if the form isn't saved -->
<script>
    document.getElementById("nextBtn").addEventListener("click", function() {
        var formSaved = <?php echo isset($_SESSION['form_saved']) && $_SESSION['form_saved'] === true ? 'true' : 'false'; ?>;
        
        if (!formSaved) { 
            alert('Please save the form first before proceeding to the next form.');
            window.location.href = "c_form.php"; // Redirect back to the current form page
        } else {
            // Proceed to the next form logic here
            window.location.href = "e_merit_form.php"; // Example next form page
        }
    });
</script>
