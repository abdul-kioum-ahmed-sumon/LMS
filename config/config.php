<?php
if (session_status() == PHP_SESSION_NONE) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Fix for PHP built-in server
$document_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$script_dir = dirname(__DIR__);

// XAMPP specific path handling
$project_root = basename(dirname(__FILE__, 2)); // Gets the name of the project folder
$xampp_detect = strpos($document_root, 'xampp') !== false || strpos($document_root, 'XAMPP') !== false;

// Check if using PHP built-in server or XAMPP
$using_builtin_server = php_sapi_name() === 'cli-server' ||
    (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '8000');

if ($using_builtin_server || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)) {
    // For PHP's built-in server or localhost
    if ($xampp_detect) {
        // XAMPP environment
        define("BASE_URL", "/lms-master/");
        define("DIR_URL", $document_root . "/lms-master/");
    } else {
        // Regular localhost environment
        define("BASE_URL", "http://localhost:8000/");
        define("DIR_URL", $script_dir . "/");
    }

    // Database credentials
    define("SERVER_NAME", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DATABASE", "lms");
} else {
    // Production server settings
    define("BASE_URL", "https://lms.com");
    define("DIR_URL", $document_root . "/");

    // Production database credentials - update these as needed
    define("SERVER_NAME", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DATABASE", "lms");
}
