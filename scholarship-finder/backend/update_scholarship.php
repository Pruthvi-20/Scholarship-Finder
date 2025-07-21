<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $conn->real_escape_string($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $criteria_1 = $conn->real_escape_string($_POST['criteria_1']);
    $criteria_2 = $conn->real_escape_string($_POST['criteria_2']);
    $criteria_3 = $conn->real_escape_string($_POST['criteria_3']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $deadline = $conn->real_escape_string($_POST['deadline']);

    $sql = "UPDATE scholarships 
            SET name='$name', criteria_1='$criteria_1', criteria_2='$criteria_2', criteria_3='$criteria_3', 
                amount='$amount', deadline='$deadline' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin.php?message=Scholarship updated successfully");
    } else {
        header("Location: ../admin.php?error=" . $conn->error);
    }
}

$conn->close();
?>