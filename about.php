<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Resume Builder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url('about-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-align: center;
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
        }

        h1 {
            font-size: 50px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            max-width: 800px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .navbar {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .navbar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="contact.php">Contact</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>About Resume Builder</h1>
        <p>Resume Builder is a user-friendly tool designed to help individuals create professional resumes in just a few minutes. 
        Whether you're a student, job seeker, or professional, our platform provides an easy way to build, edit, and download resumes effortlessly.</p>
        <p>Our goal is to simplify the resume creation process, ensuring that you can showcase your skills and achievements effectively.</p>
    </div>

</body>
</html>
