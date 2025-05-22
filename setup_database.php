<?php
// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "lms";

// Create connection
$conn = new mysqli($server, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($database);

// Create users table if not exists
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_no VARCHAR(20),
    profile_pic VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully or already exists<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Check if the admin user exists
$sql = "SELECT * FROM users WHERE email = 'abdulkioumahmed@gmail.com'";
$result = $conn->query($sql);

if ($result && $result->num_rows == 0) {
    // Insert default admin user
    $name = "Admin User";
    $email = "abdulkioumahmed@gmail.com";
    $password = password_hash("1252", PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Default admin user created successfully<br>";
    } else {
        echo "Error creating default admin user: " . $conn->error . "<br>";
    }
} else {
    echo "Admin user already exists<br>";
}

// Create reset_password table if not exists
$sql = "CREATE TABLE IF NOT EXISTS reset_password (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reset_code VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Reset password table created successfully or already exists<br>";
} else {
    echo "Error creating reset_password table: " . $conn->error . "<br>";
}

echo "Database setup completed. <a href='index.php'>Click here</a> to go to the login page.";
$conn->close();
