<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "DELETE FROM scholarships WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin.php?message=Scholarship deleted successfully");
    } else {
        header("Location: ../admin.php?error=" . $conn->error);
    }
}

$conn->close();
?>