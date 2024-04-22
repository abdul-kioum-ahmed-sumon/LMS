<?php
include 'db_connection.php';

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

$sql = "UPDATE notices SET title='$title', content='$content' WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
