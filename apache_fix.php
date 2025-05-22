<?php
// Apache Session Fix Tool

// 1. Start with clean session
if (session_status() == PHP_SESSION_ACTIVE) {
    session_destroy();
}

// 2. Set optimal session parameters
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_path', '/lms-master');
ini_set('session.gc_maxlifetime', 86400); // 24 hours

// 3. Restart session with proper path
session_start();

// 4. Display current environment info
echo "<h1>Apache Session Configuration Fix</h1>";
echo "<p>This tool helps fix session issues when using Apache instead of PHP built-in server.</p>";

echo "<h2>Environment Information</h2>";
echo "<ul>";
echo "<li>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
echo "<li>Server Name: " . $_SERVER['SERVER_NAME'] . "</li>";
echo "<li>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>Session Path: " . session_save_path() . "</li>";
echo "<li>Session Name: " . session_name() . "</li>";
echo "<li>Session ID: " . session_id() . "</li>";
echo "</ul>";

// 5. Set a test session variable
$_SESSION['apache_fix_test'] = "Session is working! " . date('Y-m-d H:i:s');
$_SESSION['student_id'] = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 1;

// 6. Check if .htaccess exists and has proper settings
$htaccess_path = __DIR__ . '/.htaccess';
$htaccess_fix_needed = false;

if (file_exists($htaccess_path)) {
    $htaccess_content = file_get_contents($htaccess_path);

    // Check if session path is properly set
    if (strpos($htaccess_content, 'php_value session.cookie_path') === false) {
        $htaccess_fix_needed = true;
    }

    echo "<h2>.htaccess Status</h2>";
    if ($htaccess_fix_needed) {
        echo "<p style='color:orange'>⚠️ .htaccess file exists but may need session path configuration.</p>";
    } else {
        echo "<p style='color:green'>✅ .htaccess file exists with proper session configuration.</p>";
    }
} else {
    echo "<h2>.htaccess Status</h2>";
    echo "<p style='color:red'>❌ .htaccess file not found! This may cause URL rewriting issues.</p>";
}

// 7. Create links to test pages
echo "<h2>Test Links</h2>";
echo "<p>Click these links to test if sessions are working properly:</p>";
echo "<ul>";
echo "<li><a href='student_dashboard.php' target='_blank'>Go to Student Dashboard</a></li>";
echo "<li><a href='booking_check.php' target='_blank'>Go to Booking Diagnostic Tool</a></li>";

// Allow setting different student IDs for testing
echo "<li>Test with different Student IDs:";
echo "<ul>";
for ($i = 1; $i <= 5; $i++) {
    echo "<li><a href='apache_fix.php?student_id={$i}'>Set session to Student ID {$i}</a></li>";
}
echo "</ul></li>";
echo "</ul>";

// 8. Offer specific fixes
echo "<h2>Manual Fixes</h2>";
echo "<p>If you're still having issues, try these steps:</p>";
echo "<ol>";
echo "<li>Make sure Apache has mod_rewrite enabled</li>";
echo "<li>Check if AllowOverride is set to All in your Apache config</li>";
echo "<li>Restart Apache after making configuration changes</li>";
echo "<li>Clear your browser cookies for this site</li>";
echo "<li>Try accessing through the direct URL with .php extension (e.g., student_dashboard.php instead of student_dashboard)</li>";
echo "</ol>";

// 9. Show current session data
echo "<h2>Current Session Data</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// 10. Provide a link back to the main application
echo "<p><a href='index.php' class='btn'>Return to Main Page</a></p>";
