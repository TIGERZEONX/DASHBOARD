<?php
include '../config.php';
session_start();
$user_id = $_SESSION['user_id'];

$sql = "SELECT assignments.* FROM assignments
        JOIN enrollments ON assignments.course_id = enrollments.course_id
        WHERE enrollments.student_id='$user_id'";

$result = $conn->query($sql);
echo "<h3>Assignments</h3><ul>";

while ($row = $result->fetch_assoc()) {
    echo "<li>" . $row['title'] . " - Due: " . $row['due_date'] . "</li>";
}
echo "</ul>";
?>
