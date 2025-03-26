<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the users table.
$stmt_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt_profile->bind_param("i", $user_id);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();
$user_info = $result_profile->fetch_assoc();
$stmt_profile->close();

// Fetch user profile from the user_profiles table.
$stmt_profile2 = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt_profile2->bind_param("i", $user_id);
$stmt_profile2->execute();
$result_profile2 = $stmt_profile2->get_result();
$user = $result_profile2->fetch_assoc();
$stmt_profile2->close();

// Fetch skills.
$stmt_skills = $conn->prepare("SELECT skill_name FROM skills WHERE user_id = ?");
$stmt_skills->bind_param("i", $user_id);
$stmt_skills->execute();
$skills_result = $stmt_skills->get_result();
$skills = [];
while ($row = $skills_result->fetch_assoc()) {
    $skills[] = $row['skill_name'];
}
$stmt_skills->close();

// Fetch projects.
$stmt_projects = $conn->prepare("SELECT * FROM projects WHERE user_id = ?");
$stmt_projects->bind_param("i", $user_id);
$stmt_projects->execute();
$projects_result = $stmt_projects->get_result();
$stmt_projects->close();

// Fetch work experience.
$stmt_experience = $conn->prepare("SELECT * FROM work_experience WHERE user_id = ? ORDER BY created_at DESC");
$stmt_experience->bind_param("i", $user_id);
$stmt_experience->execute();
$work_result = $stmt_experience->get_result();
$stmt_experience->close();

