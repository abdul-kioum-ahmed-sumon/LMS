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