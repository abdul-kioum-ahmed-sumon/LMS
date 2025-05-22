<?php
// QR Code Sample Images for Testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sample booking IDs to generate QR codes for
$sample_ids = [123, 456, 789, 1001, 2022];

// Check if we should add real booking IDs from database
$include_real = isset($_GET['include_real']) && $_GET['include_real'] == 1;

// Get real booking IDs from database if requested
$real_bookings = [];
if ($include_real) {
    $sql = "SELECT id FROM book_loans ORDER BY id DESC LIMIT 5";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $real_bookings[] = $row['id'];
        }
    }
}

// Combine sample and real IDs
$all_ids = array_merge($sample_ids, $real_bookings);

// Function to get file extension from mime type
function getExtensionFromMimeType($mime_type)
{
    $extensions = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    return $extensions[$mime_type] ?? 'png';
}

// Generate a downloadable QR code image
if (isset($_GET['download'])) {
    // Accept any booking ID for download (removed the restrictive validation)
    $booking_id = $_GET['download'];
    $size = isset($_GET['size']) ? intval($_GET['size']) : 300;

    // Get QR code image from external service
    $url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$booking_id}";
    $image_data = file_get_contents($url);

    if ($image_data !== false) {
        // Get the mime type of the image
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer($image_data);
        $extension = getExtensionFromMimeType($mime_type);

        // Set headers for download
        header("Content-Type: {$mime_type}");
        header("Content-Disposition: attachment; filename=\"booking-{$booking_id}.{$extension}\"");
        header("Content-Length: " . strlen($image_data));

        // Output image data
        echo $image_data;
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Samples - LMS</title>
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css" />
    <style>
        .qr-sample {
            text-align: center;
            margin-bottom: 30px;
        }

        .qr-sample img {
            max-width: 200px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
            padding: 10px;
            background-color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-4 mb-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">QR Code Sample Images</h1>
            </div>
            <div class="card-body">
                <p>These sample QR codes can be used to test the image scanning functionality. Click on any image to download it.</p>

                <div class="mb-4">
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?include_real=1" class="btn btn-outline-primary">Include Real Booking IDs</a>
                    <a href="<?php echo BASE_URL; ?>qr_test.php" class="btn btn-outline-secondary">Go to QR Test Page</a>
                    <a href="<?php echo BASE_URL; ?>loans/verify.php" class="btn btn-outline-success">Go to Verify Page</a>
                </div>

                <h3>Sample QR Codes</h3>
                <p class="text-muted">Click on any image to download it for testing.</p>

                <div class="row">
                    <?php foreach ($all_ids as $id): ?>
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="qr-sample">
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?download=<?php echo $id; ?>&size=300" download>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo $id; ?>"
                                        alt="QR Code for ID <?php echo $id; ?>" class="img-fluid">
                                    <div>Booking ID: <?php echo $id; ?></div>
                                    <small class="text-muted">Click to download</small>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <h3 class="mt-4">Additional Test Cases</h3>
                <div class="row">
                    <!-- Text QR Code -->
                    <div class="col-md-3 col-sm-4 col-6">
                        <div class="qr-sample">
                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=TestBooking123&download=1">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TestBooking123"
                                    alt="Text QR Code" class="img-fluid">
                                <div>Text Content</div>
                                <small class="text-muted">Should be rejected</small>
                            </a>
                        </div>
                    </div>

                    <!-- URL QR Code -->
                    <div class="col-md-3 col-sm-4 col-6">
                        <div class="qr-sample">
                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://example.com&download=1">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://example.com"
                                    alt="URL QR Code" class="img-fluid">
                                <div>URL Content</div>
                                <small class="text-muted">Should be rejected</small>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h3>How to Use These QR Codes</h3>
                    <ol>
                        <li>Click on any QR code above to download the image file</li>
                        <li>Go to the <a href="<?php echo BASE_URL; ?>loans/verify.php">Verify Page</a> and click on the "Upload Image" tab</li>
                        <li>Upload the downloaded QR code image</li>
                        <li>Click the "Scan QR Code Image" button to process the image</li>
                        <li>If the QR code contains a valid booking ID, you will be redirected to the booking details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>

</html>