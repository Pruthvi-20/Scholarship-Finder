<?php
$host = "localhost"; // Your database host (usually localhost)
$username = "root";  // Your MySQL username (default for XAMPP/WAMP is root)
$password = "";      // Your MySQL password (default for XAMPP/WAMP is empty)
$dbname = "scholarship_finder"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>