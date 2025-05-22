<?php

/**
 * URL Fixer Tool for LMS
 * 
 * This tool helps identify and fix URL-related issues in the LMS system
 * when switching between PHP's built-in server and Apache.
 */

session_start();
include_once(__DIR__ . "/config/config.php");

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');

// Define some styling for better presentation
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>LMS URL Fixer</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: #333; }
        h1 { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        h2 { color: #3498db; margin-top: 20px; }
        .card { border: 1px solid #ddd; border-radius: 4px; padding: 15px; margin-bottom: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f5f5f5; }
        code { background-color: #f8f9fa; padding: 2px 4px; border-radius: 4px; font-family: monospace; }
        .links-table a { text-decoration: none; color: #2980b9; }
        .links-table a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>LMS URL Fixer</h1>
";

// Display server information
echo "<div class='card'>";
echo "<h2>Server Information</h2>";
echo "<table>";
echo "<tr><td>PHP Version:</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Server Software:</td><td>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Server Name:</td><td>" . ($_SERVER['SERVER_NAME'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Server Port:</td><td>" . ($_SERVER['SERVER_PORT'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Document Root:</td><td>" . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Using PHP Built-in Server:</td><td>" . (php_sapi_name() === 'cli-server' ? 'Yes' : 'No') . "</td></tr>";
echo "<tr><td>Base URL:</td><td>" . BASE_URL . "</td></tr>";
echo "<tr><td>Directory URL:</td><td>" . DIR_URL . "</td></tr>";
echo "</table>";
echo "</div>";

// Common URL mappings
$url_mappings = [
    '/books/add' => 'books/add.php',
    '/books' => 'books/index.php',
    '/books/edit' => 'books/edit.php',
    '/students' => 'students/index.php',
    '/loans' => 'loans/index.php',
    '/loans/add' => 'loans/add.php',
    '/student_dashboard' => 'student_dashboard.php',
    '/dashboard' => 'dashboard.php',
    '/login' => 'login.php',
    '/admin' => 'admin/index.php',
];

// Display URL mappings
echo "<div class='card'>";
echo "<h2>Common URL Mappings</h2>";
echo "<p>These are the recommended URL mappings for both built-in PHP server and Apache:</p>";
echo "<table class='links-table'>";
echo "<tr><th>Clean URL</th><th>PHP File</th><th>Access</th></tr>";

foreach ($url_mappings as $clean_url => $php_file) {
    echo "<tr>";
    echo "<td><code>" . $clean_url . "</code></td>";
    echo "<td><code>" . $php_file . "</code></td>";
    echo "<td><a href='" . BASE_URL . ltrim($php_file, '/') . "' target='_blank'>Access File Directly</a></td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

// Add a special section for the books/add issue
echo "<div class='card'>";
echo "<h2>Books Add Page Fix</h2>";
echo "<p>If you're having trouble accessing <code>/books/add</code>, try these options:</p>";
echo "<ol>";
echo "<li><a href='" . BASE_URL . "books/add.php' target='_blank'>Access books/add.php directly</a></li>";
echo "<li><a href='" . BASE_URL . "books_add_redirect.php' target='_blank'>Use the redirect helper</a></li>";
echo "<li>If using PHP's built-in server, restart it with: <code>php -S localhost:8000 router.php</code></li>";
echo "</ol>";
echo "</div>";

// Check if .htaccess exists and has proper settings
$htaccess_path = __DIR__ . '/.htaccess';
echo "<div class='card'>";
echo "<h2>.htaccess Status</h2>";

if (file_exists($htaccess_path)) {
    $htaccess_content = file_get_contents($htaccess_path);

    echo "<p class='success'>✅ .htaccess file exists</p>";

    // Check for key settings
    if (strpos($htaccess_content, 'RewriteEngine On') !== false) {
        echo "<p class='success'>✅ RewriteEngine is enabled</p>";
    } else {
        echo "<p class='warning'>⚠️ RewriteEngine might not be enabled</p>";
    }

    if (strpos($htaccess_content, 'books/add') !== false) {
        echo "<p class='success'>✅ Special rule for books/add exists</p>";
    } else {
        echo "<p class='warning'>⚠️ No special rule for books/add</p>";
    }
} else {
    echo "<p class='error'>❌ .htaccess file not found!</p>";
}
echo "</div>";

// End of HTML
echo "
    <div class='card'>
        <h2>Next Steps</h2>
        <p>If you're still experiencing URL issues:</p>
        <ol>
            <li>Try accessing files directly with their .php extension</li>
            <li>Use the router.php file with PHP's built-in server: <code>php -S localhost:8000 router.php</code></li>
            <li>For Apache, ensure mod_rewrite is enabled and AllowOverride is set to All</li>
        </ol>
        <p><a href='index.php'>Return to Homepage</a></p>
    </div>
</body>
</html>
";
