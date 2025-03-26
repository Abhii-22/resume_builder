<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $project_name = $_POST['project_name'];
    $project_description = $_POST['project_description'];
    $project_link = $_POST['project_link'];
    $technologies_used = $_POST['technologies_used'];
    
    $stmt = $conn->prepare("INSERT INTO projects (user_id, project_name, project_description, project_link, technologies_used) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $project_name, $project_description, $project_link, $technologies_used);
    
    if ($stmt->execute()) {
        header("Location: edit_profile.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Project</title>
</head>
<body>
    <h2>Add New Project</h2>
    <form method="post">
        <label>Project Name:</label>
        <input type="text" name="project_name" required><br>
        
        <label>Description:</label>
        <textarea name="project_description" required></textarea><br>
        
        <label>Project Link:</label>
        <input type="text" name="project_link"><br>
        
        <label>Technologies Used:</label>
        <input type="text" name="technologies_used"><br>
        
        <button type="submit">Add Project</button>
    </form>
    <a href="edit_profile.php">Back to Profile</a>
</body>
</html>
