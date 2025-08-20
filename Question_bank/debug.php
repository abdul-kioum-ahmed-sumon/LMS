<?php
// Debug script to test database and file upload functionality
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Debug - Question Bank</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
</head>
<body>
<div class='container mt-5'>
    <div class='row justify-content-center'>
        <div class='col-md-8'>
            <div class='card'>
                <div class='card-header bg-primary text-white'>
                    <h4 class='mb-0'>Debug Information</h4>
                </div>
                <div class='card-body'>";

// Test 1: PHP Version and Extensions
echo "<h5>Test 1: PHP Environment</h5>";
echo "<div class='alert alert-info'>PHP Version: " . phpversion() . "</div>";
echo "<div class='alert alert-info'>MySQL Extension: " . (extension_loaded('mysqli') ? 'Loaded' : 'Not Loaded') . "</div>";
echo "<div class='alert alert-info'>File Upload: " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "</div>";
echo "<div class='alert alert-info'>Max Upload Size: " . ini_get('upload_max_filesize') . "</div>";
echo "<div class='alert alert-info'>Post Max Size: " . ini_get('post_max_size') . "</div>";

// Test 2: Database Connection
echo "<h5>Test 2: Database Connection</h5>";
try {
    $conn = mysqli_connect("localhost", "root", "", "lms");
    if ($conn) {
        echo "<div class='alert alert-success'>✓ Database connection successful</div>";

        // Test table existence
        $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'previous_questions'");
        if (mysqli_num_rows($table_check) > 0) {
            echo "<div class='alert alert-success'>✓ previous_questions table exists</div>";

            // Check table structure
            $result = mysqli_query($conn, "DESCRIBE previous_questions");
            if ($result) {
                echo "<div class='alert alert-info'>Table Structure:</div>";
                echo "<table class='table table-sm'>";
                echo "<thead><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr></thead>";
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
        } else {
            echo "<div class='alert alert-warning'>⚠ previous_questions table does not exist</div>";
        }

        mysqli_close($conn);
    } else {
        echo "<div class='alert alert-danger'>✗ Database connection failed: " . mysqli_connect_error() . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>✗ Database error: " . $e->getMessage() . "</div>";
}

// Test 3: Directory Permissions
echo "<h5>Test 3: Directory Permissions</h5>";
$current_dir = __DIR__;
echo "<div class='alert alert-info'>Current Directory: $current_dir</div>";

if (is_dir("uploads")) {
    echo "<div class='alert alert-success'>✓ uploads directory exists</div>";
    if (is_writable("uploads")) {
        echo "<div class='alert alert-success'>✓ uploads directory is writable</div>";
    } else {
        echo "<div class='alert alert-danger'>✗ uploads directory is not writable</div>";
    }
} else {
    echo "<div class='alert alert-warning'>⚠ uploads directory does not exist</div>";
    if (mkdir("uploads", 0755, true)) {
        echo "<div class='alert alert-success'>✓ Created uploads directory</div>";
    } else {
        echo "<div class='alert alert-danger'>✗ Failed to create uploads directory</div>";
    }
}

// Test 4: File Upload Test
echo "<h5>Test 4: File Upload Test</h5>";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    echo "<div class='alert alert-info'>File: " . $file['name'] . "</div>";
    echo "<div class='alert alert-info'>Size: " . round($file['size'] / 1024, 2) . " KB</div>";
    echo "<div class='alert alert-info'>Type: " . $file['type'] . "</div>";
    echo "<div class='alert alert-info'>Error: " . $file['error'] . "</div>";

    if ($file['error'] == 0) {
        $upload_dir = 'uploads/';
        $unique_filename = uniqid() . '_' . time() . '.pdf';
        $upload_path = $upload_dir . $unique_filename;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            echo "<div class='alert alert-success'>✓ File uploaded successfully to: $upload_path</div>";
            // Clean up test file
            unlink($upload_path);
            echo "<div class='alert alert-info'>✓ Test file cleaned up</div>";
        } else {
            echo "<div class='alert alert-danger'>✗ File upload failed</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>✗ File upload error: " . $file['error'] . "</div>";
    }
}

echo "<form method='POST' enctype='multipart/form-data' class='mt-3'>
        <div class='mb-3'>
            <label for='test_file' class='form-label'>Test File Upload:</label>
            <input type='file' class='form-control' id='test_file' name='test_file' accept='.pdf'>
        </div>
        <button type='submit' class='btn btn-primary'>Test Upload</button>
      </form>";

echo "<div class='mt-4'>
        <a href='index.php' class='btn btn-secondary'>Back to Question Bank</a>
        <a href='add.php' class='btn btn-success'>Add New Question</a>
      </div>";

echo "</div></div></div></div>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
