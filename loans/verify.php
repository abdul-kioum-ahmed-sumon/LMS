<?php
// Include required files
include_once(__DIR__ . "/../config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "include/middleware.php");
include_once(DIR_URL . "models/loan.php");

// Initialize variables
$success_message = "";
$error_message = "";
$booking_id_value = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';

// Process form submission for verification
if (isset($_POST['verify']) || (isset($_GET['booking_id']) && !empty($_GET['booking_id']))) {
    $booking_id = isset($_POST['booking_id']) ? trim($_POST['booking_id']) : trim($_GET['booking_id']);

    if (!empty($booking_id) && is_numeric($booking_id)) {
        $result = verifyBooking($conn, $booking_id);

        if ($result['success']) {
            $booking = $result['booking'];
        } else {
            $error_message = $result['error'];
        }
    } else {
        $error_message = "Invalid booking ID. Please enter a valid numeric ID.";
    }
}

// Process book issuance
if (isset($_POST['issue_book'])) {
    $booking_id = trim($_POST['booking_id']);

    if (!empty($booking_id) && is_numeric($booking_id)) {
        $result = completeBookIssuance($conn, $booking_id);

        if ($result['success']) {
            $success_message = "Book issued successfully!";

            // Get updated booking info
            $result = verifyBooking($conn, $booking_id);
            if ($result['success']) {
                $booking = $result['booking'];
                // Set a flag to show success message instead of "Already Issued!"
                $just_issued = true;
            }
        } else {
            $error_message = $result['error'];
        }
    } else {
        $error_message = "Invalid booking ID for issuance.";
    }
}

// Include layout files
include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/topbar.php");
include_once(DIR_URL . "include/sidebar.php");
?>

<main class="mt-5 pt-3" style="padding: 20px">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-md-12 mb-3 mt-4">
                <h3 class="fw-bold text-uppercase">Verify Book Booking</h3>
                <p>Scan QR code or enter booking ID to verify and issue a book</p>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Left Column: Scanner & Input -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="fas fa-qrcode me-2"></i>QR Code Scanner</h5>
                    </div>
                    <div class="card-body">
                        <!-- Camera Scanner -->
                        <div id="camera-section">
                            <div id="reader" style="width: 100%; max-width: 450px; margin: 0 auto;"></div>
                            <div id="scan-result" class="mt-3 text-center"></div>

                            <!-- Tab Navigation for Scanning Options -->
                            <ul class="nav nav-pills mt-4 mb-3 justify-content-center" id="scanOptions" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="camera-tab" data-bs-toggle="pill" data-bs-target="#camera-content" type="button" role="tab">
                                        <i class="fas fa-camera me-1"></i> Camera
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="upload-tab" data-bs-toggle="pill" data-bs-target="#upload-content" type="button" role="tab">
                                        <i class="fas fa-file-image me-1"></i> Upload Image
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="manual-tab" data-bs-toggle="pill" data-bs-target="#manual-content" type="button" role="tab">
                                        <i class="fas fa-keyboard me-1"></i> Manual Entry
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="scanOptionsContent">
                                <!-- Camera Tab Content -->
                                <div class="tab-pane fade show active" id="camera-content" role="tabpanel">
                                    <div class="text-center mt-2 mb-3">
                                        <p class="text-muted">Point your camera at a QR code to scan</p>
                                    </div>
                                </div>

                                <!-- Upload Tab Content -->
                                <div class="tab-pane fade" id="upload-content" role="tabpanel">
                                    <div class="card bg-light border-0 p-3 mb-3">
                                        <div class="mb-3">
                                            <div class="custom-file-upload">
                                                <label for="qr-file" class="d-block text-center p-3 border rounded bg-white cursor-pointer" style="cursor: pointer;">
                                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-primary"></i>
                                                    <div>Click to select or drop QR code image</div>
                                                    <small class="text-muted">Supported formats: JPG, PNG, GIF, BMP</small>
                                                </label>
                                                <input type="file" class="form-control d-none" id="qr-file" accept="image/*">
                                            </div>
                                            <div id="selected-file-name" class="text-center mt-2 text-muted small"></div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="button" id="process-file-btn" class="btn btn-primary">
                                                <i class="fas fa-qrcode me-2"></i>Scan Image
                                            </button>
                                        </div>
                                    </div>
                                    <div id="file-result" class="mt-3"></div>
                                </div>

                                <!-- Manual Entry Tab Content -->
                                <div class="tab-pane fade" id="manual-content" role="tabpanel">
                                    <form method="post" id="verify-form" class="mt-3">
                                        <div class="mb-3">
                                            <label for="booking_id" class="form-label">Booking ID</label>
                                            <input type="text" class="form-control" id="booking_id" name="booking_id"
                                                value="<?php echo htmlspecialchars($booking_id_value); ?>"
                                                placeholder="Enter booking ID">
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" name="verify" class="btn btn-primary">
                                                <i class="fas fa-search me-2"></i>Verify Booking
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Back to Loans Button -->
                            <div class="text-center mt-4">
                                <a href="<?php echo BASE_URL ?>loans" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Loans
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Booking Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header <?php echo isset($booking) ? 'bg-success text-white' : 'bg-light'; ?>">
                        <h5><i class="fas fa-info-circle me-2"></i>Booking Details</h5>
                    </div>
                    <div class="card-body"><?php if (isset($booking)): ?>
                            <?php if (isset($just_issued) && $just_issued === true): ?>
                                <div class="alert alert-success">
                                    <div><i class="fas fa-check-circle me-2"></i><strong>Success!</strong></div>
                                    <p>Book has been successfully issued to the student.</p>
                                    <p class="mb-0"><small>Issued on: <?php echo date('d-m-Y H:i'); ?></small></p>
                                </div>
                            <?php elseif (isset($booking['issued_at']) && !empty($booking['issued_at'])): ?>
                                <div class="alert alert-info">
                                    <strong>Already Issued!</strong> This book was collected on
                                    <?php echo date('d-m-Y H:i', strtotime($booking['issued_at'])); ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">
                                    <strong>Verified!</strong> This is a valid booking.
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Book Details</h5>
                                    <p><strong>Title:</strong> <?php echo htmlspecialchars($booking['book_title']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Student Details</h5>
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['student_name']); ?></p>
                                    <p><strong>ID:</strong> <?php echo htmlspecialchars($booking['student_id_number']); ?></p>
                                </div>
                            </div>

                            <h5 class="mt-3">Booking Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['id']); ?></p>
                                    <p><strong>Loan Date:</strong> <?php echo date('d M Y', strtotime($booking['loan_date'])); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Return By:</strong> <?php echo date('d M Y', strtotime($booking['return_date'])); ?></p>
                                    <p><strong>Status:</strong>
                                        <span class="badge <?php echo !empty($booking['issued_at']) ? 'bg-primary' : 'bg-warning'; ?>">
                                            <?php echo !empty($booking['issued_at']) ? 'Collected' : 'Booked'; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <?php if (isset($just_issued) && $just_issued === true): ?>
                                <div class="d-grid mt-4">
                                    <a href="<?php echo BASE_URL; ?>loans" class="btn btn-success">
                                        <i class="fas fa-check-circle me-2"></i>Complete
                                    </a>
                                </div>
                            <?php elseif (!isset($booking['issued_at']) || empty($booking['issued_at'])): ?>
                                <div class="d-grid mt-4">
                                    <form method="post">
                                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['id']); ?>">
                                        <button type="submit" name="issue_book" class="btn btn-primary btn-lg">
                                            <i class="fas fa-check-circle me-2"></i>Issue Book
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="d-grid mt-4">
                                    <a href="<?php echo BASE_URL; ?>loans" class="btn btn-secondary">Back to Loans</a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-qrcode fa-5x text-muted mb-3"></i>
                                <p class="lead">Scan a QR code or enter a booking ID to view details</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Import QR scanning library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const readerElement = document.getElementById('reader');
        const scanResultElement = document.getElementById('scan-result');
        const fileResultElement = document.getElementById('file-result');
        const bookingIdInput = document.getElementById('booking_id');
        const verifyForm = document.getElementById('verify-form');
        const qrFileInput = document.getElementById('qr-file');
        const processFileBtn = document.getElementById('process-file-btn');
        const selectedFileName = document.getElementById('selected-file-name');

        // Variables
        let html5QrCode = null;
        let lastResult = null;
        let currentFileToScan = null;

        // Tab navigation listeners
        document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                if (event.target.id === 'camera-tab') {
                    if (html5QrCode && !html5QrCode.isScanning) {
                        startCameraScanner();
                    }
                } else if (event.target.id === 'upload-tab') {
                    if (html5QrCode && html5QrCode.isScanning) {
                        html5QrCode.stop().catch(err => console.error("Error stopping camera:", err));
                    }
                } else if (event.target.id === 'manual-tab') {
                    if (html5QrCode && html5QrCode.isScanning) {
                        html5QrCode.stop().catch(err => console.error("Error stopping camera:", err));
                    }
                    // Focus on manual input field
                    setTimeout(() => bookingIdInput.focus(), 300);
                }
            });
        });

        // Display selected filename
        qrFileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                selectedFileName.textContent = fileName;
                currentFileToScan = this.files[0];

                // Enable the scan button
                processFileBtn.disabled = false;
                processFileBtn.classList.remove('btn-secondary');
                processFileBtn.classList.add('btn-primary');
            } else {
                selectedFileName.textContent = '';
                currentFileToScan = null;

                // Disable the scan button
                processFileBtn.disabled = true;
                processFileBtn.classList.remove('btn-primary');
                processFileBtn.classList.add('btn-secondary');
            }
        });

        // Initial state of scan button
        processFileBtn.disabled = true;
        processFileBtn.classList.remove('btn-primary');
        processFileBtn.classList.add('btn-secondary');

        // Start the camera scanner when page loads (for initial active tab)
        startCameraScanner();

        // Helper function to process QR code result
        function processQrResult(decodedText) {
            if (decodedText !== lastResult) {
                lastResult = decodedText;

                if (/^\d+$/.test(decodedText)) {
                    bookingIdInput.value = decodedText;

                    const resultHtml = `
                        <div class="alert alert-success">
                            <strong>QR Code Detected!</strong><br>
                            Booking ID: ${decodedText}
                            <div class="mt-2">
                                <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                                <span>Verifying booking...</span>
                            </div>
                        </div>
                    `;

                    if (fileResultElement.style.display !== 'none') {
                        fileResultElement.innerHTML = resultHtml;
                    } else {
                        scanResultElement.innerHTML = resultHtml;
                    }

                    // Show feedback for longer before submitting
                    setTimeout(() => {
                        // Add the booking ID to the URL and redirect instead of form submission
                        window.location.href = `<?php echo BASE_URL; ?>loans/verify.php?booking_id=${decodedText}`;
                    }, 1500); // Increased timeout to give user more time to see the message
                } else {
                    const errorHtml = `
                        <div class="alert alert-danger">
                            <strong>Invalid QR Code</strong><br>
                            Expected a numeric booking ID, found: ${decodedText}
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary retry-scan">
                                    <i class="fas fa-redo-alt me-1"></i>Try Again
                                </button>
                            </div>
                        </div>
                    `;

                    if (fileResultElement.style.display !== 'none') {
                        fileResultElement.innerHTML = errorHtml;
                    } else {
                        scanResultElement.innerHTML = errorHtml;
                        // Add event listener for retry button
                        setTimeout(() => {
                            const retryBtn = scanResultElement.querySelector('.retry-scan');
                            if (retryBtn) {
                                retryBtn.addEventListener('click', function() {
                                    scanResultElement.innerHTML = '';
                                    lastResult = null; // Reset last result to allow scanning the same code again
                                });
                            }
                        }, 100);
                    }
                }
            }
        }

        // Initialize camera scanner
        function startCameraScanner() {
            html5QrCode = new Html5Qrcode("reader");

            scanResultElement.innerHTML = `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <div>Initializing camera...</div>
                    </div>
                </div>
            `;

            // First try to get all cameras
            Html5Qrcode.getCameras()
                .then(cameras => {
                    if (cameras && cameras.length > 0) {
                        // Prefer back camera if available (usually better for QR scanning)
                        let cameraId = cameras[cameras.length - 1].id; // Default to last camera (usually back camera)

                        // Camera selection UI for multiple cameras
                        if (cameras.length > 1) {
                            let cameraOptions = '';
                            cameras.forEach((camera, index) => {
                                const label = camera.label || `Camera ${index + 1}`;
                                const isBack = label.toLowerCase().includes('back');
                                cameraOptions += `
                                    <div class="form-check mb-2">
                                        <input class="form-check-input camera-option" type="radio" name="cameraId" 
                                            id="camera${index}" value="${camera.id}" ${isBack ? 'checked' : ''}>
                                        <label class="form-check-label" for="camera${index}">
                                            ${label}
                                        </label>
                                    </div>
                                `;
                            });

                            scanResultElement.innerHTML = `
                                <div class="alert alert-info">
                                    <strong>Multiple cameras detected</strong>
                                    <p class="mb-2">Please select which camera to use:</p>
                                    <form id="camera-select-form">
                                        ${cameraOptions}
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-camera me-1"></i>Start Scanner
                                        </button>
                                    </form>
                                </div>
                            `;

                            setTimeout(() => {
                                const cameraForm = document.getElementById('camera-select-form');
                                if (cameraForm) {
                                    cameraForm.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        const selected = document.querySelector('input[name="cameraId"]:checked');
                                        if (selected) {
                                            startScanningWithCamera(selected.value);
                                        } else {
                                            startScanningWithCamera(cameraId); // Default camera
                                        }
                                    });

                                    // Auto-select back camera if detected
                                    const backCamera = document.querySelector('input.camera-option[id*="back" i]');
                                    if (backCamera) {
                                        backCamera.checked = true;
                                    }
                                }
                            }, 100);
                        } else {
                            // Only one camera, start scanning directly
                            startScanningWithCamera(cameraId);
                        }
                    } else {
                        // No cameras found
                        handleCameraError("No cameras found on this device. Please try the file upload option.");
                    }
                })
                .catch(err => {
                    console.error("Error getting cameras: ", err);
                    // Fall back to default camera access
                    startScanningWithFallback();
                });
        }

        function startScanningWithCamera(cameraId) {
            scanResultElement.innerHTML = `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <div>Starting camera...</div>
                    </div>
                </div>
            `;

            html5QrCode.start(
                    cameraId, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        },
                        experimentalFeatures: {
                            useBarCodeDetectorIfSupported: true
                        }
                    },
                    processQrResult,
                    (errorMessage) => {
                        // Only log scanning errors, don't display to user as they're expected during scanning
                        console.log("QR Code scanning error:", errorMessage);
                    }
                )
                .then(() => {
                    scanResultElement.innerHTML = `
                    <div class="alert alert-success">
                        <div class="mb-2"><i class="fas fa-camera me-2"></i><strong>Camera ready</strong></div>
                        <div>Point your camera at a QR code to scan it</div>
                    </div>
                `;
                    setTimeout(() => {
                        if (scanResultElement.querySelector('.alert-success')) {
                            scanResultElement.innerHTML = '';
                        }
                    }, 3000);
                })
                .catch(err => {
                    console.error("Camera start error:", err);
                    handleCameraError("Could not start camera. " + err.toString());
                });
        }

        function startScanningWithFallback() {
            scanResultElement.innerHTML = `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <div>Starting camera with default settings...</div>
                    </div>
                </div>
            `;

            html5QrCode.start({
                        facingMode: "environment"
                    }, // Use environment facing camera (usually back camera)
                    {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    processQrResult,
                    (errorMessage) => {
                        console.log("QR Code scanning error:", errorMessage);
                    }
                )
                .then(() => {
                    scanResultElement.innerHTML = `
                    <div class="alert alert-success">
                        <div class="mb-2"><i class="fas fa-camera me-2"></i><strong>Camera ready</strong></div>
                        <div>Point your camera at a QR code to scan it</div>
                    </div>
                `;
                    setTimeout(() => {
                        if (scanResultElement.querySelector('.alert-success')) {
                            scanResultElement.innerHTML = '';
                        }
                    }, 3000);
                })
                .catch(err => {
                    console.error("Camera fallback error:", err);
                    handleCameraError("Could not access camera. Please try the file upload option instead.");
                });
        }

        function handleCameraError(errorMessage) {
            scanResultElement.innerHTML = `
                <div class="alert alert-danger">
                    <div class="mb-2"><i class="fas fa-exclamation-circle me-2"></i><strong>Camera Error</strong></div>
                    <div>${errorMessage}</div>
                    <div class="mt-3">
                        <button class="btn btn-outline-primary btn-sm retry-camera me-2">
                            <i class="fas fa-redo-alt me-1"></i>Try Again
                        </button>
                        <button class="btn btn-primary btn-sm switch-to-file">
                            <i class="fas fa-file-image me-1"></i>Use File Upload
                        </button>
                    </div>
                </div>
            `;

            // Add event listeners to buttons
            setTimeout(() => {
                const retryBtn = scanResultElement.querySelector('.retry-camera');
                const switchToFileBtn = scanResultElement.querySelector('.switch-to-file');

                if (retryBtn) {
                    retryBtn.addEventListener('click', function() {
                        startCameraScanner();
                    });
                }

                if (switchToFileBtn) {
                    switchToFileBtn.addEventListener('click', function() {
                        document.getElementById('upload-tab').click();
                    });
                }
            }, 100);
        }

        // Handle file scanning
        processFileBtn.addEventListener('click', function() {
            if (!qrFileInput.files || qrFileInput.files.length === 0) {
                fileResultElement.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Please select an image file first
                    </div>
                `;
                return;
            }

            const imageFile = qrFileInput.files[0];

            // Show processing state
            processFileBtn.disabled = true;
            processFileBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';

            fileResultElement.innerHTML = `
                <div class="alert alert-info">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <div>Scanning image...</div>
                    </div>
                </div>
            `;

            // Create a new html5QrCode instance for file scanning to avoid conflicts with camera
            const fileScanner = new Html5Qrcode("reader", /* verbose= */ false);

            fileScanner.scanFile(imageFile, /* showImage= */ true)
                .then(decodedText => {
                    // Reset button state
                    processFileBtn.disabled = false;
                    processFileBtn.innerHTML = '<i class="fas fa-qrcode me-2"></i>Scan Image';

                    // Process the result
                    console.log("QR Code detected! Result:", decodedText);

                    // Check if it's a valid booking ID (numeric)
                    if (/^\d+$/.test(decodedText)) {
                        // Get the numeric value
                        const bookingId = parseInt(decodedText, 10);

                        fileResultElement.innerHTML = `
                            <div class="alert alert-success">
                                <strong>QR Code Detected!</strong><br>
                                Booking ID: ${bookingId}
                                <div class="mt-2">
                                    <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                                    <span>Verifying booking...</span>
                                </div>
                            </div>
                        `;

                        // Show verification progress for a moment before redirecting
                        setTimeout(() => {
                            window.location.href = `<?php echo BASE_URL; ?>loans/verify.php?booking_id=${bookingId}`;
                        }, 1500);
                    } else {
                        // Invalid QR code content
                        fileResultElement.innerHTML = `
                            <div class="alert alert-danger">
                                <strong>Invalid QR Code</strong><br>
                                Expected a numeric booking ID, found: ${decodedText}
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary reset-upload me-2">
                                        <i class="fas fa-redo-alt me-1"></i>Try Another Image
                                    </button>
                                </div>
                            </div>
                        `;

                        // Add reset button handler
                        setTimeout(() => {
                            const resetBtn = fileResultElement.querySelector('.reset-upload');
                            if (resetBtn) {
                                resetBtn.addEventListener('click', function() {
                                    // Clear the file input
                                    qrFileInput.value = '';
                                    selectedFileName.textContent = '';
                                    fileResultElement.innerHTML = '';

                                    // Disable scan button
                                    processFileBtn.disabled = true;
                                    processFileBtn.classList.remove('btn-primary');
                                    processFileBtn.classList.add('btn-secondary');
                                });
                            }
                        }, 100);
                    }
                })
                .catch(error => {
                    // Reset button state
                    processFileBtn.disabled = false;
                    processFileBtn.innerHTML = '<i class="fas fa-qrcode me-2"></i>Scan Image';

                    console.error("Error scanning file:", error);
                    fileResultElement.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Scanning failed</strong><br>
                            No valid QR code found in the image. Please try another image or make sure the QR code is clearly visible.
                            <div class="mt-3">
                                <button class="btn btn-sm btn-outline-primary reset-upload">
                                    <i class="fas fa-redo-alt me-1"></i>Try Another Image
                                </button>
                            </div>
                        </div>
                    `;

                    // Add reset button handler
                    setTimeout(() => {
                        const resetBtn = fileResultElement.querySelector('.reset-upload');
                        if (resetBtn) {
                            resetBtn.addEventListener('click', function() {
                                // Clear the file input
                                qrFileInput.value = '';
                                selectedFileName.textContent = '';
                                fileResultElement.innerHTML = '';

                                // Focus the file input to prompt for a new selection
                                qrFileInput.click();
                            });
                        }
                    }, 100);
                });
        });

        // Enable drag and drop for file upload
        const fileDropArea = document.querySelector('.custom-file-upload label');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileDropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileDropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileDropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileDropArea.classList.add('border-primary');
        }

        function unhighlight() {
            fileDropArea.classList.remove('border-primary');
        }

        fileDropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files && files.length > 0) {
                qrFileInput.files = files;
                const event = new Event('change');
                qrFileInput.dispatchEvent(event);
            }
        }
    });
</script>

<?php include_once(DIR_URL . "include/footer.php"); ?>