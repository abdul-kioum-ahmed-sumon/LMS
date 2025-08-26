<!--Student Top Navbar Start-->
<nav class=" navbar navbar-expand-lg navbar-dark bg-dark fixed-top " style="background-image: radial-gradient(circle, #051937, #00172b, #00141e, #000d11, #010202);">
    <div class="container-fluid bg-hess ">
        <!--offcanvar trigger start-->
        <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!--offcanvar trigger start-->
        <p class="m-0"><a href="<?php echo BASE_URL ?>student_dashboard.php"><img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="baust" class="baust_logo  "></a></p>

        <a class="navbar-brand fw-bold text-uppercase me-auto" href="<?php echo BASE_URL ?>student_dashboard.php"> BAUST Library</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <!-- Student Profile Dropdown (No Profile Picture) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg me-2" style="color: #b2bafb;"></i>
                        <span>Hi <?php echo isset($_SESSION['student_name']) ? htmlspecialchars($_SESSION['student_name']) : 'Student'; ?></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL ?>student_profile.php">
                                <i class="fas fa-user me-2"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL ?>change_pass.php">
                                <i class="fas fa-key me-2"></i>Change Password
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL ?>student_logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--Student Top Navbar End-->