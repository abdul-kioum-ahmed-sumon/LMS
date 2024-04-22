<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sanitize input data
function sanitize_input($data) {
    global $conn;
    return htmlspecialchars(strip_tags($conn->real_escape_string($data)));
}

// Insert staff
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $position = sanitize_input($_POST['position']);

    $sql = "INSERT INTO staff (name, email, password, position) VALUES ('$name', '$email', '$password', '$position')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Staff added successfully.";
        header("Location: view_staff.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error adding staff: " . $conn->error . "</div>";
    }
}
?>
