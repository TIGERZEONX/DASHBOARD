<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$course_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if already enrolled
$check = $conn->prepare("SELECT * FROM enrollments WHERE user_id=? AND course_id=?");
$check->bind_param("ii", $user_id, $course_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    // Enroll the user
    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
}

header("Location: my-courses.php");
exit();
?>
