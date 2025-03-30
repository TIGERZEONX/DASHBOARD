<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    header("Location: it_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields exist in $_POST
    if (!isset($_POST['course_name'], $_POST['course_code'], $_POST['description'], $_POST['level'], $_POST['duration'], $_POST['prerequisites'], $_POST['teacher_id'])) {
        die("❌ Error: All fields are required! <a href='dashboard.php'>Go Back</a>");
    }

    // Get form values safely
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $description = trim($_POST['description']);
    $level = trim($_POST['level']);
    $duration = trim($_POST['duration']);
    $prerequisites = trim($_POST['prerequisites']);
    $teacher_id = $_POST['teacher_id'];

    // Validate required fields
    if (empty($course_name) || empty($course_code) || empty($description) || empty($level) || empty($duration) || empty($prerequisites) || empty($teacher_id)) {
        die("❌ Error: All fields must be filled! <a href='dashboard.php'>Go Back</a>");
    }

    // Check for duplicate course code
    $check_sql = "SELECT id FROM courses WHERE course_code = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $course_code);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("❌ Error: Course code already exists! <a href='dashboard.php'>Go Back</a>");
    }
    $check_stmt->close();

    // Insert the new course
    $sql = "INSERT INTO courses (course_name, course_code, description, level, duration, prerequisites, teacher_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("❌ SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ssssisi", $course_name, $course_code, $description, $level, $duration, $prerequisites, $teacher_id);

    if ($stmt->execute()) {
        echo "✅ Course added successfully! <a href='it_dashboard.php'>Go Back</a>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❌ Invalid request! <a href='it_dashboard.php'>Go Back</a>";
}
?>
