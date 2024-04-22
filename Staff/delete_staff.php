<?php
include("config.php");

// Get staff ID from URL parameter
$id = $_GET['id'];

// Delete staff record
$sql = "DELETE FROM staff WHERE staff_id='$id'";

if (mysqli_query($conn, $sql)) {
    header("Location: view_staff.php");
    exit();
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
