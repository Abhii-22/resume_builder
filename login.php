<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Redirect to the profile page after successful login
            header("Location: profile.php");
            exit;
        } else {
            $error_message = "Invalid credentials.";
        }
    } else {
        $error_message = "User not found.";
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
    <title>Login</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            width: 350px;
            text-align: center;
            color: white;
        }

        h2 {
            margin-bottom: 20px;
            color: #FFD700;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
            color: #ddd;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            background: #f8f8f8;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }

        p {
            margin-top: 10px;
        }

        p a {
            color: #FFD700;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        
        <!-- Display error message -->
        <?php if (isset($error_message)) { ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>

        <!-- Login Form -->
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

</body>
</html>
