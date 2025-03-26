<?php
// Include the database connection
include 'db.php';

// Include the TCPDF library
require_once('tcpdf/tcpdf.php'); // Adjust this path if necessary

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Access Denied! Please login.";
    exit;
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];

// Query to fetch user details from the database
$query = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch other details (skills, projects, work experience, certifications)
$query_skills = "SELECT * FROM skills WHERE user_id = ?";
$stmt = $conn->prepare($query_skills);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$skills_result = $stmt->get_result();

$query_projects = "SELECT * FROM projects WHERE user_id = ?";
$stmt = $conn->prepare($query_projects);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$projects_result = $stmt->get_result();

$query_experience = "SELECT * FROM work_experience WHERE user_id = ?";
$stmt = $conn->prepare($query_experience);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$experience_result = $stmt->get_result();

$query_certifications = "SELECT * FROM certifications WHERE user_id = ?";
$stmt = $conn->prepare($query_certifications);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certifications_result = $stmt->get_result();

// Build HTML content with inline CSS styling
$html = '
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; }
    h1 { text-align: center; font-size: 16px; color: #333; margin-bottom: 10px; }
    h2 { font-size: 14px; color: #007BFF; border-bottom: 1px solid #ccc; padding-bottom: 3px; margin-top: 10px; }
    p { font-size: 10px; line-height: 1.3; margin: 3px 0; }
    ul { list-style: none; padding-left: 0; }
    li { font-size: 10px; margin-bottom: 2px; }
    .section { margin-bottom: 8px; }
    .entry { margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 3px; }
</style>
';

$html .= '<h1>Resume - ' . htmlspecialchars($user['name']) . '</h1>';

// Personal Information
$html .= '<div class="section"><h2>Personal Information</h2>';
$html .= '<p><strong>Name:</strong> ' . htmlspecialchars($user['name']) . '</p>';
$html .= '<p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
$html .= '<p><strong>Phone:</strong> ' . htmlspecialchars($user['phone']) . '</p>';
$html .= '<p><strong>Education:</strong> ' . htmlspecialchars($user['education']) . '</p>';
$html .= '<p><strong>Experience:</strong> ' . htmlspecialchars($user['experience']) . ' years</p>';
$html .= '<p><strong>LinkedIn:</strong> ' . htmlspecialchars($user['linkedin']) . '</p>';
$html .= '<p><strong>Portfolio:</strong> ' . htmlspecialchars($user['portfolio']) . '</p>';
$html .= '</div>';

// Skills
$html .= '<div class="section"><h2>Skills</h2><ul>';
while ($skill = $skills_result->fetch_assoc()) {
    $html .= '<li>- ' . htmlspecialchars($skill['skill_name']) . '</li>';
}
$html .= '</ul></div>';

// Projects
$html .= '<div class="section"><h2>Projects</h2><ul>';
while ($project = $projects_result->fetch_assoc()) {
    $html .= '<li><strong>' . htmlspecialchars($project['project_name']) . ':</strong> ' . htmlspecialchars($project['project_description']) . '</li>';
}
$html .= '</ul></div>';

// Work Experience
$html .= '<div class="section"><h2>Work Experience</h2>';
while ($work = $experience_result->fetch_assoc()) {
    $html .= '<div class="entry">';
    $html .= '<p><strong>Job Title:</strong> ' . htmlspecialchars($work['job_title']) . '</p>';
    $html .= '<p><strong>Company:</strong> ' . htmlspecialchars($work['company_name']) . '</p>';
    $html .= '<p><strong>Start Date:</strong> ' . htmlspecialchars($work['start_date']) . '</p>';
    $html .= '<p><strong>End Date:</strong> ' . htmlspecialchars($work['end_date'] ?: 'Present') . '</p>';
    $html .= '<p><strong>Description:</strong> ' . nl2br(htmlspecialchars($work['job_description'])) . '</p>';
    $html .= '<p><strong>Location:</strong> ' . htmlspecialchars($work['location']) . '</p>';
    $html .= '<p><strong>Responsibilities:</strong> ' . nl2br(htmlspecialchars($work['responsibilities'])) . '</p>';
    $html .= '<p><strong>Added On:</strong> ' . htmlspecialchars($work['created_at']) . '</p>';
    $html .= '</div>';
}
$html .= '</div>';

// Certifications
$html .= '<div class="section"><h2>Certifications</h2>';
while ($cert = $certifications_result->fetch_assoc()) {
    $html .= '<div class="entry">';
    $html .= '<p><strong>Certification:</strong> ' . htmlspecialchars($cert['certification_name']) . '</p>';
    $html .= '<p><strong>Issued By:</strong> ' . htmlspecialchars($cert['issuing_organization']) . '</p>';
    $html .= '<p><strong>Issue Date:</strong> ' . htmlspecialchars($cert['issue_date']) . '</p>';
    $html .= '<p><strong>Expiration Date:</strong> ' . htmlspecialchars($cert['expiration_date'] ?: 'No Expiration') . '</p>';
    $html .= '<p><strong>Certificate ID:</strong> ' . htmlspecialchars($cert['certification_id']) . '</p>';
    $html .= '<p><strong>URL:</strong> <a href="' . htmlspecialchars($cert['certification_url']) . '" target="_blank">View</a></p>';
    $html .= '</div>';
}
$html .= '</div>';

// Initialize TCPDF and force all content on one page
$pdf = new TCPDF();
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->AddPage();
// Disable auto page breaks to force single-page output
$pdf->SetAutoPageBreak(false, 0);

// Optionally, reduce font size if needed
$pdf->SetFont('helvetica', '', 10);

// Write the HTML content to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF inline in the browser
$pdf->Output('resume_' . $user['name'] . '.pdf', 'I');
?>
