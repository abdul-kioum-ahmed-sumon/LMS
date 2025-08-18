<?php

// Ensure books.quantity column exists
function ensureBooksQuantityColumn(mysqli $conn)
{
    $checkCol = $conn->query("SHOW COLUMNS FROM books LIKE 'quantity'");
    if ($checkCol && $checkCol->num_rows === 0) {
        // Add quantity column default 1
        $conn->query("ALTER TABLE books ADD COLUMN quantity INT NOT NULL DEFAULT 1");
    }
}

// Function to store book (supports quantity field on same ISBN)
function storeBook($conn, $param)
{
    extract($param);

    ## Validation start
    if (empty($title)) {
        $result = array("error" => "Title is required");
        return $result;
    } else if (empty($isbn)) {
        $result = array("error" => "ISBN is required");
        return $result;
    } else if (isIsbnUnique($conn, $isbn)) {
        $result = array("error" => "ISBN is already registered");
        return $result;
    }
    ## Validation end

    ensureBooksQuantityColumn($conn);

    $qty = isset($quantity) && is_numeric($quantity) && (int)$quantity > 0 ? (int)$quantity : 1;

    $datetime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO books (title, author, publication_year, isbn, category_id, created_at, shelf_no, quantity)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssissi", $title, $author, $publication_year, $isbn, $category_id, $datetime, $shelf_number, $qty);
    $result['success'] = $stmt->execute();
    $stmt->close();
    return $result;
}

// Function to get all books
function getBooks($conn)
{
    ensureBooksQuantityColumn($conn);
    $sql = "select b.*, c.name as cat_name,
                COALESCE(b.quantity,1) as quantity,
                (COALESCE(b.quantity,1) - (select count(*) from book_loans l where l.book_id = b.id and l.is_return = 0)) as available_copies
            from books b 
            inner join categories c on c.id = b.category_id 
            order by id desc";
    $result = $conn->query($sql);
    return $result;
}

// Function to get all available books (not currently loaned)
function getAvailableBooks($conn)
{
    // Books with remaining copies (quantity - active_loans > 0)
    $sql = "SELECT b.*, c.name as cat_name,
                   (b.quantity - (SELECT COUNT(*) FROM book_loans l WHERE l.book_id = b.id AND l.is_return = 0)) as available_copies
            FROM books b
            INNER JOIN categories c ON c.id = b.category_id
            WHERE b.status = 1 
              AND (b.quantity - (SELECT COUNT(*) FROM book_loans l WHERE l.book_id = b.id AND l.is_return = 0)) > 0
            ORDER BY b.id DESC";
    $result = $conn->query($sql);
    return $result;
}

