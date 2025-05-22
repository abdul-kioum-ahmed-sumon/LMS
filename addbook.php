<?php

/**
 * Simple redirect to books/add.php
 * This file provides a convenient way to access the add book page
 */
include_once(__DIR__ . "/config/config.php");
header("Location: " . BASE_URL . "books/add.php");
exit;
