<?php
// Simple QR code test page
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "models/loan.php");

$test_booking_id = 123; // Test booking ID
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Test - LMS</title>
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .qr-test {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .qr-image {
            max-width: 300px;
            margin: 20px auto;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1>QR Code Generation Test</h1>
            </div>
            <div class="card-body">
                <h3>Testing QR Code Generation for Booking ID: <?php echo $test_booking_id; ?></h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="qr-test">
                            <h4>Direct API Call</h4>
                            <p>Using generateBookingQRCode() function:</p>
                            <?php
                            $qr_url = generateBookingQRCode($test_booking_id, 'url', 300);
                            if ($qr_url) {
                                echo '<img src="' . htmlspecialchars($qr_url) . '" alt="QR Code" class="img-fluid qr-image">';
                                echo '<p><strong>URL:</strong> ' . htmlspecialchars($qr_url) . '</p>';
                            } else {
                                echo '<p class="text-danger">Failed to generate QR code URL</p>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="qr-test">
                            <h4>Download Test</h4>
                            <p>Testing download functionality:</p>
                            <?php
                            $download_url = generateBookingQRCode($test_booking_id, 'download_url', 300);
                            if ($download_url) {
                                echo '<a href="' . htmlspecialchars($download_url) . '" class="btn btn-primary" download="test-booking-<?php echo $test_booking_id; ?>.png">';
                                echo '<i class="fas fa-download me-2"></i>Download QR Code</a>';
                                echo '<p><strong>Download URL:</strong> ' . htmlspecialchars($download_url) . '</p>';
                            } else {
                                echo '<p class="text-danger">Failed to generate download URL</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="qr-test">
                            <h4>QR Code Data Format</h4>
                            <p><strong>Expected Format:</strong> <?php echo $test_booking_id; ?>|qr_scan=true</p>
                            <p><strong>Purpose:</strong> This format tells the system that when this QR code is scanned, it should auto-issue the book.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>student_dashboard.php" class="btn btn-secondary">Back to Student Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>loans/verify.php" class="btn btn-primary">Test QR Scanner</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>