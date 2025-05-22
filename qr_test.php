<?php
// QR Code Test Page for diagnosing issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get test booking ID from GET parameters or use a default
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 123;

// Get test size for QR code
$size = isset($_GET['size']) ? intval($_GET['size']) : 200;

// Get option for QR code implementation
$implementation = isset($_GET['implementation']) ? $_GET['implementation'] : 'all';

// Function to get a sample booking
function getSampleBooking($conn, $booking_id = null)
{
    $sql = "SELECT l.*, b.title as book_title, s.name as student_name, s.dept_id as student_id_number
            FROM book_loans l
            JOIN books b ON b.id = l.book_id
            JOIN students s ON s.id = l.student_id
            ORDER BY l.id DESC
            LIMIT 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        // Return dummy data if no real bookings exist
        return [
            'id' => $booking_id ?: 123,
            'book_title' => 'Test Book',
            'student_name' => 'Test Student',
            'student_id_number' => 'S12345',
            'loan_date' => date('Y-m-d'),
            'return_date' => date('Y-m-d', strtotime('+14 days')),
            'created_at' => date('Y-m-d H:i:s'),
            'is_return' => 0
        ];
    }
}

// Get a sample booking
$booking = getSampleBooking($conn, $booking_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Test - LMS</title>
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/custom.css" />
    <style>
        .qr-container {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: white;
        }

        .scanner-container {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: white;
        }
    </style>
    <!-- Library 1: qrcode.js -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode.js@1.0.0/lib/qrcode.min.js"></script>
    <!-- Library 2: qrcodejs -->
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <!-- Scanner library: html5-qrcode -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>

<body>
    <div class="container mt-4 mb-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">QR Code Test Page</h1>
            </div>
            <div class="card-body">
                <p>This page tests different QR code generation methods to ensure compatibility with the scanner.</p>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Test Booking Details</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Booking ID</th>
                                <td><?php echo $booking['id']; ?></td>
                            </tr>
                            <tr>
                                <th>Book</th>
                                <td><?php echo htmlspecialchars($booking['book_title']); ?></td>
                            </tr>
                            <tr>
                                <th>Student</th>
                                <td><?php echo htmlspecialchars($booking['student_name']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>QR Code Settings</h4>
                        <form method="get" class="mb-3">
                            <div class="mb-3">
                                <label for="booking_id" class="form-label">Booking ID to encode</label>
                                <input type="number" class="form-control" id="booking_id" name="booking_id" value="<?php echo $booking['id']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="size" class="form-label">QR Code Size</label>
                                <input type="number" class="form-control" id="size" name="size" value="<?php echo $size; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Implementation</label>
                                <select class="form-select" name="implementation">
                                    <option value="all" <?php echo $implementation == 'all' ? 'selected' : ''; ?>>All</option>
                                    <option value="qrcodejs" <?php echo $implementation == 'qrcodejs' ? 'selected' : ''; ?>>QRCode.js</option>
                                    <option value="qrcode-min" <?php echo $implementation == 'qrcode-min' ? 'selected' : ''; ?>>qrcode.min.js</option>
                                    <option value="api" <?php echo $implementation == 'api' ? 'selected' : ''; ?>>QR Server API</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <?php if ($implementation == 'all' || $implementation == 'qrcodejs'): ?>
                        <div class="col-md-4">
                            <div class="qr-container text-center">
                                <h4>Method 1: QRCode.js</h4>
                                <p class="text-muted">(Used in student dashboard)</p>
                                <div id="qrcode1"></div>
                                <p class="mt-2">Content: <code><?php echo $booking['id']; ?></code></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($implementation == 'all' || $implementation == 'qrcode-min'): ?>
                        <div class="col-md-4">
                            <div class="qr-container text-center">
                                <h4>Method 2: qrcode.min.js</h4>
                                <p class="text-muted">(Alternative library)</p>
                                <div id="qrcode2"></div>
                                <p class="mt-2">Content: <code><?php echo $booking['id']; ?></code></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($implementation == 'all' || $implementation == 'api'): ?>
                        <div class="col-md-4">
                            <div class="qr-container text-center">
                                <h4>Method 3: QR Server API</h4>
                                <p class="text-muted">(Fallback approach)</p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=<?php echo $size; ?>x<?php echo $size; ?>&data=<?php echo $booking['id']; ?>"
                                    alt="QR Code for booking <?php echo $booking['id']; ?>"
                                    style="max-width: 100%;">
                                <p class="mt-2">Content: <code><?php echo $booking['id']; ?></code></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- QR Code Scanner Section -->
                <div class="scanner-container">
                    <h3>QR Code Scanner Test</h3>
                    <p>Scan any of the QR codes above to test compatibility.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Camera Scanner</h4>
                            <div id="qr-reader" style="width: 100%;"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Scan Results</h5>
                                </div>
                                <div class="card-body">
                                    <div id="qr-reader-results">
                                        <p class="text-muted">Scan a QR code to see results</p>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload Scanner -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>Image File Scanner</h5>
                                </div>
                                <div class="card-body">
                                    <p>Test scanning QR codes from image files</p>
                                    <div class="mb-3">
                                        <label for="qr-file-input" class="form-label">Upload QR Code Image</label>
                                        <input class="form-control" type="file" id="qr-file-input" accept="image/*">
                                    </div>
                                    <button type="button" class="btn btn-primary" id="file-scan-btn">Scan Image</button>
                                    <div id="file-scan-results" class="mt-3">
                                        <p class="text-muted">Upload an image to scan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>loans/verify.php" class="btn btn-primary">Go to Verify Page</a>
                    <a href="<?php echo BASE_URL; ?>student_dashboard.php" class="btn btn-secondary">Go to Student Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-dark">Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Generate QR codes using different libraries
        window.addEventListener('load', function() {
            // Method 1: Using QRCode.js (from qrcode.js library)
            <?php if ($implementation == 'all' || $implementation == 'qrcodejs'): ?>
                try {
                    new QRCode(document.getElementById("qrcode1"), {
                        text: "<?php echo $booking['id']; ?>",
                        width: <?php echo $size; ?>,
                        height: <?php echo $size; ?>,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                } catch (e) {
                    console.error("Error generating QR code with Method 1:", e);
                    document.getElementById("qrcode1").innerHTML =
                        '<div class="alert alert-danger">Failed to generate QR code</div>';
                }
            <?php endif; ?>

            // Method 2: Using qrcode.min.js
            <?php if ($implementation == 'all' || $implementation == 'qrcode-min'): ?>
                try {
                    const qrcode2 = new QRCode(document.getElementById("qrcode2"), {
                        text: "<?php echo $booking['id']; ?>",
                        width: <?php echo $size; ?>,
                        height: <?php echo $size; ?>
                    });
                } catch (e) {
                    console.error("Error generating QR code with Method 2:", e);
                    document.getElementById("qrcode2").innerHTML =
                        '<div class="alert alert-danger">Failed to generate QR code</div>';
                }
            <?php endif; ?>

            // Initialize QR scanner
            let lastResult, countResults = 0;
            let html5QrCode; // Instance for file scanning

            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    lastResult = decodedText;
                    ++countResults;

                    // Display scan result
                    const resultElement = document.getElementById('qr-reader-results');
                    if (resultElement) {
                        resultElement.innerHTML = `
                            <div class="alert alert-success">
                                <h5>Successfully scanned!</h5>
                                <p><strong>Content:</strong> ${decodedText}</p>
                                <p><strong>Scan count:</strong> ${countResults}</p>
                            </div>
                            <div class="mt-3">
                                <a href="<?php echo BASE_URL; ?>loans/verify.php?booking_id=${decodedText}" 
                                   class="btn btn-primary">Verify This Code</a>
                            </div>
                        `;
                    }
                }
            }

            function onScanFailure(error) {
                // Only log significant errors
                if (error && error.includes("camera")) {
                    console.warn(`QR scanner error: ${error}`);

                    const resultElement = document.getElementById('qr-reader-results');
                    if (resultElement) {
                        resultElement.innerHTML = `
                            <div class="alert alert-warning">
                                <strong>Scanner Issue:</strong> ${error}
                            </div>
                            <p>Try granting camera permissions or use a different browser.</p>
                        `;
                    }
                }
            }

            // Create and configure the QR scanner
            window.addEventListener('load', function() {
                // Camera scanner
                let html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        },
                        aspectRatio: 1.0,
                        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
                        rememberLastUsedCamera: true,
                        showTorchButtonIfSupported: true
                    }
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);

                // Initialize file scanning
                html5QrCode = new Html5Qrcode("qr-reader");

                // Setup the file scan button
                document.getElementById('file-scan-btn').addEventListener('click', function() {
                    const fileInput = document.getElementById('qr-file-input');
                    if (fileInput.files.length === 0) {
                        document.getElementById('file-scan-results').innerHTML =
                            `<div class="alert alert-warning">Please select an image file first</div>`;
                        return;
                    }

                    const imageFile = fileInput.files[0];

                    // Show loading indicator
                    document.getElementById('file-scan-results').innerHTML =
                        `<div class="alert alert-info">Scanning image file...</div>`;

                    // Scan the file
                    html5QrCode.scanFile(imageFile, true)
                        .then(decodedText => {
                            // Handle success
                            document.getElementById('file-scan-results').innerHTML = `
                                <div class="alert alert-success">
                                    <h5>Successfully scanned image!</h5>
                                    <p><strong>Content:</strong> ${decodedText}</p>
                                </div>
                                <div class="mt-3">
                                    <a href="<?php echo BASE_URL; ?>loans/verify.php?booking_id=${decodedText}" 
                                       class="btn btn-primary">Verify This Code</a>
                                </div>
                            `;
                        })
                        .catch(err => {
                            // Handle errors
                            console.error(`Error scanning image file: ${err}`);
                            document.getElementById('file-scan-results').innerHTML =
                                `<div class="alert alert-danger">Could not find a valid QR code in the image</div>`;
                        });
                });
            });
        });
    </script>
</body>

</html>