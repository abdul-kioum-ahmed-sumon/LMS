</html>
<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/dashboard.php");


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL ?>assets/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
    <link href="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" rel="icon">
    <script src="<?php echo BASE_URL ?>assets/js/1c26fb5c51.js" crossorigin="anonymous"></script>
    <title>BAUST LIBRARY</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .delete-btn {
            margin-left: 10px;
            /* Increase space between edit and delete buttons */
        }
    </style>
</head>

<!--Top Navbar Start-->
<nav class=" navbar navbar-expand-lg navbar-dark bg-dark fixed-top " style="background-image: radial-gradient(circle, #051937, #00172b, #00141e, #000d11, #010202);">
    <div class="container-fluid bg-hess ">
        <!--offcanvar trigger start-->
        <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!--offcanvar trigger start-->
        <p class="m-0"><a href="<?php echo BASE_URL ?>dashboard.php"><img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="baust" class="baust_logo  "></a></p>

        <a class="navbar-brand fw-bold text-uppercase me-auto" href="<?php echo BASE_URL ?>dashboard.php"> BAUST Library</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex ms-auto" role="search">
                <div class="input-group my-3 my-lg-0">
                    <input type="text" class="form-control" placeholder="Search" aria-describedby="button-addon2" style />
                    <button class="btn btn-outline-secondary bg-primary text-white mt-0" type="button" id="button-addon2">
                        <i class="fa-solid fa-magnifying-glass"></i></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>
