<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $notice_id = $_GET['id'];
    
    // Delete notice from the database
    $sql = "DELETE FROM notices2 WHERE id = $notice_id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Notice deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting notice: " . $conn->error;
    }
} else {
    $_SESSION['error_message'] = "Invalid request!";
}

header("Location: index.php");
exit();
?>
