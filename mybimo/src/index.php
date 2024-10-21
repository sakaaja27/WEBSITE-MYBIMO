<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Arsha Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets-frontend/img/favicon.png" rel="icon">
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
  <link href="assets-frontend-frontend/css/main.css" rel="stylesheet">

</head>

<body class="index-page">
 
  

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
       
        <img src="assets/img/mybimo.png" alt="MyBimo Logo" style="height: 40px;">
    </a>

      <nav id="navmenu" class="navmenu">
        
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-login" href="#about">Login</a>
      <a class="btn-getstarted" href="#about">Get Started</a>


    </div>
  </div>
 
  </header>

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
          <div class="col-lg-6 order-1 order-lg-2   " data-aos="zoom-out" data-aos-delay="200">
            <img src="assets-frontend/img/img1.png" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

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
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 1.png" class="img-fluid" ></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 2.png" class="img-fluid" ></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 3.png" class="img-fluid" ></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 4.png" class="img-fluid" ></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 5.png" class="img-fluid" ></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 6.png" class="img-fluid" ></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 7.png" class="img-fluid" ></div>
            <div class="swiper-slide"><img src="assets-frontend/img/clients/Component 8.png" class="img-fluid" ></div>
          </div>
        </div>

      </div>

    </section>
    <!-- /Clients Section -->
    <!-- Why Us Section -->
    <section id="why-us" class="section why-us light-background" data-builder="section">

      <div class="container-fluid">

        <div class="row gy-4">

          <div class="col-lg-7 d-flex flex-column justify-content-center order-2 order-lg-1">

            <div class="content px-xl-5" data-aos="fade-up" data-aos-delay="100">
              <h2><span>Free. Fun. Effective.</h2>
              <p>
                Learning English has never been this enjoyable with <br>MyBimo! Our innovative platform offers a complete <br>package to help you master vocabulary, grammar, <br>pronouns, listening, and reading skills. With interactive <br>quizzes, a bank of practice questions, and engaging <br>educational videos, learning is both rewarding and fun.
              </p>
            </div>
          </div>

          <div class="col-lg-5 order-1 order-lg-2 why-us-img">
            <img src="assets-frontend/img/why-us.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100" >
          </div>
        </div>

      </div>

    </section><!-- /Why Us Section -->

    <!-- Skills Section -->
<section id="skills" class="section skills light-background" data-builder="section">


  <div class="container-fluid">

    <div class="row gy-4">

      <!-- Ubah urutan kolom: Gambar di sebelah kiri (order 1) -->
      <div class="col-lg-5 order-1 order-lg-1 skills-img">
        <img src="assets-frontend/img/why-us2.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100">
      </div>

      <!-- Teks di sebelah kanan (order 2) -->
      <div class="col-lg-7 d-flex flex-column justify-content-center order-2 order-lg-2">
        <div class="content px-xl-5" data-aos="fade-up" data-aos-delay="100">
          <h2><span>Stay Motivated.</h2>
          <p>
            We make English learning exciting with fun <br>challenges, personalized progress <br>tracking, and motivating reminders. Get <br>support from our rich library of exercises <br>and master the language with ease!
          </p>
        </div>
      </div>

    </div>

  </div>

    </section><!-- /Skills Section -->

    <!-- Why Us 3 Section -->
    <section id="why-us3" class="section why-us3 gradasi background" data-builder="section">

      <div class="container-fluid">

        <div class="row gy-4">

          <div class="col-lg-7 d-flex flex-column justify-content-center order-2 order-lg-1">

            <div class="content px-xl-5" data-aos="fade-up" data-aos-delay="100">
              <h2><span>Personalized Learning.</h2>
              <p>
                Tailored to your level and pace, MyBimo <br>ensures you get the most out of every <br>lesson. Whether you're a beginner or <br>looking to enhance your skills, we’ve got <br>you covered with adaptive content to suit your needs.
              </p>
            </div>
          </div>

          <div class="col-lg-5 order-1 order-lg-2 why-us-img">
            <img src="assets-frontend/img/why-us3.png" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100" >
          </div>
        </div>

      </div>

    </section><!-- /Why Us Section -->

  <!-- Download Section -->
   <section id="downloadapp" class="download-section" data-builder="section">
     <!-- Title Tengah -->
    <div class="title-tengah" data-aos="fade-up">
      <h1>Learning <br>Anytime, Anywhere <br>with MyBimo!</h1>
      <!-- End Title Tengah -->
      <div class="d-flex">
        <a href="#about" class="btn-download-now">Download Now!</a>
      </div>
       
       <div class="gambar-tengah">
        <img src="assets-frontend/img/appdownload.png" class="gambardownload" >
       </div>

       <div class="gambar-tengah">
        <img src="assets-frontend/img/gambarpalingbawah.PNG" class="gambardownload" >
       </div>

       <div class="gambar-bawah">
        <img src="assets-frontend/img/gambarbawah.png" class="gambartengah" alt="" data-aos="zoom-in" data-aos-delay="100">
       </div>

       

       
       

    </div>
   </section>
<!-- /Download Section -->


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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

</body>

</html>