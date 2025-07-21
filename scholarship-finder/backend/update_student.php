<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $gpa = $conn->real_escape_string($_POST['gpa']);

    $sql = "UPDATE students SET name='$name', email='$email', phone='$phone', gpa='$gpa' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin.php?message=Student updated successfully");
    } else {
        header("Location: ../admin.php?error=" . $conn->error);
    }
}

$conn->close();
?>