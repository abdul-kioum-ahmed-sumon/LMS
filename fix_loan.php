<?php
// Start session
session_start();

// Include required files
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/student_auth.php");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit;
}

// Check if form was submitted with loan_id
if (isset($_POST['fix_loan']) && isset($_POST['loan_id'])) {
    $loan_id = (int)$_POST['loan_id'];
    $student_id = (int)$_SESSION['student_id'];

    // Security check: make sure the loan belongs to this student
    $check_sql = "SELECT id FROM book_loans WHERE id = $loan_id AND student_id = $student_id";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        // Fix the loan - set is_return to 0
        $fix_sql = "UPDATE book_loans SET is_return = 0 WHERE id = $loan_id AND student_id = $student_id";
        $result = $conn->query($fix_sql);

        if ($result) {
            $_SESSION['success_message'] = "Loan status fixed successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to fix loan status: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "Invalid loan or you do not have permission to fix it.";
    }
}

// Redirect back to student dashboard
header("Location: student_dashboard.php");
exit;