// Fetch certifications.
$stmt_cert = $conn->prepare("SELECT * FROM certifications WHERE user_id = ? ORDER BY issue_date DESC");
$stmt_cert->bind_param("i", $user_id);
$stmt_cert->execute();
$cert_result = $stmt_cert->get_result();
$stmt_cert->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <style>
    /* General Styles */
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 20px;
      color: #444;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h1, h2 {
      text-align: center;
      color: #333;
    }
    h1 {
      font-size: 2.5em;
      margin-bottom: 20px;
    }
    h2 {
      font-size: 2em;
      margin-top: 30px;
    }
    p {
      margin: 8px 0;
      font-size: 1.1em;
    }
    a {
      color: #007BFF;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    /* Header Section (Profile Photo + Personal Info) */
    .header {
      display: flex;
      align-items: flex-start;
      gap: 20px;
      margin-bottom: 30px;
    }
    .left-column {
      flex: 0 0 150px;
      text-align: center;
    }
    .right-column {
      flex: 1;
    }
    .profile-image {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #007BFF;
    }
    .profile-placeholder {
      font-size: 1.1em;
      color: #777;
      text-align: center;
      padding: 40px 0;
    }
    /* Section Styles */
    .section {
      margin-top: 40px;
    }
    .section h3 {
      font-size: 1.8em;
      color: #555;
      border-bottom: 2px solid #007BFF;
      padding-bottom: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
    .section ul {
      list-style: none;
      padding-left: 0;
    }
    .section ul li {
      background-color: #f9f9f9;
      margin: 10px 0;
      padding: 12px;
      border-radius: 4px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    /* Entry Dividers for Work & Certifications */
    .entry {
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #ddd;
    }
    /* Button Link Style */
    .button-link {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #007BFF;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      text-align: center;
    }
    .button-link:hover {
      background-color: #0056b3;
    }
    /* Download/Generate Button */
    .btn-download {
      display: block;
      width: 200px;
      margin: 30px auto;
      background-color: #28a745;
      color: white;
      text-align: center;
      padding: 12px 20px;
      border-radius: 4px;
      text-decoration: none;
    }
    .btn-download:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Profile</h1>
    
    <!-- Header Section: Profile Photo on Left, Personal Info on Right -->
    <div class="header">
      <div class="left-column">
        <?php if (!empty($user['profile_photo']) && file_exists("uploads/" . $user['profile_photo'])): ?>
          <img src="uploads/<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Photo" class="profile-image">
        <?php else: ?>
          <div class="profile-placeholder">No profile photo uploaded.</div>
        <?php endif; ?>
      </div>
      <div class="right-column">
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name'] ?? 'Not Provided') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user_info['email'] ?? 'Not Provided') ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not Provided') ?></p>
        <p><strong>Education:</strong> <?= htmlspecialchars($user['education'] ?? 'Not Provided') ?></p>
        <p><strong>Experience:</strong> <?= htmlspecialchars($user['experience'] ?? '0') ?> years</p>
        <p><strong>LinkedIn:</strong>
          <?php if (!empty($user['linkedin'])): ?>
            <a href="<?= htmlspecialchars($user['linkedin']) ?>" target="_blank">View</a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </p>
        <p><strong>Portfolio:</strong>
          <?php if (!empty($user['portfolio'])): ?>
            <a href="<?= htmlspecialchars($user['portfolio']) ?>" target="_blank">View</a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </p>
        <p><strong>GitHub:</strong>
          <?php if (!empty($user['github'])): ?>
            <a href="<?= htmlspecialchars($user['github']) ?>" target="_blank">View</a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </p>
        <p><strong>Twitter:</strong>
          <?php if (!empty($user['twitter'])): ?>
            <a href="<?= htmlspecialchars($user['twitter']) ?>" target="_blank">View</a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </p>
        <p><strong>Website:</strong>
          <?php if (!empty($user['website'])): ?>
            <a href="<?= htmlspecialchars($user['website']) ?>" target="_blank">Visit</a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </p>
        <p><strong>Address:</strong> <?= htmlspecialchars($user['address'] ?? 'Not Provided') ?></p>
      </div>
    </div>
    
    <!-- Skills Section -->
    <div class="section">
      <h3>Skills</h3>
      <?php if (!empty($skills)): ?>
        <ul>
          <?php foreach ($skills as $skill): ?>
            <li><?= htmlspecialchars($skill) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No skills added yet.</p>
      <?php endif; ?>
      <a class="button-link" href="add_skill.php">Add Skill</a>
    </div>
    
    <!-- Projects Section -->
    <div class="section">
      <h3>Projects</h3>
      <ul>
        <?php while ($project = $projects_result->fetch_assoc()): ?>
          <li><strong><?= htmlspecialchars($project['project_name']) ?>:</strong> <?= htmlspecialchars($project['project_description']) ?></li>
        <?php endwhile; ?>
      </ul>
      <a class="button-link" href="add_project.php">Add Project</a>
    </div>
    
    <!-- Work Experience Section -->
    <div class="section">
      <h3>Work Experience</h3>
      <?php if ($work_result->num_rows > 0): ?>
        <?php while ($work = $work_result->fetch_assoc()): ?>
          <div class="entry">
            <p><strong>Job Title:</strong> <?= htmlspecialchars($work['job_title']) ?></p>
            <p><strong>Company:</strong> <?= htmlspecialchars($work['company_name']) ?></p>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($work['start_date']) ?></p>
            <p><strong>End Date:</strong> <?= htmlspecialchars($work['end_date'] ?: 'Present') ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($work['job_description'])) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($work['location']) ?></p>
            <p><strong>Responsibilities:</strong> <?= nl2br(htmlspecialchars($work['responsibilities'])) ?></p>
            <p><strong>Added On:</strong> <?= htmlspecialchars($work['created_at']) ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No work experience added yet.</p>
      <?php endif; ?>
      <a class="button-link" href="add_work_experience.php">Add Work Experience</a>
    </div>
    
    <!-- Certifications Section -->
    <div class="section">
      <h3>Certifications</h3>
      <?php if ($cert_result->num_rows > 0): ?>
        <?php while ($cert = $cert_result->fetch_assoc()): ?>
          <div class="entry">
            <p><strong>Certification:</strong> <?= htmlspecialchars($cert['certification_name']) ?></p>
            <p><strong>Issued By:</strong> <?= htmlspecialchars($cert['issuing_organization']) ?></p>
            <p><strong>Issue Date:</strong> <?= htmlspecialchars($cert['issue_date']) ?></p>
            <p><strong>Expiration Date:</strong> <?= htmlspecialchars($cert['expiration_date'] ?: 'No Expiration') ?></p>
            <p><strong>Certificate ID:</strong> <?= htmlspecialchars($cert['certification_id']) ?></p>
            <p><strong>URL:</strong> <a href="<?= htmlspecialchars($cert['certification_url']) ?>" target="_blank">View</a></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No certifications added yet.</p>
      <?php endif; ?>
      <a class="button-link" href="add_certification.php">Add Certification</a>
    </div>
    
    <!-- Generate Resume Button -->
    <a class="btn-download" href="generate_resume.php">Generate Resume</a>
    
    <br><br>
    <div style="text-align: center;">
      <a class="button-link" href="edit_profile.php">Edit Profile</a>
      <a class="button-link" href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
