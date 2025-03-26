<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Resume Builder</title>
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
        }

        .container {
            max-width: 600px;
            background: rgba(0, 0, 0, 0.8);
            margin: 100px auto;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #FFD700;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background: #f8f8f8;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
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

        .footer {
            text-align: center;
            margin-top: 20px;
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

    <!-- Contact Form -->
    <div class="container">
        <h2>Contact Us</h2>
        <p>If you have any questions or feedback, feel free to reach out to us via email.</p>
        
        <form method="POST" action="send_email.php">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Message:</label>
            <textarea name="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Resume Builder. All rights reserved.</p>
    </div>

</body>
</html>
