<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechVritti - Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url('background.jpg') no-repeat center center fixed;
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

        .btn {
            display: inline-block;
            background: #007BFF;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            margin-top: 10px;
        }

        .btn:hover {
            background: #0056b3;
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

        .section {
            padding: 50px;
            background: rgba(0, 0, 0, 0.8);
            margin: 20px 10%;
            border-radius: 8px;
        }

        .footer {
            margin-top: 50px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>

    <!-- Hero Section -->
    <div class="container">
        <h1>Welcome to TechVritti</h1>
        <p>Create professional resumes in minutes. Simple, fast, and free.</p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="btn">Go to Dashboard</a>
        <?php else: ?>
            <a href="register.php" class="btn">Get Started</a>
        <?php endif; ?>
    </div>

    <!-- About Section -->
    <div class="section">
        <h2>About TechVritti</h2>
        <p>We are a dynamic and innovative company dedicated to empowering the next generation of talent across India. Specializing in educational enrichment and professional development, we offer a range of services tailored to students and institutions alike. At TechVritti, we thrive on collaboration..</p>
    </div>

    <!-- Contact Section -->
    <div class="section">
        <h2>Contact Us</h2>
        <p>If you have any questions, suggestions, or need assistance, feel free to reach out to us.</p>
        <a href="contact.php" class="btn">Contact Now</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Resume Builder. All rights reserved.</p>
    </div>

</body>
</html>
