<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    header("Location: it_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['student_id'], $_POST['course_id'])) {
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];

        // Debugging: Print SQL statement
        $sql = "DELETE FROM course_access WHERE student_id = ? AND course_id = ?";
        echo "SQL Query: $sql<br>";

        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("❌ Error in SQL Query: " . $conn->error); // Show SQL error
        }

        $stmt->bind_param("ii", $student_id, $course_id);

        if ($stmt->execute()) {
            echo "✅ Student removed successfully!";
        } else {
            echo "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "❌ Invalid input!";
    }
} else {
    echo "❌ Invalid request!";
}
?>
