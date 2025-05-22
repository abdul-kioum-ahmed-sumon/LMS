<?php

/**
 * Router for PHP's built-in server
 * 
 * This file allows clean URLs to work with PHP's built-in server.
 * When running with `php -S localhost:8000 router.php`, this file will handle all requests.
 */

/**
 * Simple router for PHP built-in web server
 * This helps the development server handle routes properly
 */

// parse URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route static files directly
if (preg_match('/\.(?:css|js|jpe?g|gif|png|woff2?|eot|ttf|svg|ico)$/', $uri)) {
    return false; // Let the server handle static files directly
}

// Special handling for verify.php since it's in a subdirectory
if (preg_match('/^\/loans\/verify\.php/', $uri)) {
    // Include the file directly
    include __DIR__ . '/loans/verify.php';
    return true;
}

// Check if file exists - if yes, include it
if (file_exists(__DIR__ . $uri)) {
    // If it's a PHP file, include it
    if (preg_match('/\.php$/', $uri)) {
        include __DIR__ . $uri;
        return true;
    }

    // Otherwise let the server handle it
    return false;
}

// Default to index.php for other routes that don't exist
include __DIR__ . '/index.php';
return true;
