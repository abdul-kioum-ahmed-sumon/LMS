<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/auth.php");
$user = $_SESSION['user'];

// Change password functionality
if (isset($_POST['password_submit'])) {
    $res = changePassword($conn, $_POST);
    if ($res['status'] == true) {
        $_SESSION['success'] = $res['message'];
        header("LOCATION: " . BASE_URL . 'change_pass.php');
        exit;
    } else {
        $_SESSION['error'] = $res['message'];
        header("LOCATION: " . BASE_URL . 'change_pass.php');
        exit;
    }
}


include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");

?>

<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <!--Cards-->
        <div class="row">
            <div class="col-md-12 mt-4">
                <h3 class="fw-bold text-uppercase">Change Password</h3>
                <?php include_once(DIR_URL . "include/alerts.php"); ?>
            </div>
            <div class="col-md-12 mt-4">
                <div class="card" style="min-height:457px;">
                    <div class="card-header">
                        Change Password
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo BASE_URL ?>change_pass.php">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" required name="current_pass" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" required name="new_pass" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" required name="conf_pass" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" name="password_submit" class="btn btn-success btn1">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</main>

<?php include_once(DIR_URL . "include/footer.php") ?>