<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Display success message if it exists
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}

// sanitize input data
function sanitize_input($data)
{
    global $conn;
    return htmlspecialchars(strip_tags($conn->real_escape_string($data)));
}

// Fetch all staff records or filtered records if search query is provided
if (isset($_GET['search'])) {
    $search = sanitize_input($_GET['search']);
    $sql = "SELECT * FROM staff WHERE name LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM staff";
}

$result = $conn->query($sql);
?>

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
            /* Additional CSS for styling */
            body {
                background-color: #f4f4f4;
                /* Light gray background */
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 80%;
                margin: 20px auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                position: relative;
                /* Ensure position relative for absolute positioning */
            }

            h2 {
                color: #007bff;
                /* Blue heading */
                text-align: center;
                /* Center align heading */
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th,
            td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #007bff;
                color: #fff;
                text-align: center;
                /* Center align column names */
                text-transform: uppercase;
                font-weight: bold;
                /* Bold font for column names */
            }

            th:first-child {
                border-top-left-radius: 8px;
                /* Rounded corners for first column header */
            }

            th:last-child {
                border-top-right-radius: 8px;
                /* Rounded corners for last column header */
            }

            /* Different style for column name rows */
            th:first-child,
            /* First column */
            th:nth-child(2),
            /* Second column */
            th:nth-child(3),
            /* Third column */
            th:nth-child(4) {
                /* Fourth column */
                background-color: #0056b3;
                /* Darker blue background for column name rows */
            }

            th:first-child span,
            /* First column text */
            th:nth-child(2) span,
            /* Second column text */
            th:nth-child(3) span,
            /* Third column text */
            th:nth-child(4) span {
                /* Fourth column text */
                color: #fff;
                /* White text */
            }

            td:first-child {
                font-weight: bold;
            }

            /* Center-align email and position column values */
            td:nth-child(2),
            /* 2nd column, which is the Email column */
            td:nth-child(3) {
                /* 3rd column, which is the Position column */
                text-align: center;
            }

            /* Center-align action column values */
            .action-links {
                display: flex;
                justify-content: center;
            }

            .action-links a {
                text-decoration: none;
                color: #fff;
                margin-right: 10px;
                padding: 8px;
                /* Adjusted padding */
                border-radius: 5px;
                transition: background-color 0.3s;
                display: inline-block;
            }

            .action-links a:hover {
                background-color: #0056b3;
            }

            .btn-square {
                background-color: #007bff;
                /* Blue button */
                border: none;
                color: #fff;
                padding: 8px;
                /* Adjusted padding */
                border-radius: 5px;
                text-decoration: none;
                transition: background-color 0.3s;
                display: inline-block;
            }

            .btn-square:hover {
                background-color: #0056b3;
                /* Darker blue on hover */
            }

            .btn-danger {
                background-color: #dc3545;
                /* Red button */
                padding: 8px;
                /* Adjusted padding */
            }

            .btn-danger:hover {
                background-color: #c82333;
                /* Darker red on hover */
            }

            /* Ensure names appear in a single line */
            td:first-child {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Adjust position of back button */
            .btn-back {
                position: absolute;
                top: 20px;
                right: 20px;
            }

            /* Style for the back button */
            .btn-back {
                background-color: #6c757d;
                /* Gray button */
                color: #fff;
                /* White text */
                padding: 8px 16px;
                /* Adjusted padding */
                border-radius: 5px;
                /* Rounded corners */
                text-decoration: none;
                /* Remove default link underline */
                transition: background-color 0.3s;
                /* Add transition effect */
            }

            /* Style the back button on hover */
            .btn-back:hover {
                background-color: #5a6268;
                /* Darker gray on hover */
            }

            /* Change background color of even rows */
            tbody tr:nth-child(even) {
                background-color: #f2f2f2;
                /* Light gray background for even rows */
            }
        </style>
        </head>


        <div class="container">
            <h2>Staff Management System</h2>
            <a href="staff_management.php" class="btn btn-primary btn-square mb-3"><i class="fas fa-home fa-lg"></i> Home</a> <!-- Home button with home icon -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form class="form-inline">
                        <input type="text" class="form-control mr-2" name="search" placeholder="Search by name">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><span>Name</span></th>
                        <th><span>Email</span></th>
                        <th><span>Position</span></th>
                        <th><span>Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["position"] . "</td>";
                            echo "<td class='action-links'>
                                <a href='edit_staff.php?id=" . $row["staff_id"] . "' class='btn btn-primary btn-square'><i class='fas fa-edit fa-lg'></i> Edit</a> <!-- Edit button with edit icon -->
                                <a href='delete_staff.php?id=" . $row["staff_id"] . "' class='btn btn-danger btn-square' onclick='return confirm(\"Are you sure you want to delete this staff?\")'><i class='fas fa-trash-alt fa-lg'></i> Delete</a> <!-- Delete button with trash icon -->
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No staff found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="javascript:history.back()" class="btn btn-back"><i class="fas fa-arrow-left fa-lg"></i> Back</a> <!-- Back button with back arrow icon -->
        </div>

    </main>
    <!--Main content end-->
    <?php include_once(DIR_URL . "include/footer.php") ?>