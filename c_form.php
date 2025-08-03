<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php'; 

$user_id = $_SESSION['user_id'];
$editing = false;
$submissionData = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $submission_id = (int)$_GET['id'];
    $sql = "SELECT * FROM c_form_submissions WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $submission_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $editing = true;
        $submissionData = $row;
    } else {
        echo "<script>alert('Submission not found or not yours.'); window.location.href='my_submissions.php';</script>";
        exit();
    }
    mysqli_stmt_close($stmt);
}

$softSkillsArray = [];
if ($editing && !empty($submissionData['soft_skills'])) {
    $softSkillsArray = array_map('trim', explode(',', $submissionData['soft_skills']));
}

// Store form data in session if the form is being filled out or updated
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>C Form</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="navbar">
        <img src="assets/uitm logo.jpeg" alt="Logo" class="logo">
        <div class="menu-toggle" onclick="toggleNav()">
            <div class="bar"></div><div class="bar"></div><div class="bar"></div>
        </div>
        <div class="nav-title">HEP Program Registration System</div>
        <div class="nav-user">Logged in as: <strong><?php echo $_SESSION['user_login']; ?></strong></div>
    </div>

    <div id="sideNav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleNav()">×</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="submit_program.php">Submit Program</a>
        <a href="my_submissions.php">My Submissions</a>
        <a href="logout.php">Log Out</a>
    </div>

    <div class="form-tabs">
        <a href="c_form.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) === 'c_form.php' ? 'active' : ''; ?>">C Form</a>
        <a href="e_merit_form.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) === 'e_merit_form.php' ? 'active' : ''; ?>">E-Merit</a>
        <a href="certificate_form.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) === 'certificate_form.php' ? 'active' : ''; ?>">Certificate</a>
        <a href="book_hall_form.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) === 'book_hall_form.php' ? 'active' : ''; ?>">Book Hall</a>
        <a href="honorarium_form.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) === 'honorarium_form.php' ? 'active' : ''; ?>">Honorarium</a>
        <a href="upload_paperwork.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) === 'upload_paperwork.php' ? 'active' : ''; ?>">Paperwork</a>
    </div>

    <div class="form-section">
        <form action="submit_c_form.php" method="POST" class="form-grid" id="cForm">
            <?php if ($editing): ?>
                <input type="hidden" name="submission_id" value="<?php echo $submissionData['id']; ?>">
            <?php endif; ?>

            <label for="association_code">Association Code:</label>
            <input type="text" id="association_code" name="association_code" required value="<?php echo htmlspecialchars(isset($formData['association_code']) ? $formData['association_code'] : ($editing ? $submissionData['association_code'] : '')); ?>">

            <label for="association_name">Association Name:</label>
            <input type="text" id="association_name" name="association_name" required value="<?php echo htmlspecialchars(isset($formData['association_name']) ? $formData['association_name'] : ($editing ? $submissionData['association_name'] : '')); ?>">

            <label for="activity_name">Activity Name:</label>
            <input type="text" id="activity_name" name="activity_name" required value="<?php echo htmlspecialchars(isset($formData['activity_name']) ? $formData['activity_name'] : ($editing ? $submissionData['activity_name'] : '')); ?>">

            <label>Activity Level:</label>
            <div class="radio-group">
                <?php
                $levels = ["International", "State", "University", "National", "District", "Faculty", "Association/Club", "Residential College"];
                foreach ($levels as $level):
                ?>
                    <label><input type="radio" name="activity_level" value="<?php echo $level; ?>" required
                        <?php if (isset($formData['activity_level']) && $formData['activity_level'] === $level) echo 'checked'; ?>
                        <?php if ($editing && $submissionData['activity_level'] === $level) echo 'checked'; ?>> <?php echo $level; ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <label>Activity Category:</label>
            <div class="radio-group">
                <?php
                $categories = ["Academic/Intellectual", "Entrepreneurship", "Culture/Heritage", "Public Speaking", "Spiritual", "Sports & Recreation", "Volunteering", "Intellectual Discourse", "Science, Technology & Innovation"];
                foreach ($categories as $category):
                ?>
                    <label><input type="radio" name="activity_category" value="<?php echo $category; ?>" required
                        <?php if (isset($formData['activity_category']) && $formData['activity_category'] === $category) echo 'checked'; ?>
                        <?php if ($editing && $submissionData['activity_category'] === $category) echo 'checked'; ?>> <?php echo $category; ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <label for="joint_organizer">Joint Organizer (if any):</label>
            <input type="text" id="joint_organizer" name="joint_organizer" value="<?php echo htmlspecialchars(isset($formData['joint_organizer']) ? $formData['joint_organizer'] : ($editing ? $submissionData['joint_organizer'] : '')); ?>">

            <label for="activity_venue">Activity Venue:</label>
            <input type="text" id="activity_venue" name="activity_venue" required value="<?php echo htmlspecialchars(isset($formData['activity_venue']) ? $formData['activity_venue'] : ($editing ? $submissionData['activity_venue'] : '')); ?>">

            <label for="activity_date">Activity Date:</label>
            <input type="date" id="activity_date" name="activity_date" required value="<?php echo htmlspecialchars(isset($formData['activity_date']) ? $formData['activity_date'] : ($editing ? $submissionData['activity_date'] : '')); ?>">

            <label>Number of Participants:</label>
            <div class="participants-row">
                <div class="participant-field">
                    <label>Male</label>
                    <input type="number" name="participants_male" id="participants_male" required value="<?php echo htmlspecialchars(isset($formData['participants_male']) ? $formData['participants_male'] : ($editing ? $submissionData['participants_male'] : '')); ?>">
                </div>
                <div class="participant-field">
                    <label>Female</label>
                    <input type="number" name="participants_female" id="participants_female" required value="<?php echo htmlspecialchars(isset($formData['participants_female']) ? $formData['participants_female'] : ($editing ? $submissionData['participants_female'] : '')); ?>">
                </div>
                <div class="participant-field">
                    <label>Total</label>
                    <input type="number" name="participants_total" id="participants_total" required readonly value="<?php echo htmlspecialchars(isset($formData['participants_total']) ? $formData['participants_total'] : ($editing ? $submissionData['participants_total'] : '')); ?>">
                </div>
            </div>

            <label>Estimated Cost (RM):</label>
            <input type="number" step="0.01" name="estimated_cost" required value="<?php echo htmlspecialchars(isset($formData['estimated_cost']) ? $formData['estimated_cost'] : ($editing ? $submissionData['estimated_cost'] : '')); ?>">

            <label>Estimated Sponsorship (RM):</label>
            <input type="number" step="0.01" name="estimated_sponsorship" required value="<?php echo htmlspecialchars(isset($formData['estimated_sponsorship']) ? $formData['estimated_sponsorship'] : ($editing ? $submissionData['estimated_sponsorship'] : '')); ?>">

            <label>Estimated Revenue (RM):</label>
            <input type="number" step="0.01" name="estimated_revenue" required value="<?php echo htmlspecialchars(isset($formData['estimated_revenue']) ? $formData['estimated_revenue'] : ($editing ? $submissionData['estimated_revenue'] : '')); ?>">

            <label>Impact on Students:</label>
            <input type="text" name="impact_students" required value="<?php echo htmlspecialchars(isset($formData['impact_students']) ? $formData['impact_students'] : ($editing ? $submissionData['impact_students'] : '')); ?>">

            <label>Impact on University:</label>
            <input type="text" name="impact_university" required value="<?php echo htmlspecialchars(isset($formData['impact_university']) ? $formData['impact_university'] : ($editing ? $submissionData['impact_university'] : '')); ?>">

            <label>Impact on Community:</label>
            <input type="text" name="impact_community" required value="<?php echo htmlspecialchars(isset($formData['impact_community']) ? $formData['impact_community'] : ($editing ? $submissionData['impact_community'] : '')); ?>">

            <label>Soft Skills (tick as applicable):</label>
            <div class="radio-group">
                <?php
                $skills = ["Communication", "Critical Thinking", "Teamwork", "Leadership", "Ethics"];
                foreach ($skills as $skill):
                ?>
                    <label><input type="checkbox" name="soft_skills[]" value="<?php echo $skill; ?>"
                        <?php if (isset($formData['soft_skills']) && in_array($skill, $formData['soft_skills'])) echo 'checked'; ?>
                        <?php if (in_array($skill, $softSkillsArray)) echo 'checked'; ?>> <?php echo $skill; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </form>

        <div class="form-buttons">
            <a href="submit_program.php<?php echo $editing ? '?id=' . $submissionData['id'] : ''; ?>" class="button back-button">← Back</a>
            <button type="submit" form="cForm" class="button save-button" name="save">Save</button>
            <a href="javascript:void(0);" onclick="checkFormSaved()" class="button next-button">Next →</a>
        </div>

    </div>

    <script>
        function toggleNav() {
            const sideNav = document.getElementById("sideNav");
            const burger = document.querySelector(".menu-toggle");
            if (sideNav.style.width === "250px") {
                sideNav.style.width = "0";
                burger.style.display = "flex";
            } else {
                sideNav.style.width = "250px";
                burger.style.display = "none";
            }
        }

        function updateTotalParticipants() {
            const male = parseInt(document.getElementById('participants_male').value) || 0;
            const female = parseInt(document.getElementById('participants_female').value) || 0;
            document.getElementById('participants_total').value = male + female;
        }

        document.getElementById('participants_male').addEventListener('input', updateTotalParticipants);
        document.getElementById('participants_female').addEventListener('input', updateTotalParticipants);

        function checkFormSaved() {
            <?php 
            if (!isset($_SESSION['form_saved']) || $_SESSION['form_saved'] !== true) { 
            ?>
                alert('Please save the form first before proceeding to the next form.');
                window.location.href = "c_form.php"; // Redirect back to the current form page
            <?php
            } else {
            ?>
                window.location.href = "e_merit_form.php<?php echo isset($submissionData['id']) ? '?id=' . $submissionData['id'] : ''; ?>";
            <?php } ?>
        }
    </script>

</body>
</html>
