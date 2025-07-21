<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $scholarship_id = $conn->real_escape_string($_POST['scholarship_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    // Handle file upload
    $target_dir = "uploads/"; // Relative path
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $file_name = basename($_FILES["attachment"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ['pdf', 'doc', 'docx'];
    if (!in_array($file_type, $allowed_types)) {
        header("Location: ../student.php?error=Invalid file type. Only PDF, DOC, and DOCX are allowed.");
        exit();
    }

    if ($_FILES["attachment"]["size"] > 5000000) {
        header("Location: ../student.php?error=File is too large. Maximum size is 5MB.");
        exit();
    }

    if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO applications (student_id, scholarship_id, attachment, status) 
                VALUES ('$student_id', '$scholarship_id', '$target_file', 'pending')";
        if ($conn->query($sql) === TRUE) {
            $update_student = "UPDATE students SET name='$name', email='$email' WHERE id='$student_id'";
            $conn->query($update_student);
            header("Location: ../student.php?message=Application submitted successfully");
        } else {
            header("Location: ../student.php?error=" . $conn->error);
        }
    } else {
        header("Location: ../student.php?error=Error uploading file");
    }
}

$conn->close();
?>