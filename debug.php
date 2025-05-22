<?php
session_start();
echo "<pre>";
echo "Session variables:\n";
print_r($_SESSION);
echo "\n\nCurrent URL: " . $_SERVER['REQUEST_URI'];
echo "</pre>";
