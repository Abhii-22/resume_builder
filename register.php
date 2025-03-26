<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the users table
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id; // Get the inserted user's ID
        
        // Now insert an empty profile for this user in the user_profiles table
        $profile_stmt = $conn->prepare("INSERT INTO user_profiles (user_id) VALUES (?)");
        $profile_stmt->bind_param("i", $user_id);
        if ($profile_stmt->execute()) {
            echo "Registration successful! Please <a href='login.php'>login</a>.";
        } else {
            echo "Error creating user profile: " . $profile_stmt->error;
        }
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register</title>
  <style>
    body {
      background-color: #f0f4f8;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    label {
      display: block;
      font-weight: bold;
      margin-top: 15px;
      color: #555;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
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
      margin-top: 20px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    a {
      text-decoration: none;
      color: #007BFF;
      display: block;
      text-align: center;
      margin-top: 15px;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Register</h1>
    <form method="POST">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
  </div>
</body>
</html>
