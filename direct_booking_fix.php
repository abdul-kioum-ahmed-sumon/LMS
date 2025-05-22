<?php
// Direct Booking Fix - Simplified version to check/add bookings
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/loan.php");

// Start a session regardless of previous state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set header
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}

// Test variables
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
$test_mode = isset($_GET['test']) && $_GET['test'] == 'true';
$fix_mode = isset($_GET['fix']) && $_GET['fix'] == 'true';

// Get student information if ID provided
$student_info = null;
if ($student_id > 0) {
    $sql = "SELECT * FROM students WHERE id = $student_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $student_info = $result->fetch_assoc();
    }
}

// Test direct booking retrieval
function testDirectBookingQuery($conn, $student_id)
{
    $direct_sql = "SELECT 
                l.id, 
                l.book_id, 
                l.student_id,
                l.loan_date,
                l.return_date,
                l.created_at,
                l.is_return,
                l.issued_at,
                b.title as book_title 
                FROM book_loans l
                JOIN books b ON b.id = l.book_id
                WHERE l.student_id = $student_id
                ORDER BY l.created_at DESC";

    $result = $conn->query($direct_sql);

    return [
        'success' => ($result && $result->num_rows > 0),
        'count' => ($result) ? $result->num_rows : 0,
        'error' => ($result) ? null : $conn->error,
        'result' => $result,
        'sql' => $direct_sql
    ];
}

// Test function-based booking retrieval
function testFunctionBookingQuery($conn, $student_id)
{
    $result = getStudentBookings($conn, $student_id);

    return [
        'success' => ($result && $result->num_rows > 0),
        'count' => ($result) ? $result->num_rows : 0,
        'error' => ($result) ? null : $conn->error,
        'result' => $result
    ];
}

