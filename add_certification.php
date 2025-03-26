<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $certification_name = trim($_POST['certification_name']);
    $issuing_organization = trim($_POST['issuing_organization']);
    $issue_date = $_POST['issue_date'];
    // If expiration date is empty, store NULL
    $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : NULL;
    $certification_id = trim($_POST['certification_id']);
    $certification_url = trim($_POST['certification_url']);

    $sql = "INSERT INTO certifications (user_id, certification_name, issuing_organization, issue_date, expiration_date, certification_id, certification_url)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Prepare failed: " . $conn->error;
        exit();
    }
    $stmt->bind_param("issssss", $user_id, $certification_name, $issuing_organization, $issue_date, $expiration_date, $certification_id, $certification_url);
    
    if ($stmt->execute()) {
        header("Location: profile.php?message=Certification+added+successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Certification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            background: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Certification</h2>
        <form method="POST">
            <label>Certification Name:</label>
            <input type="text" name="certification_name" required>

            <label>Issuing Organization:</label>
            <input type="text" name="issuing_organization" required>

            <label>Issue Date:</label>
            <input type="date" name="issue_date" required>

            <label>Expiration Date (optional):</label>
            <input type="date" name="expiration_date">

            <label>Certification ID (optional):</label>
            <input type="text" name="certification_id">

            <label>Certification URL (optional):</label>
            <input type="url" name="certification_url">

            <button type="submit">Add Certification</button>
        </form>
        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>
