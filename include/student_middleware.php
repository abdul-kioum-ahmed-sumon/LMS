<?php
// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: " . BASE_URL . "student_login.php");
    exit;
}
