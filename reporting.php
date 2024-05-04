<?php
// Include necessary database connection file
include_once("/Xampp/htdocs/lms-master/config/config.php");

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "lms";

$con = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
$report = '';
// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["start_date"]) && isset($_GET["end_date"])) {
    // Get start and end dates from form
    $startDate = $_GET["start_date"];
    $endDate = $_GET["end_date"];




    function getreport($conn, $startDate, $endDate)
    {
        $tabs = array(
            'students' => array(),
            'loans' => array(),
            'subscriptions' => array(),
        );

        // Get recent students
        $sql = "SELECT * FROM students WHERE created_at BETWEEN '$startDate' AND '$endDate' ORDER BY id";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $tabs['students'][] = $row;
            }
        }

        // Get recent loans
        $sql = "SELECT l.*, b.title AS book_title, s.name AS student_name 
                FROM book_loans l
                INNER JOIN books b ON b.id = l.book_id
                INNER JOIN students s ON s.id = l.student_id
                WHERE l.created_at BETWEEN '$startDate' AND '$endDate'
                ORDER BY l.id";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $tabs['loans'][] = $row;
            }
        }

        // Get recent subscriptions
        $sql = "SELECT s.*, p.title AS plan_name, st.name AS student_name 
                FROM subscriptions s
                INNER JOIN subscription_plans p ON p.id = s.plan_id
                INNER JOIN students st ON st.id = s.student_id 
                WHERE s.created_at BETWEEN '$startDate' AND '$endDate'
                ORDER BY s.id";
        $res = $conn->query($sql);
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $tabs['subscriptions'][] = $row;
            }
        }

        return $tabs;
    }

    $tabs = getreport($con, $startDate, $endDate);
} else {
    // Handle error if query fails
    $report = "<div class='alert alert-danger'>Error: Please provide start and end dates.</div>";
}

?>

<!-- HTML and PHP code continues here -->





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
        @media print {
            .btn {
                display: none;
            }

            .noprint{
                display: none;
            }
        }
    </style>
    <script>
        function Getprint() {
            window.print();
        }
    </script>
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



        <h2 class="mt-3 fw-bold ">Library Management System - Generate Reports.</h2>
        <form id="reportForm" method="GET">
            <div class="form-group col-md-4 noprint" style="margin-left: 7px;">
                <label for="start_date" class="form-label mt-3">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate ?>" required>
            </div>
            <div class="form-group col-md-4 noprint" style="margin-left: 7px;">
                <label for="end_date" class="form-label mt-3 ">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate ?>" required>
            </div>

            <div class="row ">
                <div class="col-8 mt-3 mb-5">
                    <button type="submit" class="btn btn-primary  btn1">Get Data</button>
                    <button type="button" class="btn btn-primary  btn1" onclick="Getprint()">Print</button>
                </div>
            </div>
        </form>
        <?php
        if (isset($_GET["start_date"]) && isset($_GET["end_date"])) {

            echo $report = "<h4>Information Between ($startDate) and ($endDate) :</h4>";
        }
        ?>
        <!--Tabs-->
        <div class="row mt-2 ">
            <div class="col-md-12 mt-5">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase active " id="new-students" data-bs-toggle="tab" data-bs-target="#new-students-pane" type="button" role="tab" aria-controls="new-students-pane" aria-selected="true">
                            Students
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase" id="recent-loans" data-bs-toggle="tab" data-bs-target="#recent-loans-pane" type="button" role="tab" aria-controls="recent-loans-pane" aria-selected="false">
                            Book Issue
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase" id="recent-subscription" data-bs-toggle="tab" data-bs-target="#recent-subscription-pane" type="button" role="tab" aria-controls="recent-subscription-pane" aria-selected="false">
                            Membership
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="new-students-pane" role="tabpanel" aria-labelledby="new-students" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID</th>
                                        <th scope="col">Phone No</th>
                                        <th scope="col">Registered On</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($tabs['students'])) {
                                        $i = 1;
                                        foreach ($tabs['students'] as $st) {
                                    ?>
                                            <tr>
                                                <th><?php echo $i++ ?></th>
                                                <td><?php echo $st['name'] ?></td>
                                                <td><?php echo $st['dept_id'] ?></td>
                                                <td><?php echo $st['phone_no'] ?></td>
                                                <td><?php echo date("d-m-Y H:i A", strtotime($st['created_at'])) ?></td>
                                                <td>
                                                    <?php
                                                    if ($st['status'] == 1)
                                                        echo '<span class="badge text-bg-success">Active</span>';
                                                    else echo '<span class="badge text-bg-danger">Inactive</span>';

                                                    ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="recent-loans-pane" role="tabpanel" aria-labelledby="recent-loans" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Book Name</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Issue Date</th>
                                        <th scope="col">Return Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($tabs['loans'])) {
                                        $i = 1;
                                        foreach ($tabs['loans'] as $l) {
                                    ?>
                                            <tr>
                                                <th><?php echo $i++ ?></th>
                                                <td><?php echo $l['book_title'] ?></td>
                                                <td><?php echo $l['student_name'] ?></td>
                                                <td><?php echo date("d-m-Y", strtotime($l['loan_date'])) ?></td>
                                                <td><?php echo date("d-m-Y", strtotime($l['return_date'])) ?></td>
                                                <td>
                                                    <?php
                                                    if ($l['is_return'] == 1)
                                                        echo '<span class="badge text-bg-success">Returned</span>';
                                                    else echo '<span class="badge text-bg-warning">Active</span>';

                                                    ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="recent-subscription-pane" role="tabpanel" aria-labelledby="recent-subscription" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($tabs['subscriptions'])) {
                                        $i = 1;
                                        foreach ($tabs['subscriptions'] as $s) {
                                    ?>
                                            <tr>
                                                <th><?php echo $i++ ?></th>
                                                <td><?php echo $s['student_name'] ?></td>
                                                <td>
                                                    <span class="badge text-bg-info me-1"><?php echo $s['plan_name'] ?></span>
                                                    <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                                    <?php echo $s['amount'] ?>
                                                </td>
                                                <td><?php echo date("d-m-Y", strtotime($s['start_date'])) ?></td>
                                                <td><?php echo date("d-m-Y", strtotime($s['end_date'])) ?></td>
                                                <td>
                                                    <?php
                                                    $today = date("Y-m-d");
                                                    if ($s['end_date'] >= $today)
                                                        echo '<span class="badge text-bg-success">Active</span>';
                                                    else
                                                        echo  '<span class="badge text-bg-danger">Expired</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>









    </main>
    <!--Main content end-->
    <?php include_once(DIR_URL . "include/footer.php") ?>