<?php
// Database fix script to ensure all required columns exist
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Fix Database - Question Bank</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
</head>
<body>
<div class='container mt-5'>
    <div class='row justify-content-center'>
        <div class='col-md-8'>
            <div class='card'>
                <div class='card-header bg-warning text-dark'>
                    <h4 class='mb-0'><i class='fas fa-tools me-2'></i>Database Fix Tool</h4>
                </div>
                <div class='card-body'>";

// Database connection
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    echo "<div class='alert alert-danger'>✗ Database connection failed: " . mysqli_connect_error() . "</div>";
    exit;
}

echo "<div class='alert alert-success'>✓ Database connection successful</div>";

// Check if table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'previous_questions'");
if (mysqli_num_rows($table_check) == 0) {
    // Create table if it doesn't exist
    $create_table = "CREATE TABLE `previous_questions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `department` varchar(100) DEFAULT NULL,
        `course_code` varchar(50) DEFAULT NULL,
        `course` varchar(100) DEFAULT NULL,
        `subject` varchar(100) DEFAULT NULL,
        `year` int(11) DEFAULT NULL,
        `level` varchar(50) DEFAULT NULL,
        `term` varchar(50) DEFAULT NULL,
        `file_path` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if (mysqli_query($conn, $create_table)) {
        echo "<div class='alert alert-success'>✓ Created previous_questions table</div>";
    } else {
        echo "<div class='alert alert-danger'>✗ Error creating table: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='alert alert-info'>✓ previous_questions table exists</div>";
}

// Define required columns
$required_columns = [
    'department' => "ALTER TABLE previous_questions ADD COLUMN department VARCHAR(100) DEFAULT NULL AFTER id",
    'course_code' => "ALTER TABLE previous_questions ADD COLUMN course_code VARCHAR(50) DEFAULT NULL AFTER department",
    'level' => "ALTER TABLE previous_questions ADD COLUMN level VARCHAR(50) DEFAULT NULL AFTER year",
    'term' => "ALTER TABLE previous_questions ADD COLUMN term VARCHAR(50) DEFAULT NULL AFTER level",
    'file_path' => "ALTER TABLE previous_questions ADD COLUMN file_path VARCHAR(255) DEFAULT NULL AFTER term"
];

// Check and add missing columns
foreach ($required_columns as $column => $sql) {
    $column_check = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE '$column'");
    if (mysqli_num_rows($column_check) == 0) {
        if (mysqli_query($conn, $sql)) {
            echo "<div class='alert alert-success'>✓ Added $column column</div>";
        } else {
            echo "<div class='alert alert-danger'>✗ Error adding $column column: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div class='alert alert-info'>✓ $column column already exists</div>";
    }
}

// Show current table structure
echo "<h5 class='mt-4'>Current Table Structure:</h5>";
$result = mysqli_query($conn, "DESCRIBE previous_questions");
if ($result) {
    echo "<table class='table table-sm table-bordered'>";
    echo "<thead class='table-dark'><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr></thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}

// Check uploads directory
echo "<h5 class='mt-4'>Directory Check:</h5>";
if (is_dir("uploads")) {
    echo "<div class='alert alert-success'>✓ uploads directory exists</div>";
    if (is_writable("uploads")) {
        echo "<div class='alert alert-success'>✓ uploads directory is writable</div>";
    } else {
        echo "<div class='alert alert-warning'>⚠ uploads directory is not writable</div>";
    }
} else {
    if (mkdir("uploads", 0755, true)) {
        echo "<div class='alert alert-success'>✓ Created uploads directory</div>";
    } else {
        echo "<div class='alert alert-danger'>✗ Failed to create uploads directory</div>";
    }
}

echo "<div class='alert alert-success mt-4'>
        <i class='fas fa-check-circle me-2'></i>
        <strong>Database fix completed!</strong> Your Question Bank system should now work properly.
      </div>";

echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center mt-4'>
        <a href='index.php' class='btn btn-primary me-md-2'>
            <i class='fas fa-list me-1'></i>View Questions
        </a>
        <a href='add.php' class='btn btn-success me-md-2'>
            <i class='fas fa-plus me-1'></i>Add Question
        </a>
        <a href='debug.php' class='btn btn-info'>
            <i class='fas fa-vial me-1'></i>Debug System
        </a>
      </div>";

mysqli_close($conn);

echo "</div></div></div></div>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
