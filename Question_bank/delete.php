<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // First check if file_path column exists
    $column_check = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'file_path'");
    if (mysqli_num_rows($column_check) == 0) {
        // Add file_path column if it doesn't exist
        mysqli_query($conn, "ALTER TABLE previous_questions ADD COLUMN file_path VARCHAR(255) DEFAULT NULL");
    }

    // Get file path before deleting
    $sql = "SELECT file_path FROM previous_questions WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $file_path = $row['file_path'];

        // Delete the file if it exists
        if (!empty($file_path) && file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete from database
        $delete_sql = "DELETE FROM previous_questions WHERE id = $id";
        if (mysqli_query($conn, $delete_sql)) {
            header("Location: index.php?message=deleted");
            exit;
        } else {
            header("Location: index.php?message=error&error=" . urlencode(mysqli_error($conn)));
            exit;
        }
    } else {
        header("Location: index.php?message=notfound");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

mysqli_close($conn);
?>
