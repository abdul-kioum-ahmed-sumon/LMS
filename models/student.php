<?php

// Function to create student
function createStudentStudent($conn, $param)
{
    extract($param);

    ## Validation start
    if (empty($name)) {
        $result = array("error" => "Name is required");
        return $result;
    } else if (empty($email)) {
        $result = array("error" => "Email is required");
        return $result;
    } else if (empty($phone_no)) {
        $result = array("error" => "Phone no is required");
        return $result;
    } else if (empty($password)) {
        $result = array("error" => "Password is required");
        return $result;
    } else if (empty($dept_id)) {
        $result = array("error" => "Student ID is required");
        return $result;
    }
    ## Validation end

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

    // Make sure to include password and verified fields in the SQL query
    $sql = "INSERT INTO students (name, phone_no, email, address, created_at, dept, dept_id, password, status, verified) 
            VALUES ('$name', '$phone_no', '$email', '$address', '$datetime', '$dept', '$dept_id', '$hashed_password', '$status', '$verified')";

    $result['success'] = $conn->query($sql);
    if (!$result['success']) {
        $result['error'] = "Error: " . $conn->error;
    }
    return $result;
}

// Function to get all students
function getStudents($conn)
{
    $sql = "select * from students order by id desc ";
    $result = $conn->query($sql);
    return $result;
}

// Function to get student details
function getStudentById($conn, $id)
{
    $sql = "select * from students where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to delete
function delete($conn, $id)
{
    $sql = "delete from students where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to update student status
function updateStatus($conn, $id, $status)
{
    $sql = "update students set status = '$status' where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to update verification status
function updateVerificationStatus($conn, $id, $verified)
{
    $sql = "update students set verified = '$verified' where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to update
function update($conn, $param)
{
    extract($param);
    ## Validation start
    if (empty($name)) {
        $result = array("error" => "Name is required");
        return $result;
    } else if (empty($email)) {
        $result = array("error" => "Email is required");
        return $result;
    } else if (empty($phone_no)) {
        $result = array("error" => "Phone no is required");
        return $result;
    }
    ## Validation end

    $datetime = date("Y-m-d H:i:s");

    // Sanitize variables
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $phone_no = $conn->real_escape_string($phone_no);
    $address = $conn->real_escape_string($address);
    $datetime = $conn->real_escape_string($datetime);
    $dept = $conn->real_escape_string($dept);
    $dept_id = $conn->real_escape_string($dept_id);
    $status = isset($status) ? $conn->real_escape_string($status) : 1;
    $verified = isset($verified) ? $conn->real_escape_string($verified) : 0;

    $sql = "UPDATE students SET 
        name = '$name', 
        email = '$email', 
        phone_no = '$phone_no',
        address = '$address',
        updated_at = '$datetime',
        dept = '$dept',
        dept_id = '$dept_id',
        status = '$status',
        verified = '$verified'";

    // Update password if provided
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql .= ", password = '$hashed_password'";
    }

    $sql .= " WHERE id = $id";

    $result['success'] = $conn->query($sql);
    return $result;
}

// Function to get categories
function getCategories($conn)
{
    $sql = "select id, name from categories";
    $result = $conn->query($sql);
    return $result;
}

// Function to get student bookings
function getStudentBookings($conn, $student_id)
{
    $sql = "SELECT l.*, b.title as book_title 
            FROM book_loans l
            INNER JOIN books b ON b.id = l.book_id
            WHERE l.student_id = $student_id
            ORDER BY l.created_at DESC";
    $result = $conn->query($sql);
    return $result;
}

// Function to get pending student registrations
function getPendingStudentRegistrations($conn)
{
    $sql = "SELECT * FROM students WHERE status = 0 AND verified = 1 ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result;
}

// Function to approve student registration
function approveStudentRegistration($conn, $id)
{
    $sql = "UPDATE students SET status = 1 WHERE id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to reject student registration
function rejectStudentRegistration($conn, $id)
{
    $sql = "DELETE FROM students WHERE id = $id AND status = 0";
    $result = $conn->query($sql);
    return $result;
}
