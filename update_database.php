<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "lms"; // Corrected database name from config.php

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to database successfully.\n";

echo "Script starting...\n";

// Check if students table exists
$result = $conn->query("SHOW TABLES LIKE 'students'");
if ($result->num_rows == 0) {
    die("Error: 'students' table does not exist in the database.\n");
}

// Get all tables
$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}
echo "Tables in database: " . implode(", ", $tables) . "\n";

// Check what columns already exist in students table
$result = $conn->query("DESCRIBE students");
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'] . "(" . $row['Type'] . ")";
}

echo "Existing columns in students table: " . implode(", ", $columns) . "\n";

// Add password column if it doesn't exist
if (!in_array('password', array_map(function ($col) {
    return explode('(', $col)[0];
}, $columns))) {
    echo "Adding password column to students table...\n";
    $sql = "ALTER TABLE students ADD COLUMN password VARCHAR(255) NULL";
    if ($conn->query($sql)) {
        echo "Password column added successfully.\n";
    } else {
        echo "Error adding password column: " . $conn->error . "\n";
    }
} else {
    echo "Password column already exists.\n";
}

// Add verified column if it doesn't exist
if (!in_array('verified', array_map(function ($col) {
    return explode('(', $col)[0];
}, $columns))) {
    echo "Adding verified column to students table...\n";
    $sql = "ALTER TABLE students ADD COLUMN verified TINYINT(1) DEFAULT 0";
    if ($conn->query($sql)) {
        echo "Verified column added successfully.\n";
    } else {
        echo "Error adding verified column: " . $conn->error . "\n";
    }
} else {
    echo "Verified column already exists.\n";
}

// Check if book_loans table exists
$result = $conn->query("SHOW TABLES LIKE 'book_loans'");
if ($result->num_rows > 0) {
    // Check what columns already exist in book_loans table
    $result = $conn->query("DESCRIBE book_loans");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'] . "(" . $row['Type'] . ")";
    }

    echo "Existing columns in book_loans table: " . implode(", ", $columns) . "\n";

    // Add issued_at column if it doesn't exist
    if (!in_array('issued_at', array_map(function ($col) {
        return explode('(', $col)[0];
    }, $columns))) {
        echo "Adding issued_at column to book_loans table...\n";
        $sql = "ALTER TABLE book_loans ADD COLUMN issued_at DATETIME NULL";
        if ($conn->query($sql)) {
            echo "issued_at column added successfully.\n";
        } else {
            echo "Error adding issued_at column: " . $conn->error . "\n";
        }
    } else {
        echo "issued_at column already exists.\n";
    }
} else {
    echo "book_loans table does not exist. Skipping.\n";
}

echo "Schema update completed.\n";

// Close the connection
$conn->close();
