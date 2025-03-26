<?php
$servername = "localhost:3307"; // XAMPP default
$username = "root"; // Default MySQL username in XAMPP
$password = ""; // No password in XAMPP by default
$dbname = "resume_builder"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
