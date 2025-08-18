<?php
include_once(__DIR__ . "/../config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/magazine.php");

## Get Magazines
$magazines = getMagazines($conn);
if (!$magazines) {
    $_SESSION['error'] = "Error: " . $conn->error;
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
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold text-uppercase">Manage Magazines</h3>
                    <a href="<?php echo BASE_URL ?>Magazine/add_magazine.php" class="btn btn-success">
                        <i class="fa-solid fa-plus me-2"></i> Add New Magazine
                    </a>
                </div>
            </div>

            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        All Magazines
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-responsive table-striped" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Publisher</th>
                                        <th scope="col">Publication Date</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Language</th>
                                        <th scope="col">ISSN/ISBN</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($magazines && $magazines->num_rows > 0) {
                                        $i = 1;
                                        while ($row = $magazines->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++ ?></th>
                                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                                                <td><?php echo htmlspecialchars($row['publication_date']); ?></td>
                                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                                <td><?php echo htmlspecialchars($row['language']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ISSN_or_ISBN']); ?></td>
                                                <td>
                                                    <a href="<?php echo BASE_URL ?>Magazine/UpdateMagazine.php?id=<?php echo $row['magazine_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                                    <a href="<?php echo BASE_URL ?>Magazine/view_magazine.php?id=<?php echo $row['magazine_id'] ?>" class="btn btn-info btn-sm">View</a>
                                                    <a onclick="return confirm('Are you sure?')" href="<?php echo BASE_URL ?>Magazine/DeleteMagazine.php?id=<?php echo $row['magazine_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No magazines found</td>
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