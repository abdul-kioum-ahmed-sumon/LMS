<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Library Management System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/images/LMS_fabicon.png" rel="icon">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets_2/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets_2/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets_2/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets_2/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets_2/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets_2/css/main.css" rel="stylesheet">

</head>

<body class="container1">

  <header id="header" class="header fixed-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:info@baust.edu.bd">info@baust.edu.bd</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>+8801769-675559</span></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding">

      <di class="container position-relative d-flex align-items-center justify-content-between">
        <a href="landing_page.php" class="logo d-flex align-items-center">
          <h1 class=""><img src="assets/images/LMS_fabicon.png" alt="baust" class="baust_logo  ">Library Management System</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero" class="">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="student_login.php" target="_self">Student Login</a></li>
            <li><a href="index.php" target="_self">Admin Login</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </di>
    </div>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <img src="assets/images/Library_bg (3).jpg" alt="" data-aos="fade-in">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-start">
          <div class="col-lg-8">
            <h1 class="">Welcome to Library Management System!</h1>
            <p>Introducing Our Advanced Library Management System. </p>
            <a href="#about" class="btn-get-started">Get Started</a>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->


    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="">About Us<br></span>
        <h2 class="">About Us<br></h2>
        <p>Welcome to our Library Management System! Our innovative platform is designed to streamline library operations and enhance the user experience for both librarians and patrons.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="100">
            <img src="assets/images/LIBRARY-MANAGEMENT-SYSTEM.png" class="img-fluid" alt="">
          </div>

          <div class="col-lg-6 order-2 order-lg-1 content" data-aos="fade-up" data-aos-delay="200">
            <h3>Revolutionizing Library Operations.</h3>
            <p class="fst-italic">
              The core functionalities of our library management system (LMS) can be grouped into several key facilities.
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> <span>Resource Management</span></li>
              <li><i class="bi bi-check-circle"></i> <span>User Management</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Circulation Management</span></li>
            </ul>
            <a href="index.php" class="read-more"><span>See demo</span><i class="bi bi-arrow-right"></i></a>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Cards Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="">Services</span>
        <h2>Services</h2>
        <p>A library management system is software that is designed to manage all the functions of a library. It helps librarian to maintain the database of new books and the books that are borrowed by members along with their due dates.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="bi bi-activity"></i>
              </div>
              <a href="#" class="stretched-link" style="cursor:default">
                <h3>Catalog Circulation</h3>
              </a>
              <p>To keep track of book movement and maintain an electronic inventory of the library's holdings, the Library Management Software provides a seamless process of any book's whereabouts at any given time..</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-broadcast"></i>
              </div>
              <a href="#" class="stretched-link" style="cursor:default">
                <h3>Self Management</h3>
              </a>
              <p>To self-check-in and self-check-out books, the members of digital libraries can log in, search for, choose, issue, and return books on their own thanks to the library management system software..</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-easel"></i>
              </div>
              <a href="#" class="stretched-link " style="cursor:default">
                <h3>Membership Administration</h3>
              </a>
              <p>Keeping a thorough database of the members, the Management System stores each user's name, ID, and password for aiding in determining the member's history. The software is user-friendly and improves the effectiveness of the librarian and library administration.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-bounding-box-circles"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>User-Friendly System</h3>
              </a>
              <p>Simple And Easy To Use.
                Online And Offline Storage Of Data.
                Automatically Updates And Backups Data.
                Flexible And Can Be Fully Configurable.</p>
              <a href="#" class="stretched-link" style="cursor:default"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-calendar4-week"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Cost Effective</h3>
              </a>
              <p>Eliminate The Need For Extensive Paperwork.
                Maintenance Overheads And Operation Costs Are Reduced.
                Eliminates The Need For Manual Entries.
                Makes The Database Error-Free And Accurate.</p>
              <a href="#" class="stretched-link" style="cursor:default"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-chat-square-text"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Management Of Fees</h3>
              </a>
              <p>To maintain each member's account and collect membership payments, the Library System covers it all. The fine that is owed for lost, damaged, or non-returned books is calculated by the program. The mechanism notifies the members of the fines.</p>
              <a href="#" class="stretched-link" style="cursor:default"></a>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->



    </section><!-- /Team Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span class="">Contact</span>
        <h2 class="">Contact</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4 container-fluid d-flex justify-content-center">

          <div class=" col-lg-3">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt"></i>
              <h3>Address</h3>
              <p>Saidpur Cantonment, Saidpur</p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-3 col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone"></i>
              <h3>Call Us</h3>
              <p>+8801769-675559</p>
            </div>
          </div><!-- End Info Item -->

          <div class="col-lg-3 col-md-6">
            <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope"></i>
              <h3>Email Us</h3>
              <p>info@baust.edu.bd</p>
            </div>
          </div><!-- End Info Item -->

        </div>

        <div class="row gy-4 mt-1 container-fluid d-flex justify-content-center">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3593.250610915543!2d88.91524227616145!3d25.762284577351686!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39e35189fdfbb01d%3A0x15261313ab3f22f4!2sBangladesh%20Army%20University%20of%20Science%20and%20Technology!5e0!3m2!1sen!2sbd!4v1713509952882!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div><!-- End Google Maps -->
    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer position-relative">

    <div class="container footer-top">
      <div class="footer-about">
        <a href="landing_page.php" class="logo">Library Management System</a>
        <div class="footer-contact pt-3">
          <p>Saidpur Cantonment, Saidpur</p>
          <p class="mt-3"><strong>Phone:</strong> <span>+8801769-675559</span></p>
          <p><strong>Email:</strong> <span>info@baust.edu.bd</span></p>
        </div>
        <div class="social-links d-flex mt-4">
          <a href=""><i class="bi bi-twitter"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
    </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1">Library Management System</strong> <span>All Rights Reserved</span></p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets_2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets_2/vendor/php-email-form/validate.js"></script>
  <script src="assets_2/vendor/aos/aos.js"></script>
  <script src="assets_2/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets_2/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets_2/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets_2/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets_2/js/main.js"></script>

</body>

</html>