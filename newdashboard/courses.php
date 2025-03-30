<?php
session_start();
include 'config.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT courses.id AS course_id, courses.course_name, courses.level, courses.course_code, 
               courses.duration, courses.prerequisites, 
               chapters.id AS chapter_id, chapters.chapter_name, 
               topics.id AS topic_id, topics.topic_name, 
               youtube_videos.youtube_url 
        FROM courses 
        JOIN course_access ON courses.id = course_access.course_id 
        LEFT JOIN chapters ON courses.id = chapters.course_id
        LEFT JOIN topics ON chapters.id = topics.chapter_id
        LEFT JOIN youtube_videos ON topics.id = youtube_videos.topic_id
        WHERE course_access.user_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Organizing the data
$courses = [];
while ($row = $result->fetch_assoc()) {
    $course_id = $row['course_id'];
    $chapter_id = $row['chapter_id'];
    $topic_id = $row['topic_id'];

    if (!isset($courses[$course_id])) {
        $courses[$course_id] = [
            "name" => $row["course_name"],
            "level" => $row["level"],
            "code" => $row["course_code"],
            "duration" => $row["duration"],
            "prerequisites" => $row["prerequisites"],
            "chapters" => []
        ];
    }

    if (!empty($chapter_id) && !isset($courses[$course_id]["chapters"][$chapter_id])) {
        $courses[$course_id]["chapters"][$chapter_id] = [
            "name" => $row["chapter_name"],
            "topics" => []
        ];
    }

    if (!empty($topic_id) && !isset($courses[$course_id]["chapters"][$chapter_id]["topics"][$topic_id])) {
        $courses[$course_id]["chapters"][$chapter_id]["topics"][$topic_id] = [
            "name" => $row["topic_name"],
            "videos" => []
        ];
    }

    if (!empty($row["youtube_url"])) {
        $courses[$course_id]["chapters"][$chapter_id]["topics"][$topic_id]["videos"][] = $row["youtube_url"];
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Courses</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #202020;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .sidebar h2 {
            color: #f00;
        }
        .sidebar a {
            display: block;
            color: #bbb;
            padding: 10px;
            margin: 10px 0;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background: #303030;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .navbar {
            background: #202020;
            padding: 10px;
            text-align: right;
        }
        .navbar span {
            color: #fff;
        }
        .courses-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .course-card {
            background: #282828;
            padding: 15px;
            border-radius: 8px;
            width: 300px;
            cursor: pointer;
            transition: 0.3s;
        }
        .course-card:hover {
            background: #404040;
        }
        .chapter-card, .topic-card {
            background: #333;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
        }
        .video-container {
            display: none;
            padding: 10px;
        }
        iframe {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Student Dashboard</h2>
        <a href="dashboard.php">🏠 Home</a>
        <a href="profile.php">👤 Profile</a>
        <a href="courses.php">📖 Courses</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
    <div class="main-content">
        <div class="navbar">
            <span>Welcome, <?php echo $_SESSION['name']; ?></span>
        </div>
        <h2>Available Courses</h2>
        <div class="courses-container">
            <?php foreach ($courses as $course_id => $course) { ?>
                <div class="course-card" onclick="toggleElement('course-<?php echo $course_id; ?>')">
                    <strong><?php echo $course['name']; ?></strong>
                </div>
                <div class="chapter" id="course-<?php echo $course_id; ?>" style="display:none;">
                    <?php foreach ($course['chapters'] as $chapter_id => $chapter) { ?>
                        <div class="chapter-card" onclick="toggleElement('chapter-<?php echo $chapter_id; ?>')">
                            📂 <?php echo $chapter['name']; ?>
                        </div>
                        <div class="topic" id="chapter-<?php echo $chapter_id; ?>" style="display:none;">
                            <?php foreach ($chapter['topics'] as $topic_id => $topic) { ?>
                                <div class="topic-card" onclick="toggleElement('topic-<?php echo $topic_id; ?>')">
                                    📜 <?php echo $topic['name']; ?>
                                </div>
                                <div class="video-container" id="topic-<?php echo $topic_id; ?>">
                                    <?php if (!empty($topic['videos'])) { ?>
                                        <?php foreach ($topic['videos'] as $video_url) { ?>
                                            <iframe src="<?php echo htmlspecialchars($video_url); ?>" frameborder="0" allowfullscreen></iframe>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <p>No videos available for this topic.</p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        function toggleElement(id) {
            var element = document.getElementById(id);
            element.style.display = (element.style.display === "none" || element.style.display === "") ? "block" : "none";
        }
    </script>
</body>
</html>
