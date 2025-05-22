<?php
// First check if constants are properly defined
if (!defined('SERVER_NAME') || !defined('USERNAME')) {
    // Hardcode the database connection settings as a fallback
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "lms";
} else {
    // Use the constants from config.php
    $server = SERVER_NAME;
    $username = USERNAME;
    $password = PASSWORD;
    $database = DATABASE;
}

// Create connection with proper error handling
try {
    $conn = new mysqli($server, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
