</html>
<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/dashboard.php");

$counts = getCounts($conn);
$tabs = getTabData($conn);


?>

<?php
// Function to retrieve books from the database
function GetBooks($conn)
{
    // Perform your database query here to fetch books
    // Example query:
    $query = "select b.*, c.name as cat_name from books b 
        inner join categories c on c.id = b.category_id 
        order by id desc";
    $result = $conn->query($query);

    // Check if query was successful
    if ($result) {
        // Return the result set
        return $result;
    } else {
        // Return an empty result or handle the error as needed
        return false;
    }
}

$books = GetBooks($conn);
if (!$books) {
    $_SESSION['error'] = "Error fetching books: " . $conn->error;
}

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
</head>

<!--Top Navbar Start-->
<nav class=" navbar navbar-expand-lg navbar-dark bg-dark fixed-top " style="background-image: radial-gradient(circle, #051937, #00172b, #00141e, #000d11, #010202);">
    <div class="container-fluid bg-hess ">
        <!--offcanvar trigger start-->
        <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!--offcanvar trigger start-->
        <p class="m-0"><img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="baust" class="baust_logo  "></p>

        <a class="navbar-brand fw-bold text-uppercase me-auto"> BAUST Library</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex ms-auto" role="search">
                <div class="input-group my-3 my-lg-0">
                    <input type="text" class="form-control" placeholder="Search" aria-describedby="button-addon2" />
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
                    <a class="nav-link active" aria-current="page">
                        <i class="fa-solid fa-display  me-2" style="color: #b2bafb;"></i> Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL ?>User/Magazine.php" class="nav-link"><i class="fas fa-newspaper me-2" style="color: #b2bafb;"></i>Magazine
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL ?>User/qb_read.php" class="nav-link"><i class="fas fa-newspaper me-2" style="color: #b2bafb;"></i>Question Bank
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL ?>User/faq.php" class="nav-link"><i class="fa-solid fa-circle-question me-2" style="color: #b2bafb;"></i>FAQ
                    </a>
                </li>
                <li class="my-0">
                    <hr />
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link"><i class="fa-solid fa-right-from-bracket me-2" style="color: #b2bafb;"></i> Logout</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!--Offcanvas Menu end-->

<body class="container1">
    <!--Main Container Start-->
    <main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-md-12 mt-4">
                    <p style="font-size:40px; font-weight:bold;">
                        Hello <?php
                                if (isset($_SESSION['email'])) {
                                    $email = $_SESSION['email'];
                                    $query = mysqli_query($conn, "SELECT users_2.* FROM `users_2` WHERE users_2.email='$email'");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo $row['firstName'] . ' ' . $row['lastName'];
                                    }
                                }
                                ?>!<br>
                        ID : <?php
                                if (isset($_SESSION['email'])) {
                                    $email = $_SESSION['email'];
                                    $query = mysqli_query($conn, "SELECT users_2.* FROM `users_2` WHERE users_2.email='$email'");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo $row['dept_id'];
                                    }
                                }
                                ?>
                    </p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-3 mt-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="card-title text-uppercase text-muted">
                                    Total Books
                                </h6>
                                <p class="h1 fw-bold"><?php echo $counts['total_books'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mt-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h6 class="card-title text-uppercase text-muted">
                                    Total Books Issue
                                </h6>
                                <p class="h1 fw-bold"><?php echo $counts['total_loans'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
    </main>

    <main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
        <div class="container-fluid">
            <!--Cards-->
            <div class="row dashboard-counts">
                <div class="col-md-12 mt-4">

                    <h3 class="fw-bold text-uppercase">All Books</h3>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            All Books
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-responsive table-striped" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">Sl</th>
                                            <th scope="col">Book Name</th>
                                            <th scope="col">Publication Year</th>
                                            <th scope="col">Author Name</th>
                                            <th scope="col">ISBN No</th>
                                            <th scope="col">Cat Name</th>
                                            <th scope="col">Shelf Number</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        while ($row = $books->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++ ?></th>
                                                <td><?php echo $row['title'] ?></td>
                                                <td><?php echo $row['publication_year'] ?></td>
                                                <td><?php echo $row['author'] ?></td>
                                                <td><?php echo $row['isbn'] ?></td>
                                                <td><?php echo $row['cat_name'] ?></td>
                                                <td><?php echo $row['shelf_no'] ?></td>
                                                <td>
                                                    <?php
                                                    if ($row['status'] == 1)
                                                        echo '<span class="badge text-bg-success">Available</span>';
                                                    else echo '<span class="badge text-bg-danger">Not available</span>';

                                                    ?>
                                                </td>

                                            <?php } ?>
                                            </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--Main content end-->
    <?php include_once(DIR_URL . "include/footer.php") ?>