<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    header("Location: it_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['student_id'];  // user_id instead of student_id
    $course_id = $_POST['course_id'];

    // ✅ Validate input
    if (empty($user_id) || empty($course_id)) {
        die("<p style='color:red;'>❌ Error: Invalid Student or Course ID.</p>");
    }

    // ✅ Check if the user exists and is a student
    $role_check = $conn->prepare("SELECT role FROM users WHERE id = ?");
    if (!$role_check) {
        die("<p style='color:red;'>❌ SQL Error: " . $conn->error . "</p>");
    }
    $role_check->bind_param("i", $user_id);
    $role_check->execute();
    $role_result = $role_check->get_result();

    if ($role_result->num_rows === 0) {
        die("<p style='color:red;'>❌ Error: User not found.</p>");
    }

    $role_data = $role_result->fetch_assoc();
    if ($role_data['role'] !== 'student') {
        die("<p style='color:red;'>❌ Error: Only students can be assigned to courses.</p>");
    }
    $role_check->close();

    // ✅ Check for duplicate access
    $check = $conn->prepare("SELECT * FROM course_access WHERE user_id = ? AND course_id = ?");
    if (!$check) {
        die("<p style='color:red;'>❌ SQL Error: " . $conn->error . "</p>");
    }
    $check->bind_param("ii", $user_id, $course_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<p style='color:red;'>❌ Student already has access to this course!</p>";
    } else {
        // ✅ Assign student to the course
        $stmt = $conn->prepare("INSERT INTO course_access (user_id, course_id) VALUES (?, ?)");
        if (!$stmt) {
            die("<p style='color:red;'>❌ Prepare Failed: " . $conn->error . "</p>");
        }
        $stmt->bind_param("ii", $user_id, $course_id);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>✅ Access granted successfully!</p>";
        } else {
            echo "<p style='color:red;'>❌ Execution Failed: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>
<a href="it_dashboard.php">Go Back</a>
