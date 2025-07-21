<?php
session_start();
include 'backend/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch scholarships
$scholarships = $conn->query("SELECT * FROM scholarships");

// Fetch applications
$applications = $conn->query("
    SELECT a.id, a.student_id, a.scholarship_id, a.attachment, a.status, a.applied_at, 
           s.name AS student_name, sch.name AS scholarship_name 
    FROM applications a 
    JOIN students s ON a.student_id = s.id 
    JOIN scholarships sch ON a.scholarship_id = sch.id
");

// Fetch students (only once)
$students_query = "SELECT * FROM students";
$students = $conn->query($students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Scholarship Finder</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="#update-topics">Update Topics for Search</a></li>
                <li><a href="#update-roles">Update Roles</a></li>
                <li><a href="#check-activity">Check Activity</a></li>
                <li><a href="#manage-scholarships">Manage Scholarships</a></li>
                <li><a href="#manage-students">Manage Students</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <header>
                <h3>Welcome, Admin</h3>
                <a href="backend/logout.php" class="logout-btn">Logout</a>
            </header>

            <!-- Display Messages -->
            <?php if (isset($_GET['message'])): ?>
                <div class="success"><?php echo htmlspecialchars($_GET['message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <!-- Update Topics for Search -->
            <div class="card" id="update-topics">
                <h4>Update Topics for Search</h4>
                <p>Add, edit, or delete topics that users can search for scholarships.</p>
                <a href="#" class="card-btn">Go to Update</a>
            </div>

            <!-- Update Roles -->
            <div class="card" id="update-roles">
                <h4>Update Roles</h4>
                <p>Manage roles such as Provider, User, and Admin for the application.</p>
                <a href="#" class="card-btn">Manage Roles</a>
            </div>

            <!-- Check Activity -->
            <div class="card" id="check-activity">
                <h4>Check Activity</h4>
                <p>View logs and activity performed by users and providers.</p>
                <a href="#" class="card-btn">View Activity</a>
            </div>

            <!-- Manage Scholarships -->
            <div class="card" id="manage-scholarships">
                <h4>Manage Scholarships</h4>
                <form action="backend/add_scholarship.php" method="POST" class="scholarship-form">
                    <input type="text" name="name" placeholder="Scholarship Name" required>
                    <input type="text" name="criteria_1" placeholder="Criteria 1 (e.g., Minimum GPA)" required>
                    <input type="text" name="criteria_2" placeholder="Criteria 2 (e.g., Income Level)" required>
                    <input type="text" name="criteria_3" placeholder="Criteria 3 (e.g., Field of Study)" required>
                    <input type="number" name="amount" placeholder="Amount (e.g., 5000)" step="0.01" required>
                    <input type="date" name="deadline" required>
                    <button type="submit">Add Scholarship</button>
                </form>
                <table>
                    <thead><tr><th>ID</th><th>Name</th><th>Criteria 1</th><th>Criteria 2</th><th>Criteria 3</th><th>Amount</th><th>Deadline</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php while ($row = $scholarships->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['criteria_1']; ?></td>
                                <td><?php echo $row['criteria_2']; ?></td>
                                <td><?php echo $row['criteria_3']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['deadline']; ?></td>
                                <td>
                                    <button onclick="openUpdateForm(<?php echo $row['id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['criteria_1']; ?>', '<?php echo $row['criteria_2']; ?>', '<?php echo $row['criteria_3']; ?>', <?php echo $row['amount']; ?>, '<?php echo $row['deadline']; ?>')">Update</button>
                                    <a href="backend/delete_scholarship.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div id="update-form" class="update-form" style="display: none;">
                    <form action="backend/update_scholarship.php" method="POST">
                        <input type="hidden" name="id" id="update-id">
                        <input type="text" name="name" id="update-name" required>
                        <input type="text" name="criteria_1" id="update-criteria_1" required>
                        <input type="text" name="criteria_2" id="update-criteria_2" required>
                        <input type="text" name="criteria_3" id="update-criteria_3" required>
                        <input type="number" name="amount" id="update-amount" step="0.01" required>
                        <input type="date" name="deadline" id="update-deadline" required>
                        <button type="submit">Update</button>
                        <button type="button" onclick="closeUpdateForm()">Cancel</button>
                    </form>
                </div>
            </div>

            <!-- Verify Applications -->
            <div class="card">
                <h4>Verify Applications</h4>
                <table>
                    <thead><tr><th>ID</th><th>Student Name</th><th>Scholarship</th><th>Attachment</th><th>Status</th><th>Applied On</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while ($app = $applications->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $app['id']; ?></td>
                                <td><?php echo $app['student_name']; ?></td>
                                <td><?php echo $app['scholarship_name']; ?></td>
                                <td><a href="/scholarship-finder/<?php echo $app['attachment']; ?>" target="_blank">View Attachment</a></td>
                                <td><?php echo $app['status']; ?></td>
                                <td><?php echo $app['applied_at']; ?></td>
                                <td>
                                    <?php if ($app['status'] == 'pending'): ?>
                                        <a href="backend/verify_application.php?id=<?php echo $app['id']; ?>&action=verify" onclick="return confirm('Verify?')">Verify</a>
                                        <a href="backend/verify_application.php?id=<?php echo $app['id']; ?>&action=reject" onclick="return confirm('Reject?')">Reject</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Manage Students (Merged and Improved) -->
            <div class="card" id="manage-students">
                <h4>Manage Students</h4>
                <?php if ($students->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>GPA</th>
                                <th>Status</th>
                                <th>Joined On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $student['id']; ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['phone'] ?: 'Not provided'); ?></td>
                                    <td><?php echo htmlspecialchars($student['gpa'] ?: 'Not provided'); ?></td>
                                    <td><?php echo htmlspecialchars($student['status'] ?? 'Not set'); ?></td>
                                    <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                                    <td>
                                        <!-- Verification Actions -->
                                        <?php if (isset($student['status']) && $student['status'] == 'pending'): ?>
                                            <a href="backend/verify_student.php?id=<?php echo $student['id']; ?>&action=verify" onclick="return confirm('Verify this student?')">Verify</a>
                                            <a href="backend/verify_student.php?id=<?php echo $student['id']; ?>&action=reject" onclick="return confirm('Reject this student?')">Reject</a>
                                        <?php endif; ?>
                                        <!-- Edit and Delete Actions -->
                                        <button onclick="openEditStudentForm(<?php echo $student['id']; ?>, '<?php echo addslashes($student['name']); ?>', '<?php echo addslashes($student['email']); ?>', '<?php echo addslashes($student['phone'] ?? ''); ?>', '<?php echo addslashes($student['gpa'] ?? ''); ?>')">Edit</button>
                                        <a href="backend/delete_student.php?id=<?php echo $student['id']; ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No students found.</p>
                <?php endif; ?>

                <!-- Edit Student Form -->
                <div id="edit-student-form" class="update-form" style="display: none;">
                    <h4>Edit Student</h4>
                    <form action="backend/update_student.php" method="POST">
                        <input type="hidden" name="id" id="edit-student-id">
                        <input type="text" name="name" id="edit-student-name" required>
                        <input type="email" name="email" id="edit-student-email" required>
                        <input type="text" name="phone" id="edit-student-phone" placeholder="Phone (optional)">
                        <input type="number" name="gpa" id="edit-student-gpa" step="0.1" min="0" max="4" placeholder="GPA (optional)">
                        <button type="submit">Update</button>
                        <button type="button" onclick="closeEditStudentForm()">Cancel</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>
</html>