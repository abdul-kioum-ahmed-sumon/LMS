<?php
include_once(__DIR__ . "/../config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/book.php");

// Add Book Functionality
if (isset($_POST['publish'])) {
    $res = storeBook($conn, $_POST);
    if (isset($res['success'])) {
        $_SESSION['success'] = "Book has been created successfully";
        header("LOCATION: " . BASE_URL . "books");
        exit;
    } else {
        $_SESSION['error'] = $res['error']; //"Something went wrong, please try again. ";
        //header("LOCATION: " . BASE_URL . "books/add.php");
    }
}
?>
<?php
include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");
?>
<!--Main content start-->
<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <!--Cards-->
        <div class="row">
            <div class="col-md-12 mt-4">
                <?php include_once(DIR_URL . "include/alerts.php"); ?>

                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL ?>books/index.php">Books Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Book</li>
                    </ol>
                </nav>

                <h3 class="fw-bold text-uppercase">Add Book</h3>
            </div>

            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        Fill the form
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo BASE_URL ?>books/add.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Book Title</label>
                                        <input type="text" name="title" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ISBN Number</label>
                                        <input type="text" name="isbn" class="form-control" required="required" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Author Name</label>
                                        <input type="text" name="author" class="form-control" required />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Publication Year</label>
                                        <input type="number" name="publication_year" class="form-control" required />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Shelf Number</label>
                                        <input type="text" name="shelf_number" class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <?php
                                        $cats = getCategories($conn);
                                        ?>
                                        <select name="category_id" class="form-control" required>
                                            <option value="">Please select</option>
                                            <?php while ($row = $cats->fetch_assoc()) { ?>
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button name="publish" type="submit" class="btn btn-success btn1">
                                        ADD Book
                                    </button>

                                    <button type="reset" class="btn btn-secondary btn1">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--Main content end-->

<?php include_once(DIR_URL . "include/footer.php") ?>