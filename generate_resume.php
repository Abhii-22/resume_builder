<?php
session_start();
require 'db.php';
require_once('tcpdf/tcpdf.php'); // Adjust path if necessary

if (!isset($_SESSION['user_id'])) {
    echo "Access Denied! Please login.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user profile from the user_profiles table.
$stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch skills.
$stmt = $conn->prepare("SELECT skill_name FROM skills WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$skills_result = $stmt->get_result();
$skills = [];
while($row = $skills_result->fetch_assoc()){
    $skills[] = $row['skill_name'];
}
$stmt->close();

// Fetch projects.
$stmt = $conn->prepare("SELECT * FROM projects WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$projects_result = $stmt->get_result();
$projects = [];
while($row = $projects_result->fetch_assoc()){
    $projects[] = $row;
}
$stmt->close();

// Fetch work experience.
$stmt = $conn->prepare("SELECT * FROM work_experience WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$work_result = $stmt->get_result();
$work_experiences = [];
while($row = $work_result->fetch_assoc()){
    $work_experiences[] = $row;
}
$stmt->close();

// Fetch certifications.
$stmt = $conn->prepare("SELECT * FROM certifications WHERE user_id = ? ORDER BY issue_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cert_result = $stmt->get_result();
$certifications = [];
while($row = $cert_result->fetch_assoc()){
    $certifications[] = $row;
}
$stmt->close();

// Build HTML content with inline CSS styling.
$profilePhoto = "";
if (!empty($user['profile_photo']) && file_exists("uploads/" . $user['profile_photo'])) {
    $profilePhoto = "uploads/" . $user['profile_photo'];
}

$html = '
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; }
    .header { display: flex; align-items: center; }
    .header .photo { flex: 0 0 100px; }
    .header .photo img { width: 100px; height: 100px; border-radius: 50%; }
    .header .info { flex: 1; padding-left: 20px; }
    .header .info p { margin: 3px 0; }
    .section { margin-top: 20px; }
    .section h2 { font-size: 16px; color: #007BFF; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px; }
    .section p { margin: 4px 0; }
    ul { list-style: none; padding-left: 0; }
    ul li { background-color: #f9f9f9; margin-bottom: 3px; padding: 5px; border-radius: 3px; }
</style>';

// Header section with profile photo and personal info.
$html .= '<div class="header">';
if ($profilePhoto != "") {
    $html .= '<div class="photo"><img src="' . $profilePhoto . '" alt="Profile Photo"></div>';
} else {
    $html .= '<div class="photo"><div style="width:100px;height:100px;background:#ccc;border-radius:50%;text-align:center;line-height:100px;color:#fff;font-size:12px;">No Photo</div></div>';
}
$html .= '<div class="info">';
$html .= '<p><strong>Name:</strong> ' . htmlspecialchars($user['name']) . '</p>';
$html .= '<p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
$html .= '<p><strong>Phone:</strong> ' . htmlspecialchars($user['phone']) . '</p>';
$html .= '<p><strong>Education:</strong> ' . htmlspecialchars($user['education']) . '</p>';
$html .= '<p><strong>Experience:</strong> ' . htmlspecialchars($user['experience']) . ' years</p>';
$html .= '<p><strong>LinkedIn:</strong> ' . htmlspecialchars($user['linkedin']) . '</p>';
$html .= '<p><strong>Portfolio:</strong> ' . htmlspecialchars($user['portfolio']) . '</p>';
$html .= '<p><strong>GitHub:</strong> ' . htmlspecialchars($user['github']) . '</p>';
$html .= '<p><strong>Twitter:</strong> ' . htmlspecialchars($user['twitter']) . '</p>';
$html .= '<p><strong>Website:</strong> ' . htmlspecialchars($user['website']) . '</p>';
$html .= '<p><strong>Address:</strong> ' . htmlspecialchars($user['address']) . '</p>';
$html .= '</div></div>';

// Skills Section.
$html .= '<div class="section"><h2>Skills</h2>';
if (!empty($skills)) {
    $html .= '<ul>';
    foreach ($skills as $skill) {
        $html .= '<li>' . htmlspecialchars($skill) . '</li>';
    }
    $html .= '</ul>';
} else {
    $html .= '<p>No skills added yet.</p>';
}
$html .= '</div>';

// Projects Section.
$html .= '<div class="section"><h2>Projects</h2>';
if (!empty($projects)) {
    $html .= '<ul>';
    foreach ($projects as $project) {
        $html .= '<li><strong>' . htmlspecialchars($project['project_name']) . ':</strong> ' . htmlspecialchars($project['project_description']) . '</li>';
    }
    $html .= '</ul>';
} else {
    $html .= '<p>No projects added yet.</p>';
}
$html .= '</div>';

// Work Experience Section.
$html .= '<div class="section"><h2>Work Experience</h2>';
if (!empty($work_experiences)) {
    foreach ($work_experiences as $work) {
        $html .= '<p><strong>' . htmlspecialchars($work['job_title']) . ' at ' . htmlspecialchars($work['company_name']) . '</strong></p>';
        $html .= '<p>From: ' . htmlspecialchars($work['start_date']) . ' To: ' . htmlspecialchars($work['end_date'] ?: 'Present') . '</p>';
        $html .= '<p>' . nl2br(htmlspecialchars($work['job_description'])) . '</p>';
        $html .= '<hr>';
    }
} else {
    $html .= '<p>No work experience added yet.</p>';
}
$html .= '</div>';

// Certifications Section.
$html .= '<div class="section"><h2>Certifications</h2>';
if (!empty($certifications)) {
    foreach ($certifications as $cert) {
        $html .= '<p><strong>' . htmlspecialchars($cert['certification_name']) . '</strong> - ' . htmlspecialchars($cert['issuing_organization']) . '</p>';
        $html .= '<p>Issued: ' . htmlspecialchars($cert['issue_date']) . ' Exp: ' . htmlspecialchars($cert['expiration_date'] ?: 'No Expiration') . '</p>';
        $html .= '<hr>';
    }
} else {
    $html .= '<p>No certifications added yet.</p>';
}
$html .= '</div>';

// Initialize TCPDF and output the HTML.
$pdf = new TCPDF();
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('resume_' . $user['name'] . '.pdf', 'I');
?>
