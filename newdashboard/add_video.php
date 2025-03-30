<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    header("Location: it_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $chapter_name = trim($_POST['chapter_name']);
    $topic_name = trim($_POST['topic_name']);
    $youtube_link = trim($_POST['youtube_link']);

    // ✅ Check if chapter exists or insert a new one
    $chapter_query = $conn->prepare("SELECT id FROM chapters WHERE course_id = ? AND chapter_name = ?");
    $chapter_query->bind_param("is", $course_id, $chapter_name);
    $chapter_query->execute();
    $chapter_result = $chapter_query->get_result();

    if ($chapter_result->num_rows > 0) {
        $chapter_row = $chapter_result->fetch_assoc();
        $chapter_id = $chapter_row['id'];
    } else {
        $insert_chapter = $conn->prepare("INSERT INTO chapters (course_id, chapter_name) VALUES (?, ?)");
        $insert_chapter->bind_param("is", $course_id, $chapter_name);
        $insert_chapter->execute();
        $chapter_id = $insert_chapter->insert_id;
    }

    // ✅ Check if topic exists or insert a new one
    $topic_query = $conn->prepare("SELECT id FROM topics WHERE chapter_id = ? AND topic_name = ?");
    $topic_query->bind_param("is", $chapter_id, $topic_name);
    $topic_query->execute();
    $topic_result = $topic_query->get_result();

    if ($topic_result->num_rows > 0) {
        $topic_row = $topic_result->fetch_assoc();
        $topic_id = $topic_row['id'];
    } else {
        $insert_topic = $conn->prepare("INSERT INTO topics (chapter_id, topic_name) VALUES (?, ?)");
        $insert_topic->bind_param("is", $chapter_id, $topic_name);
        $insert_topic->execute();
        $topic_id = $insert_topic->insert_id;
    }

    // ❌ If topic_id is NULL, return an error message
    if (!$topic_id) {
        echo "Error: Topic ID is missing!";
        exit();
    }

    // ✅ Insert YouTube video with correct topic_id
    $insert_video = $conn->prepare("INSERT INTO youtube_videos (course_id, topic_id, video_title, youtube_url) VALUES (?, ?, ?, ?)");
    $insert_video->bind_param("iiss", $course_id, $topic_id, $topic_name, $youtube_link);

    if ($insert_video->execute()) {
        echo "<script>alert('YouTube video added successfully!'); window.location.href = 'it_dashboard.php';</script>";
    } else {
        echo "Error adding video: " . $insert_video->error; // Debugging output
    }
}
?>
