<?php
session_start();
include 'backend/db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$scholarships = $conn->query("SELECT * FROM scholarships");
$today = date("Y-m-d");
$notifications = $conn->query("SELECT name, deadline FROM scholarships WHERE deadline BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 7 DAY)");
$applied_scholarships = [];
$applied_query = $conn->query("SELECT scholarship_id FROM applications WHERE student_id='$student_id'");
while ($row = $applied_query->fetch_assoc()) {
    $applied_scholarships[] = $row['scholarship_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Scholarship Finder</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <h2>Student Dashboard</h2>
            <ul>
                <li><a href="#notifications">Notifications</a></li>
                <li><a href="#scholarships">Scholarships</a></li>
                <li><a href="#profile">Profile</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <header>
                <h3>Welcome, Student</h3>
                <a href="backend/logout.php" class="logout-btn">Logout</a>
            </header>

            <!-- Notifications -->
            <div class="card" id="notifications">
                <h4>Notifications</h4>
                <?php if ($notifications->num_rows > 0): ?>
                    <ul><?php while ($notification = $notifications->fetch_assoc()): ?><li>Scholarship "<?php echo $notification['name']; ?>" is available till <?php echo $notification['deadline']; ?>!</li><?php endwhile; ?></ul>
                <?php else: ?>
                    <p>No upcoming deadlines within the next 7 days.</p>
                <?php endif; ?>
            </div>

            <!-- Available Scholarships -->
            <div class="card" id="scholarships">
                <h4>Available Scholarships</h4>
                <table>
                    <thead><tr><th>Name</th><th>Criteria 1</th><th>Criteria 2</th><th>Criteria 3</th><th>Amount</th><th>Deadline</th><th>Action</th></tr></thead>
                    <tbody><?php while ($scholarship = $scholarships->fetch_assoc()): $deadline = $scholarship['deadline']; $days_until_deadline = (strtotime($deadline) - strtotime($today)) / (60 * 60 * 24); $deadline_class = ($days_until_deadline <= 3) ? 'deadline-near' : ''; $already_applied = in_array($scholarship['id'], $applied_scholarships); ?><tr><td><?php echo $scholarship['name']; ?></td><td><?php echo $scholarship['criteria_1']; ?></td><td><?php echo $scholarship['criteria_2']; ?></td><td><?php echo $scholarship['criteria_3']; ?></td><td><?php echo $scholarship['amount']; ?></td><td class="<?php echo $deadline_class; ?>"><?php echo $deadline; ?></td><td><?php if ($already_applied): ?><span class="applied">Already Applied</span><?php else: ?><button onclick="openApplyForm(<?php echo $scholarship['id']; ?>, '<?php echo $scholarship['name']; ?>')">Apply</button><?php endif; ?></td></tr><?php endwhile; ?></tbody>
                </table>
                <div id="apply-form" class="apply-form" style="display: none;"><h4>Apply for Scholarship: <span id="scholarship-name"></span></h4><form action="backend/apply_scholarship.php" method="POST" enctype="multipart/form-data"><input type="hidden" name="student_id" value="<?php echo $student_id; ?>"><input type="hidden" name="scholarship_id" id="apply-scholarship-id"><input type="text" name="name" placeholder="Your Name" required><input type="email" name="email" placeholder="Your Email" required><input type="file" name="attachment" accept=".pdf,.doc,.docx" required><button type="submit">Submit</button><button type="button" onclick="closeApplyForm()">Cancel</button></form></div>
            </div>

            <!-- Profile -->
            <div class="card" id="profile">
                <h4>Profile</h4>
                <?php
                $student = $conn->query("SELECT * FROM students WHERE id='$student_id'")->fetch_assoc();
                ?>
                <p><strong>Name:</strong> <?php echo $student['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $student['phone'] ?: 'Not provided'; ?></p>
                <p><strong>GPA:</strong> <?php echo $student['gpa'] ?: 'Not provided'; ?></p>
                <p><strong>Joined On:</strong> <?php echo $student['created_at']; ?></p>
            </div>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>
</html>