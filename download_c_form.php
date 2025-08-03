<?php
require('fpdf/fpdf.php'); 
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You are not logged in.");
}

// Collect data from the submitted form (via POST)
$association_code = $_POST['association_code'];
$association_name = $_POST['association_name'];
$activity_name = $_POST['activity_name'];
$activity_level = $_POST['activity_level'];
$activity_category = $_POST['activity_category'];
$joint_organizer = $_POST['joint_organizer'];
$activity_venue = $_POST['activity_venue'];
$activity_date = $_POST['activity_date'];
$participants_male = $_POST['participants_male'];
$participants_female = $_POST['participants_female'];
$participants_total = $_POST['participants_total'];
$estimated_cost = $_POST['estimated_cost'];
$estimated_sponsorship = $_POST['estimated_sponsorship'];
$estimated_revenue = $_POST['estimated_revenue'];
$impact_students = $_POST['impact_students'];
$impact_university = $_POST['impact_university'];
$impact_community = $_POST['impact_community'];
$soft_skill = isset($_POST['soft_skill']) 
    ? (is_array($_POST['soft_skill']) ? implode(", ", $_POST['soft_skill']) : $_POST['soft_skill']) 
    : '';

// Create the PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'C Form Submission', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

function addRow($pdf, $label, $value) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 8, $label, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, $value);
}

// Add form values to the PDF
addRow($pdf, 'Association Code:', $association_code);
addRow($pdf, 'Association Name:', $association_name);
addRow($pdf, 'Activity Name:', $activity_name);
addRow($pdf, 'Activity Level:', $activity_level);
addRow($pdf, 'Activity Category:', $activity_category);
addRow($pdf, 'Joint Organizer:', $joint_organizer);
addRow($pdf, 'Venue:', $activity_venue);
addRow($pdf, 'Date:', $activity_date);
addRow($pdf, 'Participants (Male):', $participants_male);
addRow($pdf, 'Participants (Female):', $participants_female);
addRow($pdf, 'Participants (Total):', $participants_total);
addRow($pdf, 'Estimated Cost (RM):', $estimated_cost);
addRow($pdf, 'Estimated Sponsorship (RM):', $estimated_sponsorship);
addRow($pdf, 'Estimated Revenue (RM):', $estimated_revenue);
addRow($pdf, 'Impact on Students:', $impact_students);
addRow($pdf, 'Impact on University:', $impact_university);
addRow($pdf, 'Impact on Community:', $impact_community);
addRow($pdf, 'Soft Skills:', $soft_skill);

// Output PDF in browser
$pdf->Output("I", "C_Form.pdf");
