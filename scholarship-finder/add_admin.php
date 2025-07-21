<?php
// add_admin.php
$host = "localhost";
$username = "root"; 
$password = "";     
$dbname = "scholarship_finder";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete existing admin user to avoid duplicates
$conn->query("DELETE FROM users WHERE username = 'admin'");

// Hash the password and insert the admin user
$admin_username = "admin";
$admin_password = password_hash("123", PASSWORD_DEFAULT);
$admin_role = "admin";
$admin_student_id = NULL;

$sql = "INSERT INTO users (username, password, role, student_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $admin_username, $admin_password, $admin_role, $admin_student_id);

if ($stmt->execute()) {
    echo "Admin user 'admin' with password '123' added successfully!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>