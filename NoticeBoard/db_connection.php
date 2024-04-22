<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "lms";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo "Failed to connect DB" . $conn->connect_error;
}

// Insert Notice
if(isset($_POST['add_notice'])) {
$title = $_POST['title'];
$content = $_POST['content'];
$sql = "INSERT INTO notices (title, content) VALUES ('$title', '$content')";
if ($conn->query($sql) === TRUE) {
header("Location: ".$_SERVER['PHP_SELF']);
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}
}

// Update Notice
if(isset($_POST['update_notice'])) {
$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
$sql = "UPDATE notices SET title='$title', content='$content' WHERE id=$id";
if ($conn->query($sql) === TRUE) {
header("Location: ".$_SERVER['PHP_SELF']);
} else {
echo "Error updating record: " . $conn->error;
}
}

// Delete Notice
if(isset($_GET['delete_notice'])) {
$id = $_GET['id'];
$sql = "DELETE FROM notices WHERE id=$id";
if ($conn->query($sql) === TRUE) {
header("Location: ".$_SERVER['PHP_SELF']);
} else {
echo "Error deleting record: " . $conn->error;
}
}

?>