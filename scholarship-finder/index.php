<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: student.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Scholarship Finder</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Login</h2>
            <?php 
            if (isset($_GET['message'])) echo "<p class='success'>" . htmlspecialchars($_GET['message']) . "</p>";
            if (isset($_GET['error'])) echo "<p class='error'>" . htmlspecialchars($_GET['error']) . "</p>"; 
            ?>
            <form action="backend/login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="student">Student</option>
                </select>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register as a student</a></p>
        </div>
    </div>
</body>
</html>