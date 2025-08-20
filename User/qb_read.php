<?php
include_once(__DIR__ . "/../config/config.php");
include_once(DIR_URL . "config/database.php");

// Database connection
$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" crossorigin="anonymous"></script>
  <title>Question Bank | Library Management System</title>
</head>

<body>
  <!-- Student Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="<?php echo BASE_URL; ?>student_dashboard.php">
        <img src="<?php echo BASE_URL; ?>assets/images/BAUST_LOGO.png" alt="BAUST Logo" height="40" class="me-2">
        Library Management System
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>student_dashboard.php">
              <i class="fas fa-home me-1"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>User/index.php">
              <i class="fas fa-user me-1"></i> User Panel
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-4">
    <!-- Student Resources Top Row -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>User/Magazine.php" class="btn btn-outline-secondary w-100">
          <i class="fas fa-newspaper me-2"></i> Magazine
        </a>
      </div>
      <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>User/qb_read.php" class="btn btn-outline-secondary w-100 active">
          <i class="fas fa-question-circle me-2"></i> Question Bank
        </a>
      </div>
      <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>User/notice.php" class="btn btn-outline-secondary w-100">
          <i class="fas fa-bullhorn me-2"></i> Notice Board
        </a>
      </div>
      <div class="col-md-3">
        <a href="<?php echo BASE_URL; ?>User/faq.php" class="btn btn-outline-secondary w-100">
          <i class="fas fa-circle-question me-2"></i> FAQ
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Question Bank</h2>
        </div>

        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-book me-2"></i>Previous Year Questions</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="questionTable">
                <thead class="table-dark">
                  <tr>
                    <th>Department</th>
                    <th>Course Code</th>
                    <th>Course</th>
                    <th>Subject</th>
                    <th>Year</th>
                    <th>Level</th>
                    <th>Term</th>
                    <th>PDF File</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT department, course_code, course, subject, year, level, term, file_path FROM previous_questions ORDER BY id DESC";
                  $result = $conn->query($sql);
                  if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo '<tr>';
                      echo '<td>' . (isset($row['department']) ? htmlspecialchars($row['department']) : 'N/A') . '</td>';
                      echo '<td>' . (isset($row['course_code']) ? htmlspecialchars($row['course_code']) : 'N/A') . '</td>';
                      echo '<td>' . htmlspecialchars($row['course']) . '</td>';
                      echo '<td>' . htmlspecialchars($row['subject']) . '</td>';
                      echo '<td>' . htmlspecialchars($row['year']) . '</td>';
                      echo '<td>' . (isset($row['level']) ? htmlspecialchars($row['level']) : 'N/A') . '</td>';
                      echo '<td>' . (isset($row['term']) ? htmlspecialchars($row['term']) : 'N/A') . '</td>';

                      // PDF file column
                      if (!empty($row['file_path']) && file_exists($row['file_path'])) {
                        echo '<td><a href="' . $row['file_path'] . '" target="_blank" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-file-pdf"></i> Download PDF
                                                      </a></td>';
                      } else {
                        echo '<td><span class="text-muted">No PDF</span></td>';
                      }

                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="8" class="text-center">No questions found</td></tr>';
                  }
                  $conn->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo BASE_URL; ?>assets/js/jquery-3.5.1.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/dataTables.bootstrap5.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#questionTable').DataTable({
        "pageLength": 25,
        "order": [
          [4, "desc"]
        ],
        "language": {
          "search": "Search questions:",
          "lengthMenu": "Show _MENU_ questions per page",
          "info": "Showing _START_ to _END_ of _TOTAL_ questions"
        }
      });
    });
  </script>
</body>

</html>