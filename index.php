<?php
include_once(__DIR__ . "/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/auth.php");

// Login Functionality
if (isset($_POST['submit'])) {
    $res = login($conn, $_POST);
    if ($res['status'] == true) {
        $_SESSION['is_login'] = true;
        $_SESSION['user'] = $res['user'];
        header("Location: " . BASE_URL . 'dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = "Invalid login information";
    }
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link href="assets/images/BAUST_LOGO.png" rel="icon">

    <script src="./assets/js/1c26fb5c51.js" crossorigin="anonymous"></script>
    <title>Login | Baust Library</title>

    <style>
        body {
            background-color: #e8f5e9;
            /* A professional light green background */
        }

        .login-form .card {
            border: none;
            /* Removes default card border for a cleaner look */
            background-color: #ffffff;
            /* Clean white background for the login card */
            max-width: 900px;
        }

        /* Ensures the BAUST logo in the title is sized appropriately */
        .baust_logo_index {
            width: 40px;
            height: auto;
            margin-right: 8px;
        }

        /* Styling for form input fields */
        .input1 {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .input1:focus {
            color: #212529;
            background-color: #fff;
            border-color: #004d40;
            /* BAUST green for focus */
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(0, 77, 64, 0.25);
        }

        /* Professional button style */
        .button-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background-color: #004d40;
            /* Primary BAUST Green */
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            width: 100%;
            /* Make button full-width */
        }

        .button-login:hover {
            background-color: #00382e;
            /* Darker green on hover */
        }

        .button-login .icon {
            width: 1.2em;
            height: 1.2em;
            margin-left: 8px;
        }

        /* Link styling */
        .link-underline-light {
            color: #6c757d;
            text-decoration: none;
        }

        .link-underline-light:hover {
            color: #004d40;
            text-decoration: underline;
        }

        /* Removing the wave background for a cleaner look */
        .wave {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center vh-100 ">
        <div class="row ">
            <div class="col-md-12 login-form ">
                <div class="card mb-3 shadow-lg p-3 mb-5 bg-body rounded">
                    <div class="row g-0 ">
                        <div class="col-md-5">
                            <img src="assets/images/IMG_20221121_095126_tigr-01.jpeg" class="img-fluid rounded shadow-lg bg-body rounded" />
                        </div>
                        <div class="col-md-7 ">
                            <div class="card-body ms-3 ">
                                <h1 class="card-title text-uppercase fw-bold ">
                                    <img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="baust" class="baust_logo_index mb-1">
                                    Baust library
                                </h1>
                                <p class="card-text mb-4 ms-2">Enter email and password to login</p>
                                <?php
                                if (file_exists(DIR_URL . "include/alerts.php")) {
                                    include_once(DIR_URL . "include/alerts.php");
                                } elseif (file_exists(__DIR__ . "/include/alerts.php")) {
                                    include_once(__DIR__ . "/include/alerts.php");
                                }
                                ?>
                                <form method="post" action="<?php echo BASE_URL ?>">
                                    <div class="mb-4">
                                        <label class="form-label ms-2">Email address:</label><br>
                                        <input type="email" class="input1" name="email" required />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label ms-2 ">Password:</label><br>
                                        <input type="password" class="input1 mb-2" name="password" required />
                                    </div>
                                    <button type="submit" name="submit" class="button-login mb-5">Login<svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                                            <path clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" fill-rule="evenodd"></path>
                                        </svg></button>
                                </form>

                                <hr />

                                <a href="./forgot-password.php" class="card-text text-center link-underline-light ms-2">Forgot Password?</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./assets/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>