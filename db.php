<?php
$servername = "localhost:3307";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password is empty
$database = "resume_builder";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
