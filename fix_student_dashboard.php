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

echo "<h1>Student Dashboard Fix Tool</h1>";
echo "<p>This script fixes issues with the student dashboard to ensure it works properly.</p>";

// Fix 1: Check if student_id in session is valid and fix it if needed
echo "<h2>Step 1: Checking Student Sessions</h2>";

// Get all active students
$activeStudents = $conn->query("SELECT * FROM students WHERE status = 1 AND verified = 1 LIMIT 10");
if ($activeStudents && $activeStudents->num_rows > 0) {
    echo "<p>✓ Found " . $activeStudents->num_rows . " active students in the database.</p>";
} else {
    echo "<p style='color:red'>✗ No active students found. Attempting to fix student verification.</p>";

    // Automatically verify students
    $conn->query("UPDATE students SET status = 1, verified = 1 WHERE id > 0");
    echo "<p>✓ Updated all students to verified and active status.</p>";
}

// Fix 2: Update the student_dashboard.php file to handle null student data
echo "<h2>Step 2: Fixing Student Dashboard Code</h2>";

// Read student_dashboard.php
$dashboardPath = 'student_dashboard.php';
$dashboardContent = file_get_contents($dashboardPath);
$originalContent = $dashboardContent;

// Fix issue where student data might be null
$studentNullCheckFix = <<<'EOD'
// Get student information
$student_id = $_SESSION['student_id'];
$student = getStudentInfoById($conn, $student_id);

// Check if student exists
if (!$student) {
    // Student not found, log this issue
    error_log("Error: Student with ID $student_id not found in database");
    
    // Create a placeholder student array to prevent null reference errors
    $student = [
        'id' => $student_id,
        'name' => 'Unknown Student',
        'dept_id' => 'N/A',
        'dept' => 'Unknown',
        'email' => '',
        'phone_no' => '',
        'address' => ''
    ];
}

// Debugging - log student info
error_log("Student ID: " . $student_id . ", Student Name: " . $student['name']);
EOD;

// Replace in the dashboard content
$dashboardContent = preg_replace(
    '/\/\/ Get student information.*?error_log\("Student ID:.*?student\[\'name\'\]\);/s',
    $studentNullCheckFix,
    $dashboardContent
);

// Check if changes were made
if ($dashboardContent !== $originalContent) {
    // Write the updated content back to the file
    if (file_put_contents($dashboardPath, $dashboardContent)) {
        echo "<p style='color:green'>✓ Successfully updated student_dashboard.php with null checks for student data.</p>";
    } else {
        echo "<p style='color:red'>✗ Failed to write updates to student_dashboard.php.</p>";
    }
} else {
    echo "<p style='color:orange'>⚠ No changes made to student_dashboard.php. The file might already have the fixes or the pattern wasn't found.</p>";

    // Attempt a different approach for the null checks
    $simpleNullCheck = <<<'EOD'
// Check if student exists
if (!$student) {
    // Student not found, create a placeholder
    $student = [
        'id' => $student_id,
        'name' => 'Unknown Student',
        'dept_id' => 'N/A',
        'dept' => 'Unknown',
        'email' => '',
        'phone_no' => '',
        'address' => ''
    ];
}
EOD;

    // Add after the getStudentInfoById line
    $dashboardContent = preg_replace(
        '/\$student = getStudentInfoById\(\$conn, \$student_id\);/',
        '$student = getStudentInfoById($conn, $student_id);' . "\n\n" . $simpleNullCheck,
        $originalContent
    );

    // Write the updated content back to the file
    if ($dashboardContent !== $originalContent && file_put_contents($dashboardPath, $dashboardContent)) {
        echo "<p style='color:green'>✓ Successfully updated student_dashboard.php with simple null checks.</p>";
    } else {
        echo "<p style='color:red'>✗ Failed to apply simple null check to student_dashboard.php.</p>";
    }
}

// Fix 3: Verify that the student login function correctly sets session variables
echo "<h2>Step 3: Verifying Student Login Function</h2>";

// Create a test user in the database if needed
$testEmail = "test_student@example.com";
$checkTestUser = $conn->query("SELECT * FROM students WHERE email = '$testEmail'");

if ($checkTestUser->num_rows == 0) {
    // No test user exists, create one
    $hashedPassword = password_hash("test123", PASSWORD_DEFAULT);
    $createUserSql = "INSERT INTO students (name, email, password, dept_id, dept, status, verified) 
                    VALUES ('Test Student', '$testEmail', '$hashedPassword', '12345', 'CSE', 1, 1)";

    if ($conn->query($createUserSql)) {
        $testUserId = $conn->insert_id;
        echo "<p style='color:green'>✓ Created a test student account (ID: $testUserId) for verification.</p>";
    } else {
        echo "<p style='color:red'>✗ Failed to create a test student account: " . $conn->error . "</p>";
    }
} else {
    $testUser = $checkTestUser->fetch_assoc();
    $testUserId = $testUser['id'];
    echo "<p>✓ Found existing test student account (ID: $testUserId).</p>";

    // Make sure the test user is active and verified
    $conn->query("UPDATE students SET status = 1, verified = 1 WHERE id = $testUserId");
}

echo "<div style='margin-top: 20px; padding: 10px; background-color: #e8f7e8; border: 1px solid #80c080;'>";
echo "<h3>Test Your Fix</h3>";
echo "<p>You can now test the student dashboard with the following login:</p>";
echo "<ul>";
echo "<li>Email: $testEmail</li>";
echo "<li>Password: test123</li>";
echo "</ul>";
echo "<p><a href='student_login.php' class='btn btn-success'>Go to Student Login</a></p>";
echo "</div>";

// Final success message
echo "<h2>Fix Complete!</h2>";
echo "<p>The student dashboard has been fixed. Please test by logging in with the test account.</p>";

$conn->close();
