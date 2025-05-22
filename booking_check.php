<?php
// Diagnostic file to check student bookings
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");

// Display all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Booking System Diagnostics</h1>";

// 1. Check database connection
echo "<h2>1. Database Connection</h2>";
if ($conn && !$conn->connect_error) {
    echo "<p style='color:green'>✅ Database connection successful</p>";
} else {
    echo "<p style='color:red'>❌ Database connection failed: " . ($conn ? $conn->connect_error : "Connection not established") . "</p>";
    exit;
}

// 2. Check if student is logged in
echo "<h2>2. Session Status</h2>";
session_start();
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session Data:</p><pre>" . print_r($_SESSION, true) . "</pre>";

// 3. Check if student ID exists in session
$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;
echo "<h2>3. Student ID Check</h2>";
if ($student_id) {
    echo "<p style='color:green'>✅ Student ID found in session: $student_id</p>";
} else {
    echo "<p style='color:orange'>⚠️ No student ID in session. Using test ID 1 for diagnostics.</p>";
    $student_id = 1; // Use ID 1 for testing if no session exists
}

// 4. Check book_loans table structure
echo "<h2>4. Database Table Structure</h2>";
$result = $conn->query("DESCRIBE book_loans");
if ($result) {
    echo "<p style='color:green'>✅ book_loans table exists</p>";
    echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th><th>Key</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Key']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>❌ Could not query book_loans table: " . $conn->error . "</p>";
}

// 5. Check if student exists in students table
echo "<h2>5. Student Record</h2>";
$result = $conn->query("SELECT * FROM students WHERE id = $student_id");
if ($result && $result->num_rows > 0) {
    $student = $result->fetch_assoc();
    echo "<p style='color:green'>✅ Student record found:</p>";
    echo "<ul>";
    echo "<li>Name: " . htmlspecialchars($student['name']) . "</li>";
    echo "<li>Email: " . htmlspecialchars($student['email']) . "</li>";
    echo "<li>Status: " . ($student['status'] ? 'Active' : 'Inactive') . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color:red'>❌ Student not found in database: " . $conn->error . "</p>";
}

// 6. Check if student has any bookings
echo "<h2>6. Student Bookings</h2>";
$bookings_sql = "SELECT l.*, b.title as book_title 
                FROM book_loans l
                INNER JOIN books b ON b.id = l.book_id
                WHERE l.student_id = $student_id
                ORDER BY l.created_at DESC";
echo "<p>Executing query: <code>$bookings_sql</code></p>";
$result = $conn->query($bookings_sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<p style='color:green'>✅ {$result->num_rows} bookings found for student $student_id</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Book Title</th><th>Date</th><th>Return Date</th><th>Status</th></tr>";
        while ($booking = $result->fetch_assoc()) {
            $status = $booking['is_return'] == 1 ? 'Returned' : 'Active';
            echo "<tr>";
            echo "<td>{$booking['id']}</td>";
            echo "<td>" . htmlspecialchars($booking['book_title']) . "</td>";
            echo "<td>" . date('M d, Y', strtotime($booking['created_at'])) . "</td>";
            echo "<td>" . date('M d, Y', strtotime($booking['return_date'])) . "</td>";
            echo "<td>$status</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:orange'>⚠️ No bookings found for student $student_id</p>";

        // Show last 5 bookings in system for reference
        $recent_books = $conn->query("SELECT l.*, b.title, s.name as student_name 
                                     FROM book_loans l 
                                     JOIN books b ON l.book_id = b.id 
                                     JOIN students s ON l.student_id = s.id
                                     ORDER BY l.created_at DESC LIMIT 5");

        if ($recent_books && $recent_books->num_rows > 0) {
            echo "<p>Last 5 bookings in system:</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Student</th><th>Book</th><th>Date</th></tr>";
            while ($recent = $recent_books->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$recent['id']}</td>";
                echo "<td>" . htmlspecialchars($recent['student_name']) . " (ID: {$recent['student_id']})</td>";
                echo "<td>" . htmlspecialchars($recent['title']) . "</td>";
                echo "<td>" . date('M d, Y', strtotime($recent['created_at'])) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No bookings found in system</p>";
        }
    }
} else {
    echo "<p style='color:red'>❌ Error executing booking query: " . $conn->error . "</p>";
}

// 7. Add test booking form
echo "<h2>7. Add Test Booking</h2>";
echo "<form method='post' action='booking_check.php'>";
echo "<input type='hidden' name='add_test_booking' value='1'>";

// Get available books
$books = $conn->query("SELECT id, title FROM books WHERE status = 1 LIMIT 10");
if ($books && $books->num_rows > 0) {
    echo "<select name='book_id'>";
    while ($book = $books->fetch_assoc()) {
        echo "<option value='{$book['id']}'>{$book['title']}</option>";
    }
    echo "</select>";
    echo "<button type='submit'>Add Test Booking</button>";
} else {
    echo "<p>No available books found</p>";
}
echo "</form>";

// Process test booking
if (isset($_POST['add_test_booking']) && isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    $return_date = date('Y-m-d', strtotime('+14 days'));
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO book_loans (book_id, student_id, loan_date, return_date, created_at, is_return)
            VALUES ($book_id, $student_id, NOW(), '$return_date', '$created_at', 0)";

    if ($conn->query($sql)) {
        echo "<p style='color:green'>✅ Test booking added successfully! Booking ID: {$conn->insert_id}</p>";
        echo "<p><a href='booking_check.php'>Refresh page to see new booking</a></p>";
    } else {
        echo "<p style='color:red'>❌ Failed to add test booking: " . $conn->error . "</p>";
    }
}
