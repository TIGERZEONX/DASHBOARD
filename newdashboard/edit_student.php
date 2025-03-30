<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM students WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $query = "UPDATE students SET name=?, email=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    header("Location: students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Student</title>
</head>
<body>
    <h2>Edit Student</h2>
    <form method="POST">
        <input type="text" name="name" value="<?= $student['name']; ?>" required>
        <input type="email" name="email" value="<?= $student['email']; ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
