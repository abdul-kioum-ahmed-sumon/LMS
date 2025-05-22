<?php
// Simple session diagnostic and setup tool
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle form submission
if (isset($_POST['set_session'])) {
    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;

    if ($student_id > 0) {
        // Check if student exists
        $sql = "SELECT * FROM students WHERE id = $student_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $student = $result->fetch_assoc();

            // Set session variables
            $_SESSION['student_id'] = $student_id;
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_email'] = $student['email'];
            $_SESSION['student_dept'] = $student['dept'];
            $_SESSION['student_dept_id'] = $student['dept_id'];
            $_SESSION['user_type'] = 'student';
            $_SESSION['user_logged_in'] = true;

            $success_message = "Session variables set for student: " . htmlspecialchars($student['name']);
        } else {
            $error_message = "Student with ID $student_id does not exist!";
        }
    } else {
        $error_message = "Invalid student ID provided!";
    }
}

// Handle session clearing
if (isset($_GET['clear']) && $_GET['clear'] == 'true') {
    // Backup some values we want to display after clearing
    $old_student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : 'Not set';
    $old_user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Not set';

    // Clear session
    session_unset();
    session_regenerate_id(true);

    $success_message = "Session cleared. Previous values - Student ID: $old_student_id, User Type: $old_user_type";
}

// Get current session data
$session_data = [];
foreach ($_SESSION as $key => $value) {
    $session_data[$key] = $value;
}

// Get all students for selection
$students = $conn->query("SELECT id, name, email, dept_id FROM students ORDER BY id LIMIT 20");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Diagnostic Tool - LMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h1,
        h2 {
            color: #333;
        }

        h1 {
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .success {
            color: green;
            font-weight: bold;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        button,
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover,
        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            background-color: #555;
        }

        .btn-danger {
            background-color: #d9534f;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        .actions {
            margin-top: 20px;
        }

        .actions a {
            margin-right: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Session Diagnostic Tool</h1>
        <p>This tool helps diagnose and fix session-related issues in the LMS system.</p>

        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Current Session Information -->
        <h2>Current Session Information</h2>
        <table>
            <tr>
                <th>Session ID</th>
                <td><?php echo session_id(); ?></td>
            </tr>
            <?php if (empty($session_data)): ?>
                <tr>
                    <td colspan="2">No session data available</td>
                </tr>
            <?php else: ?>
                <?php foreach ($session_data as $key => $value): ?>
                    <tr>
                        <th><?php echo htmlspecialchars($key); ?></th>
                        <td>
                            <?php
                            if (is_array($value)) {
                                echo '<pre>' . htmlspecialchars(print_r($value, true)) . '</pre>';
                            } else {
                                echo htmlspecialchars($value);
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>

        <!-- Set Session Form -->
        <h2>Set Student Session</h2>
        <form method="post" action="test_session.php">
            <div>
                <label for="student_id">Select Student:</label>
                <select id="student_id" name="student_id">
                    <?php if ($students && $students->num_rows > 0): ?>
                        <?php while ($student = $students->fetch_assoc()): ?>
                            <option value="<?php echo $student['id']; ?>" <?php echo (isset($_SESSION['student_id']) && $_SESSION['student_id'] == $student['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($student['name']) . ' (ID: ' . $student['id'] . ', ' . $student['dept_id'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No students found</option>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <input type="submit" name="set_session" value="Set Student Session">
            </div>
        </form>

        <!-- Actions -->
        <div class="actions">
            <a href="test_session.php?clear=true"><button class="btn-danger">Clear Session</button></a>
            <a href="student_dashboard.php"><button>Go to Student Dashboard</button></a>
            <a href="direct_booking_fix.php"><button>Go to Booking Diagnostic Tool</button></a>
            <a href="add_test_booking.php"><button>Add Test Booking</button></a>
            <a href="index.php"><button class="btn-secondary">Go to Home Page</button></a>
        </div>
    </div>
</body>

</html>