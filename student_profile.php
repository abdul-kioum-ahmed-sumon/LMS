<?php
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/student_auth.php");
include_once(DIR_URL . "include/student_middleware.php");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit;
}

// Get student information
$student_id = $_SESSION['student_id'];
$student = getStudentInfoById($conn, $student_id);

// Update profile functionality 
if (isset($_POST['update_profile'])) {
    $result = updateStudentProfile($conn, $student_id, $_POST);

    if (isset($result['success'])) {
        $_SESSION['success'] = "Profile updated successfully!";
        // Refresh student info
        $student = getStudentInfoById($conn, $student_id);
    } else {
        $_SESSION['error'] = $result['error'];
    }
}

include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/student_topbar.php");
include_once(DIR_URL . "include/student_sidebar.php");
?>

<!--Main Container Start-->
<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-md-12 mt-4 d-flex align-items-center">
                <h3><span class="fw-bold text-uppercase">My Profile</span></h3>
                <div class="ms-auto">
                    <span class="badge bg-primary me-2">Student ID: <?php echo htmlspecialchars($student['dept_id']); ?></span>
                    <span class="badge bg-info"><?php echo htmlspecialchars($student['dept']); ?></span>
                </div>
            </div>
        </div>

        <?php include_once(DIR_URL . "include/alerts.php"); ?>

        <!-- Profile Information Card -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user me-2"></i>Profile Information</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="student_profile.php">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone_no" value="<?php echo htmlspecialchars($student['phone_no']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['dept']); ?>" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Student ID</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['dept_id']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Registration Date</label>
                                    <input type="text" class="form-control" value="<?php echo isset($student['created_at']) ? date('d M Y', strtotime($student['created_at'])) : 'N/A'; ?>" readonly>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo BASE_URL; ?>student_dashboard.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Security Card -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Account Security</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Change Password</h5>
                                <p class="text-muted">Update your login password for security</p>
                                <a href="<?php echo BASE_URL; ?>change_pass.php" class="btn btn-warning">
                                    <i class="fas fa-key me-2"></i>Change Password
                                </a>
                            </div>
                            <div class="col-md-6">
                                <h5>Account Status</h5>
                                <p class="text-muted">Your account is currently active and verified</p>
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--Main Container End-->

<?php include_once(DIR_URL . "include/footer.php"); ?>