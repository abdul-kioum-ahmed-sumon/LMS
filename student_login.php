<?php
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/student_auth.php");

// Check if already logged in
if (isset($_SESSION['student_id'])) {
    header("Location: student_dashboard.php");
    exit;
}

$error = '';

// Process login
if (isset($_POST['login_form_submitted'])) {
    $user_email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Improved error handling
    try {
        // Authenticate student
        $result = loginStudent($conn, $user_email, $password);

        if (isset($result['success'])) {
            // Additional session variables - make sure we set all needed ones
            $_SESSION['student_id'] = $result['student_id'];
            $_SESSION['student_name'] = $result['student_name'];
            $_SESSION['student_email'] = $result['student_email'];

            // Get full student info to set additional fields
            $student = getStudentInfoById($conn, $result['student_id']);

            // Set additional session variables
            $_SESSION['student_dept'] = $student['dept'];
            $_SESSION['student_dept_id'] = $student['dept_id'];
            $_SESSION['user_type'] = 'student';
            $_SESSION['user_logged_in'] = true;

            // Debug log
            error_log("Student login successful. ID: {$result['student_id']}, Name: {$result['student_name']}");
            error_log("Session variables set: " . print_r($_SESSION, true));

            // Redirect to dashboard
            header("Location: student_dashboard.php");
            exit;
        } else {
            $login_error = $result['error'];
            error_log("Student login failed: $login_error");
        }
    } catch (Exception $e) {
        $login_error = "An error occurred during login. Please try again.";
        error_log("Exception during student login: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #333;
            font-size: 28px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px 20px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="form-header">
                <h1><i class="fas fa-book-reader"></i> Library Management System</h1>
                <h2>Student Login</h2>
            </div>

            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" name="login_form_submitted" class="btn btn-primary">Login</button>
            </form>

            <div class="mt-4 text-center">
                <p>Don't have an account? <a href="student_register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>