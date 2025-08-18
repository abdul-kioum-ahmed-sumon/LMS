<?php
include_once(__DIR__ . "/config/config.php");
session_destroy();
header("LOCATION: " . BASE_URL);
exit;
