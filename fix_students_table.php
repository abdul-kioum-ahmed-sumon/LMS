<?php
// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "lms";

// Create connection
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add password column if it doesn't exist
$sqlCheckPassword = "SHOW COLUMNS FROM students LIKE 'password'";
$resultCheckPassword = $conn->query($sqlCheckPassword);
if ($resultCheckPassword->num_rows == 0) {
    $sqlAddPassword = "ALTER TABLE students ADD COLUMN password VARCHAR(255) AFTER email";
    if ($conn->query($sqlAddPassword)) {
        echo "Added 'password' column to students table<br>";
    } else {
        echo "Error adding password column: " . $conn->error . "<br>";
    }
}

// Add verified column if it doesn't exist
$sqlCheckVerified = "SHOW COLUMNS FROM students LIKE 'verified'";
$resultCheckVerified = $conn->query($sqlCheckVerified);
if ($resultCheckVerified->num_rows == 0) {
    $sqlAddVerified = "ALTER TABLE students ADD COLUMN verified TINYINT(1) NOT NULL DEFAULT 0 AFTER status";
    if ($conn->query($sqlAddVerified)) {
        echo "Added 'verified' column to students table<br>";
    } else {
        echo "Error adding verified column: " . $conn->error . "<br>";
    }
}

// Check if any students exist and set their status and verified to 1
$sqlCheckStudents = "SELECT id FROM students";
$resultCheckStudents = $conn->query($sqlCheckStudents);
if ($resultCheckStudents->num_rows > 0) {
    $sqlUpdateStudents = "UPDATE students SET status = 1, verified = 1";
    if ($conn->query($sqlUpdateStudents)) {
        echo "Updated existing students to verified and active status<br>";
    } else {
        echo "Error updating students: " . $conn->error . "<br>";
    }
}

// Update any NULL values to empty strings to prevent errors
$sqlCheckNulls = "UPDATE students SET phone_no = '' WHERE phone_no IS NULL";
$conn->query($sqlCheckNulls);

$sqlCheckNullsEmail = "UPDATE students SET email = CONCAT('user', id, '@example.com') WHERE email IS NULL";
$conn->query($sqlCheckNullsEmail);

$sqlCheckNullsAddress = "UPDATE students SET address = '' WHERE address IS NULL";
$conn->query($sqlCheckNullsAddress);

// Check if users_2 table exists to migrate student credentials
$sqlCheckUsers2 = "SHOW TABLES LIKE 'users_2'";
$resultCheckUsers2 = $conn->query($sqlCheckUsers2);
if ($resultCheckUsers2->num_rows > 0) {
    echo "Found users_2 table, migrating student credentials...<br>";

    // Get all entries from users_2
    $sqlGetUsers2 = "SELECT * FROM users_2";
    $resultUsers2 = $conn->query($sqlGetUsers2);

    while ($user = $resultUsers2->fetch_assoc()) {
        $firstName = $conn->real_escape_string($user['firstName']);
        $lastName = $conn->real_escape_string($user['lastName']);
        $fullName = $firstName . " " . $lastName;
        $email = $conn->real_escape_string($user['email']);
        $deptId = $conn->real_escape_string($user['dept_id']);

        // Check if this user already exists in students table
        $sqlCheckStudent = "SELECT id FROM students WHERE dept_id = '$deptId' OR email = '$email'";
        $resultCheckStudent = $conn->query($sqlCheckStudent);

        if ($resultCheckStudent->num_rows == 0) {
            // User doesn't exist in students table, create them
            $sqlAddStudent = "INSERT INTO students (name, email, dept_id, status, verified) 
                            VALUES ('$fullName', '$email', '$deptId', 1, 1)";

            if ($conn->query($sqlAddStudent)) {
                $studentId = $conn->insert_id;
                echo "Created student record for $fullName<br>";

                // If there's a password in md5 format, convert it to bcrypt and update
                if (!empty($user['password'])) {
                    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
                    $sqlUpdatePassword = "UPDATE students SET password = '$hashedPassword' WHERE id = $studentId";
                    $conn->query($sqlUpdatePassword);
                }
            } else {
                echo "Error creating student from users_2: " . $conn->error . "<br>";
            }
        } else {
            // User exists, update their password if needed
            $student = $resultCheckStudent->fetch_assoc();
            $studentId = $student['id'];

            if (!empty($user['password'])) {
                $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
                $sqlUpdatePassword = "UPDATE students SET password = '$hashedPassword' WHERE id = $studentId";
                $conn->query($sqlUpdatePassword);
                echo "Updated password for $fullName<br>";
            }
        }
    }
}

echo "<br>Fix completed. <a href='student_login.php'>Go to student login page</a> or <a href='student_register.php'>Register as a new student</a>.";
$conn->close();
