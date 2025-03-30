<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = intval($_POST['course_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (empty($course_id) || (empty($title) && empty($description))) {
        die("Invalid input.");
    }

    $query = "UPDATE courses SET ";
    $params = [];
    if (!empty($title)) {
        $params[] = "course_name = ?";
    }
    if (!empty($description)) {
        $params[] = "description = ?";
    }

    $query .= implode(", ", $params) . " WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    if (!empty($title) && !empty($description)) {
        $stmt->bind_param("ssi", $title, $description, $course_id);
    } elseif (!empty($title)) {
        $stmt->bind_param("si", $title, $course_id);
    } else {
        $stmt->bind_param("si", $description, $course_id);
    }

    if ($stmt->execute()) {
        echo "Course updated successfully.";
    } else {
        echo "Failed to update course.";
    }
    $stmt->close();
}
?>
