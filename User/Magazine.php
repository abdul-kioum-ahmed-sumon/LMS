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
    <title>Magazines | Library Management System</title>
</head>

<body>
    <!-- Student Navbar -->
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
                <h5 class="mb-0">Magazines</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Publisher</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT magazine_id, title, publisher, category, description FROM magazine ORDER BY magazine_id DESC";
                            $result = $conn->query($sql);
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['publisher']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                                    $viewUrl = 'view_magazine.php?id=' . (int)$row['magazine_id'];
                                    $download = '';
                                    if (!empty($row['description'])) {
                                        $raw = trim($row['description']);
                                        $src = $raw;
                                        if ($raw !== '') {
                                            if (!preg_match('#^https?://#i', $raw) && !(isset($raw[0]) && $raw[0] === '/')) {
                                                $src = BASE_URL . 'Magazine/' . ltrim($raw, '/');
                                            }
                                            $download = '<a class="btn btn-sm btn-outline-success ms-2" href="' . htmlspecialchars($src) . '" download>Download</a>';
                                        }
                                    }
                                    echo '<td><a class="btn btn-sm btn-outline-primary" href="' . $viewUrl . '">View</a>' . $download . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">No magazines found</td></tr>';
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