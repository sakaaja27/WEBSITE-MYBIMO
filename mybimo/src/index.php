
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>My Bimo</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="./assets-frontend/img/mybimo.png" rel="icon">
  <link href="assets-frontend/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets-frontend/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets-frontend/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets-frontend/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets-frontend/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets-frontend/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets-frontend/css/main.css" rel="stylesheet">

</head>

<body class="index-page">
  <header id="header" class="header navbar navbar-expand-lg fixed-top">
    <div class="container-fluid container-xl">
      <div class="d-flex align-items-center justify-content-between w-100">
        <!-- Logo -->
        <a href="index.html" class="navbar-brand d-flex align-items-center">
          <img src="assets-frontend/img/mybimo.png" alt="MyBimo Logo" class="img-fluid" style="max-height: 40px;">
        </a>

        <!-- Navigasi Desktop -->
        <nav class="navbar-nav d-none d-lg-flex align-items-center ms-auto">
          <div class="d-flex align-items-center gap-3">
            <a href="auth/login.php" class="btn btn-primary">Login</a>
            <a href="#about" class="btn btn-primary">Get Started</a>
          </div>
        </nav>

        <!-- Tombol Mobile Hamburger -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <!-- Off-Canvas Mobile Menu -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="d-flex flex-column gap-3">
        <div class="d-grid gap-2">
          <a href="auth/login.php" class="btn btn-outline-primary btn-lg">Login</a>
          <a href="#about" class="btn btn-primary btn-lg">Get Started</a>
        </div>

        <div class="mt-3">
          <nav class="nav flex-column">

          </nav>
        </div>
      </div>
    </div>
  </div>

  <main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <img src="assets-frontend/img/lingkaran.png" alt="Background Circle" class="circle-background">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1>Empowering Learning,<br>Anytime, Anywhere <br>with MyBimo!</h1>
            <div class="d-flex">
              <a href="#about" class="btn-get-started">Get Started</a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-out" data-aos-delay="200">
            <img src="assets-frontend/img/img1.png" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>
    </section>
    <!-- /Hero Section -->

    <!-- Clients Section -->
    <section id="clients" class="clients section light-background">
      <div class="container" data-aos="zoom-in">
        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "spaceBetween": 5
                },
                "480": {
                  "slidesPerView": 3,
                  "spaceBetween": 10
                },
                "640": {
                  "slidesPerView": 4,
                  "spaceBetween": 20
                },
                "992": {
                  "slidesPerView": 5,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 6,
                  "spaceBetween": 40
                }
              }
            }
          </script>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 1.png" class="img-fluid"></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 2.png" class="img-fluid"></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 3.png" class="img-fluid"></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 4.png" class="img-fluid"></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 5.png" class="img-fluid"></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 6.png" class="img-fluid"></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 7.png" class="img-fluid"></div>
            <!-- Clients Section (lanjutan) -->
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 8.png" class="img-fluid"></div>
          </div>
        </div>
      </div>
    </section>
    <!-- /Clients Section -->

    <!-- Why Us Section -->
    <section id="why-us" class="section why-us light-background" data-builder="section">
      <div class="container-fluid">
        <div class="row gy-4 align-items-center">
          <!-- Kolom untuk teks -->
          <div class="col-lg-7 col-12 order-2 order-lg-0 text-center text-lg-start">
            <div class="" data-aos="fade-up" data-aos-delay="100">
              <h2 class="text-responsive text-warning fw-bold fs-2"><span>Free. Fun. Effective.</span></h2>
              <p class="text-responsive text-white">
                Learning English has never been this enjoyable with MyBimo! Our innovative platform offers a complete package to help you master vocabulary, grammar, pronouns, listening, and reading skills. With interactive quizzes, a bank of practice questions, and engaging educational videos, learning is both rewarding and fun.
              </p>
            </div>
          </div>

          <!-- Kolom untuk gambar -->
          <div class="col-lg-5 col-12 order-1 order-lg-1">
            <img src="assets-frontend/img/top1.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100">
          </div>
        </div>
      </div>
    </section>
    <!-- /Why Us Section -->

    <!-- Skills Section -->
    <section id="skills" class="section skills light-background" data-builder="section">
      <div class="container-fluid">
        <div class="row gy-4 align-items-center">
          <!-- Kolom untuk gambar -->
          <div class="col-lg-5 col-12 order-0 order-lg-0">
            <img src="assets-frontend/img/top3.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100">
          </div>

          <!-- Kolom untuk teks -->
          <div class="col-lg-7 col-12 order-1 order-lg-1 text-center text-lg-start">
            <div class="" data-aos="fade-up" data-aos-delay="100">
              <h2 class="text-responsive text-warning fw-bold fs-2"><span>Stay Motivated.</span></h2>
              <p class="text-responsive text-white fs-5">
                We make English learning exciting with fun challenges, personalized progress tracking, and motivating reminders. Get support from our rich library of exercises and master the language with ease!
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /Skills Section -->

    <!-- Why Us 3 Section -->
    <section id="why-us3" class="section why-us3 gradasi background" data-builder="section">
      <div class="container-fluid">
        <div class="row gy-4">
          <div class="col-lg-7 col-12 order-2 order-lg-0 text-center text-lg-start">
            <div class="" data-aos="fade-up" data-aos-delay="100">
              <h2 class="text-responsive text-warning fw-bold fs-2"><span>Personalized Learning.</span></h2>
              <p class="text-responsive text-white">
                Tailored to your level and pace, MyBimo ensures you get the most out of every lesson. Whether you're a beginner or looking to enhance your skills, we've got you covered with adaptive content to suit your needs.
              </p>
            </div>
          </div>

          <div class="col-lg-5 col-12 order-1 order-lg-1">
            <img src="assets-frontend/img/top2.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100">
          </div>
        </div>
      </div>
    </section>
    <!-- /Why Us Section -->

    <!-- Download Section -->
    <section id="downloadapp" class="download-section container-fluid text-center" data-builder="section">
      <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
          <div data-aos="fade-up">
            <h1 class="display-4 mb-4">Learning <br>Anytime, Anywhere <br>with MyBimo!</h1>
            <div class="d-flex justify-content-center mb-4">
              <a href="#" class="btn btn-primary btn-lg">Download Now!</a>
            </div>
          </div>

          <div class="row mt-4">
            <div class="">
              <img src="assets-frontend/img/appdownload.png" class="img-fluid" alt="App Download">
            </div>
            <div class="">
              <img src="assets-frontend/img/gambarpalingbawah.PNG" class="img-fluid" alt="App Screenshot">
            </div>
          </div>
          <div class="">
            <img src="assets-frontend/img/gambarbawah.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100">
          </div>
        </div>
      </div>
    </section>
    <!-- /Download Section -->

    <!-- Mobile Menu Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="auth/login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">Get Started</a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets-frontend/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets-frontend/vendor/php-email-form/validate.js"></script>
    <script src="assets-frontend/vendor/aos/aos.js"></script>
    <script src="assets-frontend/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets-frontend/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets-frontend/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets-frontend/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets-frontend/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="assets-frontend/js/main.js"></script>
  </main>
</body>

</html>