// Get sample student for testing
function getSampleStudent($conn)
{
    $sql = "SELECT id, name, dept_id FROM students WHERE status = 1 LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Fix student bookings if in fix mode
$fix_result = null;
if ($fix_mode && $student_id > 0) {
    // This will create a test booking for diagnostic purposes
    $book_sql = "SELECT id FROM books WHERE status = 1 LIMIT 1";
    $book_result = $conn->query($book_sql);

    if ($book_result && $book_result->num_rows > 0) {
        $book = $book_result->fetch_assoc();
        $book_id = $book['id'];

        // Create a test booking
        $return_date = date('Y-m-d', strtotime('+14 days'));
        $result = createBookReservation($conn, $student_id, $book_id, $return_date);
        $fix_result = $result;
    } else {
        $fix_result = ['error' => 'No available books found'];
    }
}

// Get sample student if not provided
if (!$student_id && $test_mode) {
    $sample_student = getSampleStudent($conn);
    if ($sample_student) {
        $student_id = $sample_student['id'];
        $student_info = $sample_student;
    }
}

// Run tests if student ID is provided
$direct_test = null;
$function_test = null;
if ($student_id > 0) {
    $direct_test = testDirectBookingQuery($conn, $student_id);
    $function_test = testFunctionBookingQuery($conn, $student_id);
}

// Simple styling
echo "
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.warning { color: orange; font-weight: bold; }
button, input[type=submit] { background-color: #4CAF50; color: white; padding: 8px 12px; border: none; cursor: pointer; }
</style>";

echo "<h1>Direct Booking Fix</h1>";

// Add a booking if requested
if (isset($_POST['add_booking'])) {
    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 1;
    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 1;

    $insert_sql = "INSERT INTO book_loans (book_id, student_id, loan_date, return_date, created_at, is_return) 
                  VALUES ($book_id, $student_id, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), NOW(), 0)";

    if ($conn->query($insert_sql)) {
        echo "<div class='success'>✅ Booking added successfully! ID: " . $conn->insert_id . "</div>";
        $_SESSION['student_id'] = $student_id;
        echo "<div class='success'>✅ Session updated with student ID: $student_id</div>";
    } else {
        echo "<div class='error'>❌ Error adding booking: " . $conn->error . "</div>";
    }
}

// Set student ID in session if requested
if (isset($_GET['set_student'])) {
    $student_id = (int)$_GET['set_student'];
    $_SESSION['student_id'] = $student_id;
    echo "<div class='success'>✅ Session updated with student ID: $student_id</div>";
}

// Get current student ID from session
$current_student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;
echo "<h2>Current Session Status</h2>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Student ID in session: " . ($current_student_id ? $current_student_id : "Not set") . "</p>";

// List students for selection
echo "<h2>Available Students</h2>";
$students = $conn->query("SELECT id, name, email, dept_id FROM students ORDER BY id LIMIT 10");
if ($students && $students->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Student ID</th><th>Actions</th></tr>";
    while ($student = $students->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $student['id'] . "</td>";
        echo "<td>" . htmlspecialchars($student['name']) . "</td>";
        echo "<td>" . htmlspecialchars($student['email']) . "</td>";
        echo "<td>" . htmlspecialchars($student['dept_id']) . "</td>";
        echo "<td><a href='direct_booking_fix.php?set_student=" . $student['id'] . "'>Set as active student</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='warning'>⚠️ No students found in database</p>";
}

// List books for bookings
echo "<h2>Available Books</h2>";
$books = $conn->query("SELECT id, title, author FROM books WHERE status = 1 ORDER BY id LIMIT 10");
if ($books && $books->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Author</th></tr>";
    while ($book = $books->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $book['id'] . "</td>";
        echo "<td>" . htmlspecialchars($book['title']) . "</td>";
        echo "<td>" . htmlspecialchars($book['author']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='warning'>⚠️ No books found in database</p>";
}

// Add booking form
echo "<h2>Add Test Booking</h2>";
echo "<form method='post' action='direct_booking_fix.php'>";
echo "<p>";
echo "Student ID: <input type='number' name='student_id' value='" . ($current_student_id ?: 1) . "' required> ";
echo "Book ID: <input type='number' name='book_id' value='1' required> ";
echo "<input type='submit' name='add_booking' value='Add Test Booking'>";
echo "</p>";
echo "</form>";

// Show current bookings for the student
echo "<h2>Current Bookings for Student</h2>";
if ($current_student_id) {
    // Direct SQL query to get bookings
    $bookings_sql = "SELECT l.*, b.title as book_title 
                    FROM book_loans l
                    JOIN books b ON l.book_id = b.id
                    WHERE l.student_id = $current_student_id
                    ORDER BY l.created_at DESC";

    $bookings = $conn->query($bookings_sql);

    if ($bookings && $bookings->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Book</th><th>Loan Date</th><th>Return Date</th><th>Status</th></tr>";
        while ($booking = $bookings->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $booking['id'] . "</td>";
            echo "<td>" . htmlspecialchars($booking['book_title']) . "</td>";
            echo "<td>" . date('Y-m-d', strtotime($booking['loan_date'])) . "</td>";
            echo "<td>" . date('Y-m-d', strtotime($booking['return_date'])) . "</td>";
            echo "<td>" . ($booking['is_return'] ? 'Returned' : 'Active') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>⚠️ No bookings found for student ID $current_student_id</p>";

        // Show recent bookings in system
        echo "<h3>Recent Bookings in System (All Students)</h3>";
        $recent_sql = "SELECT l.*, b.title as book_title, s.name as student_name 
                      FROM book_loans l
                      JOIN books b ON l.book_id = b.id
                      JOIN students s ON l.student_id = s.id
                      ORDER BY l.created_at DESC LIMIT 5";

        $recent = $conn->query($recent_sql);

        if ($recent && $recent->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Student</th><th>Book</th><th>Loan Date</th><th>Return Date</th></tr>";
            while ($booking = $recent->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $booking['id'] . "</td>";
                echo "<td>" . htmlspecialchars($booking['student_name']) . " (ID: " . $booking['student_id'] . ")</td>";
                echo "<td>" . htmlspecialchars($booking['book_title']) . "</td>";
                echo "<td>" . date('Y-m-d', strtotime($booking['loan_date'])) . "</td>";
                echo "<td>" . date('Y-m-d', strtotime($booking['return_date'])) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='warning'>⚠️ No bookings found in system</p>";
        }
    }
} else {
    echo "<p class='error'>❌ No student ID set in session. Please select a student first.</p>";
}

// Link to go back
echo "<div style='margin-top: 20px;'>";
echo "<a href='student_dashboard.php'><button>Go to Student Dashboard</button></a> ";
echo "<a href='booking_check.php'><button>Go to Booking Check Tool</button></a> ";
echo "<a href='index.php'><button>Go to Home Page</button></a>";
echo "</div>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Diagnostic Tool - LMS</title>
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/custom.css" />
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #343a40;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            overflow-x: auto;
        }

        .test-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background-color: white;
        }

        .alert-success pre {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Booking Diagnostic Tool</h1>
        <p>This tool helps diagnose and fix issues with student bookings in the LMS system.</p>

        <!-- Student Selection Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Select Student for Testing</h4>
            </div>
            <div class="card-body">
                <form method="get" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="number" class="form-control" id="student_id" name="student_id"
                            value="<?php echo $student_id; ?>" placeholder="Enter student ID">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="test" name="test" value="true"
                                <?php echo $test_mode ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="test">Test Mode</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Run Diagnostic</button>
                        <a href="<?php echo BASE_URL ?>" class="btn btn-secondary">Back to Home</a>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($student_info): ?>
            <!-- Student Information -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Student Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($student_info['name']); ?></p>
                            <p><strong>ID Number:</strong> <?php echo htmlspecialchars($student_info['dept_id']); ?></p>
                            <p><strong>Department:</strong> <?php echo htmlspecialchars($student_info['dept']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <span class="badge <?php echo $student_info['status'] ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $student_info['status'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </p>
                            <p><strong>Verified:</strong>
                                <span class="badge <?php echo $student_info['verified'] ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo $student_info['verified'] ? 'Yes' : 'No'; ?>
                                </span>
                            </p>
                            <form method="get">
                                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                <input type="hidden" name="fix" value="true">
                                <button type="submit" class="btn btn-warning">Create Test Booking</button>
                            </form>
                        </div>
                    </div>

                    <?php if ($fix_result): ?>
                        <div class="alert <?php echo isset($fix_result['success']) ? 'alert-success' : 'alert-danger'; ?> mt-3">
                            <?php if (isset($fix_result['success'])): ?>
                                <strong>Success!</strong> Created test booking ID: <?php echo $fix_result['booking_id']; ?>
                            <?php else: ?>
                                <strong>Error:</strong> <?php echo $fix_result['error']; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Test Results -->
            <div class="row">
                <!-- Direct Query Test -->
                <div class="col-md-6">
                    <div class="test-section">
                        <h3>Direct SQL Query Test</h3>
                        <?php if ($direct_test): ?>
                            <div class="alert <?php echo $direct_test['success'] ? 'alert-success' : 'alert-danger'; ?>">
                                <?php if ($direct_test['success']): ?>
                                    <strong>Success!</strong> Found <?php echo $direct_test['count']; ?> bookings.
                                <?php else: ?>
                                    <strong>Error:</strong> <?php echo $direct_test['error'] ?: 'No bookings found'; ?>
                                <?php endif; ?>
                            </div>

                            <h5>SQL Query:</h5>
                            <pre><?php echo htmlspecialchars($direct_test['sql']); ?></pre>

                            <?php if ($direct_test['success']): ?>
                                <h5>Results:</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Book</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $direct_test['result'];
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . $row['id'] . '</td>';
                                            echo '<td>' . htmlspecialchars($row['book_title']) . '</td>';
                                            echo '<td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
                                            echo '<td>' . ($row['is_return'] ? 'Returned' : 'Active') . '</td>';
                                            echo '</tr>';
                                        }
                                        $result->data_seek(0); // Reset pointer
                                        ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-warning">Test not run yet.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Function Test -->
                <div class="col-md-6">
                    <div class="test-section">
                        <h3>Function API Test</h3>
                        <?php if ($function_test): ?>
                            <div class="alert <?php echo $function_test['success'] ? 'alert-success' : 'alert-danger'; ?>">
                                <?php if ($function_test['success']): ?>
                                    <strong>Success!</strong> Found <?php echo $function_test['count']; ?> bookings.
                                <?php else: ?>
                                    <strong>Error:</strong> <?php echo $function_test['error'] ?: 'No bookings found'; ?>
                                <?php endif; ?>
                            </div>

                            <h5>Function Call:</h5>
                            <pre>getStudentBookings($conn, <?php echo $student_id; ?>)</pre>

                            <?php if ($function_test['success']): ?>
                                <h5>Results:</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Book</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $function_test['result'];
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . $row['id'] . '</td>';
                                            echo '<td>' . htmlspecialchars($row['book_title']) . '</td>';
                                            echo '<td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
                                            echo '<td>' . ($row['is_return'] ? 'Returned' : 'Active') . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-warning">Test not run yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <strong>Info:</strong> Please select a student ID to run diagnostics or enable Test Mode to use a sample student.
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="<?php echo BASE_URL ?>" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <script src="<?php echo BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>