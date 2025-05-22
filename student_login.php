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
    <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #1e2124;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 900px;
            height: 560px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            display: flex;
        }

        .image-side {
            width: 45%;
            position: relative;
            overflow: hidden;
        }

        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .form-side {
            width: 55%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333;
        }

        .login-subtitle {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
        }

        .login-form {
            width: 100%;
            max-width: 340px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-check-input {
            margin-right: 8px;
        }

        .form-check-label {
            color: #555;
            font-size: 14px;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #0d6efd;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.2s;
        }

        .login-btn:hover {
            background-color: #0b5ed7;
        }

        .register-link {
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        .register-link a {
            color: #0d6efd;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            width: 100%;
            max-width: 340px;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="image-side">
            <img src="<?php echo BASE_URL ?>assets/images/Library_bg (2).jpg" alt="Library Image">
        </div>
        <div class="form-side">
            <img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="BAUST Logo" class="logo">
            <h1 class="login-title">Library Management System</h1>
            <p class="login-subtitle">Student Login</p>

            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" name="login_form_submitted" class="login-btn">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="student_register.php">Register here</a></p>
        </div>
    </div>

    <script src="<?php echo BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>