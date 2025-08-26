<!--Student Offcanvas Menu start-->
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
                    <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL ?>student_dashboard.php">
                        <i class="fa-solid fa-display  me-2" style="color: #b2bafb;"></i> Dashboard</a>
                </li>
                <li class="my-0">
                    <hr />
                </li>

                <li>
                    <div class="text-secondary small fw-bold text-uppercase">
                        Library Services
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#booksSection" role="button" aria-expanded="false" aria-controls="booksSection">
                        <i class="fa-solid fa-book  me-2" style="color: #b2bafb;"></i>
                        Books
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="booksSection">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>student_dashboard.php" class="nav-link"><i class="fa-solid fa-book-open me-2" style="color: #FFD43B;"></i> Available Books</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL ?>student_dashboard.php#loans" class="nav-link"><i class="fa-solid fa-list me-2" style="color: #FFD43B;"></i> My Loans</a>
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
                        Resources
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#magazinesSection" role="button" aria-expanded="false" aria-controls="magazinesSection">
                        <i class="fas fa-newspaper me-2" style="color: #b2bafb;"></i>
                        Magazines
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="magazinesSection">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>User/Magazine.php" class="nav-link"><i class="fa-solid fa-eye me-2" style="color: #FFD43B;"></i> View All</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL ?>User/view_magazine.php" class="nav-link"><i class="fa-solid fa-download me-2" style="color: #FFD43B;"></i> Downloads</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#questionBankSection" role="button" aria-expanded="false" aria-controls="questionBankSection">
                        <i class="fas fa-question-circle me-2" style="color: #b2bafb;"></i>
                        Question Bank
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="questionBankSection">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>Question_bank/student_view.php" class="nav-link"><i class="fa-solid fa-eye me-2" style="color: #FFD43B;"></i> View All</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE_URL ?>Question_bank/qb_read.php" class="nav-link"><i class="fa-solid fa-download me-2" style="color: #FFD43B;"></i> Downloads</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#noticeSection" role="button" aria-expanded="false" aria-controls="noticeSection">
                        <i class="fa-solid fa-bullhorn me-2" style="color: #b2bafb;"></i>
                        Notice Board
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="noticeSection">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>User/notice.php" class="nav-link"><i class="fa-solid fa-eye me-2" style="color: #FFD43B;"></i> View All</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link sidebar-link" data-bs-toggle="collapse" href="#faqSection" role="button" aria-expanded="false" aria-controls="faqSection">
                        <i class="fa-solid fa-circle-question me-2" style="color: #b2bafb;"></i>
                        FAQ
                        <span class="right-icon float-end"><i class="fas fa-chevron-down"></i></span>
                    </a>

                    <div class="collapse" id="faqSection">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo BASE_URL ?>User/faq.php" class="nav-link"><i class="fa-solid fa-eye me-2" style="color: #FFD43B;"></i> View All</a>
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
                        Account
                    </div>
                </li>

                <li class="nav-item">
                    <a href="<?php echo BASE_URL ?>student_profile.php" class="nav-link"><i class="fa-solid fa-user me-2" style="color: #b2bafb;"></i> My Profile</a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL ?>student_logout.php" class="nav-link"><i class="fa-solid fa-right-from-bracket me-2" style="color: #b2bafb;"></i> Logout</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!--Student Offcanvas Menu end-->
