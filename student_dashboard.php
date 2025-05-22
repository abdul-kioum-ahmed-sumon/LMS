<?php
// Start session at the very beginning
session_start();
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/student_auth.php");
include_once(DIR_URL . "models/book.php");
include_once(DIR_URL . "models/loan.php");
include_once(DIR_URL . "include/student_middleware.php");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit;
}

// Get student information
$student_id = $_SESSION['student_id'];
$student = getStudentInfoById($conn, $student_id);

// Check if student exists
if (!$student) {
    // Student not found, log this issue
    error_log("Error: Student with ID $student_id not found in database");

    // Create a placeholder student array to prevent null reference errors
    $student = [
        'id' => $student_id,
        'name' => 'Unknown Student',
        'dept_id' => 'N/A',
        'dept' => 'Unknown',
        'email' => '',
        'phone_no' => '',
        'address' => ''
    ];
}

// Debugging - log student info
error_log("Student ID: " . $student_id . ", Student Name: " . $student['name']);

// Handle book reservation
if (isset($_POST['book_id']) && isset($_POST['reserve'])) {
    $book_id = $_POST['book_id'];
    $return_date = date('Y-m-d', strtotime('+14 days')); // Default 14 days loan period

    $result = createBookReservation($conn, $student_id, $book_id, $return_date);

    if (isset($result['success'])) {
        // Store success in session instead of variables
        $_SESSION['success_message'] = "Book reserved successfully! Your booking ID is: " . $result['booking_id'];
        $_SESSION['booking_id'] = $result['booking_id'];
    } else {
        $_SESSION['error_message'] = $result['error'];
    }

    // Redirect to prevent form resubmission (POST-Redirect-GET pattern)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Get messages from session and clear them
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
$booking_id = isset($_SESSION['booking_id']) ? $_SESSION['booking_id'] : null;

// Clear session messages
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
unset($_SESSION['booking_id']);

// Get available books
$available_books = getAvailableBooks($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode.js@1.0.0/lib/qrcode.min.js"></script>
    <title>Student Dashboard | Library Management System</title>
</head>

<body>
    <!-- Student Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="BAUST Logo" height="40" class="me-2">
                Library Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($student['name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="student_profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="student_logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Welcome<?php echo (!empty($student['name'])) ? ', ' . htmlspecialchars($student['name']) : ''; ?></h2>
                    <div>
                        <?php if (!empty($student['dept_id'])): ?>
                            <span class="badge bg-primary">Student ID: <?php echo htmlspecialchars($student['dept_id']); ?></span>
                        <?php endif; ?>

                        <?php if (!empty($student['dept'])): ?>
                            <span class="badge bg-info"><?php echo htmlspecialchars($student['dept']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php include_once(DIR_URL . "include/alerts.php"); ?>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Show QR code for new booking -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5>Your Booking QR Code</h5>
                        </div>
                        <div class="card-body text-center">
                            <p>Show this QR code at the library to collect your book</p>
                            <div class="d-flex justify-content-center mb-3">
                                <div id="booking-qrcode-<?php echo $booking_id; ?>" class="qrcode-container"></div>
                                <!-- Fallback QR code image if JavaScript fails -->
                                <img id="fallback-qr-<?php echo $booking_id; ?>"
                                    src="<?php echo generateBookingQRCode($booking_id, 'url', 200); ?>"
                                    alt="Booking QR Code"
                                    style="display: none; max-width: 200px; margin: 0 auto;"
                                    class="img-fluid">
                            </div>
                            <p class="mb-1">Booking ID: <strong><?php echo $booking_id; ?></strong></p>
                            <div class="mt-3">
                                <a href="<?php echo generateBookingQRCode($booking_id, 'download_url', 500); ?>" class="btn btn-outline-primary btn-sm" download="booking-<?php echo $booking_id; ?>.png">
                                    <i class="fas fa-download me-1"></i> Download QR Code
                                </a>
                                <a href="show_qr.php?id=<?php echo $booking_id; ?>" class="btn btn-outline-success btn-sm ms-2">
                                    <i class="fas fa-qrcode me-1"></i> View QR Code
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Available Books Section -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Available Books</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="booksTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Publication Year</th>
                                        <th>Category</th>
                                        <th>Shelf No</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($available_books->num_rows > 0) {
                                        while ($book = $available_books->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $book['title']; ?></td>
                                                <td><?php echo $book['author']; ?></td>
                                                <td><?php echo $book['publication_year']; ?></td>
                                                <td><?php echo $book['cat_name']; ?></td>
                                                <td><?php echo $book['shelf_no']; ?></td>
                                                <td>
                                                    <?php
                                                    // Check if student has already booked this book - use direct query
                                                    $check_booked_sql = "SELECT COUNT(*) as booked 
                                                                        FROM book_loans l 
                                                                        JOIN students s ON s.id = l.student_id
                                                                        WHERE l.book_id = {$book['id']} 
                                                                        AND s.email = '" . $conn->real_escape_string($student['email']) . "'
                                                                        AND l.is_return = 0";
                                                    $check_result = $conn->query($check_booked_sql);
                                                    $has_booked = false;

                                                    if ($check_result && $check_result->num_rows > 0) {
                                                        $booked_data = $check_result->fetch_assoc();
                                                        $has_booked = ($booked_data['booked'] > 0);
                                                    }

                                                    if ($has_booked) {
                                                        echo '<span class="badge bg-warning">Already Booked</span>';
                                                    } else {
                                                    ?>
                                                        <form method="post" class="d-inline">
                                                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                                            <button type="submit" name="reserve" class="btn btn-sm btn-primary">
                                                                Reserve Book
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">No books available</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Current Bookings Section -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Your Book Loans</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Use student's email to fetch book loans - more reliable than using student_id
                                $student_email = $student['email'];

                                // If we have a valid email, use it to fetch the loans
                                if (!empty($student_email)) {
                                    // Direct SQL query using email for more reliability
                                    $loans_query = "SELECT 
                                        l.id, 
                                        l.book_id, 
                                        l.loan_date,
                                        l.return_date,
                                        l.created_at,
                                        l.is_return,
                                        b.title as book_title,
                                        s.email as student_email
                                        FROM book_loans l
                                        JOIN books b ON b.id = l.book_id
                                        JOIN students s ON s.id = l.student_id
                                        WHERE s.email = '" . $conn->real_escape_string($student_email) . "' 
                                        AND l.is_return = 0
                                        ORDER BY l.created_at DESC";

                                    error_log("Executing loan query for student email: " . $student_email);
                                    $loans_result = $conn->query($loans_query);

                                    if ($loans_result && $loans_result->num_rows > 0) {
                                ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped" id="loansTable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Book</th>
                                                        <th>Loan Date</th>
                                                        <th>Return By</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($loan = $loans_result->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo $loan['id']; ?></td>
                                                            <td><?php echo htmlspecialchars($loan['book_title']); ?></td>
                                                            <td><?php echo date('d M Y', strtotime($loan['loan_date'])); ?></td>
                                                            <td><?php echo date('d M Y', strtotime($loan['return_date'])); ?></td>
                                                            <td>
                                                                <?php if ($loan['is_return'] == 1): ?>
                                                                    <span class="badge bg-success">Returned</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-warning text-dark">Active</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <script>
                                            $(document).ready(function() {
                                                $('#loansTable').DataTable({
                                                    "pageLength": 5,
                                                    "lengthMenu": [5, 10, 25],
                                                    "order": [
                                                        [0, "desc"]
                                                    ]
                                                });
                                            });
                                        </script>
                                <?php
                                    } else {
                                        // No loans found or query failed
                                        echo '<div class="alert alert-info">';
                                        echo '<h5><i class="fas fa-info-circle me-2"></i>No active book loans found</h5>';
                                        echo '<p>You don\'t have any active book loans at the moment. Browse the available books below to borrow something interesting!</p>';
                                        echo '</div>';
                                    }
                                } else {
                                    // No valid email found
                                    echo '<div class="alert alert-warning">';
                                    echo '<h5><i class="fas fa-exclamation-triangle me-2"></i>Missing Student Email</h5>';
                                    echo '<p>We cannot retrieve your book loans because your email information is missing. Please update your profile.</p>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="qrModalLabel">Your Booking QR Code</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="modal-qrcode-container" class="mb-3"></div>
                    <p class="mb-2">Booking ID: <strong id="modal-booking-id"></strong></p>
                    <p>Show this QR code at the library to collect your book</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a id="download-qr-link" href="#" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Download QR Code
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode.js@1.0.0/lib/qrcode.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#booksTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100]
            });

            // Note: The loans table is now initialized directly in the loans section
            // when the loans are displayed

            // Function to generate QR codes with fallback
            function generateQRCode(elementId, data, size = 200) {
                const container = document.getElementById(elementId);
                if (!container) return;

                container.innerHTML = ''; // Clear existing content

                try {
                    // Use reliable QR code generation
                    new QRCode(container, {
                        text: data.toString(),
                        width: size,
                        height: size,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H // High error correction level
                    });
                } catch (error) {
                    console.error("QR code generation failed:", error);
                    // Show fallback image if JavaScript QR generation fails
                    const fallbackId = elementId.replace('booking-qrcode', 'fallback-qr');
                    const fallbackImg = document.getElementById(fallbackId);
                    if (fallbackImg) {
                        fallbackImg.style.display = 'block';
                    }
                }
            }

            // Generate QR code for new booking if present
            <?php if (isset($booking_id)): ?>
                generateQRCode('booking-qrcode-<?php echo $booking_id; ?>', '<?php echo $booking_id; ?>', 200);
            <?php endif; ?>

            // Handle show QR code button clicks
            document.querySelectorAll('.show-qr-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    document.getElementById('modal-booking-id').textContent = bookingId;

                    // Set download link
                    document.getElementById('download-qr-link').href = '<?php echo BASE_URL; ?>qr_samples.php?download=' + bookingId + '&size=500';
                    document.getElementById('download-qr-link').setAttribute('download', 'booking-' + bookingId + '.png');

                    // Clear previous QR code
                    const container = document.getElementById('modal-qrcode-container');
                    if (container) {
                        container.innerHTML = '';

                        // Add direct image as fallback
                        const fallbackImg = document.createElement('img');
                        fallbackImg.src = '<?php echo BASE_URL; ?>qr_samples.php?download=' + bookingId + '&size=250';
                        fallbackImg.alt = 'Booking QR Code';
                        fallbackImg.style.display = 'none';
                        fallbackImg.className = 'img-fluid';
                        fallbackImg.id = 'modal-fallback-qr';
                        container.appendChild(fallbackImg);

                        // Generate QR with JavaScript
                        try {
                            // Generate QR code in modal
                            generateQRCode('modal-qrcode-container', bookingId, 250);
                        } catch (error) {
                            console.error("Modal QR generation failed:", error);
                            // Show fallback image
                            fallbackImg.style.display = 'block';
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>