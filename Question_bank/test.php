<?php
// Test file for Question Bank functionality

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Question Bank System Test</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
    <style>
        body { background-color: #f8f9fa; }
        .test-card { box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); }
    </style>
</head>
<body>";

echo "<div class='container mt-5'>
    <div class='row justify-content-center'>
        <div class='col-md-10'>
            <div class='card test-card'>
                <div class='card-header bg-info text-white'>
                    <h4 class='mb-0'><i class='fas fa-vial me-2'></i>Question Bank System Test</h4>
                </div>
                <div class='card-body'>";

// Test database connection
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    echo "<div class='alert alert-danger'>
            <i class='fas fa-exclamation-triangle me-2'></i>
            <strong>Database Connection Failed:</strong> " . mysqli_connect_error() . "
          </div>";
    exit;
} else {
    echo "<div class='alert alert-success'>
            <i class='fas fa-check-circle me-2'></i>
            <strong>Database Connection:</strong> Successful
          </div>";
}

// Test if previous_questions table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'previous_questions'");
if (mysqli_num_rows($table_check) > 0) {
    echo "<div class='alert alert-success'>
            <i class='fas fa-check-circle me-2'></i>
            <strong>Database Table:</strong> previous_questions table exists
          </div>";
} else {
    echo "<div class='alert alert-danger'>
            <i class='fas fa-exclamation-triangle me-2'></i>
            <strong>Database Table:</strong> previous_questions table does not exist
          </div>";
    exit;
}

// Test if file_path column exists
$column_check = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'file_path'");
if (mysqli_num_rows($column_check) > 0) {
    echo "<div class='alert alert-success'>
            <i class='fas fa-check-circle me-2'></i>
            <strong>Database Column:</strong> file_path column exists
          </div>";
} else {
    echo "<div class='alert alert-warning'>
            <i class='fas fa-exclamation-triangle me-2'></i>
            <strong>Database Column:</strong> file_path column does not exist - run setup.php
          </div>";
}

// Test uploads directory
if (is_dir("uploads")) {
    echo "<div class='alert alert-success'>
            <i class='fas fa-check-circle me-2'></i>
            <strong>Uploads Directory:</strong> uploads directory exists
          </div>";
    if (is_writable("uploads")) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Directory Permissions:</strong> uploads directory is writable
              </div>";
    } else {
        echo "<div class='alert alert-warning'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Directory Permissions:</strong> uploads directory is not writable
              </div>";
    }
} else {
    echo "<div class='alert alert-danger'>
            <i class='fas fa-exclamation-triangle me-2'></i>
            <strong>Uploads Directory:</strong> uploads directory does not exist
          </div>";
}

// Count existing questions
$count_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM previous_questions");
$count_result = mysqli_fetch_assoc($count_query);
echo "<div class='alert alert-info'>
        <i class='fas fa-info-circle me-2'></i>
        <strong>Database Content:</strong> Total questions in database: " . $count_result['count'] . "
      </div>";

mysqli_close($conn);

echo "<div class='row mt-4'>
        <div class='col-md-6'>
            <h5><i class='fas fa-link me-2'></i>Test Links:</h5>
            <div class='list-group'>
                <a href='index.php' class='list-group-item list-group-item-action'>
                    <i class='fas fa-list me-2'></i>View Questions (Admin)
                </a>
                <a href='add.php' class='list-group-item list-group-item-action'>
                    <i class='fas fa-plus me-2'></i>Add Question
                </a>
                <a href='student_view.php' class='list-group-item list-group-item-action'>
                    <i class='fas fa-user-graduate me-2'></i>Student View
                </a>
                <a href='setup.php' class='list-group-item list-group-item-action'>
                    <i class='fas fa-cogs me-2'></i>Setup Database
                </a>
            </div>
        </div>
        <div class='col-md-6'>
            <h5><i class='fas fa-upload me-2'></i>File Upload Test:</h5>
            <form method='post' enctype='multipart/form-data' class='border p-3 rounded'>
                <div class='mb-3'>
                    <label for='test_file' class='form-label'>Select PDF File:</label>
                    <input type='file' class='form-control' name='test_file' accept='.pdf' required>
                </div>
                <button type='submit' class='btn btn-primary'>
                    <i class='fas fa-upload me-1'></i>Test Upload
                </button>
            </form>";

// Test file upload functionality
if (isset($_FILES['test_file'])) {
    echo "<div class='mt-3'>
            <h6><i class='fas fa-info-circle me-2'></i>File Upload Test Results:</h6>
            <div class='table-responsive'>
                <table class='table table-sm table-bordered'>
                    <tr><td><strong>File name:</strong></td><td>" . $_FILES['test_file']['name'] . "</td></tr>
                    <tr><td><strong>File size:</strong></td><td>" . $_FILES['test_file']['size'] . " bytes</td></tr>
                    <tr><td><strong>File type:</strong></td><td>" . $_FILES['test_file']['type'] . "</td></tr>
                    <tr><td><strong>Upload error:</strong></td><td>" . $_FILES['test_file']['error'] . "</td></tr>
                </table>
            </div>";

    if ($_FILES['test_file']['error'] == 0) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Upload Test:</strong> File upload test successful
              </div>";
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Upload Test:</strong> File upload test failed
              </div>";
    }
}

echo "</div></div>";

echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center mt-4'>
        <a href='index.php' class='btn btn-primary me-md-2'>
            <i class='fas fa-list me-1'></i>Go to Question Bank
        </a>
        <a href='add.php' class='btn btn-success me-md-2'>
            <i class='fas fa-plus me-1'></i>Add Question
        </a>
        <a href='setup.php' class='btn btn-warning'>
            <i class='fas fa-cogs me-1'></i>Setup Database
        </a>
      </div>";

echo "</div></div></div></div>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
