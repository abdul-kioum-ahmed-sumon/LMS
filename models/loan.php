<?php

if (!defined('DAILY_FINE_TAKA')) {
    define('DAILY_FINE_TAKA', 5);
}

// Ensure fine tracking columns exist on book_loans
function ensureFineColumns(mysqli $conn): void
{
    $cols = [];
    if ($res = $conn->query("SHOW COLUMNS FROM book_loans LIKE 'fine_accrued'")) {
        if ($res->num_rows === 0) {
            $cols[] = "ADD COLUMN fine_accrued INT NOT NULL DEFAULT 0";
        }
    }
    if ($res = $conn->query("SHOW COLUMNS FROM book_loans LIKE 'fine_paid'")) {
        if ($res->num_rows === 0) {
            $cols[] = "ADD COLUMN fine_paid INT NOT NULL DEFAULT 0";
        }
    }
    if ($res = $conn->query("SHOW COLUMNS FROM book_loans LIKE 'fine_paid_at'")) {
        if ($res->num_rows === 0) {
            $cols[] = "ADD COLUMN fine_paid_at DATETIME NULL";
        }
    }
    if (!empty($cols)) {
        $conn->query("ALTER TABLE book_loans " . implode(", ", $cols));
    }
}

// Function to create loan
function create($conn, $param)
{
    extract($param);
    ## Validation start
    if (empty($book_id)) {
        $result = array("error" => "Book selection is required");
        return $result;
    } else if (empty($student_id)) {
        $result = array("error" => "Student selection is required");
        return $result;
    }
    ## Validation end

    $datetime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO book_loans (book_id, student_id, loan_date, return_date, created_at)
        VALUES ($book_id, $student_id, '$loan_date', '$return_date', '$datetime')";
    $result['success'] = $conn->query($sql);
    return $result;
}

// Calculate current fine for a loan record
function calculateCurrentFineAmount(array $loan): int
{
    // Only calculate for active (not returned) loans
    if (!isset($loan['is_return']) || (int)$loan['is_return'] === 1) {
        return 0;
    }

    if (!isset($loan['return_date']) || empty($loan['return_date'])) {
        return 0;
    }

    $today = new DateTime(date('Y-m-d'));
    $due = new DateTime(date('Y-m-d', strtotime($loan['return_date'])));
    if ($today <= $due) {
        return 0;
    }

    $daysOver = (int)$due->diff($today)->format('%a');
    return $daysOver * DAILY_FINE_TAKA;
}

// Function to create a book booking by a student
function createBookReservation($conn, $student_id, $book_id, $return_date)
{
    // Validate parameters
    if (empty($student_id) || !is_numeric($student_id)) {
        return array("error" => "Invalid student ID");
    }

    if (empty($book_id) || !is_numeric($book_id)) {
        return array("error" => "Invalid book ID");
    }

    if (empty($return_date)) {
        return array("error" => "Return date is required");
    }

    // Check if student is active and verified
    $sql = "SELECT * FROM students WHERE id = '$student_id' AND status = 1 AND verified = 1";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        return array("error" => "Student account is not active or verified");
    }

    // Check if book exists and has available copies according to quantity
    $bookSql = "SELECT quantity, status FROM books WHERE id = '$book_id'";
    $bookRes = $conn->query($bookSql);
    if (!$bookRes || $bookRes->num_rows === 0) {
        return array("error" => "Book not found");
    }
    $bookRow = $bookRes->fetch_assoc();
    if ((int)$bookRow['status'] !== 1) {
        return array("error" => "Book is not available");
    }

    $quantity = isset($bookRow['quantity']) ? (int)$bookRow['quantity'] : 1;
    if ($quantity < 1) {
        $quantity = 1;
    }

    // Active loans for this book row
    $activeSql = "SELECT COUNT(*) AS active FROM book_loans WHERE book_id = '$book_id' AND is_return = 0";
    $activeRes = $conn->query($activeSql);
    $activeCount = 0;
    if ($activeRes && $activeRes->num_rows > 0) {
        $activeCount = (int)$activeRes->fetch_assoc()['active'];
    }

    if ($activeCount >= $quantity) {
        return array("error" => "No copies available for this book at the moment");
    }

    // Check if student already has any reservation or loan for this book
    $existingSql = "SELECT id, issued_at, is_return FROM book_loans WHERE book_id = '$book_id' AND student_id = '$student_id' ORDER BY created_at DESC LIMIT 1";
    $existingRes = $conn->query($existingSql);
    if ($existingRes && $existingRes->num_rows > 0) {
        $existingLoan = $existingRes->fetch_assoc();

        if (empty($existingLoan['issued_at'])) {
            // Pending reservation exists
            return array("error" => "You already have a pending reservation for this book");
        } elseif ($existingLoan['is_return'] == 0) {
            // Active loan exists (book is currently borrowed)
            return array("error" => "You already have this book borrowed. Please return it before reserving again.");
        } else {
            // Book was previously borrowed and returned
            return array("error" => "You have already borrowed this book before. You cannot reserve the same book multiple times.");
        }
    }

    // Create pending reservation (not yet issued)
    $loan_date = date('Y-m-d'); // Today's date
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO book_loans (book_id, student_id, loan_date, return_date, created_at, is_return, issued_at)
            VALUES ('$book_id', '$student_id', '$loan_date', '$return_date', '$created_at', 0, NULL)";

    if ($conn->query($sql)) {
        $booking_id = $conn->insert_id;
        return array(
            "success" => true,
            "booking_id" => $booking_id,
            "message" => "Book reservation submitted successfully! Please wait for admin approval."
        );
    } else {
        return array("error" => "Failed to create reservation: " . $conn->error);
    }
}

