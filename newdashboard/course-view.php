<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$course_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch course details
$stmt = $conn->prepare("SELECT * FROM courses WHERE id=?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

// Fetch enrollment progress
$progress_stmt = $conn->prepare("SELECT completed_videos, status, certificate_url FROM enrollments WHERE user_id=? AND course_id=?");
$progress_stmt->bind_param("ii", $user_id, $course_id);
$progress_stmt->execute();
$progress_result = $progress_stmt->get_result();
$progress = $progress_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $course['title']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef; text-align: center; }
        .container { width: 60%; margin: auto; padding: 20px; background: white; border-radius: 10px; }
        iframe { width: 100%; height: 400px; border-radius: 10px; }
        .progress { width: 100%; background: #ddd; border-radius: 10px; margin-top: 20px; }
        .progress-bar { width: <?php echo $progress['completed_videos'] / 10 * 100; ?>%; background: green; height: 20px; border-radius: 10px; }
        .certificate { color: white; background: blue; padding: 10px 15px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $course['title']; ?></h2>
        <iframe src="<?php echo $course['video_url']; ?>" allowfullscreen></iframe>
        <p><?php echo $course['description']; ?></p>
        
        <!-- Progress Bar -->
        <div class="progress">
            <div class="progress-bar"></div>
        </div>

        <!-- Show certificate if completed -->
        <?php if ($progress['status'] === "Completed"): ?>
            <p>🎉 Congratulations! You completed this course.</p>
            <a href="<?php echo $progress['certificate_url']; ?>" class="certificate">Download Certificate</a>
        <?php endif; ?>
    </div>
</body>
</html>
