<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Access Denied! Please login.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch skills
$query_skills = "SELECT * FROM skills WHERE user_id = ?";
$stmt = $conn->prepare($query_skills);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$skills_result = $stmt->get_result();

// Fetch projects
$query_projects = "SELECT * FROM projects WHERE user_id = ?";
$stmt = $conn->prepare($query_projects);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$projects_result = $stmt->get_result();

// Fetch work experience
$query_experience = "SELECT * FROM work_experience WHERE user_id = ?";
$stmt = $conn->prepare($query_experience);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$experience_result = $stmt->get_result();

// Fetch certifications
$query_certifications = "SELECT * FROM certifications WHERE user_id = ?";
$stmt = $conn->prepare($query_certifications);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$certifications_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin: 20px 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .section {
            margin-top: 30px;
        }

        .section h3 {
            color: #444;
            font-size: 18px;
        }

        .section ul {
            list-style-type: none;
            padding: 0;
        }

        .section li {
            background-color: #f1f1f1;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }

        .section a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .section a:hover {
            text-decoration: underline;
        }

        .btn-back {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Profile</h2>

        <!-- Profile Form -->
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>"><br>

            <label>Education:</label>
            <input type="text" name="education" value="<?php echo $user['education']; ?>"><br>

            <label>Experience (years):</label>
            <input type="text" name="experience" value="<?php echo $user['experience']; ?>"><br>

            <label>LinkedIn:</label>
            <input type="text" name="linkedin" value="<?php echo $user['linkedin']; ?>"><br>

            <label>Portfolio:</label>
            <input type="text" name="portfolio" value="<?php echo $user['portfolio']; ?>"><br>

            <label>Address:</label>
            <input type="text" name="address" value="<?php echo $user['address']; ?>"><br>

            <label>Website:</label>
            <input type="text" name="website" value="<?php echo $user['website']; ?>"><br>

            <label>GitHub:</label>
            <input type="text" name="github" value="<?php echo $user['github']; ?>"><br>

            <label>Twitter:</label>
            <input type="text" name="twitter" value="<?php echo $user['twitter']; ?>"><br>

            <label>Profile Photo:</label>
            <input type="file" name="profile_photo"><br>

            <input type="submit" value="Update Profile">
        </form>

        <!-- Skills Section -->
        <div class="section">
            <h3>Skills</h3>
            <ul>
                <?php while ($skill = $skills_result->fetch_assoc()) { ?>
                    <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                <?php } ?>
            </ul>
            <a href="add_skill.php">Add Skill</a>
        </div>

        <!-- Projects Section -->
        <div class="section">
            <h3>Projects</h3>
            <ul>
                <?php while ($project = $projects_result->fetch_assoc()) { ?>
                    <li><?php echo htmlspecialchars($project['project_name']) . " - " . htmlspecialchars($project['project_description']); ?></li>
                <?php } ?>
            </ul>
            <a href="add_project.php">Add Project</a>
        </div>

        <!-- Work Experience Section -->
        <div class="section">
            <h3>Work Experience</h3>
            <ul>
                <?php while ($experience = $experience_result->fetch_assoc()) { ?>
                    <li><?php echo htmlspecialchars($experience['job_title']) . " at " . htmlspecialchars($experience['company_name']); ?></li>
                <?php } ?>
            </ul>
            <a href="add_work_experience.php">Add Work Experience</a>
        </div>

        <!-- Certifications Section -->
        <div class="section">
            <h3>Certifications</h3>
            <ul>
                <?php while ($certification = $certifications_result->fetch_assoc()) { ?>
                    <li><?php echo htmlspecialchars($certification['cert_name']) . " from " . htmlspecialchars($certification['issuing_org']); ?></li>
                <?php } ?>
            </ul>
            <a href="add_certification.php">Add Certification</a>
        </div>

        <a href="profile.php" class="btn-back">Back to Profile</a>
    </div>

</body>
</html>