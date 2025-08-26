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
    // Borrow time limit from settings (fallback to 14)
    include_once(DIR_URL . 'models/settings.php');
    $borrowDays = (int) getIntSetting($conn, 'borrow_days', 14);
    $return_date = date('Y-m-d', strtotime('+' . $borrowDays . ' days'));

    $result = createBookReservation($conn, $student_id, $book_id, $return_date);

    if (isset($result['success'])) {
        // Store success in session instead of variables
        $_SESSION['success_message'] = $result['message'] ?: "Book reservation submitted successfully! Your reservation ID is: " . $result['booking_id'] . ". Please wait for admin approval.";
        $_SESSION['booking_id'] = $result['booking_id'];
        $_SESSION['reservation_status'] = 'pending';
    } else {
        $_SESSION['error_message'] = $result['error'];
    }

    // Redirect to prevent form resubmission (POST-Redirect-GET pattern)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle reservation by ISBN (choose an available copy)
if (isset($_POST['reserve_by_isbn']) && isset($_POST['reserve_isbn'])) {
    $isbn = trim($_POST['reserve_isbn']);
    $availableCopyId = findReservableBookIdByIsbn($conn, $isbn);
    if ($availableCopyId) {
        include_once(DIR_URL . 'models/settings.php');
        $borrowDays = (int) getIntSetting($conn, 'borrow_days', 14);
        $return_date = date('Y-m-d', strtotime('+' . $borrowDays . ' days'));
        $result = createBookReservation($conn, $student_id, $availableCopyId, $return_date);
        if (isset($result['success'])) {
            $_SESSION['success_message'] = $result['message'] ?: "Book reservation submitted successfully! Your reservation ID is: " . $result['booking_id'] . ". Please wait for admin approval.";
            $_SESSION['booking_id'] = $result['booking_id'];
            $_SESSION['reservation_status'] = 'pending';
        } else {
            $_SESSION['error_message'] = $result['error'];
        }
    } else {
        $_SESSION['error_message'] = 'No available copies found for this title.';
    }
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

// Get student statistics
$student_loans = getStudentBookings($conn, $student_id);
$active_loans_count = 0;
$total_loans_count = 0;
if ($student_loans && $student_loans->num_rows > 0) {
    $total_loans_count = $student_loans->num_rows;
    while ($loan = $student_loans->fetch_assoc()) {
        if ($loan['is_return'] == 0) {
            $active_loans_count++;
        }
    }
    // Reset pointer for later use
    $student_loans->data_seek(0);
}

// Get available books count
$available_books_count = 0;
if ($available_books && $available_books->num_rows > 0) {
    $available_books_count = $available_books->num_rows;
}

include_once(DIR_URL . "include/header.php");
include_once(DIR_URL . "include/student_topbar.php");
include_once(DIR_URL . "include/student_sidebar.php");
?>

<!--Main Container Start-->
<main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-md-12 mt-4 d-flex align-items-center">
                <h3><span class="fw-bold text-uppercase">Student Dashboard</span></h3>
                <div class="ms-auto">
                    <?php if (!empty($student['dept_id'])): ?>
                        <span class="badge bg-primary me-2">Student ID: <?php echo htmlspecialchars($student['dept_id']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($student['dept'])): ?>
                        <span class="badge bg-info"><?php echo htmlspecialchars($student['dept']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <p>Welcome<?php echo (!empty($student['name'])) ? ', ' . htmlspecialchars($student['name']) : ''; ?>!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-4 mt-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase text-muted">
                            Available Books
                        </h6>
                        <p class="h1 fw-bold"><?php echo $available_books_count ?></p>
                        <a href="#availableBooks" class="card-link link-underline-light">View more</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase text-muted">
                            Active Loans
                        </h6>
                        <p class="h1 fw-bold"><?php echo $active_loans_count ?></p>
                        <a href="#myLoans" class="card-link link-underline-light">View more</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase text-muted">
                            Total Loans
                        </h6>
                        <p class="h1 fw-bold"><?php echo $total_loans_count ?></p>
                        <a href="#myLoans" class="card-link link-underline-light">View more</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <h5><i class="fas fa-clock me-2"></i>Reservation Submitted</h5>
                <p><?php echo $success_message; ?></p>
                <div class="mt-3">
                    <span class="badge bg-warning text-dark">Status: Pending Admin Approval</span>
                    <span class="badge bg-primary ms-2">Reservation ID: <?php echo $booking_id; ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Show QR code for new booking -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-qrcode me-2"></i>Your Reservation QR Code</h5>
                </div>
                <div class="card-body text-center">
                    <p>Show this QR code at the library when your reservation is approved</p>
                    <div class="d-flex justify-content-center mb-3">
                        <div id="booking-qrcode-<?php echo $booking_id; ?>" class="qrcode-container"></div>
                        <!-- Fallback QR code image if JavaScript fails -->
                        <img id="fallback-qr-<?php echo $booking_id; ?>"
                            src="<?php echo generateBookingQRCode($booking_id, 'url', 200); ?>"
                            alt="Booking QR Code"
                            style="display: none; max-width: 200px; margin: 0 auto;"
                            class="img-fluid">
                    </div>
                    <p class="mb-1">Reservation ID: <strong><?php echo $booking_id; ?></strong></p>
                    <div class="mt-3">
                        <a href="<?php echo generateBookingQRCode($booking_id, 'download_url', 500); ?>" class="btn btn-outline-primary btn-sm" download="reservation-<?php echo $booking_id; ?>.png">
                            <i class="fas fa-download me-1"></i> Download QR Code
                        </a>
                        <a href="show_qr.php?id=<?php echo $booking_id; ?>" class="btn btn-outline-info btn-sm ms-2" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i> View Full Page
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Student Resources Cards -->
        <div class="row mt-4">
            <div class="col-md-12">
                <h4 class="fw-bold text-uppercase mb-3">Quick Access</h4>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Magazines</h5>
                        <p class="card-text">Access digital magazines and publications</p>
                        <a href="<?php echo BASE_URL; ?>User/Magazine.php" class="btn btn-primary">View Magazines</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Question Bank</h5>
                        <p class="card-text">Study materials and past exam questions</p>
                        <a href="<?php echo BASE_URL; ?>Question_bank/student_view.php" class="btn btn-success">View Questions</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-bullhorn fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Notice Board</h5>
                        <p class="card-text">Stay updated with latest announcements</p>
                        <a href="<?php echo BASE_URL; ?>User/notice.php" class="btn btn-warning">View Notices</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-circle-question fa-3x text-info mb-3"></i>
                        <h5 class="card-title">FAQ</h5>
                        <p class="card-text">Find answers to common questions</p>
                        <a href="<?php echo BASE_URL; ?>User/faq.php" class="btn btn-info">View FAQ</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Books Section -->
        <div class="row mt-4" id="availableBooks">
            <div class="col-md-12">
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
                                        <th>Category</th>
                                        <th>ISBN</th>
                                        <th>Available/Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once(DIR_URL . 'models/book.php');
                                    $agg = getBooksAvailabilityAggregated($conn);
                                    if ($agg && $agg->num_rows > 0) {
                                        while ($row = $agg->fetch_assoc()) {
                                            $available = (int)$row['available_copies'];
                                            $total = (int)$row['total_copies'];
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['author']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['cat_name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['isbn']) . '</td>';
                                            echo '<td>' . $available . ' / ' . $total . '</td>';
                                            echo '<td>';
                                            if ($available > 0) {
                                                echo '<form method="post" class="d-inline">';
                                                echo '<input type="hidden" name="reserve_isbn" value="' . htmlspecialchars($row['isbn']) . '">';
                                                echo '<button type="submit" name="reserve_by_isbn" class="btn btn-sm btn-primary">Reserve Copy</button>';
                                                echo '</form>';
                                            } else {
                                                echo '<span class="badge bg-secondary">All Copies Issued</span>';
                                            }
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">No books found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Bookings Section -->
        <div class="row mt-4" id="myLoans">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Your Book Reservations & Loans</h5>
                        <small class="d-block mt-1">Return codes are shown for active loans - scan them at the library to return books</small>
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
                                l.issued_at,
                                b.title as book_title,
                                s.email as student_email
                                FROM book_loans l
                                JOIN books b ON b.id = l.book_id
                                JOIN students s ON s.id = l.student_id
                                WHERE s.email = '" . $conn->real_escape_string($student_email) . "' 
                                AND l.is_return = 0
                                ORDER BY l.issued_at ASC, l.created_at DESC";

                            error_log("Executing loan query for student email: " . $student_email);
                            $loans_result = $conn->query($loans_query);

                            if ($loans_result && $loans_result->num_rows > 0) {
                        ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped" id="loansTable" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th style="border: none; padding: 15px 12px;"><i class="fas fa-hashtag me-2"></i>ID</th>
                                                <th style="border: none; padding: 15px 12px;"><i class="fas fa-book me-2"></i>Book</th>
                                                <th style="border: none; padding: 15px 12px;"><i class="fas fa-calendar-plus me-2"></i>Loan Date</th>
                                                <th style="border: none; padding: 15px 12px;"><i class="fas fa-calendar-minus me-2"></i>Return By</th>
                                                <th style="border: none; padding: 15px 12px;"><i class="fas fa-info-circle me-2"></i>Status</th>
                                                <th style="border: none; padding: 15px 12px;"><i class="fas fa-money-bill me-2"></i>Fine</th>
                                                <th style="border: none; padding: 15px 12px;"><i class="fas fa-qrcode me-2"></i>Return Code</th>
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
                                                        <?php if (empty($loan['issued_at'])): ?>
                                                            <span class="badge bg-warning text-dark">Pending Approval</span>
                                                        <?php elseif ($loan['is_return'] == 1): ?>
                                                            <span class="badge bg-success">Returned</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-primary">Active</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $fine = 0;
                                                        if (!empty($loan['issued_at']) && (int)$loan['is_return'] === 0) {
                                                            $fine = calculateCurrentFineAmount($loan);
                                                        }
                                                        echo $fine > 0 ? '<span class="badge bg-danger">' . $fine . ' ৳</span>' : '<span class="text-muted">0</span>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($loan['issued_at']) && (int)$loan['is_return'] === 0): ?>
                                                            <!-- Show QR code for active loans that can be returned -->
                                                            <div class="text-center">
                                                                <div class="qr-code-wrapper" style="position: relative; display: inline-block;">
                                                                    <div id="return-qrcode-<?php echo $loan['id']; ?>" class="qrcode-container" style="width: 80px; height: 80px; border: 2px solid #e9ecef; border-radius: 8px; background: white;"></div>
                                                                    <!-- Fallback QR code image -->
                                                                    <img id="fallback-return-qr-<?php echo $loan['id']; ?>"
                                                                        src="<?php echo generateBookingQRCode($loan['id'], 'url', 80); ?>"
                                                                        alt="Return QR Code"
                                                                        style="display: none; width: 80px; height: 80px; border: 2px solid #e9ecef; border-radius: 8px;"
                                                                        class="img-fluid">
                                                                </div>
                                                                <div class="mt-2">
                                                                    <small class="text-muted">Scan at library to return</small>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <button type="button" class="btn btn-sm btn-outline-primary show-qr-fullscreen"
                                                                        data-booking-id="<?php echo $loan['id']; ?>"
                                                                        data-book-title="<?php echo htmlspecialchars($loan['book_title']); ?>"
                                                                        title="View QR Code in Full Screen">
                                                                        <i class="fas fa-expand me-1"></i>Full Screen
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php elseif (empty($loan['issued_at'])): ?>
                                                            <div class="text-center">
                                                                <span class="badge bg-warning text-dark">
                                                                    <i class="fas fa-clock me-1"></i>Pending
                                                                </span>
                                                                <div class="mt-1">
                                                                    <small class="text-muted">Awaiting approval</small>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="text-center">
                                                                <span class="badge bg-secondary">
                                                                    <i class="fas fa-check-circle me-1"></i>Returned
                                                                </span>
                                                                <div class="mt-1">
                                                                    <small class="text-muted">Book returned</small>
                                                                </div>
                                                            </div>
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
                                            ],
                                            "language": {
                                                "search": "Search loans:",
                                                "lengthMenu": "Show _MENU_ loans per page",
                                                "info": "Showing _START_ to _END_ of _TOTAL_ loans",
                                                "paginate": {
                                                    "first": "First",
                                                    "last": "Last",
                                                    "next": "Next",
                                                    "previous": "Previous"
                                                }
                                            }
                                        });
                                    });
                                </script>

                                <style>
                                    #loansTable tbody tr {
                                        transition: all 0.3s ease;
                                    }

                                    #loansTable tbody tr:hover {
                                        background-color: #f8f9fa !important;
                                        transform: translateY(-1px);
                                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                    }

                                    #loansTable td {
                                        vertical-align: middle;
                                        padding: 12px 8px;
                                    }

                                    .qr-code-wrapper {
                                        transition: all 0.3s ease;
                                    }

                                    .qr-code-wrapper:hover {
                                        transform: scale(1.05);
                                    }

                                    .badge {
                                        font-size: 0.75rem;
                                        padding: 0.5em 0.75em;
                                    }

                                    .table-responsive {
                                        border-radius: 8px;
                                        overflow: hidden;
                                    }

                                    /* Full-screen modal enhancements */
                                    .modal-fullscreen .modal-body {
                                        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                                    }

                                    .qr-fullscreen-container {
                                        background: white;
                                        padding: 40px;
                                        border-radius: 15px;
                                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                                        border: 3px solid #28a745;
                                        min-width: 500px;
                                        min-height: 500px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                    }

                                    /* Button enhancements */
                                    .show-qr-fullscreen {
                                        transition: all 0.3s ease;
                                        border-radius: 20px;
                                        font-size: 0.8rem;
                                        padding: 0.4rem 0.8rem;
                                    }

                                    .show-qr-fullscreen:hover {
                                        transform: translateY(-2px);
                                        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
                                    }

                                    /* Modal header enhancements */
                                    .modal-header {
                                        border-bottom: 3px solid rgba(255, 255, 255, 0.2);
                                    }

                                    /* Footer button styling */
                                    .modal-footer .btn {
                                        border-radius: 25px;
                                        padding: 0.5rem 1.5rem;
                                        font-weight: 500;
                                        transition: all 0.3s ease;
                                    }

                                    .modal-footer .btn:hover {
                                        transform: translateY(-1px);
                                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                                    }

                                    /* Responsive adjustments */
                                    @media (max-width: 768px) {
                                        .qr-fullscreen-container {
                                            padding: 30px;
                                        }

                                        .modal-footer .btn {
                                            padding: 0.4rem 1rem;
                                            font-size: 0.9rem;
                                        }
                                    }
                                </style>
                        <?php
                            } else {
                                // No loans found or query failed
                                echo '<div class="alert alert-info">';
                                echo '<h5><i class="fas fa-info-circle me-2"></i>No active book loans found</h5>';
                                echo '<p>You don\'t have any active book loans at the moment. Browse the available books above to borrow something interesting!</p>';
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
</main>
<!--Main Container End-->

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

<!-- Full Screen QR Code Modal -->
<div class="modal fade" id="fullscreenQrModal" tabindex="-1" aria-labelledby="fullscreenQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title" id="fullscreenQrModalLabel">
                    <i class="fas fa-qrcode me-2"></i>Return QR Code
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
                <div class="mb-4">
                    <h5 id="fullscreen-book-title" class="text-success mb-2"></h5>
                    <p class="text-muted">Booking ID: <strong id="fullscreen-booking-id"></strong></p>
                </div>

                <div class="qr-fullscreen-container">
                    <div id="fullscreen-qrcode-container" style="min-height: 400px; display: flex; align-items: center; justify-content: center;"></div>
                    <div id="fullscreen-fallback-container" style="display: none;">
                        <img id="fullscreen-fallback-qr" src="" alt="Return QR Code" class="img-fluid" style="width: 400px; height: 400px;">
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Show this QR code at the library counter to return your book
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode.js@1.0.0/lib/qrcode.min.js"></script>

<script>
    $(document).ready(function() {
        // Check if QRCode library is loaded
        if (typeof QRCode === 'undefined') {
            console.error("❌ QRCode library not loaded! Check if the CDN is accessible.");
            // Show all fallback images immediately
            setTimeout(function() {
                document.querySelectorAll('[id^="fallback-return-qr"]').forEach(img => {
                    img.style.display = 'block';
                });
                document.querySelectorAll('[id^="return-qrcode"]').forEach(container => {
                    container.style.display = 'none';
                });
            }, 500);
        } else {
            console.log("✅ QRCode library loaded successfully");
        }

        $('#booksTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100]
        });

        // Note: The loans table is now initialized directly in the loans section
        // when the loans are displayed

        // Function to generate QR codes with fallback
        function generateQRCode(elementId, data, size = 200) {
            const container = document.getElementById(elementId);
            if (!container) {
                console.error("Container not found:", elementId);
                return;
            }

            container.innerHTML = ''; // Clear existing content

            try {
                // Check if QRCode library is available
                if (typeof QRCode === 'undefined') {
                    throw new Error("QRCode library not loaded");
                }

                console.log("Generating QR code for:", elementId, "with data:", data, "size:", size);

                // Use reliable QR code generation
                new QRCode(container, {
                    text: data.toString(),
                    width: size,
                    height: size,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H, // High error correction level
                    quietZone: 2, // Add some padding around the QR code
                    quietZoneColor: '#ffffff'
                });

                // Add success styling
                container.style.border = '2px solid #28a745';
                container.style.boxShadow = '0 2px 8px rgba(40, 167, 69, 0.2)';
                console.log("QR code generated successfully for:", elementId);

            } catch (error) {
                console.error("QR code generation failed for", elementId, ":", error);
                // Show fallback image if JavaScript QR generation fails
                const fallbackId = elementId.replace('return-qrcode', 'fallback-return-qr');
                const fallbackImg = document.getElementById(fallbackId);
                if (fallbackImg) {
                    fallbackImg.style.display = 'block';
                    container.style.display = 'none';
                    console.log("Showing fallback image for:", elementId);
                } else {
                    console.error("Fallback image not found for:", elementId);
                }
            }
        }

        // Function to generate QR codes with better error handling
        function generateQRCodeWithFallback(elementId, data, size = 200) {
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
                    correctLevel: QRCode.CorrectLevel.H, // High error correction level
                    quietZone: 2, // Add some padding around the QR code
                    quietZoneColor: '#ffffff'
                });

                // Add success styling
                container.style.border = '2px solid #28a745';
                container.style.boxShadow = '0 2px 8px rgba(40, 167, 69, 0.2)';

            } catch (error) {
                console.error("QR code generation failed:", error);
                // Show fallback image if JavaScript QR generation fails
                const fallbackId = elementId.replace('return-qrcode', 'fallback-return-qr');
                const fallbackImg = document.getElementById(fallbackId);
                if (fallbackImg) {
                    fallbackImg.style.display = 'block';
                    container.style.display = 'none';
                }
            }
        }

        // Generate QR code for new booking if present
        <?php if (isset($booking_id)): ?>
            generateQRCode('booking-qrcode-<?php echo $booking_id; ?>', '<?php echo $booking_id; ?>|qr_scan=true', 200);
        <?php endif; ?>

        // Generate return QR codes for active loans after a short delay
        setTimeout(function() {
            generateReturnQRCodes();
        }, 1000);

        // Function to generate return QR codes for active loans
        function generateReturnQRCodes() {
            // Check if QRCode library is available
            if (typeof QRCode === 'undefined') {
                console.error("QRCode library not loaded, showing fallback images");
                // Show all fallback images
                document.querySelectorAll('[id^="fallback-return-qr"]').forEach(img => {
                    img.style.display = 'block';
                });
                document.querySelectorAll('[id^="return-qrcode"]').forEach(container => {
                    container.style.display = 'none';
                });
                return;
            }

            // Find all active loan rows and generate QR codes
            const activeLoanRows = document.querySelectorAll('#loansTable tbody tr');
            activeLoanRows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(5)'); // Status column
                const qrCell = row.querySelector('td:nth-child(7)'); // Return Code column

                if (statusCell && qrCell) {
                    const statusText = statusCell.textContent.trim();

                    // Only generate QR codes for active loans (not pending or returned)
                    if (statusText.includes('Active')) {
                        const qrContainer = qrCell.querySelector('[id^="return-qrcode-"]');
                        if (qrContainer) {
                            const loanId = qrContainer.id.replace('return-qrcode-', '');
                            console.log("Generating return QR code for loan ID:", loanId);
                            generateQRCode('return-qrcode-' + loanId, loanId + '|qr_scan=true', 80);
                        }
                    }
                }
            });
        }

        // Also generate QR codes when DataTable is fully loaded
        $('#loansTable').on('draw.dt', function() {
            setTimeout(function() {
                generateReturnQRCodes();
            }, 300);
        });

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

                    // Generate QR with JavaScript using new format
                    try {
                        // Generate QR code in modal with qr_scan=true parameter
                        generateQRCode('modal-qrcode-container', bookingId + '|qr_scan=true', 250);
                    } catch (error) {
                        console.error("Modal QR generation failed:", error);
                        // Show fallback image
                        fallbackImg.style.display = 'block';
                    }
                }
            });
        });

        // Handle full-screen QR code button clicks
        document.querySelectorAll('.show-qr-fullscreen').forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                const bookTitle = this.getAttribute('data-book-title');

                // Set modal content
                document.getElementById('fullscreen-booking-id').textContent = bookingId;
                document.getElementById('fullscreen-book-title').textContent = bookTitle;

                // Clear previous QR code
                const container = document.getElementById('fullscreen-qrcode-container');
                const fallbackContainer = document.getElementById('fullscreen-fallback-container');
                const fallbackImg = document.getElementById('fullscreen-fallback-qr');

                if (container) {
                    container.innerHTML = '';
                    fallbackContainer.style.display = 'none';

                    // Set fallback image source
                    fallbackImg.src = '<?php echo BASE_URL; ?>qr_samples.php?download=' + bookingId + '&size=400';

                    // Generate large QR code
                    try {
                        if (typeof QRCode === 'undefined') {
                            throw new Error("QRCode library not loaded");
                        }
                        generateQRCode('fullscreen-qrcode-container', bookingId + '|qr_scan=true', 400);
                    } catch (error) {
                        console.error("Fullscreen QR generation failed:", error);
                        // Show fallback image
                        fallbackContainer.style.display = 'block';
                        console.log("Showing fallback image in fullscreen modal");
                    }
                }

                // Show the modal
                const fullscreenModal = new bootstrap.Modal(document.getElementById('fullscreenQrModal'));
                fullscreenModal.show();
            });
        });
    });
</script>

<?php include_once(DIR_URL . "include/footer.php"); ?>