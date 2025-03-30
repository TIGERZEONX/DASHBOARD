<?php
include 'config.php';

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $query = $conn->prepare("SELECT id, chapter_name FROM chapters WHERE course_id = ?");
    $query->bind_param("i", $course_id);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['chapter_name']}</option>";
    }
}
?>
