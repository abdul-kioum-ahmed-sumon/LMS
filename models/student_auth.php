<?php

/**
 * Student Authentication Model
 * 
 * This file contains functions for student authentication including:
 * - Student registration
 * - Student login
 * - Password reset
 * - Session management
 */

// Function to register a new student
function registerStudent($conn, $param)
{
    // Extract parameters
    $name = isset($param['name']) ? $conn->real_escape_string($param['name']) : '';
    $email = isset($param['email']) ? $conn->real_escape_string($param['email']) : '';
    $password = isset($param['password']) ? $param['password'] : '';
    $confirm_password = isset($param['confirm_password']) ? $param['confirm_password'] : '';
    $dept_id = isset($param['dept_id']) ? $conn->real_escape_string($param['dept_id']) : '';
    $dept = isset($param['dept']) ? $conn->real_escape_string($param['dept']) : '';
    $phone_no = isset($param['phone_no']) ? $conn->real_escape_string($param['phone_no']) : '';
    $address = isset($param['address']) ? $conn->real_escape_string($param['address']) : '';

    // Validation
    if (empty($name)) {
        return array("error" => "Name is required");
    } elseif (empty($email)) {
        return array("error" => "Email is required");
    } elseif (empty($password)) {
        return array("error" => "Password is required");
    } elseif (empty($confirm_password)) {
        return array("error" => "Confirm password is required");
    } elseif ($password !== $confirm_password) {
        return array("error" => "Passwords do not match");
    } elseif (empty($dept_id)) {
        return array("error" => "Student ID is required");
    } elseif (empty($dept)) {
        return array("error" => "Department is required");
    }

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

    // Set student status to pending verification (status = 0)
    $status = 0;
    $verified = 0; // ID not verified initially

    $datetime = date("Y-m-d H:i:s");

    // Insert new student
    $sql = "INSERT INTO students (name, email, password, dept_id, dept, phone_no, address, status, verified, created_at) 
            VALUES ('$name', '$email', '$hashed_password', '$dept_id', '$dept', '$phone_no', '$address', '$status', '$verified', '$datetime')";

    if ($conn->query($sql)) {
        return array("success" => true);
    } else {
        return array("error" => "Registration failed: " . $conn->error);
    }
}

// Function to authenticate student login
function loginStudent($conn, $email, $password)
{
    // Sanitize inputs
    $email = $conn->real_escape_string($email);

    // Validation
    if (empty($email)) {
        return array("error" => "Email is required");
    } elseif (empty($password)) {
        return array("error" => "Password is required");
    }

    // Check if student exists
    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        return array("error" => "Invalid email or password");
    }

    $student = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $student['password'])) {
        return array("error" => "Invalid email or password");
    }

    // Check if account is active
    if ($student['status'] != 1) {
        return array("error" => "Your account is not active. Please contact the library administrator.");
    }

    // Check if ID is verified
    if (!isset($student['verified']) || $student['verified'] != 1) {
        return array("error" => "Your ID has not been verified yet. Please wait for administrator verification.");
    }

    // Set session cookie parameters to be compatible with both built-in server and Apache
    if (session_status() == PHP_SESSION_NONE) { if (session_status() == PHP_SESSION_NONE) { session_set_cookie_params([
        'lifetime' => 86400, // 24 hours
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true
    ]); } }

    // Make sure session is started
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Set session variables
    $_SESSION['student_id'] = $student['id'];
    $_SESSION['student_name'] = $student['name'];
    $_SESSION['student_email'] = $student['email'];

    // Log session info for debugging
    error_log("Student login successful. ID: {$student['id']}, Name: {$student['name']}, Session ID: " . session_id());

    // Return success and student info
    return array(
        "success" => true,
        "student_id" => $student['id'],
        "student_name" => $student['name'],
        "student_email" => $student['email']
    );
}

// Function to check if student is logged in
function isStudentLoggedIn()
{
    return isset($_SESSION['student_id']);
}

// Function to log out student
function logoutStudent()
{
    // Unset all session variables
    unset($_SESSION['student_id']);
    unset($_SESSION['student_name']);
    unset($_SESSION['student_email']);

    // Clear any cookies
    if (isset($_COOKIE['student_login'])) {
        setcookie('student_login', '', time() - 3600, '/');
    }

    return true;
}

// Function to get student details by ID
function getStudentInfoById($conn, $student_id)
{
    $student_id = $conn->real_escape_string($student_id);

    $sql = "SELECT * FROM students WHERE id = '$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to update student profile
function updateStudentProfile($conn, $student_id, $param)
{
    // Extract and sanitize parameters
    $name = isset($param['name']) ? $conn->real_escape_string($param['name']) : '';
    $phone_no = isset($param['phone_no']) ? $conn->real_escape_string($param['phone_no']) : '';
    $address = isset($param['address']) ? $conn->real_escape_string($param['address']) : '';
    $new_password = isset($param['new_password']) ? $param['new_password'] : '';

    // Start building the update query
    $sql = "UPDATE students SET ";
    $updateParts = array();

    if (!empty($name)) {
        $updateParts[] = "name = '$name'";
    }

    if (!empty($phone_no)) {
        $updateParts[] = "phone_no = '$phone_no'";
    }

    if (!empty($address)) {
        $updateParts[] = "address = '$address'";
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updateParts[] = "password = '$hashed_password'";
    }

    // Only proceed if there's something to update
    if (count($updateParts) > 0) {
        $sql .= implode(", ", $updateParts);
        $sql .= " WHERE id = '$student_id'";

        if ($conn->query($sql)) {
            return array("success" => true);
        } else {
            return array("error" => "Update failed: " . $conn->error);
        }
    } else {
        return array("error" => "No data to update");
    }
}
