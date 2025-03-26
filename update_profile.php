<?php
session_start();
require 'db.php';

// Check if the user is logged in.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user details from the 'users' table.
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
if (!$stmt) {
    die("Prepare failed (users): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();
$stmt->close();

// Fetch current user profile from the 'user_profiles' table.
$stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
if (!$stmt) {
    die("Prepare failed (user_profiles): " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Process the form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get textual fields with fallback to current values.
    $name       = $_POST['name']       ?? $user['name'];
    $email      = $_POST['email']      ?? $user_info['email'];
    $phone      = $_POST['phone']      ?? $user['phone'];
    $education  = $_POST['education']  ?? $user['education'];
    $experience = $_POST['experience'] ?? $user['experience'];
    $linkedin   = $_POST['linkedin']   ?? $user['linkedin'];
    $portfolio  = $_POST['portfolio']  ?? $user['portfolio'];
    $address    = $_POST['address']    ?? $user['address'];
    $website    = $_POST['website']    ?? $user['website'];
    $github     = $_POST['github']     ?? $user['github'];
    $twitter    = $_POST['twitter']    ?? $user['twitter'];
    
    // Set profile photo to existing value by default.
    $profile_photo = $user['profile_photo'] ?? "";
    
    // If a new file is uploaded, process it.
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_photo']['name'];
        $filetmp  = $_FILES['profile_photo']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            echo "Error: Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }
        
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Generate a unique file name.
        $new_filename = "profile_" . time() . "." . $ext;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($filetmp, $target_file)) {
            $profile_photo = $new_filename;
        } else {
            echo "Error moving uploaded file.";
            exit;
        }
    }
    
    // Update the user_profiles table with new values.
    $stmt = $conn->prepare("UPDATE user_profiles SET name = ?, email = ?, phone = ?, education = ?, experience = ?, linkedin = ?, portfolio = ?, address = ?, website = ?, github = ?, twitter = ?, profile_photo = ? WHERE user_id = ?");
    if (!$stmt) {
        die("Prepare failed (update): " . $conn->error);
    }
    $stmt->bind_param("ssssssssssssi", $name, $email, $phone, $education, $experience, $linkedin, $portfolio, $address, $website, $github, $twitter, $profile_photo, $user_id);
    
    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    .flex-container {
      display: flex;
      gap: 20px;
    }
    .left-column {
      width: 30%;
      text-align: center;
    }
    .right-column {
      width: 70%;
    }
    .profile-image {
      width: 100%;
      border-radius: 50%;
      border: 3px solid #007BFF;
      object-fit: cover;
      max-width: 150px;
      margin: 0 auto;
    }
    .profile-placeholder {
      text-align: center;
      font-size: 1em;
      color: #777;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }
    input[type="text"],
    input[type="email"],
    input[type="url"],
    input[type="number"],
    input[type="file"] {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    button {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #007BFF;
      color: #fff;
      border: none;
      border-radius: 4px;
      margin-top: 20px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    a {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #007BFF;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Update Profile</h1>
    <div class="flex-container">
      <!-- Left Column: Display Profile Photo -->
      <div class="left-column">
        <?php if (!empty($user['profile_photo'])): ?>
          <img src="uploads/<?= htmlspecialchars($user['profile_photo']) ?>" alt="Profile Photo" class="profile-image">
        <?php else: ?>
          <div class="profile-placeholder">No profile photo uploaded.</div>
        <?php endif; ?>
      </div>
      <!-- Right Column: Update Form -->
      <div class="right-column">
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
          <label for="name">Name:</label>
          <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>

          <label for="email">Email:</label>
          <input type="email" name="email" id="email" value="<?= htmlspecialchars($user_info['email']) ?>" required>

          <label for="phone">Phone:</label>
          <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

          <label for="education">Education:</label>
          <input type="text" name="education" id="education" value="<?= htmlspecialchars($user['education']) ?>" required>

          <label for="experience">Experience (Years):</label>
          <input type="number" name="experience" id="experience" value="<?= htmlspecialchars($user['experience']) ?>" required>

          <label for="linkedin">LinkedIn:</label>
          <input type="url" name="linkedin" id="linkedin" value="<?= htmlspecialchars($user['linkedin']) ?>">

          <label for="portfolio">Portfolio:</label>
          <input type="url" name="portfolio" id="portfolio" value="<?= htmlspecialchars($user['portfolio']) ?>">

          <label for="address">Address:</label>
          <input type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']) ?>">

          <label for="website">Website:</label>
          <input type="url" name="website" id="website" value="<?= htmlspecialchars($user['website']) ?>">

          <label for="github">GitHub:</label>
          <input type="url" name="github" id="github" value="<?= htmlspecialchars($user['github']) ?>">

          <label for="twitter">Twitter:</label>
          <input type="url" name="twitter" id="twitter" value="<?= htmlspecialchars($user['twitter']) ?>">

          <label for="profile_photo">Upload Profile Photo:</label>
          <input type="file" name="profile_photo" id="profile_photo">
          
          <button type="submit">Update Profile</button>
        </form>
      </div>
    </div>
    <br>
    <a href="profile.php">Back to Profile</a>
  </div>
</body>
</html>
