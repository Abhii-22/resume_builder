<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $job_title = trim($_POST['job_title']);
    $company_name = trim($_POST['company_name']);
    $start_date = $_POST['start_date'];
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;
    $job_description = trim($_POST['job_description']);
    $location = trim($_POST['location']);
    $responsibilities = trim($_POST['responsibilities']);

    $sql = "INSERT INTO work_experience (user_id, job_title, company_name, start_date, end_date, job_description, location, responsibilities) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $user_id, $job_title, $company_name, $start_date, $end_date, $job_description, $location, $responsibilities);

    if ($stmt->execute()) {
        header("Location: profile.php?message=Work+experience+added+successfully");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Work Experience</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
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
        <h2>Add Work Experience</h2>
        <form method="POST">
            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" id="job_title" required>

            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" required>

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" required>

            <label for="end_date">End Date (leave blank if current):</label>
            <input type="date" name="end_date" id="end_date">

            <label for="job_description">Job Description:</label>
            <textarea name="job_description" id="job_description" rows="4"></textarea>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location">

            <label for="responsibilities">Responsibilities:</label>
            <textarea name="responsibilities" id="responsibilities" rows="4"></textarea>

            <button type="submit">Add Experience</button>
        </form>
        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>
