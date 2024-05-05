<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/dashboard.php");

$counts = getCounts($conn);
$tabs = getTabData($conn);

include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");
?>

<!--Main Container Start-->
<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid ">
        <div class="row">
            <div class="col-md-12 mt-4 d-flex align-items-center">
                <h3><span class="fw-bold text-uppercase">Dashboard</span></h3>
                <a href="<?php echo BASE_URL ?>reporting.php" class="btn btn-success  btn1 ms-auto ">Generate Report
                </a>
            </div>
            <p>Statistics of the system!</p>

            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase text-muted">
                            Total Books
                        </h6>
                        <p class="h1 fw-bold"><?php echo $counts['total_books'] ?></p>
                        <a href="<?php echo BASE_URL ?>books" class="card-link link-underline-light">View more
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase text-muted">
                            Total Students
                        </h6>
                        <p class="h1 fw-bold"><?php echo $counts['total_students'] ?></p>
                        <a href="<?php echo BASE_URL ?>students" class="card-link link-underline-light">View more</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase text-muted">
                            Total Revenue
                        </h6>
                        <p class="h2 fw-bold">
                            <i class="fa-solid fa-bangladeshi-taka-sign "></i> <?php echo number_format($counts['total_revenue']) ?>
                        </p>
                        </p>
                        <a href="<?php echo BASE_URL ?>subscriptions/purchase-history.php" class="card-link link-underline-light">View more
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase text-muted">
                            Total Books Issue
                        </h6>
                        <p class="h1 fw-bold"><?php echo $counts['total_loans'] ?></p>
                        <a href="<?php echo BASE_URL ?>loans" class="card-link link-underline-light">View more</a>
                    </div>
                </div>
            </div>
        </div>
        <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lms";


        $con = new mysqli($servername, $username, $password, $dbname);

        $cse_result = mysqli_query($con, "SELECT COUNT(id) as cse FROM students WHERE dept = 'CSE'");
        $cse_row = mysqli_fetch_assoc($cse_result);
        $cse_count = $cse_row['cse'];

        $eee_result = mysqli_query($con, "SELECT COUNT(id) as eee FROM students WHERE dept = 'EEE'");
        $eee_row = mysqli_fetch_assoc($eee_result);
        $eee_count = $eee_row['eee'];

        $bba_result = mysqli_query($con, "SELECT COUNT(id) as bba FROM students WHERE dept = 'BBA'");
        $bba_row = mysqli_fetch_assoc($bba_result);
        $bba_count = $bba_row['bba'];

        $ipe_result = mysqli_query($con, "SELECT COUNT(id) as ipe FROM students WHERE dept = 'IPE'");
        $ipe_row = mysqli_fetch_assoc($ipe_result);
        $ipe_count = $ipe_row['ipe'];

        $CE_result = mysqli_query($con, "SELECT COUNT(id) as CE FROM students WHERE dept = 'CE'");
        $CE_row = mysqli_fetch_assoc($CE_result);
        $CE_count = $CE_row['CE'];

        $me_result = mysqli_query($con, "SELECT COUNT(id) as me FROM students WHERE dept = 'ME'");
        $me_row = mysqli_fetch_assoc($me_result);
        $me_count = $me_row['me'];

        $total_result = mysqli_query($con, "SELECT COUNT(id) as total FROM students");
        $total_row = mysqli_fetch_assoc($total_result);
        $total_count = $total_row['total'];
        ?>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {
                packages: ["corechart"]
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Department', '<?php echo $total_count; ?>'],
                    ['CSE', <?php echo $cse_count; ?>],
                    ['EEE', <?php echo $eee_count; ?>],
                    ['BBA', <?php echo $bba_count; ?>],
                    ['IPE', <?php echo $ipe_count; ?>],
                    ['CE', <?php echo $CE_count; ?>],
                    ['ME', <?php echo $me_count; ?>]
                ]);

                var options = {
                    title: 'Distribution of Students by Department',
                    pieHole: 0.4,
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
                chart.draw(data, options);
            }
        </script>

        <div style="display: flex; justify-content: center; align-items: center;border-radius:10px ">
            <div id="donutchart" class="card mt-5" style="width: 900px; height: 500px; border-radius:10px"></div>
        </div>


        <!--Tabs-->
        <div class="row mt-3 ">
            <div class="col-md-12 mt-4">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase active " id="new-students" data-bs-toggle="tab" data-bs-target="#new-students-pane" type="button" role="tab" aria-controls="new-students-pane" aria-selected="true">
                            New Students
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase" id="recent-loans" data-bs-toggle="tab" data-bs-target="#recent-loans-pane" type="button" role="tab" aria-controls="recent-loans-pane" aria-selected="false">
                            Recent Book Issue
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-uppercase" id="recent-subscription" data-bs-toggle="tab" data-bs-target="#recent-subscription-pane" type="button" role="tab" aria-controls="recent-subscription-pane" aria-selected="false">
                            Recent Membership
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="new-students-pane" role="tabpanel" aria-labelledby="new-students" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone No</th>
                                        <th scope="col">Registered On</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($tabs['students'] as $st) {
                                    ?>
                                        <tr>
                                            <th><?php echo $i++ ?></th>
                                            <td><?php echo $st['name'] ?></td>
                                            <td><?php echo $st['phone_no'] ?></td>
                                            <td><?php echo date("d-m-Y H:i A", strtotime($st['created_at'])) ?></td>
                                            <td>
                                                <?php
                                                if ($st['status'] == 1)
                                                    echo '<span class="badge text-bg-success">Active</span>';
                                                else echo '<span class="badge text-bg-danger">Inactive</span>';

                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="recent-loans-pane" role="tabpanel" aria-labelledby="recent-loans" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Book Name</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Issue Date</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($tabs['loans'] as $l) {
                                    ?>
                                        <tr>
                                            <th><?php echo $i++ ?></th>
                                            <td><?php echo $l['book_title'] ?></td>
                                            <td><?php echo $l['student_name'] ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($l['loan_date'])) ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($l['return_date'])) ?></td>
                                            <td>
                                                <?php
                                                if ($l['is_return'] == 1)
                                                    echo '<span class="badge text-bg-success">Returned</span>';
                                                else echo '<span class="badge text-bg-warning">Active</span>';

                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="recent-subscription-pane" role="tabpanel" aria-labelledby="recent-subscription" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-responsive table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($tabs['subscriptions'] as $s) {
                                    ?>
                                        <tr>
                                            <th><?php echo $i++ ?></th>
                                            <td><?php echo $s['student_name'] ?></td>
                                            <td>
                                                <span class="badge text-bg-info me-1"><?php echo $s['plan_name'] ?></span>
                                                <i class="fa-solid fa-bangladeshi-taka-sign"></i>
                                                <?php echo $s['amount'] ?>
                                            </td>
                                            <td><?php echo date("d-m-Y", strtotime($s['start_date'])) ?></td>
                                            <td><?php echo date("d-m-Y", strtotime($s['end_date'])) ?></td>
                                            <td>
                                                <?php
                                                $today = date("Y-m-d");
                                                if ($s['end_date'] >= $today)
                                                    echo '<span class="badge text-bg-success">Active</span>';
                                                else
                                                    echo  '<span class="badge text-bg-danger">Expired</span>';
                                                ?>
                                            </td>
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
<!--Main Container End-->

<?php include_once(DIR_URL . "include/footer.php") ?>