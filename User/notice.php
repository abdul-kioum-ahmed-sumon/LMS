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
	<link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
	<link href="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" rel="icon">
	<title>Notices | BAUST Library</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="<?php echo BASE_URL ?>student_dashboard.php">
				<img src="<?php echo BASE_URL; ?>assets/images/BAUST_LOGO.png" alt="BAUST Logo" height="32" class="me-2">
				BAUST Library
			</a>
		</div>
	</nav>

	<div class="container mt-4">
		<h3 class="mb-3">Notice Board</h3>
		<div class="row">
			<div class="col-12">
				<?php
				// Read only notices for students
				$sql = "SELECT id, title, content, file_path, created_at FROM notices2 ORDER BY created_at DESC";
				$result = $conn->query($sql);
				if ($result && $result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						echo '<div class="card mb-3">';
						echo '<div class="card-body">';
						echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
						echo '<p class="card-text">' . nl2br(htmlspecialchars($row['content'])) . '</p>';
						echo '<p class="card-text"><small class="text-muted">' . date('d M Y, h:i A', strtotime($row['created_at'])) . '</small></p>';
						if (!empty($row['file_path'])) {
							$raw = trim($row['file_path']);
							$src = $raw;
							if ($raw !== '') {
								if (!preg_match('#^https?://#i', $raw) && !(isset($raw[0]) && $raw[0] === '/')) {
									$src = BASE_URL . 'NoticeBoard/' . ltrim($raw, '/');
								}
								echo '<div class="mt-2"><img alt="Notice" src="' . htmlspecialchars($src) . '" class="img-fluid" /></div>';
							}
						}
						echo '</div>';
						echo '</div>';
					}
				} else {
					echo '<div class="alert alert-info">No notices found.</div>';
				}
				?>
			</div>
		</div>
	</div>

	<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>