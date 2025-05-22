<?php
// Simple QR code display page
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/loan.php");

// Get booking ID from GET parameter
$booking_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if valid booking ID
if ($booking_id <= 0) {
    echo "Invalid booking ID";
    exit;
}

// Verify the booking exists
$result = verifyBooking($conn, $booking_id);
$valid = isset($result['success']) && $result['success'];

// Optional: Get the book title if booking is valid
$book_title = '';
if ($valid) {
    $book_title = $result['booking']['book_title'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking QR Code</title>
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .qr-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .qr-header {
            background-color: #198754;
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .qr-image {
            text-align: center;
            margin: 20px 0;
        }

        .qr-image img {
            max-width: 100%;
            height: auto;
            border: 1px solid #eee;
            padding: 5px;
            background-color: white;
        }

        .qr-details {
            text-align: center;
            margin: 20px 0;
        }

        .booking-id {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .book-title {
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="qr-container">
            <div class="qr-header">
                <h2 class="text-center mb-0">Your Booking QR Code</h2>
            </div>

            <?php if ($valid): ?>
                <div class="alert alert-success text-center">
                    Show this QR code at the library to collect your book
                </div>

                <div class="qr-image">
                    <!-- Direct QR code image from API - most reliable method -->
                    <img src="<?php echo generateBookingQRCode($booking_id, 'url', 300); ?>"
                        alt="Booking QR Code"
                        class="img-fluid">
                </div>

                <div class="qr-details">
                    <div class="booking-id">Booking ID: <?php echo $booking_id; ?></div>
                    <?php if (!empty($book_title)): ?>
                        <div class="book-title"><?php echo htmlspecialchars($book_title); ?></div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo generateBookingQRCode($booking_id, 'download_url', 500); ?>" class="btn btn-primary" download="booking-<?php echo $booking_id; ?>.png">
                        <i class="fas fa-download"></i> Download QR Code
                    </a>
                    <a href="<?php echo BASE_URL; ?>student_dashboard.php" class="btn btn-secondary">
                        Back to Dashboard
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    Invalid booking ID or booking has already been completed
                </div>
                <div class="text-center mt-4">
                    <a href="<?php echo BASE_URL; ?>student_dashboard.php" class="btn btn-secondary">
                        Back to Dashboard
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>