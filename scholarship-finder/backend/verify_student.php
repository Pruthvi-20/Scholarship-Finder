<?php
include 'db_connect.php';

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $action = $_GET['action'];
    $status = ($action == 'verify') ? 'verified' : 'rejected';

    $sql = "UPDATE students SET status='$status' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        if ($status == 'rejected') {
            // Delete associated user and applications
            $conn->query("DELETE FROM applications WHERE student_id='$id'");
            $conn->query("DELETE FROM users WHERE student_id='$id'");
            $conn->query("DELETE FROM students WHERE id='$id'");
            header("Location: ../admin.php?message=Student rejected and removed successfully");
        } else {
            header("Location: ../admin.php?message=Student verified successfully");
        }
    } else {
        header("Location: ../admin.php?error=" . $conn->error);
    }
}

$conn->close();
?>