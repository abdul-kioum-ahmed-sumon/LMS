<?php
// Database setup for Question Bank PDF functionality

// Database connection
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Question Bank Setup</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <style>
        body { background-color: #f8f9fa; }
        .setup-card { box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); }
    </style>
</head>
<body>";

echo "<div class='container mt-5'>
    <div class='row justify-content-center'>
        <div class='col-md-8'>
            <div class='card setup-card'>
                <div class='card-header bg-primary text-white'>
                    <h4 class='mb-0'><i class='fas fa-cogs me-2'></i>Question Bank Database Setup</h4>
                </div>
                <div class='card-body'>";

// Check if previous_questions table exists
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
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> Created previous_questions table
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Error!</strong> Creating table: " . mysqli_error($conn) . "
              </div>";
    }
} else {
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Info:</strong> previous_questions table already exists
          </div>";
}

// Check if department column exists
$check_department = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'department'");
if (mysqli_num_rows($check_department) == 0) {
    // Add department column
    $alter_department = "ALTER TABLE previous_questions ADD COLUMN department VARCHAR(100) DEFAULT NULL AFTER id";

    if (mysqli_query($conn, $alter_department)) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> Added department column
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Error!</strong> Adding department column: " . mysqli_error($conn) . "
              </div>";
    }
} else {
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Info:</strong> department column already exists
          </div>";
}

// Check if course_code column exists
$check_course_code = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'course_code'");
if (mysqli_num_rows($check_course_code) == 0) {
    // Add course_code column
    $alter_course_code = "ALTER TABLE previous_questions ADD COLUMN course_code VARCHAR(50) DEFAULT NULL AFTER department";

    if (mysqli_query($conn, $alter_course_code)) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> Added course_code column
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Error!</strong> Adding course_code column: " . mysqli_error($conn) . "
              </div>";
    }
} else {
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Info:</strong> course_code column already exists
          </div>";
}

// Check if level column exists
$check_level = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'level'");
if (mysqli_num_rows($check_level) == 0) {
    // Add level column
    $alter_level = "ALTER TABLE previous_questions ADD COLUMN level VARCHAR(50) DEFAULT NULL AFTER year";

    if (mysqli_query($conn, $alter_level)) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> Added level column
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Error!</strong> Adding level column: " . mysqli_error($conn) . "
              </div>";
    }
} else {
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Info:</strong> level column already exists
          </div>";
}

// Check if term column exists
$check_term = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'term'");
if (mysqli_num_rows($check_term) == 0) {
    // Add term column
    $alter_term = "ALTER TABLE previous_questions ADD COLUMN term VARCHAR(50) DEFAULT NULL AFTER level";

    if (mysqli_query($conn, $alter_term)) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> Added term column
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Error!</strong> Adding term column: " . mysqli_error($conn) . "
              </div>";
    }
} else {
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Info:</strong> term column already exists
          </div>";
}

// Check if file_path column exists
$column_check = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'file_path'");
if (mysqli_num_rows($column_check) == 0) {
    // Add file_path column
    $alter_table = "ALTER TABLE previous_questions ADD COLUMN file_path VARCHAR(255) DEFAULT NULL AFTER term";

    if (mysqli_query($conn, $alter_table)) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> Added file_path column
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Error!</strong> Adding column: " . mysqli_error($conn) . "
              </div>";
    }
} else {
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Info:</strong> file_path column already exists
          </div>";
}

// Check uploads directory
if (is_dir("uploads")) {
    echo "<div class='alert alert-info'>
            <i class='fas fa-info-circle me-2'></i>
            <strong>Info:</strong> uploads directory exists
          </div>";
    if (is_writable("uploads")) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> uploads directory is writable
              </div>";
    } else {
        echo "<div class='alert alert-warning'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Warning:</strong> uploads directory is not writable
              </div>";
    }
} else {
    if (mkdir("uploads", 0755, true)) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Success!</strong> Created uploads directory
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Error!</strong> Creating uploads directory
              </div>";
    }
}

echo "<div class='alert alert-success mt-3'>
        <i class='fas fa-check-circle me-2'></i>
        <strong>Setup Complete!</strong> Your Question Bank system is ready to use.
      </div>";

echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center mt-4'>
        <a href='index.php' class='btn btn-primary me-md-2'>
            <i class='fas fa-list me-1'></i>View Questions
        </a>
        <a href='add.php' class='btn btn-success me-md-2'>
            <i class='fas fa-plus me-1'></i>Add Question
        </a>
        <a href='test.php' class='btn btn-info'>
            <i class='fas fa-vial me-1'></i>Test System
        </a>
      </div>";

echo "</div></div></div></div>";

mysqli_close($conn);

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
