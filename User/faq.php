</html>
<?php
include_once("/Xampp/htdocs/lms-master/config/config.php");
include_once(DIR_URL . "config/database.php");
include_once(DIR_URL . "models/dashboard.php");

$counts = getCounts($conn);
$tabs = getTabData($conn);


?>

<?php
// Function to retrieve books from the database
function GetBooks($conn)
{
  // Perform your database query here to fetch books
  // Example query:
  $query = "select b.*, c.name as cat_name from books b 
        inner join categories c on c.id = b.category_id 
        order by id desc";
  $result = $conn->query($query);

  // Check if query was successful
  if ($result) {
    // Return the result set
    return $result;
  } else {
    // Return an empty result or handle the error as needed
    return false;
  }
}

$books = GetBooks($conn);
if (!$books) {
  $_SESSION['error'] = "Error fetching books: " . $conn->error;
}

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
        background-color: #f8f9fa;
        margin: 0;
        padding: 20px;
      }

      h1 {
        text-align: center;
        margin-bottom: 30px;
      }

      .faq-container {
        max-width: 600px;
        margin: 0 auto;
      }

      .faq-item {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        background-color: #fff;
      }

      .question {
        font-size: 18px;
        margin-bottom: 10px;
      }

      .answer {
        font-size: 16px;
        line-height: 1.5;
      }
    </style>
    <!-- Heading for the FAQ page -->
    <h1>Library Management FAQ</h1>

    <!-- Container for displaying FAQ items -->
    <div class="faq-container">
      <?php
      // Define an array of frequently asked questions and their answers
      $faq = array(
        "How do I borrow a book?" => "To borrow a book, visit the library's circulation desk and present your library card to the librarian. You can then choose the books you wish to borrow.",
        "Can I renew my borrowed books?" => "Yes, you can renew your borrowed books if they are not reserved by other users. You can do this by visiting the library's website or by contacting the circulation desk.",
        "What should I do if I lose a library book?" => "If you lose a library book, you are responsible for replacing it or paying for its replacement cost. Please inform the library staff immediately to discuss the necessary steps.",
        // Add more questions and answers as needed
      );

      // Display the FAQ items
      foreach ($faq as $question => $answer) {
        echo "<div class='faq-item'>";
        echo "<h3 class='question'>$question</h3>"; // Display the question
        echo "<p class='answer'>$answer</p>"; // Display the answer
        echo "</div>";
      }
      ?>
    </div>
  </main>
  <!--Main content end-->
  <?php include_once(DIR_URL . "include/footer.php") ?>