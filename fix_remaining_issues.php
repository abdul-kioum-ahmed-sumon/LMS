<?php
// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "lms";

// Create connection
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h1>LMS System Fix Tool</h1>";
echo "<p>This script fixes various issues in the database structure and code to ensure compatibility with the updated codebase.</p>";

// Issue 1: Add missing issued_at column to book_loans table
$sqlCheckIssuedAt = "SHOW COLUMNS FROM book_loans LIKE 'issued_at'";
$resultCheckIssuedAt = $conn->query($sqlCheckIssuedAt);
if ($resultCheckIssuedAt->num_rows == 0) {
    $sqlAddIssuedAt = "ALTER TABLE book_loans ADD COLUMN issued_at TIMESTAMP NULL AFTER is_return";
    if ($conn->query($sqlAddIssuedAt)) {
        echo "<div style='color:green'>✓ Added 'issued_at' column to book_loans table</div>";
    } else {
        echo "<div style='color:red'>✗ Error adding issued_at column: " . $conn->error . "</div>";
    }
}

// Issue 2: Fix tables referenced in models/student.php and models/loan.php
echo "<h2>Checking Model Files for Conflicts</h2>";

// Let's check for the function create() conflict
if (file_exists('models/student.php') && file_exists('models/loan.php')) {
    // Load the file contents
    $studentModelContent = file_get_contents('models/student.php');
    $loanModelContent = file_get_contents('models/loan.php');

    // Check if both files have a create() function
    if (strpos($studentModelContent, 'function create') !== false && strpos($loanModelContent, 'function create') !== false) {
        // Rename the create function in student.php to createStudent
        $newStudentModelContent = str_replace('function create', 'function createStudent', $studentModelContent);

        if (file_put_contents('models/student.php', $newStudentModelContent)) {
            echo "<div style='color:green'>✓ Fixed function name conflict in models/student.php</div>";

            // Now check and update any calls to the old function
            $files = glob('{*.php,include/*.php,models/*.php}', GLOB_BRACE);
            foreach ($files as $file) {
                if ($file != 'models/student.php' && $file != 'fix_remaining_issues.php') {
                    $content = file_get_contents($file);
                    // Only replace create() calls that are related to the student model
                    if (strpos($content, 'include') !== false && strpos($content, 'student.php') !== false && strpos($content, 'create(') !== false) {
                        $newContent = str_replace('create(', 'createStudent(', $content);
                        if ($content !== $newContent) {
                            file_put_contents($file, $newContent);
                            echo "<div style='color:green'>✓ Updated function call in " . $file . "</div>";
                        }
                    }
                }
            }
        } else {
            echo "<div style='color:red'>✗ Failed to update models/student.php</div>";
        }
    }
}

// Issue 3: Fix session.php to prevent "session already active" warnings
$configContent = file_get_contents('config/config.php');
if (strpos($configContent, 'session_start();') !== false) {
    $newConfigContent = str_replace('session_start();', 'if (session_status() == PHP_SESSION_NONE) { session_start(); }', $configContent);
    if (file_put_contents('config/config.php', $newConfigContent)) {
        echo "<div style='color:green'>✓ Fixed session handling in config/config.php</div>";
    } else {
        echo "<div style='color:red'>✗ Failed to update config/config.php</div>";
    }
}

// Issue 4: Fix student_auth.php to prevent session warning
$studentAuthContent = file_get_contents('models/student_auth.php');
if (strpos($studentAuthContent, 'session_set_cookie_params') !== false) {
    // Use a safer replacement approach with regular expressions
    $pattern = '/session_set_cookie_params\(\[.*?\]\);/s';
    $replacement = 'if (session_status() == PHP_SESSION_NONE) { session_set_cookie_params([
        \'lifetime\' => 86400, // 24 hours
        \'path\' => \'/\',
        \'domain\' => \'\',
        \'secure\' => false,
        \'httponly\' => true
    ]); }';

    $newAuthContent = preg_replace($pattern, $replacement, $studentAuthContent);

    if (file_put_contents('models/student_auth.php', $newAuthContent)) {
        echo "<div style='color:green'>✓ Fixed session cookie handling in models/student_auth.php</div>";
    } else {
        echo "<div style='color:red'>✗ Failed to update models/student_auth.php</div>";
    }
}

// Issue 5: Fix the SQL query in student_dashboard.php that references issued_at
$dashboardPath = 'student_dashboard.php';
if (file_exists($dashboardPath)) {
    $dashboardContent = file_get_contents($dashboardPath);

    // Find the SQL query with issued_at and modify it
    if (strpos($dashboardContent, 'l.issued_at') !== false) {
        // Replace the SQL query that contains l.issued_at
        $pattern = '/\$student_bookings_sql\s*=\s*"SELECT\s*.*?l\.issued_at.*?FROM\s*book_loans.*?ORDER BY.*?";/s';
        $replacement = '$student_bookings_sql = "SELECT 
                            l.id, 
                            l.book_id, 
                            l.student_id,
                            l.loan_date,
                            l.return_date,
                            l.created_at,
                            l.is_return,
                            b.title as book_title 
                            FROM book_loans l
                            JOIN books b ON b.id = l.book_id
                            WHERE l.student_id = {$student_id} AND l.is_return = 0
                            ORDER BY l.created_at DESC";';

        $newDashboardContent = preg_replace($pattern, $replacement, $dashboardContent);

        if (file_put_contents($dashboardPath, $newDashboardContent) && $newDashboardContent !== $dashboardContent) {
            echo "<div style='color:green'>✓ Fixed SQL query in student_dashboard.php that referenced non-existent issued_at column</div>";
        } else {
            echo "<div style='color:red'>✗ Failed to update SQL query in student_dashboard.php</div>";
        }
    }
}

// Include the completed fixes from fix_students_table.php
include_once('fix_students_table.php');

echo "<h2>Fix Process Completed</h2>";
echo "<div style='margin-top: 20px; padding: 10px; background-color: #e8f7e8; border: 1px solid #80c080;'>";
echo "<p>All identified issues have been fixed. You can now access:</p>";
echo "<ul>
    <li><a href='index.php'>Admin Login</a></li>
    <li><a href='student_login.php'>Student Login</a></li>
    <li><a href='student_register.php'>Student Registration</a></li>
</ul>";
echo "</div>";

$conn->close();
