<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "lms";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to database successfully.\n";

// Simulated POST data for student creation
$post_data = [
    'name' => 'Test Student',
    'email' => 'test@example.com',
    'phone_no' => '1234567890',
    'password' => 'password123',
    'dept_id' => '12345',
    'dept' => 'Computer Science',
    'address' => '123 Test Street',
    'status' => '1',
    'verified' => '1'
];

echo "Testing student creation with the following data:\n";
print_r($post_data);

// Create a function to simulate the create function
function test_create($conn, $param)
{
    extract($param);

    // Check if student ID already exists
    $check_sql = "SELECT * FROM students WHERE dept_id = '$dept_id'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        return array("error" => "Student ID already registered");
    }

    // Check if email already exists
    $check_email_sql = "SELECT * FROM students WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_sql);
    if ($check_email_result->num_rows > 0) {
        return array("error" => "Email already registered");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $datetime = date("Y-m-d H:i:s");

    $sql = "INSERT INTO students (name, phone_no, email, address, created_at, dept, dept_id, password, status, verified)
        VALUES ('$name', '$phone_no', '$email', '$address', '$datetime', '$dept', '$dept_id', '$hashed_password', '$status', '$verified')";

    echo "Executing SQL: " . $sql . "\n";

    $result = $conn->query($sql);
    if ($result) {
        return array("success" => true);
    } else {
        return array("error" => "Error: " . $conn->error);
    }
}

echo "\nAttempting to create student...\n";
$res = test_create($conn, $post_data);

echo "\nResult:\n";
print_r($res);

// If there was an error, let's check the table structure
if (!isset($res['success']) || !$res['success']) {
    echo "\nStudents table structure:\n";
    $result = $conn->query("DESCRIBE students");
    while ($row = $result->fetch_assoc()) {
        echo "{$row['Field']} - {$row['Type']}\n";
    }
}

// Close the connection
$conn->close();
