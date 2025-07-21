<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Delete related applications
    $conn->query("DELETE FROM applications WHERE student_id='$id'");
    // Delete related user
    $conn->query("DELETE FROM users WHERE student_id='$id'");
    // Delete student
    $sql = "DELETE FROM students WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin.php?message=Student deleted successfully");
    } else {
        header("Location: ../admin.php?error=" . $conn->error);
    }
}

$conn->close();
?>