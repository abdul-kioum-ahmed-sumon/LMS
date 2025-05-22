<?php

/**
 * Redirect to books/add.php
 * This is a helper file to handle the incorrect URL request
 */

// Start session to preserve any session variables
session_start();

// Show a notice message about the correct URL
echo "<div style='padding:20px; background-color:#f8f9fa; border: 1px solid #ddd; margin: 20px; border-radius:5px;'>";
echo "<h3>Redirecting to the correct URL...</h3>";
echo "<p>The URL <code>http://localhost:8000/books/add</code> should be accessed directly via <code>http://localhost:8000/books/add.php</code> when using PHP's built-in server.</p>";
echo "<p>You will be redirected in 3 seconds. If not, <a href='books/add.php'>click here</a>.</p>";
echo "</div>";

// Redirect after a short delay
header("refresh:3;url=books/add.php");
