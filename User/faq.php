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
  <title>FAQ | Library Management System</title>
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
        <h5 class="mb-0">Frequently Asked Questions</h5>
      </div>
      <div class="card-body">
        <?php
        $faqHtml = '';
        try {
          $faqResult = $conn->query("SELECT question, answer FROM faq ORDER BY id DESC");
          if ($faqResult && $faqResult->num_rows > 0) {
            while ($row = $faqResult->fetch_assoc()) {
              $faqHtml .= '<div class="mb-3">';
              $faqHtml .= '<h6 class="fw-bold">' . htmlspecialchars($row['question']) . '</h6>';
              $faqHtml .= '<p class="mb-0">' . nl2br(htmlspecialchars($row['answer'])) . '</p>';
              $faqHtml .= '</div>';
            }
          }
        } catch (Exception $e) {
          // ignore, fallback below
        }

        if ($faqHtml === '') {
          // Fallback to admin reader if direct query returned nothing
          echo '<div class="alert alert-info">No FAQs available right now.</div>';
          echo '<div><a class="btn btn-outline-primary btn-sm" href="' . BASE_URL . 'FAQ/read_faq.php" target="_blank">View Admin FAQ List</a></div>';
        } else {
          echo $faqHtml;
        }
        ?>
      </div>
    </div>
  </div>
  <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>