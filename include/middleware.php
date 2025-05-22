<?php
// Debug session info to a log file for troubleshooting
file_put_contents('session_debug.log', date('Y-m-d H:i:s') . " - Session: " . json_encode($_SESSION) . " - URL: " . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

// Check if user is logged in
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
    return true;
} else {
    $_SESSION['error'] = "Please login first";
    header("Location: " . BASE_URL);
    exit;
}
