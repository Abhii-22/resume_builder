<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $skill_name = $_POST['skill_name'];
    
    if (!empty($skill_name)) {
        $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $skill_name);
        if ($stmt->execute()) {
            header("Location: edit_profile.php");
            exit();
        } else {
            echo "Error adding skill.";
        }
    } else {
        echo "Skill name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Skill</title>
</head>
<body>
    <h2>Add Skill</h2>
    <form method="post">
        <label>Skill Name:</label>
        <input type="text" name="skill_name" required>
        <button type="submit">Add Skill</button>
    </form>
    <a href="edit_profile.php">Back to Profile</a>
</body>
</html>
