<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/loan.php");

// Add Functionality
if (isset($_POST['submit'])) {
    $res = create($conn, $_POST);
    if (isset($res['success'])) {
        $_SESSION['success'] = "Book Issue has been created successfully";
        header("LOCATION: " . BASE_URL . "loans");
        exit;
    } else {
        $_SESSION['error'] = $res['error'];
        header("LOCATION: " . BASE_URL . "loans/add.php");
        exit;
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
                <h3 class="fw-bold text-uppercase ">Book Issue</h3>
            </div>

            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        Fill the form
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo BASE_URL ?>loans/add.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Select Book</label>
                                        <?php
                                        $books = getAvailableBooksForLoan($conn);
                                        ?>
                                        <select name="book_id" class="form-control">
                                            <option value="">Please select</option>
                                            <?php while ($row = $books->fetch_assoc()) { ?>
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['isbn'] ?><span class="text-muted">&nbsp;&nbsp;&nbsp;---&nbsp;&nbsp;&nbsp;</span><?php echo $row['title'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Select Student</label>
                                        <?php
                                        $students = getLoanableStudents($conn);
                                        ?>
                                        <select name="student_id" class="form-control">
                                            <option value="">Please select</option>
                                            <?php while ($row = $students->fetch_assoc()) { ?>
                                                <option value="<?php echo $row['id'] ?>"><?php echo $row['dept_id'] ?> <span class="text-muted">&nbsp;&nbsp;&nbsp;---&nbsp;&nbsp;&nbsp;</span> <?php echo $row['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>



                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Loan Date</label>
                                        <input type="date" class="form-control" name="loan_date" required />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Return/Due Date</label>
                                        <input type="date" class="form-control" name="return_date" required />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" name="submit" class="btn btn-success btn1">
                                        Save
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