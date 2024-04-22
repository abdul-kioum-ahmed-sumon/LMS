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
                    <input type="text" class="form-control mt-3" placeholder="Search" aria-describedby="button-addon2" />
                    <button class="btn btn-outline-secondary bg-primary text-white" type="button" id="button-addon2">
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
                    <a href="<?php echo BASE_URL ?>NoticeBoard/index.php" class="nav-link"><i class="fa-solid fa-pen-to-square me-2" style="color: #b2bafb;"></i>Notice Board
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




        <div class="container mt-5">
            <h2>Library Notice Board</h2>
            <?php
            if (isset($_SESSION['success_message'])) {
                echo "<p class='success-message'>" . htmlspecialchars($_SESSION['success_message']) . "</p>";
                unset($_SESSION['success_message']);
            }
            ?>
            <a href="add_notice.php" class="btn">Add Notice</a>
            <div class="notices">
                <?php
                include 'db_connection.php';

                $sql = "SELECT * FROM notices2 ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='notice'>";
                        echo "<h3>{$row['title']}</h3>";
                        echo "<p>{$row['content']}</p>";
                        echo "<small>{$row['created_at']}</small>";

                        // Check if notice has a file associated with it
                        if (!empty($row['file_path'])) {
                            // Assuming the file is an image
                            echo "<img src='{$row['file_path']}' alt='Notice Image' style='max-width: 100%;'>";
                        }

                        // Edit and Delete buttons
                        echo "<div class='notice-buttons'>";
                        echo "<a href='edit_notice.php?id={$row['id']}' class='btn edit-btn'>Edit</a>";
                        echo "<a href='delete_notice.php?id={$row['id']}' class='btn delete-btn' onclick='return confirm(\"Are you sure you want to delete this notice?\")'>Delete</a>";
                        echo "</div>";

                        echo "</div>";
                    }
                } else {
                    echo "<p>No notices found.</p>";
                }

                $conn->close();
                ?>
            </div>
        </div>



    </main>
    <!--Main content end-->
    <?php include_once(DIR_URL . "include/footer.php") ?>