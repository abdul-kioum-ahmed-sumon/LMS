<?php
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/student_auth.php");

$error = '';
$success = '';

// Registration process
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $dept_id = $_POST['dept_id'];
    $dept = $_POST['dept'];
    $phone_no = $_POST['phone_no'];
    $address = $_POST['address'];

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($dept_id) || empty($dept)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Register the student
        $result = registerStudent($conn, $_POST);

        if (isset($result['success'])) {
            $success = "Registration successful! Please wait for admin verification before logging in.";
        } else {
            $error = $result['error'];
        }
    }
}

// Get departments for dropdown
$sql = "SELECT DISTINCT name FROM categories";
$result = $conn->query($sql);
$departments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Library Management System</title>
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #212529;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="card">
            <div class="card-header bg-white text-center py-4">
                <img src="<?php echo BASE_URL; ?>assets/images/BAUST_LOGO.png" alt="BAUST Logo" style="max-height: 80px;">
                <h1 class="mt-2 fw-bold text-uppercase">Library Management System</h1>
                <h3 class="text-muted">Student Registration</h3>
            </div>

            <div class="card-body p-4">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success; ?>
                        <br>
                        <a href="student_login.php" class="btn btn-sm btn-success mt-2">Go to login page</a>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dept_id" class="form-label">Student ID Number</label>
                                <input type="text" class="form-control" id="dept_id" name="dept_id" required>
                                <small class="form-text text-muted">Your university/college ID number</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dept" class="form-label">Department</label>
                                <select class="form-select" id="dept" name="dept" required>
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone_no" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_no" name="phone_no" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                        <a href="student_login.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Login
                        </a>
                        <button type="submit" name="register" class="btn btn-primary px-4">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer bg-white text-center py-3">
                <img src="<?php echo BASE_URL; ?>assets/images/LIBRARY-MANAGEMENT-SYSTEM.png" alt="LMS" style="max-height: 40px;">
                <p class="small text-muted mt-2 mb-0">© <?php echo date('Y'); ?> BAUST Library Management System</p>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>