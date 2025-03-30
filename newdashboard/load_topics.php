<?php
include 'config.php';

if (isset($_POST['chapter_id'])) {
    $chapter_id = $_POST['chapter_id'];
    $query = $conn->prepare("SELECT id, topic_name FROM topics WHERE chapter_id = ?");
    $query->bind_param("i", $chapter_id);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['topic_name']}</option>";
    }
}
?>
