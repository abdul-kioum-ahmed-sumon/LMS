<?php
session_start();
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define("BASE_URL", "http://localhost/lms-master/");
    define("DIR_URL", $_SERVER['DOCUMENT_ROOT'] . "/lms-master/");

    define("SERVER_NAME", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DATABASE", "lms");
} else {
    define("BASE_URL", "https://lms.com");
    define("DIR_URL", $_SERVER['DOCUMENT_ROOT']);

    define("SERVER_NAME", "");
    define("USERNAME", "");
    define("PASSWORD", "");
    define("DATABASE", "");
}
