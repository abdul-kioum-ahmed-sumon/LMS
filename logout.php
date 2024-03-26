<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
session_destroy();
header("LOCATION: " . BASE_URL);
exit;
