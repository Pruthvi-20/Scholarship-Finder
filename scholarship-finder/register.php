<?php
session_start();
include 'backend/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $gpa = $conn->real_escape_string($_POST['gpa']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $check_user = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check_user->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        // Insert into students table
        $sql_student = "INSERT INTO students (name, email, phone, gpa) 
                        VALUES ('$name', '$email', '$phone', '$gpa')";
        if ($conn->query($sql_student) === TRUE) {
            $student_id = $conn->insert_id; // Get the newly created student ID

            // Insert into users table
            $sql_user = "INSERT INTO users (username, password, role, student_id) 
                         VALUES ('$username', '$password', 'student', '$student_id')";
            if ($conn->query($sql_user) === TRUE) {
                header("Location: index.php?message=Registration successful! Please log in.");
                exit();
            } else {
                $error = "Error creating user: " . $conn->error;
            }
        } else {
            $error = "Error creating student profile: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Scholarship Finder</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Register as Student</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form action="register.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone Number">
                <input type="number" name="gpa" placeholder="GPA (e.g., 3.5)" step="0.1" min="0" max="4">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html>