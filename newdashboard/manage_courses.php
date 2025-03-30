<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: it_login.php");
    exit();
}

// Handle course addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $teacher_id = $_POST['teacher_id'];
    $thumbnail = $_POST['thumbnail'];
    $duration = $_POST['duration'];
    $prerequisites = $_POST['prerequisites'];

    $sql = "INSERT INTO courses (course_name, course_code, teacher_id, thumbnail, duration, prerequisites) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisds", $course_name, $course_code, $teacher_id, $thumbnail, $duration, $prerequisites);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Course Added!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses</title>
</head>
<body>
    <h2>Manage Courses</h2>
    <form method="POST">
        <input type="text" name="course_name" placeholder="Course Name" required><br><br>
        <input type="text" name="course_code" placeholder="Course Code" required><br><br>
        <input type="number" name="teacher_id" placeholder="Teacher ID" required><br><br>
        <input type="text" name="thumbnail" placeholder="Thumbnail URL"><br><br>
        <input type="number" name="duration" placeholder="Duration (mins)" required><br><br>
        <input type="text" name="prerequisites" placeholder="Prerequisites"><br><br>
        <button type="submit">Add Course</button>
    </form>
</body>
</html>