// Function to get all loans
function getLoans($conn)
{
    $sql = "select l.*, b.title as book_title, s.name as student_name 
        from book_loans l
        inner join books b on b.id = l.book_id
        inner join students s on s.id = l.student_id
        order by l.id desc;
    ";
    $result = $conn->query($sql);
    return $result;
}

// Function to get student loans
function getStudentLoans($conn, $student_id)
{
    $sql = "SELECT l.*, b.title as book_title 
            FROM book_loans l
            INNER JOIN books b ON b.id = l.book_id
            WHERE l.student_id = $student_id
            ORDER BY l.id DESC";
    $result = $conn->query($sql);
    return $result;
}

// Function to get loan details
function getLoanById($conn, $id)
{
    $sql = "select * from book_loans where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to delete
function delete($conn, $id)
{
    $sql = "delete from book_loans where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to update student status
function updateStatus($conn, $id, $status)
{
    ensureFineColumns($conn);

    $id = (int)$id;
    $status = (int)$status;

    if ($status === 1) {
        // On return, compute fine and mark as paid now
        $loanRes = $conn->query("SELECT id, return_date, is_return FROM book_loans WHERE id = $id LIMIT 1");
        if ($loanRes && $loanRes->num_rows === 1) {
            $loan = $loanRes->fetch_assoc();
            $fine = calculateCurrentFineAmount($loan);
            $sql = "UPDATE book_loans SET is_return = 1, fine_accrued = $fine, fine_paid = $fine, fine_paid_at = NOW() WHERE id = $id";
            return $conn->query($sql);
        }
    }

    return $conn->query("UPDATE book_loans SET is_return = $status WHERE id = $id");
}

// Function to complete book return (auto on QR scan)
function completeBookReturn(mysqli $conn, int $booking_id): array
{
    ensureFineColumns($conn);

    $booking_id = (int)$booking_id;
    // Fetch current loan
    $loanRes = $conn->query("SELECT id, return_date, is_return FROM book_loans WHERE id = $booking_id LIMIT 1");
    if (!$loanRes || $loanRes->num_rows === 0) {
        return array("success" => false, "error" => "Booking not found.");
    }
    $loan = $loanRes->fetch_assoc();
    if ((int)$loan['is_return'] === 1) {
        return array("success" => true, "message" => "Already returned.");
    }

    $fine = calculateCurrentFineAmount($loan);
    $sql = "UPDATE book_loans SET is_return = 1, updated_at = NOW(), fine_accrued = $fine, fine_paid = $fine, fine_paid_at = NOW() WHERE id = $booking_id";
    if ($conn->query($sql)) {
        return array("success" => true);
    }
    return array("success" => false, "error" => "Database error: " . $conn->error);
}

// Function to update
function update($conn, $param)
{
    extract($param);
    ## Validation start
    if (empty($book_id)) {
        $result = array("error" => "Book selection is required");
        return $result;
    } else if (empty($student_id)) {
        $result = array("error" => "Student selection is required");
        return $result;
    }
    ## Validation end
    // Sanitize variables 
    $book_id = $conn->real_escape_string($book_id);
    $student_id = $conn->real_escape_string($student_id);
    $loan_date = $conn->real_escape_string($loan_date);
    $return_date = $conn->real_escape_string($return_date);
    $datetime = $conn->real_escape_string($datetime);

    $datetime = date("Y-m-d H:i:s");
    $sql = "UPDATE book_loans SET 
        book_id = '$book_id', 
        student_id = '$student_id', 
        loan_date = '$loan_date',
        return_date = '$return_date',
        updated_at = '$datetime'
        WHERE id = $id;
        ";
    $result['success'] = $conn->query($sql);
    return $result;
}

// Function to get students
function getLoanableStudents($conn)
{
    $sql = "select id, name,dept_id from students where status = 1";
    $result = $conn->query($sql);
    return $result;
}

// Function to get books for loans
function getAvailableBooksForLoan($conn)
{
    $sql = "select id, title,isbn from books where status = 1";
    $result = $conn->query($sql);
    return $result;
}

// Function to verify booking by QR code or booking ID
function verifyBooking($conn, $booking_id)
{
    // Validate input
    if (empty($booking_id) || !is_numeric($booking_id)) {
        return array("success" => false, "error" => "Invalid booking ID format. Please provide a numeric ID.");
    }

    // Sanitize the booking ID
    $booking_id = (int)$booking_id;

    // First check if the booking exists at all
    $check_sql = "SELECT id FROM book_loans WHERE id = $booking_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        return array("success" => false, "error" => "Booking ID $booking_id does not exist in our system.");
    }

    // Check if the booking was already returned
    $return_sql = "SELECT id FROM book_loans WHERE id = $booking_id AND is_return = 1";
    $return_result = $conn->query($return_sql);

    if ($return_result->num_rows > 0) {
        return array("success" => false, "error" => "Booking ID $booking_id has already been returned.");
    }

    // Check if the book was already issued
    $issued_sql = "SELECT id FROM book_loans WHERE id = $booking_id AND issued_at IS NOT NULL";
    $issued_result = $conn->query($issued_sql);

    if ($issued_result->num_rows > 0) {
        // Still get the details, but indicate it has already been issued
        $sql = "SELECT l.*, b.title as book_title, s.name as student_name, s.dept_id as student_id_number
                FROM book_loans l
                INNER JOIN books b ON b.id = l.book_id
                INNER JOIN students s ON s.id = l.student_id
                WHERE l.id = $booking_id";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return array(
                "success" => true,
                "booking" => $result->fetch_assoc(),
                "warning" => "This book has already been issued."
            );
        }
    }

    // Main query for valid bookings
    $sql = "SELECT l.*, b.title as book_title, s.name as student_name, s.dept_id as student_id_number
            FROM book_loans l
            INNER JOIN books b ON b.id = l.book_id
            INNER JOIN students s ON s.id = l.student_id
            WHERE l.id = $booking_id AND l.is_return = 0";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return array("success" => true, "booking" => $result->fetch_assoc());
    } else {
        // This should not happen given our checks above, but just in case
        return array("success" => false, "error" => "Could not verify booking ID $booking_id. Please contact support.");
    }
}

