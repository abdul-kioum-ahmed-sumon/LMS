<?php
include_once(__DIR__ . "/../config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure database structure is correct
function ensureDatabaseStructure($conn)
{
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

        if (!mysqli_query($conn, $create_table)) {
            return "Error creating table: " . mysqli_error($conn);
        }
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
            if (!mysqli_query($conn, $sql)) {
                return "Error adding $column column: " . mysqli_error($conn);
            }
        }
    }

    return null; // No errors
}

$message = "";
$message_type = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ensure database structure is correct
        $db_error = ensureDatabaseStructure($conn);
        if ($db_error) {
            $message = $db_error;
            $message_type = "danger";
        } else {
            $department = mysqli_real_escape_string($conn, $_POST['department']);
            $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
            $course = mysqli_real_escape_string($conn, $_POST['course']);
            $subject = mysqli_real_escape_string($conn, $_POST['subject']);
            $year = (int)$_POST['year'];
            $level = mysqli_real_escape_string($conn, $_POST['level']);
            $term = mysqli_real_escape_string($conn, $_POST['term']);
            $file_path = null;

            // Handle PDF file upload
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
                $file = $_FILES['pdf_file'];
                $file_name = $file['name'];
                $file_size = $file['size'];
                $file_tmp = $file['tmp_name'];
                $file_type = $file['type'];

                // Check file type
                $allowed_types = ['application/pdf'];
                if (!in_array($file_type, $allowed_types)) {
                    $message = "Error: Only PDF files are allowed!";
                    $message_type = "danger";
                } elseif ($file_size > 10 * 1024 * 1024) { // 10MB limit
                    $message = "Error: File size must be less than 10MB!";
                    $message_type = "danger";
                } else {
                    // Create uploads directory if it doesn't exist
                    $upload_dir = 'uploads/';
                    if (!is_dir($upload_dir)) {
                        if (!mkdir($upload_dir, 0755, true)) {
                            $message = "Error: Could not create uploads directory!";
                            $message_type = "danger";
                        }
                    }

                    if (empty($message)) {
                        // Generate unique filename
                        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                        $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
                        $upload_path = $upload_dir . $unique_filename;

                        // Move uploaded file
                        if (move_uploaded_file($file_tmp, $upload_path)) {
                            $file_path = $upload_path;
                        } else {
                            $message = "Error: Failed to upload file! Please check directory permissions.";
                            $message_type = "danger";
                        }
                    }
                }
            } else {
                $message = "Error: Please select a PDF file!";
                $message_type = "danger";
            }

            // Insert into database if no errors
            if (empty($message)) {
                if ($file_path) {
                    $sql = "INSERT INTO previous_questions (department, course_code, course, subject, year, level, term, file_path) 
                            VALUES ('$department', '$course_code', '$course', '$subject', $year, '$level', '$term', '$file_path')";
                } else {
                    $sql = "INSERT INTO previous_questions (department, course_code, course, subject, year, level, term) 
                            VALUES ('$department', '$course_code', '$course', '$subject', $year, '$level', '$term')";
                }

                if (mysqli_query($conn, $sql)) {
                    $message = "Question added successfully!";
                    $message_type = "success";
                    // Clear form data after successful submission
                    $_POST = array();
                } else {
                    $message = "Error: " . mysqli_error($conn);
                    $message_type = "danger";
                }
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "danger";
    }
}

include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");
?>

<!--Main Container Start-->
<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-4 d-flex align-items-center">
                <h3><span class="fw-bold text-uppercase">Add New Question</span></h3>
                <a href="index.php" class="btn btn-secondary btn1 ms-auto">
                    <i class="fas fa-list me-1"></i>View All Questions
                </a>
            </div>
            <p>Add a new question to the question bank!</p>

            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">
                                        <i class="fas fa-building me-1"></i>Department *
                                    </label>
                                    <select class="form-control" id="department" name="department" required>
                                        <option value="">Select Department</option>
                                        <option value="Computer Science & Engineering" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Computer Science & Engineering') ? 'selected' : ''; ?>>Computer Science & Engineering</option>
                                        <option value="Electrical & Electronic Engineering" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Electrical & Electronic Engineering') ? 'selected' : ''; ?>>Electrical & Electronic Engineering</option>
                                        <option value="Civil Engineering" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Civil Engineering') ? 'selected' : ''; ?>>Civil Engineering</option>
                                        <option value="Mechanical Engineering" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Mechanical Engineering') ? 'selected' : ''; ?>>Mechanical Engineering</option>
                                        <option value="Textile Engineering" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Textile Engineering') ? 'selected' : ''; ?>>Textile Engineering</option>
                                        <option value="Architecture" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Architecture') ? 'selected' : ''; ?>>Architecture</option>
                                        <option value="Business Administration" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Business Administration') ? 'selected' : ''; ?>>Business Administration</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="course_code" class="form-label">
                                        <i class="fas fa-code me-1"></i>Course Code *
                                    </label>
                                    <input type="text" class="form-control" id="course_code" name="course_code"
                                        value="<?php echo isset($_POST['course_code']) ? htmlspecialchars($_POST['course_code']) : ''; ?>"
                                        required placeholder="e.g., CSE101, EEE201">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="course" class="form-label">
                                        <i class="fas fa-book me-1"></i>Course *
                                    </label>
                                    <input type="text" class="form-control" id="course" name="course"
                                        value="<?php echo isset($_POST['course']) ? htmlspecialchars($_POST['course']) : ''; ?>"
                                        required placeholder="Enter course name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">
                                        <i class="fas fa-subject me-1"></i>Subject *
                                    </label>
                                    <input type="text" class="form-control" id="subject" name="subject"
                                        value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                                        required placeholder="Enter subject name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="year" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Year *
                                    </label>
                                    <input type="number" class="form-control" id="year" name="year"
                                        value="<?php echo isset($_POST['year']) ? $_POST['year'] : ''; ?>"
                                        min="2000" max="2030" required placeholder="Enter year">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="level" class="form-label">
                                        <i class="fas fa-layer-group me-1"></i>Level *
                                    </label>
                                    <select class="form-control" id="level" name="level" required>
                                        <option value="">Select Level</option>
                                        <option value="Level1" <?php echo (isset($_POST['level']) && $_POST['level'] == 'Level1') ? 'selected' : ''; ?>>Level1</option>
                                        <option value="Level2" <?php echo (isset($_POST['level']) && $_POST['level'] == 'Level2') ? 'selected' : ''; ?>>Level2</option>
                                        <option value="Level3" <?php echo (isset($_POST['level']) && $_POST['level'] == 'Level3') ? 'selected' : ''; ?>>Level3</option>
                                        <option value="Level4" <?php echo (isset($_POST['level']) && $_POST['level'] == 'Level4') ? 'selected' : ''; ?>>Level4</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="term" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>Term *
                                    </label>
                                    <select class="form-control" id="term" name="term" required>
                                        <option value="">Select Term</option>
                                        <option value="Term I" <?php echo (isset($_POST['term']) && $_POST['term'] == 'Term I') ? 'selected' : ''; ?>>Term I</option>
                                        <option value="Term II" <?php echo (isset($_POST['term']) && $_POST['term'] == 'Term II') ? 'selected' : ''; ?>>Term II</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pdf_file" class="form-label">
                                    <i class="fas fa-file-pdf me-1"></i>PDF File *
                                </label>
                                <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Only PDF files are allowed. Maximum size: 10MB
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Add Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--Main Container End-->

<?php include_once(DIR_URL . "include/footer.php"); ?>