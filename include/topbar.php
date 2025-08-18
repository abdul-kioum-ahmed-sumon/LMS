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
                    <input type="text" class="form-control" placeholder="Search" aria-describedby="button-addon2" />
                    <button class="btn btn-outline-secondary bg-primary text-white" type="button" id="button-addon2">
                        <i class="fa-solid fa-magnifying-glass"></i></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                <?php
                // Lightweight notifications for admins
                include_once(DIR_URL . 'models/dashboard.php');
                $notifs = getAdminNotifications($conn);
                $pendingStudents = ($notifs['pending_students'] && $notifs['pending_students']->num_rows) ? $notifs['pending_students']->num_rows : 0;
                $pendingLoans = ($notifs['pending_loans'] && $notifs['pending_loans']->num_rows) ? $notifs['pending_loans']->num_rows : 0;
                $totalNotifs = $pendingStudents + $pendingLoans;
                ?>
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell"></i>
                        <?php if ($totalNotifs > 0) { ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo $totalNotifs; ?></span>
                        <?php } ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
                        <li class="dropdown-header fw-bold">Notifications</li>
                        <li>
                            <h6 class="dropdown-item-text mb-1">Pending Student Approvals (<?php echo $pendingStudents; ?>)</h6>
                            <div class="px-3">
                                <?php if ($pendingStudents > 0) {
                                    while ($ps = $notifs['pending_students']->fetch_assoc()) { ?>
                                        <div class="small text-truncate">
                                            <i class="fa-solid fa-user me-1 text-secondary"></i>
                                            <a href="<?php echo BASE_URL; ?>students" class="text-decoration-none">Approve <?php echo htmlspecialchars($ps['name']); ?></a>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="small text-muted">No pending students</div>
                                <?php } ?>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <h6 class="dropdown-item-text mb-1">Pending Book Verifications (<?php echo $pendingLoans; ?>)</h6>
                            <div class="px-3">
                                <?php if ($pendingLoans > 0) {
                                    while ($pl = $notifs['pending_loans']->fetch_assoc()) { ?>
                                        <div class="small text-truncate">
                                            <i class="fa-solid fa-book-open-reader me-1 text-secondary"></i>
                                            <a href="<?php echo BASE_URL; ?>loans/verify.php?booking_id=<?php echo (int)$pl['booking_id']; ?>" class="text-decoration-none">
                                                Verify booking #<?php echo (int)$pl['booking_id']; ?> (<?php echo htmlspecialchars($pl['student_name']); ?>)
                                            </a>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="small text-muted">No pending verifications</div>
                                <?php } ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if ($_SESSION['user']['profile_pic']) { ?>
                            <img src="
                                            <?php echo BASE_URL . 'assets/uploads/' . $_SESSION['user']['profile_pic'] ?>" class="user-icon" />
                        <?php } else { ?>
                            <img src="
                                            <?php echo BASE_URL . 'assets/images/user.jpg' ?>" class="user-icon" />
                        <?php } ?>

                        Hi <?php echo $_SESSION['user']['name'] ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>my-profile.php">My Profle</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL ?>change_pass.php">Change Password</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL ?>logout.php">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--Top Navbar End-->