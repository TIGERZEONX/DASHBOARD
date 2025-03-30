<?php
session_start();
include 'config.php';

if (!isset($_SESSION['it_admin_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid input.");
    }

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("This email is already registered.");
    }
    $check_stmt->close();

    // Add teacher with default password
    $password = password_hash("defaultpassword", PASSWORD_DEFAULT);
    $role = "teacher";

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "Teacher added successfully.";
    } else {
        echo "Failed to add teacher.";
    }
    $stmt->close();
}
?>
