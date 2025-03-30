<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    header("Location: it_login.php");
    exit();
}

// Fetch courses
$courses = $conn->query("SELECT id, course_name FROM courses")->fetch_all(MYSQLI_ASSOC);

// Fetch students
$students = $conn->query("SELECT id, name FROM users WHERE role = 'student'")->fetch_all(MYSQLI_ASSOC);

// Fetch teachers
$teachers = $conn->query("SELECT id, name FROM users WHERE role = 'teacher'")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>IT Department Dashboard</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #0a192f;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 0px 20px rgba(0, 255, 255, 0.3);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            font-size: 26px;
            font-weight: bold;
            color: cyan;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.2);
        }

        form {
            margin-bottom: 20px;
        }

        .input-box, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            background: #0a1f44;
            color: #fff;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background: cyan;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #00c4ff;
            transform: scale(1.05);
            box-shadow: 0px 0px 10px cyan;
        }

        .logout a {
            color: cyan;
            text-decoration: none;
            font-size: 16px;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🚀 IT Department Dashboard</h2>
        <p>Welcome, <strong><?php echo $_SESSION['it_admin_name']; ?></strong></p>

        <!-- Add New Course -->
        <div class="form-card">
            <h3>📚 Add New Course</h3>
            <form method="POST" action="add_course.php">
                <input type="text" name="course_name" class="input-box" placeholder="📌 Course Name" required>
                <input type="text" name="course_code" class="input-box" placeholder="🔢 Course Code" required>
                <textarea name="description" class="input-box" placeholder="📝 Course Description" required></textarea>
                <input type="text" name="level" class="input-box" placeholder="📊 Level" required>
                <input type="text" name="duration" class="input-box" placeholder="⏳ Duration" required>
                <input type="text" name="prerequisites" class="input-box" placeholder="📋 Prerequisites" required>
                <label>👨‍🏫 Assign Teacher:</label>
                <select name="teacher_id" required>
                    <?php foreach ($teachers as $teacher) { ?>
                        <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="btn">➕ Add Course</button>
            </form>
        </div>

        <!-- Delete Course -->
        <div class="form-card">
            <h3>🗑 Delete Course</h3>
            <form method="POST" action="delete_course.php">
                <select name="course_id" required>
                    <?php foreach ($courses as $course) { ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" class="btn">🗑 Delete Course</button>
            </form>
        </div>

        <!-- Grant Course Access to Students -->
        <div class="form-card">
            <h3>🎓 Grant Course Access</h3>
            <form method="POST" action="grant_access.php">
                <label>👨‍🎓 Select Student:</label>
                <select name="student_id" required>
                    <?php foreach ($students as $student) { ?>
                        <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                    <?php } ?>
                </select>

                <label>📚 Select Course:</label>
                <select name="course_id" required>
                    <?php foreach ($courses as $course) { ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php } ?>
                </select>

                <button type="submit" class="btn">✅ Grant Access</button>
            </form>
        </div>

        <!-- Remove Student from Course -->
        <div class="form-card">
            <h3>🚫 Remove Student from Course</h3>
            <form method="POST" action="remove_student.php">
                <label>👨‍🎓 Select Student:</label>
                <select name="student_id" required>
                    <?php foreach ($students as $student) { ?>
                        <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                    <?php } ?>
                </select>

                <label>📚 Select Course:</label>
                <select name="course_id" required>
                    <?php foreach ($courses as $course) { ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php } ?>
                </select>

                <button type="submit" class="btn">🚫 Remove Access</button>
            </form>
        </div>

        <!-- Update Course -->
        <div class="form-card">
            <h3>✏️ Update Course</h3>
            <form method="POST" action="update_course.php">
                <select name="course_id" required>
                    <option value="" disabled selected>Select Course</option>
                    <?php foreach ($courses_list as $course) { ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php } ?>
                </select>
                <input type="text" name="course_name" class="input-box" placeholder="📌 New Course Name">
                <input type="text" name="course_code" class="input-box" placeholder="🔢 New Course Code">
                <textarea name="description" class="input-box" placeholder="📝 New Description"></textarea>
                <input type="text" name="level" class="input-box" placeholder="📊 New Level">
                <input type="text" name="duration" class="input-box" placeholder="⏳ New Duration">
                <input type="text" name="prerequisites" class="input-box" placeholder="📋 New Prerequisites">
                <button type="submit" class="btn">✏ Update Course</button>
            </form>
        </div>

        <!-- Add YouTube Videos -->
        <div class="form-card">
    <h3>🎥 Add YouTube Video</h3>
    <form method="POST" action="add_video.php">
        <label>📚 Select Course:</label>
        <select name="course_id" required>
            <?php foreach ($courses as $course) { ?>
                <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
            <?php } ?>
        </select>

        <label>📂 Chapter Name:</label>
        <input type="text" name="chapter_name" class="input-box" placeholder="📂 Enter Chapter Name" required>

        <label>📑 Topic Name:</label>
        <input type="text" name="topic_name" class="input-box" placeholder="📑 Enter Topic Name" required>

        <label>🔗 YouTube Video Link:</label>
        <input type="text" name="youtube_link" class="input-box" placeholder="🔗 Enter YouTube Video Link" required>

        <button type="submit" class="btn">🎥 Add Video</button>
    </form>
</div>



        <p class="logout"><a href="it_logout.php">🚪 Logout</a></p>
    </div>

    <script src="js/itscript.js"></script>

</body>
</html>
