<?php
include 'db_connect.php';

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $action = $_GET['action'];
    $status = ($action == 'verify') ? 'verified' : 'rejected';

    $sql = "UPDATE applications SET status='$status' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin.php?message=Application " . $status . " successfully");
    } else {
        header("Location: ../admin.php?error=" . $conn->error);
    }
}

$conn->close();
?>