<!--Offcanvas Menu start-->
<div class="offcanvas offcanvas-start bg-dark text-white sidebar-nav bg-hess " tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-body" style="background-image: radial-gradient(circle, #051937, #00172b, #00141e, #000d11, #010202);">
        <nav class="navbar-dark">
            <ul class="navbar-nav">
                <li>
                    <div class="text-secondary small fw-bold text-uppercase">
                        General
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL ?>dashboard">
                        <i class="fa-solid fa-display  me-2" style="color: #b2bafb;"></i> Dashboard</a>
                </li>
                <li class="my-0">
                    <hr />
                </li>

                <li>
                    <div class="text-secondary small fw-bold text-uppercase">
                        Inventory
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#booksManagement" role="button" aria-expanded="false" aria-controls="booksManagement">
                        <i class="fa-solid fa-book  me-2" style="color: #b2bafb;"></i>
                        Books Management
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="booksManagement">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>books/add" class="nav-link"><i class="fa-solid fa-book-medical me-2" style="color: #FFD43B;"></i> Add New</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL ?>books/" class="nav-link"><i class="fa-solid fa-sliders me-2" style="color: #FFD43B;"></i> Manage All</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#studentsManagement" role="button" aria-expanded="false" aria-controls="studentsManagement">
                        <i class="fa-solid fa-user-graduate  me-2" style="color: #b2bafb;"></i>
                        Students Management
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="studentsManagement">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>students/add.php" class="nav-link"><i class="fa-solid fa-user-plus me-2" style="color: #FFD43B;"></i> Add New</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL ?>students" class="nav-link"><i class="fa-solid fa-sliders me-2" style="color: #FFD43B;"></i> Manage All</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL ?>Staff/staff_management.php" class="nav-link"><i class="fa-solid fa-users-line me-2" style="color: #b2bafb;"></i>Staff Management
                    </a>
                </li>
                <li class="my-0">
                    <hr />
                </li>

                <li>
                    <div class="text-secondary small fw-bold text-uppercase">
                        All Issues
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#loanManagement" role="button" aria-expanded="false" aria-controls="loanManagement">
                        <i class="fa-solid fa-book-open-reader  me-2" style="color: #b2bafb;"></i>
                        Books Issue
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="loanManagement">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>loans/add.php" class="nav-link"><i class="fa-solid fa-book-medical me-2" style="color: #FFD43B;"></i> Add New</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL ?>loans" class="nav-link"><i class="fa-solid fa-sliders me-2" style="color: #FFD43B;"></i> Manage All</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#subscriptionManagement" role="button" aria-expanded="false" aria-controls="subscriptionManagement">
                        <i class="fa-solid fa-bangladeshi-taka-sign   me-2" style="color: #b2bafb;"></i>
                        Membership
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="subscriptionManagement">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>subscriptions" class="nav-link"><i class="fa-solid fa-layer-group me-2" style="color: #FFD43B;"></i> Plans</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL ?>subscriptions/purchase-history.php" class="nav-link"><i class="fa-solid fa-notes-medical me-2" style="color: #FFD43B;"></i> Membership
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="my-0">
                    <hr />
                </li>
                <div class="text-secondary small fw-bold text-uppercase">
                    Others
                </div>
                <li>
                    <a href="<?php echo BASE_URL ?>Magazine/Magazine.php" class="nav-link"><i class="fas fa-newspaper me-2" style="color: #b2bafb;"></i>Magazine
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL ?>Question_bank/qb_read.php" class="nav-link"><i class="fas fa-newspaper me-2" style="color: #b2bafb;"></i>Question Bank
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL ?>NoticeBoard/index.php" class="nav-link"><i class="fa-solid fa-pen-to-square me-2" style="color: #b2bafb;"></i>Notice Board
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL ?>FAQ/read_faq.php" class="nav-link"><i class="fa-solid fa-circle-question me-2" style="color: #b2bafb;"></i>FAQ
                    </a>
                </li>
                <li>
                    <a href=" #" class="nav-link"><i class="fa-solid fa-gear fa-spin me-2" style="color: #b2bafb;"></i>Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL ?>logout.php" class="nav-link"><i class="fa-solid fa-right-from-bracket me-2" style="color: #b2bafb;"></i> Logout</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!--Offcanvas Menu end-->

<body class="container1">
    <!--Main Container Start-->
    <main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">


        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }

            .container {
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                color: #007bff;
                margin-bottom: 20px;
            }

            form {
                margin-bottom: 20px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                color: #333;
            }

            input[type="text"],
            textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            textarea {
                height: 100px;
            }

            input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }
        </style>
        </head>

        <body>
            <!-- Form Container -->
            <div class="container mt-5">
                <!-- Form Heading -->
                <h1>Update Question</h1>
                <!-- Form for updating the question -->
                <form action="#" method="post">
                    <!-- Course Input -->
                    <label for="course">Course:</label>
                    <input type="text" id="course" name="course" required placeholder="Enter course name"><br>
                    <!-- Subject Input -->
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required placeholder="Enter subject name"><br>
                    <!-- Year Input -->
                    <label for="year">Year:</label>
                    <input type="number" id="year" name="year" required placeholder="Enter year"><br>
                    <!-- Semester Input -->
                    <label for="semester">Semester:</label>
                    <input type="number" id="semester" name="semester" required placeholder="Enter semester"><br>
                    <!-- Question Textarea -->
                    <label for="question">Question:</label>
                    <textarea id="question" name="question" required placeholder="Enter question"></textarea><br>
                    <!-- Submit Button -->
                    <input type="submit" name="s" value="Add Question">
                </form>
                <?php
                $db_server = "localhost";
                $db_user = "root";
                $db_pass = "";
                $db_name = "lms";
                $conn = "";
                // Establish a connection to the MySQL database
                $conn = mysqli_connect(
                    $db_server,
                    $db_user,
                    $db_pass,
                    $db_name
                );

                // Check if the form is submitted
                if (isset($_POST["s"])) {
                    // Get form data
                    $course = $_POST['course'];
                    $subject = $_POST['subject'];
                    $year = $_POST['year'];
                    $semester = $_POST['semester'];
                    $question = $_POST['question'];
                    $id = $_GET['id']; // Get the question ID from URL parameters

                    // Check if form fields are not empty
                    if (!empty($course) && !empty($subject) && !empty($year) && !empty($semester) && !empty($question)) {
                        // Construct SQL query to update the question
                        $temp =  "UPDATE previous_questions
            SET course = '$course', subject = '$subject' , year ='$year', semester= ' $semester',  question= '$question'
            WHERE id = $id";
                        // Execute the SQL query
                        mysqli_query($conn, $temp);
                        // Redirect to the question bank page after updating
                        header("location: qb_read.php");
                        echo "Updated";
                    } else {
                        // Display error message if form fields are empty
                        echo "Fill All The Information Please!!";
                    }
                    // Close the database connection
                    mysqli_close($conn);
                }


                ?>
            </div>



    </main>
    <!--Main content end-->
    <?php include_once(DIR_URL . "include/footer.php") ?>