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

// Get question ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?message=invalid_id");
    exit;
}

// Get question data
$sql = "SELECT * FROM previous_questions WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php?message=notfound");
    exit;
}

$question_data = mysqli_fetch_assoc($result);

$message = "";
$message_type = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
        $course = mysqli_real_escape_string($conn, $_POST['course']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        $year = (int)$_POST['year'];
        $level = mysqli_real_escape_string($conn, $_POST['level']);
        $term = mysqli_real_escape_string($conn, $_POST['term']);
        $file_path = $question_data['file_path']; // Keep existing file path

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
                // Delete old file if exists
                if (!empty($question_data['file_path']) && file_exists($question_data['file_path'])) {
                    unlink($question_data['file_path']);
                }

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
        }

        // Update database if no errors
        if (empty($message)) {
            // Check if new columns exist, if not add them
            $check_department = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'department'");
            if (!$check_department) {
                $message = "Error: " . mysqli_error($conn);
                $message_type = "danger";
            } elseif (mysqli_num_rows($check_department) == 0) {
                if (!mysqli_query($conn, "ALTER TABLE previous_questions ADD COLUMN department VARCHAR(100) DEFAULT NULL AFTER id")) {
                    $message = "Error: " . mysqli_error($conn);
                    $message_type = "danger";
                }
            }

            if (empty($message)) {
                $check_course_code = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'course_code'");
                if (!$check_course_code) {
                    $message = "Error: " . mysqli_error($conn);
                    $message_type = "danger";
                } elseif (mysqli_num_rows($check_course_code) == 0) {
                    if (!mysqli_query($conn, "ALTER TABLE previous_questions ADD COLUMN course_code VARCHAR(50) DEFAULT NULL AFTER department")) {
                        $message = "Error: " . mysqli_error($conn);
                        $message_type = "danger";
                    }
                }
            }

            if (empty($message)) {
                $check_level = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'level'");
                if (!$check_level) {
                    $message = "Error: " . mysqli_error($conn);
                    $message_type = "danger";
                } elseif (mysqli_num_rows($check_level) == 0) {
                    if (!mysqli_query($conn, "ALTER TABLE previous_questions ADD COLUMN level VARCHAR(50) DEFAULT NULL AFTER year")) {
                        $message = "Error: " . mysqli_error($conn);
                        $message_type = "danger";
                    }
                }
            }

            if (empty($message)) {
                $check_term = mysqli_query($conn, "SHOW COLUMNS FROM previous_questions LIKE 'term'");
                if (!$check_term) {
                    $message = "Error: " . mysqli_error($conn);
                    $message_type = "danger";
                } elseif (mysqli_num_rows($check_term) == 0) {
                    if (!mysqli_query($conn, "ALTER TABLE previous_questions ADD COLUMN term VARCHAR(50) DEFAULT NULL AFTER level")) {
                        $message = "Error: " . mysqli_error($conn);
                        $message_type = "danger";
                    }
                }
            }

            if (empty($message)) {
                $sql = "UPDATE previous_questions SET department = '$department', course_code = '$course_code', course = '$course', subject = '$subject', year = $year, 
                        level = '$level', term = '$term', file_path = " .
                    ($file_path ? "'$file_path'" : "NULL") . " WHERE id = $id";

                if (mysqli_query($conn, $sql)) {
                    $message = "Question updated successfully!";
                    $message_type = "success";
                    // Update question_data for display
                    $question_data['department'] = $department;
                    $question_data['course_code'] = $course_code;
                    $question_data['course'] = $course;
                    $question_data['subject'] = $subject;
                    $question_data['year'] = $year;
                    $question_data['level'] = $level;
                    $question_data['term'] = $term;
                    $question_data['file_path'] = $file_path;
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
                <h3><span class="fw-bold text-uppercase">Edit Question</span></h3>
                <a href="index.php" class="btn btn-secondary btn1 ms-auto">
                    <i class="fas fa-list me-1"></i>View All Questions
                </a>
            </div>
            <p>Edit question details and update PDF file!</p>

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
                                    <label for="department" class="form-label">Department *</label>
                                    <select class="form-control" id="department" name="department" required>
                                        <option value="">Select Department</option>
                                        <option value="Computer Science & Engineering" <?php echo (isset($question_data['department']) && $question_data['department'] == 'Computer Science & Engineering') ? 'selected' : ''; ?>>Computer Science & Engineering</option>
                                        <option value="Electrical & Electronic Engineering" <?php echo (isset($question_data['department']) && $question_data['department'] == 'Electrical & Electronic Engineering') ? 'selected' : ''; ?>>Electrical & Electronic Engineering</option>
                                        <option value="Civil Engineering" <?php echo (isset($question_data['department']) && $question_data['department'] == 'Civil Engineering') ? 'selected' : ''; ?>>Civil Engineering</option>
                                        <option value="Mechanical Engineering" <?php echo (isset($question_data['department']) && $question_data['department'] == 'Mechanical Engineering') ? 'selected' : ''; ?>>Mechanical Engineering</option>
                                        <option value="Textile Engineering" <?php echo (isset($question_data['department']) && $question_data['department'] == 'Textile Engineering') ? 'selected' : ''; ?>>Textile Engineering</option>
                                        <option value="Architecture" <?php echo (isset($question_data['department']) && $question_data['department'] == 'Architecture') ? 'selected' : ''; ?>>Architecture</option>
                                        <option value="Business Administration" <?php echo (isset($question_data['department']) && $question_data['department'] == 'Business Administration') ? 'selected' : ''; ?>>Business Administration</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="course_code" class="form-label">Course Code *</label>
                                    <input type="text" class="form-control" id="course_code" name="course_code"
                                        value="<?php echo htmlspecialchars(isset($question_data['course_code']) ? $question_data['course_code'] : ''); ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="course" class="form-label">Course *</label>
                                    <input type="text" class="form-control" id="course" name="course"
                                        value="<?php echo htmlspecialchars($question_data['course']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control" id="subject" name="subject"
                                        value="<?php echo htmlspecialchars($question_data['subject']); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="year" class="form-label">Year *</label>
                                    <input type="number" class="form-control" id="year" name="year"
                                        value="<?php echo $question_data['year']; ?>" min="2000" max="2030" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="level" class="form-label">Level *</label>
                                    <select class="form-control" id="level" name="level" required>
                                        <option value="">Select Level</option>
                                        <option value="Level1" <?php echo (isset($question_data['level']) && $question_data['level'] == 'Level1') ? 'selected' : ''; ?>>Level1</option>
                                        <option value="Level2" <?php echo (isset($question_data['level']) && $question_data['level'] == 'Level2') ? 'selected' : ''; ?>>Level2</option>
                                        <option value="Level3" <?php echo (isset($question_data['level']) && $question_data['level'] == 'Level3') ? 'selected' : ''; ?>>Level3</option>
                                        <option value="Level4" <?php echo (isset($question_data['level']) && $question_data['level'] == 'Level4') ? 'selected' : ''; ?>>Level4</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="term" class="form-label">Term *</label>
                                    <select class="form-control" id="term" name="term" required>
                                        <option value="">Select Term</option>
                                        <option value="Term I" <?php echo (isset($question_data['term']) && $question_data['term'] == 'Term I') ? 'selected' : ''; ?>>Term I</option>
                                        <option value="Term II" <?php echo (isset($question_data['term']) && $question_data['term'] == 'Term II') ? 'selected' : ''; ?>>Term II</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pdf_file" class="form-label">PDF File</label>
                                <?php if (!empty($question_data['file_path']) && file_exists($question_data['file_path'])): ?>
                                    <div class="mb-2">
                                        <strong>Current PDF:</strong>
                                        <a href="<?php echo $question_data['file_path']; ?>" target="_blank" class="text-danger">
                                            <i class="fas fa-file-pdf"></i> View Current PDF
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf">
                                <div class="form-text">Upload a new PDF to replace the current one. Only PDF files are allowed. Maximum size: 10MB</div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Question
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