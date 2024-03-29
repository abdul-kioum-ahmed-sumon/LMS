<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/auth.php");

// If already logged in
if (isset($_SESSION['is_user_login'])) {
    header("LOCATION: " . BASE_URL . 'dashboard.php');
    exit;
}

// Login Functionality (pizza123)
if (isset($_POST['submit'])) {
    $res = login($conn, $_POST);
    if ($res['status'] == true) {
        $_SESSION['is_user_login'] = true;
        $_SESSION['user'] = $res['user'];
        header("LOCATION: " . BASE_URL . 'dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = "Invalid login information";
        header("LOCATION: " . BASE_URL);
        exit;
    }
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

    <script src="./assets/js/1c26fb5c51.js" crossorigin="anonymous"></script>
    <title>Login | Baust Library</title>
</head>

<body style="background-image: linear-gradient( 65.9deg,  rgba(85,228,224,1) 5.5%, rgba(75,68,224,0.74) 54.2%, rgba(64,198,238,1) 55.2%, rgba(177,36,224,1) 98.4% );">
    <div class="container d-flex align-items-center justify-content-center vh-100 ">
        <div class="row ">
            <div class="col-md-12 login-form ">
                <div class="card mb-3 shadow-lg p-3 mb-5 bg-body rounded " style="background-image: linear-gradient( 174.2deg,  rgba(255,244,228,1) 7.1%, rgba(240,246,238,1) 67.4% ); max-width: 900px">
                    <div class="row g-0 ">
                        <div class="col-md-5  ">
                            <img src="assets/images/DSLR pixel by Riyan20230214_112633_R15- DSLR Pixel by Riyan.PORTRAIT.jpg" class="img-fluid rounded shadow-lg bg-body rounded" />
                        </div>
                        <div class="col-md-7 ">
                            <div class="card-body ms-3 ">
                                <h1 class="card-title text-uppercase fw-bold ">
                                    <img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="baust" class="baust_logo_index mb-1">
                                    Baust library
                                </h1>
                                <p class="card-text mb-4 ms-2">Enter email and password to login</p>
                                <?php include_once(DIR_URL . "/include/alerts.php"); ?>
                                <form method="post" action="<?php echo BASE_URL ?>">
                                    <div class="mb-4">
                                        <label class="form-label ms-2">Email address:</label><br>
                                        <input type="email" class=" input1" name="email" required />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label ms-2 ">Password:</label><br>
                                        <input type="password" class=" input1 mb-2" name="password" required />
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