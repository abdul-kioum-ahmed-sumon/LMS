<?php
include_once(__DIR__ . "/../config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = mysqli_connect("localhost", "root", "", "lms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle messages from delete operations
$message = "";
$message_type = "";
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'deleted':
            $message = "Question deleted successfully!";
            $message_type = "success";
            break;
        case 'error':
            $message = "Error deleting question: " . (isset($_GET['error']) ? urldecode($_GET['error']) : 'Unknown error');
            $message_type = "danger";
            break;
        case 'notfound':
            $message = "Question not found!";
            $message_type = "warning";
            break;
    }
}

include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");
?>

<!--Main Container Start-->
<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-4 d-flex align-items-center">
                <h3><span class="fw-bold text-uppercase">Question Bank</span></h3>
                <a href="add.php" class="btn btn-success btn1 ms-auto">
                    <i class="fas fa-plus me-1"></i>Add New Question
                </a>
            </div>
            <p>Manage all question bank entries!</p>

            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : ($message_type == 'warning' ? 'exclamation-triangle' : 'exclamation-triangle'); ?> me-2"></i>
                                <?php echo $message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="questionTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Department</th>
                                        <th>Course Code</th>
                                        <th>Course</th>
                                        <th>Subject</th>
                                        <th>Year</th>
                                        <th>Level</th>
                                        <th>Term</th>
                                        <th>PDF File</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get all questions
                                    $sql = "SELECT * FROM previous_questions ORDER BY id DESC";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . (isset($row['department']) ? htmlspecialchars($row['department']) : 'N/A') . "</td>";
                                            echo "<td>" . (isset($row['course_code']) ? htmlspecialchars($row['course_code']) : 'N/A') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                                            echo "<td>" . $row['year'] . "</td>";
                                            echo "<td>" . (isset($row['level']) ? htmlspecialchars($row['level']) : 'N/A') . "</td>";
                                            echo "<td>" . (isset($row['term']) ? htmlspecialchars($row['term']) : 'N/A') . "</td>";

                                            // PDF file column
                                            if (!empty($row['file_path']) && file_exists($row['file_path'])) {
                                                echo "<td><a href='" . $row['file_path'] . "' target='_blank' class='btn btn-sm btn-danger'>
                                                        <i class='fas fa-file-pdf'></i> View PDF
                                                      </a></td>";
                                            } else {
                                                echo "<td><span class='text-muted'>No PDF</span></td>";
                                            }

                                            echo "<td>
                                                    <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>
                                                        <i class='fas fa-edit'></i> Edit
                                                    </a>
                                                    <a href='delete.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' 
                                                       onclick='return confirm(\"Are you sure you want to delete this question?\")'>
                                                        <i class='fas fa-trash'></i> Delete
                                                    </a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-center'>No questions found</td></tr>";
                                    }

                                    mysqli_close($conn);
                                    ?>
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

<script>
    $(document).ready(function() {
        $('#questionTable').DataTable({
            "pageLength": 25,
            "order": [
                [0, "desc"]
            ],
            "language": {
                "search": "Search questions:",
                "lengthMenu": "Show _MENU_ questions per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ questions"
            }
        });
    });
</script>

<?php include_once(DIR_URL . "include/footer.php"); ?>