<?php
include_once("config/config.php");
include_once(DIR_URL . "models/student_auth.php");

// Logout the student
logoutStudent();

// Redirect to login page
header("Location: student_login.php");
exit;
