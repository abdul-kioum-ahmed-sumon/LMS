<?php
// Simple script to add a test booking for a student
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

// Set the student ID (default to 8 if not specified)
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 8;

// Check if student exists
$sql = "SELECT * FROM students WHERE id = $student_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("❌ Error: Student with ID $student_id does not exist!");
}

$student = $result->fetch_assoc();
echo "<h2>Adding test booking for student: " . htmlspecialchars($student['name']) . " (ID: $student_id)</h2>";

// Find an available book
$sql = "SELECT id, title FROM books WHERE status = 1 LIMIT 1";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    die("❌ Error: No available books found!");
}

$book = $result->fetch_assoc();
$book_id = $book['id'];
$book_title = $book['title'];

echo "<p>Selected book: " . htmlspecialchars($book_title) . " (ID: $book_id)</p>";

// Set return date (14 days from now)
$return_date = date('Y-m-d', strtotime('+14 days'));

// Add the booking
$result = createBookReservation($conn, $student_id, $book_id, $return_date);

// Display result
if (isset($result['success']) && $result['success']) {
    echo "<p style='color: green; font-weight: bold;'>✅ Success! Created booking ID: " . $result['booking_id'] . "</p>";
    
    // Set the student ID in session
    $_SESSION['student_id'] = $student_id;
    $_SESSION['booking_id'] = $result['booking_id'];
    
    echo "<p style='color: green;'>✅ Session updated with student ID: $student_id</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Error: " . ($result['error'] ?? "Unknown error") . "</p>";
}

// Check current bookings
$bookings_sql = "SELECT l.*, b.title as book_title 
                FROM book_loans l
                JOIN books b ON l.book_id = b.id
                WHERE l.student_id = $student_id
                ORDER BY l.created_at DESC";

$bookings = $conn->query($bookings_sql);

echo "<h3>Current Bookings</h3>";
if ($bookings && $bookings->num_rows > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0' width='100%'>";
    echo "<tr style='background-color: #f2f2f2'><th>ID</th><th>Book</th><th>Loan Date</th><th>Return Date</th><th>Status</th></tr>";
    
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
    echo "<p style='color: orange; font-weight: bold;'>⚠️ No bookings found for student ID $student_id</p>";
}

// Add navigation links
echo "<div style='margin-top: 20px;'>";
echo "<a href='student_dashboard.php'><button style='background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer;'>Go to Student Dashboard</button></a> ";
echo "<a href='direct_booking_fix.php'><button style='background-color: #008CBA; color: white; padding: 10px 15px; border: none; cursor: pointer;'>Go to Booking Diagnostic Tool</button></a> ";
echo "<a href='index.php'><button style='background-color: #555555; color: white; padding: 10px 15px; border: none; cursor: pointer;'>Go to Home Page</button></a>";
echo "</div>";
?> 