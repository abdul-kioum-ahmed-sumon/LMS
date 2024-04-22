<?php
session_start(); // Start the session
include 'db_connection.php';

$title = $_POST['title'];
$content = $_POST['content'];

$sql = "INSERT INTO notices (title, content) VALUES ('$title', '$content')";
if ($conn->query($sql) === TRUE) {
    $_SESSION['success_message'] = "Notice created successfully!"; // Store success message in session
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
