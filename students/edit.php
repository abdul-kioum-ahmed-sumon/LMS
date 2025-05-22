<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/student.php");

// Update Functionality
if (isset($_POST['submit'])) {
    $res = update($conn, $_POST);
    if (isset($res['success'])) {
        $_SESSION['success'] = "Student has been updated successfully";
        header("LOCATION: " . BASE_URL . "students");
        exit;
    } else {
        $_SESSION['error'] = $res['error'];
        header("LOCATION: " . BASE_URL . "students/edit.php");
        exit;
    }
}

// Read get parameter to get data
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $student = getStudentById($conn, $_GET['id']);
    if ($student->num_rows > 0) {
        $student = mysqli_fetch_assoc($student);
    }
} else {
    header("LOCATION: " . BASE_URL . "students");
    exit;
}

// Get departments for dropdown
$sql = "SELECT DISTINCT name FROM categories";
$result = $conn->query($sql);
$departments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['name'];
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
                <h4 class="fw-bold text-uppercase">Edit Student</h4>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        Fill the form
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo BASE_URL ?>students/edit.php">
                            <input type="hidden" name="id" value="<?php echo $student['id'] ?>" />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php echo $student['name'] ?>" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ID Number</label>
                                        <input type="text" class="form-control" name="dept_id" value="<?php echo $student['dept_id'] ?>" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select class="form-select" name="dept">
                                            <option value="">Select Department</option>
                                            <?php foreach ($departments as $dept) : ?>
                                                <option value="<?php echo $dept; ?>" <?php echo ($student['dept'] == $dept) ? 'selected' : ''; ?>><?php echo $dept; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo $student['email'] ?>" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Reset Password (Leave empty to keep current password)</label>
                                        <input type="password" class="form-control" name="new_password" />
                                        <small class="form-text text-muted">Enter a new password only if you want to change it</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone No</label>
                                        <input type="text" class="form-control" name="phone_no" value="<?php echo $student['phone_no'] ?>" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="address" value="<?php echo $student['address'] ?>" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Account Status</label>
                                        <select class="form-select" name="status">
                                            <option value="1" <?php echo ($student['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                                            <option value="0" <?php echo ($student['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">ID Verification</label>
                                        <select class="form-select" name="verified">
                                            <option value="1" <?php echo (isset($student['verified']) && $student['verified'] == 1) ? 'selected' : ''; ?>>Verified</option>
                                            <option value="0" <?php echo (!isset($student['verified']) || $student['verified'] == 0) ? 'selected' : ''; ?>>Not Verified</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" name="submit" class="btn btn-success btn1">
                                        Save
                                    </button>

                                    <a href="<?php echo BASE_URL ?>students" class="btn btn-secondary btn1">
                                        Cancel
                                    </a>
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