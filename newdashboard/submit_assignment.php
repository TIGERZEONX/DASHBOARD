<?php
include '../config.php';
session_start();
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $destination = "../uploads/" . $file_name;
    move_uploaded_file($file_tmp, $destination);

    $assignment_id = $_POST['assignment_id'];
    $sql = "INSERT INTO submissions (student_id, assignment_id, file) VALUES ('$user_id', '$assignment_id', '$destination')";

    if ($conn->query($sql) === TRUE) {
        echo "Assignment submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
