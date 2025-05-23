<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL ?>assets/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/custom.css" />

    <?php
    // Function to check if current page is in the students section
    $current_url = $_SERVER['REQUEST_URI'];
    if (strpos($current_url, 'students') !== false || strpos($current_url, 'student_') !== false) {
        echo '<link rel="stylesheet" href="' . BASE_URL . 'assets/css/students.css" />';
    }
    ?>

    <link href="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" rel="icon">

    <script src="<?php echo BASE_URL ?>assets/js/1c26fb5c51.js" crossorigin="anonymous"></script>
    <title>BAUST LIBRARY</title>
</head>

<body class="container1">