<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT name, email FROM users WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            min-height: 100vh;
            background: #f4f4f4;
        }
        .sidebar {
            width: 250px;
            background: #1e1e2f;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
            background: #333;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #5757a5;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: 100%;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            background: blue;
            color: white;
            padding: 15px;
            border-radius: 5px;
        }
        .profile-container {
            background: white;
            padding: 20px;
            width: 400px;
            margin: auto;
            margin-top: 50px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            background: #ccc;
            display: inline-block;
        }
        .btn {
            display: inline-block;
            padding: 10px;
            margin-top: 10px;
            width: 90%;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background: #218838;
        }
        .logout {
            color: white;
            text-decoration: none;
            background: red;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Student Dashboard</h2>
        <a href="dashboard.php">🏠 Home</a>
        <a href="profile.php">👤 Profile</a>
        <a href="students.php">📚 Students</a>
        <a href="courses.php">📖 Courses</a>
        <a href="grades.php">📊 Grades</a>
        <a href="settings.php">⚙️ Settings</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar">
            Welcome, <?php echo $_SESSION['name']; ?>
            <a href="logout.php" class="logout">Logout</a>
        </div>

        <!-- Profile Section -->
        <div class="profile-container">
            <div class="profile-img"></div>
            <h2><?= $name; ?></h2>
            <p><strong>Email:</strong> <?= $email; ?></p>
            <a href="edit-profile.php" class="btn">Edit Profile</a>
            <a href="change-password.php" class="btn">Change Password</a>
        </div>
    </div>

</body>
</html>