// Function to get book details
function getBookById($conn, $id)
{
    $sql = "select * from books where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to delete a book
function deleteBook($conn, $id)
{
    $sql = "delete from books where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to update book status
function updateBookStatus($conn, $id, $status)
{
    $sql = "update books set status = '$status' where id = $id";
    $result = $conn->query($sql);
    return $result;
}

// Function to update  book
function updateBook($conn, $param)
{
    extract($param);

    ## Validation start
    if (empty($title)) {
        $result = array("error" => "Title is required");
        return $result;
    } else if (empty($isbn)) {
        $result = array("error" => "ISBN is required");
        return $result;
    } else if (isIsbnUnique($conn, $isbn, $id)) {
        $result = array("error" => "ISBN is already registered");
        return $result;
    }
    ## Validation end

    $datetime = date("Y-m-d H:i:s");

    // Sanitize variables 
    $title = $conn->real_escape_string($title);
    $author = $conn->real_escape_string($author);
    $publication_year = $conn->real_escape_string($publication_year);
    $isbn = $conn->real_escape_string($isbn);
    $shelf_number = $conn->real_escape_string($shelf_number);
    $category_id = $conn->real_escape_string($category_id);
    ensureBooksQuantityColumn($conn);
    $quantity_val = isset($quantity) && is_numeric($quantity) && (int)$quantity > 0 ? (int)$quantity : 1;
    $datetime = $conn->real_escape_string($datetime);

    $sql = "UPDATE books SET 
            title = '$title', 
            author = '$author', 
            publication_year = '$publication_year',
            isbn = '$isbn',
            shelf_no = '$shelf_number',
            category_id = '$category_id',
            quantity = $quantity_val,
            updated_at = '$datetime'
        WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $result['success'] = true;
    } else {
        $result['success'] = false;
        $result['error'] = $conn->error;
    }

    return $result;
}


// Function to get categories
function getCategories($conn)
{
    $sql = "select id, name from categories";
    $result = $conn->query($sql);
    return $result;
}

// Function to check isbn no
function isIsbnUnique($conn, $isbn, $id = NULL)
{
    // Enforce single row per ISBN (quantity captures copies)
    $isbn = $conn->real_escape_string($isbn);
    $sql = "SELECT id FROM books WHERE isbn = '$isbn'";
    if ($id) {
        $sql .= " AND id != " . (int)$id;
    }
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

// Function to check if a book is available (not currently booked)
function isBookAvailable($conn, $book_id)
{
    $book_id = (int)$book_id;
    $sql = "SELECT 
                b.quantity AS qty,
                (SELECT COUNT(*) FROM book_loans l WHERE l.book_id = b.id AND l.is_return = 0) AS active
            FROM books b WHERE b.id = $book_id";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return ((int)$row['qty'] - (int)$row['active']) > 0;
    }
    return false;
}

// Aggregated availability by ISBN (treat each row in books as a copy)
function getBooksAvailabilityAggregated($conn)
{
    // Aggregate by ISBN using books.quantity per row
    $sql = "SELECT 
                b.isbn,
                b.title,
                b.author,
                c.name as cat_name,
                SUM(b.quantity) as total_copies,
                SUM(GREATEST(b.quantity - (SELECT COUNT(*) FROM book_loans l WHERE l.book_id = b.id AND l.is_return = 0), 0)) as available_copies
            FROM books b
            INNER JOIN categories c ON c.id = b.category_id
            WHERE b.status = 1
            GROUP BY b.isbn, b.title, b.author, c.name
            ORDER BY b.title";
    return $conn->query($sql);
}

// Return a reservable book row ID for a given ISBN respecting quantity (or null)
function findReservableBookIdByIsbn($conn, $isbn)
{
    $isbn = $conn->real_escape_string($isbn);
    $sql = "SELECT b.id,
                   (b.quantity - (SELECT COUNT(*) FROM book_loans l WHERE l.book_id = b.id AND l.is_return = 0)) AS remaining
            FROM books b
            WHERE b.status = 1 AND b.isbn = '$isbn'
              AND (b.quantity - (SELECT COUNT(*) FROM book_loans l WHERE l.book_id = b.id AND l.is_return = 0)) > 0
            ORDER BY remaining DESC, b.id ASC
            LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return (int)$row['id'];
    }
    return null;
}

// Get copy counts for a given ISBN
function getBookCopyCountsByIsbn($conn, $isbn)
{
    $isbn = $conn->real_escape_string($isbn);
    $counts = ["total" => 0, "available" => 0];

    $totalSql = "SELECT COALESCE(SUM(quantity),0) as total FROM books WHERE isbn = '$isbn'";
    $totalRes = $conn->query($totalSql);
    if ($totalRes) {
        $counts['total'] = (int)$totalRes->fetch_assoc()['total'];
    }

    $availSql = "SELECT COALESCE(SUM(GREATEST(b.quantity - (SELECT COUNT(*) FROM book_loans l WHERE l.book_id = b.id AND l.is_return = 0),0)),0) AS available
                 FROM books b
                 WHERE b.isbn = '$isbn'";
    $availRes = $conn->query($availSql);
    if ($availRes) {
        $counts['available'] = (int)$availRes->fetch_assoc()['available'];
    }

    return $counts;
}
