<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch total students
$result = $conn->query("SELECT COUNT(*) AS total_students FROM users");
if (!$result) { die("Error fetching students: " . $conn->error); }
$total_students = $result->fetch_assoc()['total_students'];

// Fetch total courses
$result = $conn->query("SELECT COUNT(*) AS total_courses FROM courses");
if (!$result) { die("Error fetching courses: " . $conn->error); }
$total_courses = $result->fetch_assoc()['total_courses'];

// Check if 'status' column exists
$column_check = $conn->query("SHOW COLUMNS FROM assignments LIKE 'status'");
if ($column_check->num_rows > 0) {
    // 'status' column exists
    $result = $conn->query("SELECT COUNT(*) AS pending_tasks FROM assignments WHERE status='pending'");
} else {
    // If status column doesn't exist, count all assignments
    $result = $conn->query("SELECT COUNT(*) AS pending_tasks FROM assignments");
}

if (!$result) { die("Error fetching tasks: " . $conn->error); }
$pending_tasks = $result->fetch_assoc()['pending_tasks'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Student Dashboard</h2>
        <a href="dashboard.php">🏠 Home</a>
        <a href="profile.php">👤 Profile</a>
        <a href="students.php">📚 Students</a>
        <a href="courses.php">📖 Courses</a>
        <a href="grades.php">📊 Grades</a>
        <a href="settings.php">⚙️ Settings</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar">
            <span>Welcome, <?php echo $_SESSION['name']; ?></span>
            <div class="right">
                <button id="dark-mode-toggle">🌙</button>
                <a href="notifications.php" class="notif-icon">🔔</a>
                <a href="logout.php" class="logout">Logout</a>
            </div>
        </div>

        <h2>Dashboard Overview</h2>

        <!-- Info Cards -->
        <div class="cards">
            <div class="card">
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>
            <div class="card">
                <h3>Total Courses</h3>
                <p><?php echo $total_courses; ?></p>
            </div>
            <div class="card">
                <h3>Pending Tasks</h3>
                <p><?php echo $pending_tasks; ?></p>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="recent-activity">
            <h3>Recent Activity</h3>
            <ul>
                <li>User X enrolled in Course Y</li>
                <li>Assignment Z is due tomorrow</li>
                <li>Admin added a new course: Machine Learning</li>
            </ul>
        </div>

        <!-- Progress Chart -->
        <div class="chart-container">
            <h3>Course Progress</h3>
            <canvas id="progressChart"></canvas>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
