<?php
// Bookings Check Tool - A dedicated diagnostic tool for bookings
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/loan.php");

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get student ID from session or from GET parameter
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : (isset($_SESSION['student_id']) ? $_SESSION['student_id'] : 0);

// Get function to use from GET parameter (default to getStudentBookings)
$function_name = isset($_GET['function']) ? $_GET['function'] : 'getStudentBookings';

// Check direct SQL query
$check_direct_sql = isset($_GET['check_direct_sql']) ? (bool)$_GET['check_direct_sql'] : false;

// Process form to create a test booking
if (isset($_POST['create_booking'])) {
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

    if ($student_id > 0 && $book_id > 0) {
        $return_date = date('Y-m-d', strtotime('+14 days'));
        $result = createBookReservation($conn, $student_id, $book_id, $return_date);

        if (isset($result['success']) && $result['success']) {
            $success_message = "Successfully created booking ID: " . $result['booking_id'];
        } else {
            $error_message = "Failed to create booking: " . ($result['error'] ?? "Unknown error");
        }
    } else {
        $error_message = "Valid student ID and book ID are required";
    }
}

// Function to check if a table exists
function checkTableExists($conn, $table_name)
{
    $sql = "SHOW TABLES LIKE '$table_name'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

// Function to get all bookings in system
function getAllBookings($conn)
{
    $sql = "SELECT l.*, b.title as book_title, s.name as student_name 
            FROM book_loans l
            JOIN books b ON b.id = l.book_id
            JOIN students s ON s.id = l.student_id
            ORDER BY l.created_at DESC
            LIMIT 20";
    return $conn->query($sql);
}

// Check database table structure
$table_check = [
    'book_loans' => checkTableExists($conn, 'book_loans'),
    'books' => checkTableExists($conn, 'books'),
    'students' => checkTableExists($conn, 'students')
];

// Get bookings using the specified function
$bookings_result = null;
$direct_sql_result = null;
$bookings_error = null;

if ($student_id > 0) {
    // Try to get student info
    $student_sql = "SELECT * FROM students WHERE id = $student_id";
    $student_result = $conn->query($student_sql);
    $student_info = ($student_result && $student_result->num_rows > 0) ?
        $student_result->fetch_assoc() : null;

    // Try to get bookings using function
    try {
        if ($function_name === 'getStudentBookings') {
            $bookings_result = getStudentBookings($conn, $student_id);
        } else if ($function_name === 'getStudentLoans') {
            $bookings_result = getStudentLoans($conn, $student_id);
        }
    } catch (Exception $e) {
        $bookings_error = "Error calling function: " . $e->getMessage();
    }

    // Check using direct SQL if requested
    if ($check_direct_sql) {
        $direct_sql = "SELECT l.*, b.title as book_title 
                      FROM book_loans l
                      JOIN books b ON b.id = l.book_id
                      WHERE l.student_id = $student_id
                      ORDER BY l.created_at DESC";
        $direct_sql_result = $conn->query($direct_sql);
    }
}

// Get all available books and students for the form
$books = $conn->query("SELECT id, title FROM books WHERE status = 1 ORDER BY id LIMIT 50");
$students = $conn->query("SELECT id, name, dept_id FROM students WHERE status = 1 ORDER BY id LIMIT 50");

// Get all bookings in system
$all_bookings = getAllBookings($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Check Tool - LMS</title>
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/custom.css" />
    <style>
        .test-section {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
        }

        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .warning {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container mt-4 mb-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">Bookings Check Tool</h1>
            </div>
            <div class="card-body">
                <p>This tool helps diagnose issues with student bookings in the LMS system.</p>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Database Check Section -->
                <div class="test-section">
                    <h3>Database Structure Check</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Table</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($table_check as $table => $exists): ?>
                                <tr>
                                    <td><?php echo $table; ?></td>
                                    <td>
                                        <?php if ($exists): ?>
                                            <span class="success">✅ Exists</span>
                                        <?php else: ?>
                                            <span class="error">❌ Missing</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Student Selection Form -->
                <div class="test-section">
                    <h3>Select Student to Check</h3>
                    <form method="get" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Student:</label>
                                    <select name="student_id" id="student_id" class="form-select">
                                        <option value="">Select a student</option>
                                        <?php if ($students && $students->num_rows > 0): ?>
                                            <?php while ($s = $students->fetch_assoc()): ?>
                                                <option value="<?php echo $s['id']; ?>" <?php echo ($student_id == $s['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($s['name']) . ' (ID: ' . $s['id'] . ', ' . $s['dept_id'] . ')'; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="function" class="form-label">Function to Test:</label>
                                    <select name="function" id="function" class="form-select">
                                        <option value="getStudentBookings" <?php echo ($function_name == 'getStudentBookings') ? 'selected' : ''; ?>>getStudentBookings()</option>
                                        <option value="getStudentLoans" <?php echo ($function_name == 'getStudentLoans') ? 'selected' : ''; ?>>getStudentLoans()</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="check_direct_sql" name="check_direct_sql" value="1" <?php echo $check_direct_sql ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="check_direct_sql">Also check using direct SQL</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Check Bookings</button>
                    </form>
                </div>

                <!-- Create Booking Form -->
                <div class="test-section">
                    <h3>Create Test Booking</h3>
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="form_student_id" class="form-label">Student:</label>
                                    <select name="student_id" id="form_student_id" class="form-select" required>
                                        <option value="">Select a student</option>
                                        <?php
                                        if ($students) {
                                            $students->data_seek(0); // Reset pointer
                                            while ($s = $students->fetch_assoc()):
                                        ?>
                                                <option value="<?php echo $s['id']; ?>" <?php echo ($student_id == $s['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($s['name']) . ' (ID: ' . $s['id'] . ')'; ?>
                                                </option>
                                        <?php
                                            endwhile;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="book_id" class="form-label">Book:</label>
                                    <select name="book_id" id="book_id" class="form-select" required>
                                        <option value="">Select a book</option>
                                        <?php if ($books && $books->num_rows > 0): ?>
                                            <?php while ($b = $books->fetch_assoc()): ?>
                                                <option value="<?php echo $b['id']; ?>">
                                                    <?php echo htmlspecialchars($b['title']) . ' (ID: ' . $b['id'] . ')'; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="create_booking" class="btn btn-warning">Create Test Booking</button>
                    </form>
                </div>

                <!-- Results Section -->
                <?php if ($student_id > 0): ?>
                    <div class="test-section">
                        <h3>Bookings for Student ID: <?php echo $student_id; ?></h3>

                        <?php if ($student_info): ?>
                            <div class="mb-3">
                                <h5>Student Information</h5>
                                <p>
                                    <strong>Name:</strong> <?php echo htmlspecialchars($student_info['name']); ?><br>
                                    <strong>ID Number:</strong> <?php echo htmlspecialchars($student_info['dept_id']); ?><br>
                                    <strong>Email:</strong> <?php echo htmlspecialchars($student_info['email']); ?><br>
                                    <strong>Status:</strong> <?php echo $student_info['status'] ? 'Active' : 'Inactive'; ?><br>
                                    <strong>Verified:</strong> <?php echo $student_info['verified'] ? 'Yes' : 'No'; ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">Student not found</div>
                        <?php endif; ?>

                        <h5>Function Results: <?php echo $function_name; ?>()</h5>
                        <?php if ($bookings_error): ?>
                            <div class="alert alert-danger"><?php echo $bookings_error; ?></div>
                        <?php else: ?>
                            <?php if ($bookings_result && $bookings_result->num_rows > 0): ?>
                                <div class="success mb-2">✅ Found <?php echo $bookings_result->num_rows; ?> bookings</div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Book</th>
                                            <th>Loan Date</th>
                                            <th>Return Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $bookings_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row['loan_date'])); ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row['return_date'])); ?></td>
                                                <td><?php echo ($row['is_return'] == 0) ? 'Active' : 'Returned'; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="error">❌ No bookings found</div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($check_direct_sql): ?>
                            <h5 class="mt-4">Direct SQL Results</h5>
                            <?php if ($direct_sql_result && $direct_sql_result->num_rows > 0): ?>
                                <div class="success mb-2">✅ Found <?php echo $direct_sql_result->num_rows; ?> bookings</div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Book</th>
                                            <th>Loan Date</th>
                                            <th>Return Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $direct_sql_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row['loan_date'])); ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row['return_date'])); ?></td>
                                                <td><?php echo ($row['is_return'] == 0) ? 'Active' : 'Returned'; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="error">❌ No bookings found with direct SQL</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- All Bookings in System -->
                <div class="test-section">
                    <h3>All Bookings in System</h3>
                    <?php if ($all_bookings && $all_bookings->num_rows > 0): ?>
                        <div class="success mb-2">✅ Found <?php echo $all_bookings->num_rows; ?> bookings in system</div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $all_bookings->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['student_name']) . ' (ID: ' . $row['student_id'] . ')'; ?></td>
                                        <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                        <td><?php echo ($row['is_return'] == 0) ? 'Active' : 'Returned'; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="warning">⚠️ No bookings found in system</div>
                    <?php endif; ?>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex mt-4">
                    <a href="student_dashboard.php" class="btn btn-primary me-2">Go to Student Dashboard</a>
                    <a href="test_session.php" class="btn btn-secondary me-2">Session Diagnostic Tool</a>
                    <a href="index.php" class="btn btn-dark">Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>