// Function to complete book issuance
function completeBookIssuance($conn, $booking_id)
{
    // This function could update the booking with additional information
    // such as who issued the book, when, etc.
    // For now, we'll just mark it as collected/issued

    $sql = "UPDATE book_loans SET 
            issued_at = NOW(),
            updated_at = NOW(),
            is_return = 0
            WHERE id = $booking_id";

    if ($conn->query($sql)) {
        return array("success" => true);
    } else {
        return array("success" => false, "error" => "Database error: " . $conn->error);
    }
}

// Function to get student bookings
function getStudentBookings($conn, $student_id)
{
    // Special handling for problematic student IDs (like ID 8)
    if ($student_id == 8) {
        error_log("Special handling for student ID 8");
    }

    if (!$student_id || !is_numeric($student_id)) {
        error_log("Invalid student ID provided to getStudentBookings: " . print_r($student_id, true));
        return $conn->query("SELECT 1 LIMIT 0"); // Return empty result set
    }

    // Sanitize the student ID
    $student_id = (int)$student_id;
    error_log("Sanitized student ID in getStudentBookings: " . $student_id);

    try {
        // Improved explicit query with detailed error handling
        $sql = "SELECT 
                l.id, 
                l.book_id, 
                l.student_id,
                l.loan_date,
                l.return_date,
                l.created_at,
                l.is_return,
                l.issued_at,
                b.title as book_title 
                FROM book_loans l
                JOIN books b ON b.id = l.book_id
                WHERE l.student_id = $student_id
                ORDER BY l.created_at DESC";

        error_log("Running SQL in getStudentBookings: " . $sql);
        $result = $conn->query($sql);

        if (!$result) {
            error_log("Database error in getStudentBookings: " . $conn->error);
            return $conn->query("SELECT 1 LIMIT 0"); // Return empty result set
        }

        $count = $result->num_rows;
        error_log("getStudentBookings found {$count} records for student {$student_id}");
        return $result;
    } catch (Exception $e) {
        error_log("Exception in getStudentBookings: " . $e->getMessage());
        return $conn->query("SELECT 1 LIMIT 0"); // Return empty result set
    }
}

// Function to generate QR code URL or data for a booking
function generateBookingQRCode($booking_id, $type = 'url', $size = 200)
{
    if (empty($booking_id) || !is_numeric($booking_id)) {
        return false;
    }

    switch ($type) {
        case 'url':
            // Generate QR code URL from QR server API (most reliable)
            // Include qr_scan=true parameter to indicate this is an actual QR scan
            $qr_data = $booking_id . "|qr_scan=true";
            return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($qr_data);

        case 'data':
            // Just return the booking ID as data (for JavaScript libraries)
            return $booking_id;

        case 'download_url':
            // URL to download the QR code
            return BASE_URL . "qr_samples.php?download={$booking_id}&size={$size}";

        default:
            return false;
    }
}

// Function to validate QR code data
function validateQRCodeData($data)
{
    // Check if it's a valid booking ID (numeric)
    if (is_numeric($data) && intval($data) > 0) {
        return intval($data);
    }

    return false;
}
