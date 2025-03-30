<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = intval($_POST['course_id']);

    if (empty($course_id)) {
        die("Invalid course selection.");
    }

    // Delete associated videos before deleting the course
    $stmt_videos = $conn->prepare("DELETE FROM youtube_videos WHERE course_id = ?");
    $stmt_videos->bind_param("i", $course_id);
    $stmt_videos->execute();
    $stmt_videos->close();

    // Delete the course
    $stmt_course = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt_course->bind_param("i", $course_id);

    if ($stmt_course->execute()) {
        echo "Course deleted successfully.";
    } else {
        echo "Failed to delete course.";
    }
    $stmt_course->close();
}
?>
