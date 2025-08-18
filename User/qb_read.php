<?php
include_once(__DIR__ . "/../config/config.php");
include_once(DIR_URL . "config/database.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css" />
  <link href="<?php echo BASE_URL; ?>assets/images/BAUST_LOGO.png" rel="icon">
  <title>Question Bank | Library Management System</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="<?php echo BASE_URL; ?>student_dashboard.php">
        <img src="<?php echo BASE_URL; ?>assets/images/BAUST_LOGO.png" alt="BAUST Logo" height="40" class="me-2">
        Library Management System
      </a>
    </div>
  </nav>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Question Bank</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Course</th>
                <th>Subject</th>
                <th>Year</th>
                <th>Semester</th>
                <th>Question</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT course, subject, year, semester, question FROM previous_questions ORDER BY id DESC";
              $result = $conn->query($sql);
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo '<tr>';
                  echo '<td>' . htmlspecialchars($row['course']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['subject']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['year']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['semester']) . '</td>';
                  echo '<td>' . nl2br(htmlspecialchars($row['question'])) . '</td>';
                  echo '</tr>';
                }
              } else {
                echo '<tr><td colspan="5" class="text-center">No questions found</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>