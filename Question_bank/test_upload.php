<?php
// Test file for PDF upload functionality
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>PDF Upload Test</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
</head>
<body>
<div class='container mt-5'>
    <div class='row justify-content-center'>
        <div class='col-md-8'>
            <div class='card'>
                <div class='card-header bg-primary text-white'>
                    <h4 class='mb-0'>PDF Upload Test</h4>
                </div>
                <div class='card-body'>";

// Test 1: Check if uploads directory exists and is writable
echo "<h5>Test 1: Directory Permissions</h5>";
if (is_dir("uploads")) {
    echo "<div class='alert alert-success'>✓ uploads directory exists</div>";
    if (is_writable("uploads")) {
        echo "<div class='alert alert-success'>✓ uploads directory is writable</div>";
    } else {
        echo "<div class='alert alert-danger'>✗ uploads directory is not writable</div>";
    }
} else {
    echo "<div class='alert alert-danger'>✗ uploads directory does not exist</div>";
    if (mkdir("uploads", 0755, true)) {
        echo "<div class='alert alert-success'>✓ Created uploads directory</div>";
    } else {
        echo "<div class='alert alert-danger'>✗ Failed to create uploads directory</div>";
    }
}

// Test 2: Check database table structure
echo "<h5>Test 2: Database Structure</h5>";
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'previous_questions'");
if (mysqli_num_rows($table_check) > 0) {
    echo "<div class='alert alert-success'>✓ previous_questions table exists</div>";

    // Check for required columns
    $columns = ['department', 'course_code', 'course', 'subject', 'year', 'level', 'term', 'file_path'];
    foreach ($columns as $column) {
        $col_check = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE '$column'");
        if (mysqli_num_rows($col_check) > 0) {
            echo "<div class='alert alert-success'>✓ $column column exists</div>";
        } else {
            echo "<div class='alert alert-warning'>⚠ $column column missing</div>";
        }
    }
} else {
    echo "<div class='alert alert-danger'>✗ previous_questions table does not exist</div>";
}

// Test 3: Test file upload functionality
echo "<h5>Test 3: File Upload Test</h5>";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_type = $file['type'];

    echo "<div class='alert alert-info'>File: $file_name, Size: " . round($file_size / 1024, 2) . " KB, Type: $file_type</div>";

    // Check file type
    $allowed_types = ['application/pdf'];
    if (!in_array($file_type, $allowed_types)) {
        echo "<div class='alert alert-danger'>✗ Invalid file type. Only PDF files are allowed.</div>";
    } elseif ($file_size > 10 * 1024 * 1024) {
        echo "<div class='alert alert-danger'>✗ File too large. Maximum size is 10MB.</div>";
    } else {
        // Generate unique filename
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $upload_path = 'uploads/' . $unique_filename;

        // Move uploaded file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            echo "<div class='alert alert-success'>✓ File uploaded successfully to: $upload_path</div>";

            // Test database insertion
            $test_department = "Computer Science & Engineering";
            $test_course_code = "CSE101";
            $test_course = "Test Course";
            $test_subject = "Test Subject";
            $test_year = 2024;
            $test_level = "Level1";
            $test_term = "Term I";

            $sql = "INSERT INTO previous_questions (department, course_code, course, subject, year, level, term, file_path) 
                    VALUES ('$test_department', '$test_course_code', '$test_course', '$test_subject', $test_year, '$test_level', '$test_term', '$upload_path')";

            if (mysqli_query($conn, $sql)) {
                echo "<div class='alert alert-success'>✓ Database insertion successful</div>";

                // Clean up test data
                $test_id = mysqli_insert_id($conn);
                mysqli_query($conn, "DELETE FROM previous_questions WHERE id = $test_id");
                unlink($upload_path);
                echo "<div class='alert alert-info'>✓ Test data cleaned up</div>";
            } else {
                echo "<div class='alert alert-danger'>✗ Database insertion failed: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>✗ File upload failed</div>";
        }
    }
}

echo "<form method='POST' enctype='multipart/form-data' class='mt-3'>
        <div class='mb-3'>
            <label for='test_file' class='form-label'>Select a PDF file to test upload:</label>
            <input type='file' class='form-control' id='test_file' name='test_file' accept='.pdf' required>
        </div>
        <button type='submit' class='btn btn-primary'>Test Upload</button>
      </form>";

echo "<div class='mt-4'>
        <a href='index.php' class='btn btn-secondary'>Back to Question Bank</a>
        <a href='add.php' class='btn btn-success'>Add New Question</a>
      </div>";

echo "</div></div></div></div>";

mysqli_close($conn);

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
