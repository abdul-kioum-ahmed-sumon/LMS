<?php

// Function to store book
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

    $datetime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO books (title, author, publication_year, isbn, category_id, created_at, shelf_no)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $title, $author, $publication_year, $isbn, $category_id, $datetime, $shelf_number);
    $result['success'] = $stmt->execute();
    $stmt->close();
    return $result;
}

// Function to get all books
function getBooks($conn)
{
    $sql = "select b.*, c.name as cat_name from books b 
        inner join categories c on c.id = b.category_id 
        order by id desc";
    $result = $conn->query($sql);
    return $result;
}

// Function to get all available books (not currently loaned)
function getAvailableBooks($conn)
{
    $sql = "SELECT b.*, c.name as cat_name 
            FROM books b
            INNER JOIN categories c ON c.id = b.category_id
            WHERE b.status = 1 
            AND (
                SELECT COUNT(*) FROM book_loans 
                WHERE book_id = b.id AND is_return = 0
            ) = 0
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
    $datetime = $conn->real_escape_string($datetime);

    $sql = "UPDATE books SET 
            title = '$title', 
            author = '$author', 
            publication_year = '$publication_year',
            isbn = '$isbn',
            shelf_no = '$shelf_number',
            category_id = '$category_id',
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
    $sql = "select id from books where isbn = '$isbn'";
    if ($id) {
        $sql .= " and id != $id";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
        return true;
    else return false;
}

// Function to check if a book is available (not currently booked)
function isBookAvailable($conn, $book_id)
{
    $sql = "SELECT COUNT(*) as count FROM book_loans 
            WHERE book_id = $book_id AND is_return = 0";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    return ($row['count'] == 0);
}
