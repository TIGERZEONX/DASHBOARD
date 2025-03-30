<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT courses.* FROM courses INNER JOIN enrollments ON courses.id = enrollments.course_id WHERE enrollments.user_id=$user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Courses</title>
</head>
<body>
    <h2>My Enrolled Courses</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div>
            <h3><?php echo $row['title']; ?></h3>
            <a href="course-view.php?id=<?php echo $row['id']; ?>">Go to Course</a>
        </div>
    <?php endwhile; ?>
</body>
</html>
