<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/loan.php");

## Get Loans
if (isset($_GET['student_id'])) {
    // If student_id is passed, show only that student's loans
    $student_id = $_GET['student_id'];
    $loans = getStudentLoans($conn, $student_id);

    // Get student details
    include_once(DIR_URL . "models/student.php");
    $student_result = getStudentById($conn, $student_id);
    if ($student_result->num_rows > 0) {
        $student = mysqli_fetch_assoc($student_result);
    }
} else {
    // Show all loans
    $loans = getLoans($conn);
}

if (!isset($loans->num_rows)) {
    $_SESSION['error'] = "Error: " . $conn->error;
}

## Delete Loan
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $del = delete($conn, $_GET['id']);
    if ($del) {
        $_SESSION['success'] = "Book Issue has been deleted successfully";
    } else {
        $_SESSION['error'] = "Something went wrong";
    }
    $redirect_url = BASE_URL . "loans";
    if (isset($_GET['student_id'])) {
        $redirect_url .= "?student_id=" . $_GET['student_id'];
    }
    header("LOCATION: " . $redirect_url);
    exit;
}

## Status update of Loan
if (isset($_GET['action']) && $_GET['action'] == 'status') {
    $update = updateStatus($conn, $_GET['id'], $_GET['status']);
    if ($update) {
        if ($_GET['status'] == 1)
            $msg = "Book has been returned successfully";
        else $msg = "Book has not been returned successfully";

        $_SESSION['success'] = $msg;
    } else {
        $_SESSION['error'] = "Something went wrong";
    }
    $redirect_url = BASE_URL . "loans";
    if (isset($_GET['student_id'])) {
        $redirect_url .= "?student_id=" . $_GET['student_id'];
    }
    header("LOCATION: " . $redirect_url);
    exit;
}

include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");


?>
<!--Main content start-->
<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <!--Cards-->
        <div class="row dashboard-counts">
            <div class="col-md-12 mt-4">
                <?php include_once(DIR_URL . "include/alerts.php"); ?>
                <h3 class="fw-bold text-uppercase">
                    <?php if (isset($student)) : ?>
                        Book Bookings for <?php echo $student['name']; ?> (ID: <?php echo $student['dept_id']; ?>)
                        <a href="<?php echo BASE_URL; ?>loans" class="btn btn-primary btn-sm float-end">Show All Bookings</a>
                    <?php else : ?>
                        Manage Books Issue
                    <?php endif; ?>
                </h3>
            </div>

            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <?php echo isset($student) ? 'Student Bookings' : 'All Books Issue'; ?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-responsive table-striped" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Book Name</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Issue Date</th>
                                        <th scope="col">Return Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Issued</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($loans->num_rows > 0) {
                                        $i = 1;
                                        while ($row = $loans->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++ ?></th>
                                                <td><?php echo $row['book_title'] ?></td>
                                                <td><?php echo $row['student_name'] ?></td>
                                                <td>
                                                    <?php echo date("d-m-Y", strtotime($row['loan_date'])) ?>
                                                </td>
                                                <td><?php echo date("d-m-Y", strtotime($row['return_date'])) ?></td>

                                                <td>
                                                    <?php
                                                    if ($row['is_return'] == 1)
                                                        echo '<span class="badge text-bg-success">Returned</span>';
                                                    else echo '<span class="badge text-bg-warning">Active</span>';
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    if (isset($row['issued_at']) && !empty($row['issued_at']))
                                                        echo '<span class="badge text-bg-success">Collected</span>';
                                                    else echo '<span class="badge text-bg-info">Booked</span>';
                                                    ?>
                                                </td>

                                                <td><?php echo date("d-m-Y", strtotime($row['created_at'])) ?></td>
                                                <td>
                                                    <a href="<?php echo BASE_URL ?>loans/edit.php?id=<?php echo $row['id'] ?><?php echo isset($_GET['student_id']) ? '&student_id=' . $_GET['student_id'] : ''; ?>" class="btn btn-primary btn-sm">
                                                        Edit
                                                    </a>
                                                    <a onclick="return confirm('Are you sure?')" href="<?php echo BASE_URL ?>loans?action=delete&id=<?php echo $row['id'] ?><?php echo isset($_GET['student_id']) ? '&student_id=' . $_GET['student_id'] : ''; ?>" class="btn btn-danger btn-sm">
                                                        Delete
                                                    </a>

                                                    <?php
                                                    if (!$row['is_return']) { ?>
                                                        <a href="<?php echo BASE_URL ?>loans?action=status&id=<?php echo $row['id'] ?>&status=1<?php echo isset($_GET['student_id']) ? '&student_id=' . $_GET['student_id'] : ''; ?>" class="btn btn-success btn-sm">
                                                            Returned
                                                        </a>
                                                    <?php } ?>

                                                    <?php if (!isset($row['issued_at']) || empty($row['issued_at'])) { ?>
                                                        <a href="<?php echo BASE_URL ?>loans/verify.php?booking_id=<?php echo $row['id'] ?>" class="btn btn-info btn-sm mt-1">
                                                            Verify & Issue
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No bookings found</td>
                                        </tr>
                                    <?php } ?>
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