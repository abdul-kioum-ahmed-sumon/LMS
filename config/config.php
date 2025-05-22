<?php
if (session_status() == PHP_SESSION_NONE) { if (session_status() == PHP_SESSION_NONE) { session_start(); } }

// Fix for PHP built-in server
$document_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$script_dir = dirname(__DIR__);

// Check if using PHP built-in server (more reliable detection)
$using_builtin_server = php_sapi_name() === 'cli-server' ||
    (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '8000');

if ($using_builtin_server || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)) {
    // Using PHP's built-in server or localhost
    define("BASE_URL", "http://localhost:8000/");
    define("DIR_URL", $script_dir . "/");

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
