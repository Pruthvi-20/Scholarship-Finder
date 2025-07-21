<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $criteria_1 = $conn->real_escape_string($_POST['criteria_1']);
    $criteria_2 = $conn->real_escape_string($_POST['criteria_2']);
    $criteria_3 = $conn->real_escape_string($_POST['criteria_3']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $deadline = $conn->real_escape_string($_POST['deadline']);

    $sql = "INSERT INTO scholarships (name, criteria_1, criteria_2, criteria_3, amount, deadline) 
            VALUES ('$name', '$criteria_1', '$criteria_2', '$criteria_3', '$amount', '$deadline')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../admin.php?message=Scholarship added successfully");
    } else {
        header("Location: ../admin.php?error=" . $conn->error);
    }
}

$conn->close();
?>