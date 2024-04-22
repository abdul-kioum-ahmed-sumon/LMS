</html>
<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/dashboard.php");

$counts = getCounts($conn);
$tabs = getTabData($conn);


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="<?php echo BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo BASE_URL ?>assets/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo BASE_URL ?>assets/css/style.css" />
  <link href="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" rel="icon">
  <script src="<?php echo BASE_URL ?>assets/js/1c26fb5c51.js" crossorigin="anonymous"></script>
  <title>BAUST LIBRARY</title>
</head>

<!--Top Navbar Start-->
<nav class=" navbar navbar-expand-lg navbar-dark bg-dark fixed-top " style="background-image: radial-gradient(circle, #051937, #00172b, #00141e, #000d11, #010202);">
  <div class="container-fluid bg-hess ">
    <!--offcanvar trigger start-->
    <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!--offcanvar trigger start-->
    <p class="m-0"><img src="<?php echo BASE_URL ?>assets/images/BAUST_LOGO.png" alt="baust" class="baust_logo  "></p>

    <a class="navbar-brand fw-bold text-uppercase me-auto"> BAUST Library</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <form class="d-flex ms-auto" role="search">
        <div class="input-group my-3 my-lg-0">
          <input type="text" class="form-control" placeholder="Search" aria-describedby="button-addon2" />
          <button class="btn btn-outline-secondary bg-primary text-white" type="button" id="button-addon2">
            <i class="fa-solid fa-magnifying-glass"></i></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</nav>
<!--Offcanvas Menu start-->
<div class="offcanvas offcanvas-start bg-dark text-white sidebar-nav bg-hess " tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-body" style="background-image: radial-gradient(circle, #051937, #00172b, #00141e, #000d11, #010202);">
    <nav class="navbar-dark">
      <ul class="navbar-nav">
        <li>
          <div class="text-secondary small fw-bold text-uppercase">
            General
          </div>
        </li>
        <li>
          <a href="<?php echo BASE_URL ?>User/homepage.php" class="nav-link"><i class="fa-solid fa-display  me-2" style="color: #b2bafb;"></i>Dashboard
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL ?>User/Magazine.php" class="nav-link"><i class="fas fa-newspaper me-2" style="color: #b2bafb;"></i>Magazine
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL ?>User/qb_read.php" class="nav-link"><i class="fas fa-newspaper me-2" style="color: #b2bafb;"></i>Question Bank
          </a>
        </li>
        <li>
          <a href="<?php echo BASE_URL ?>User/faq.php" class="nav-link"><i class="fa-solid fa-circle-question me-2" style="color: #b2bafb;"></i>FAQ
          </a>
        </li>
        <li class="my-0">
          <hr />
        </li>
        <li class="nav-item">
          <a href="logout.php" class="nav-link"><i class="fa-solid fa-right-from-bracket me-2" style="color: #b2bafb;"></i> Logout</a>
        </li>
      </ul>
    </nav>
  </div>
</div>

<!--Offcanvas Menu end-->

<body class="container1 mt-3">

  <main class="mt-5 pt-3" style="box-sizing:border-box; padding: 20px">

    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
      }

      th,
      td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
      }

      th {
        background-color: #f2f2f2;
      }

      tr:nth-child(even) {
        background-color: #f2f2f2;
      }
    </style>
    </head>


    <!-- Page Heading and Add Question Button -->
    <h2 mt-5>Data Table</h2>

    <table id="questionTable">
      <thead>
        <tr>
          <th>Course</th>
          <th>Subject</th>
          <th>Year</th>
          <th>Semester</th>
          <th>Question</th>
        </tr>
      </thead>
      <tbody>

        <?php
        // PHP code to retrieve and display data from the database
        $db_server = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "lms";
        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

        $sql = "SELECT * FROM previous_questions";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
              <td>" . $row['course'] . "</td>
              <td>" . $row['subject'] . "</td>
              <td>" . $row["year"] . "</td>
              <td>" . $row["semester"] . "</td>
              <td>" . $row["question"] . "</td>
            </tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- JavaScript for Dynamic Table Update -->
    <script>
      // Function to update the table dynamically
      function updateTable() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("questionTable").innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "get_updated_questions.php", true);
        xhttp.send();
      }

      // Call updateTable function when the page loads
      window.onload = function() {
        updateTable();
      };
    </script>

  </main>
  <!--Main content end-->
  <?php include_once(DIR_URL . "include/footer.php") ?>