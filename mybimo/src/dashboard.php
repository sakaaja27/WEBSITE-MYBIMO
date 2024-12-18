<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets-frontend/css/dashboard.css" rel="stylesheet">
  <title>MyBimo Dashboard</title>
  <style>
    .button-box {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin: 20px 0;
    }

    .card-container {
      position: relative;
    }

    .card-container:hover .button-overlay {
      display: block;
    }
  </style>
</head>

<body class="dashboard-page">
  <header id="header" class="header navbar navbar-expand-lg">
    <div class="container-fluid container-xl">
      <a href="index.html" class="navbar-brand d-flex align-items-center">
        <img src="assets-frontend/img/mybimo.png" alt="MyBimo Logo" class="img-fluid" style="max-height: 40px;">
      </a>

      <!-- Tombol Mobile Hamburger -->
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navigasi Desktop -->
      <nav class="navbar-nav d-none d-lg-flex align-items-center ms-auto">
        <div class="d-flex align-items-center gap-3">
          <a href="auth/login.php" class="btn btn-primary">Login</a>
          <a href="#about" class="btn btn-primary">Get Started</a>
        </div>
      </nav>
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
      </div>
    </div>
  </div>

  <!-- First Section -->
  <section id="first" class="first section dark-background">
    <div class="container">
      <div class="row gy-4 align-items-center">
        <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start" data-aos="zoom-out">
          <h1>Empowering Learning,<br>Anytime, Anywhere <br>with MyBimo!</h1>
          <h5>Improve your English on MyBimo</h5>
        </div>
        <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-out" data-aos-delay="200">
          <img src="assets-frontend/img/logodashboard1.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>
  </section>
  <!-- /First Section -->

  <!-- Button Box -->
  <div class="container my-4">
    <div class="row text-center">
      <div class="col-6 col-md-4 mb-3">
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#imageModal">
          <img src="assets-frontend/img/buttondashboard/button1.png" alt="Button 1" class="img-fluid">
        </button>
      </div>
      <div class="col-6 col-md-4 mb-3">
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#imageModal">
          <img src="assets-frontend/img/buttondashboard/button2.png" alt="Button 2" class="img-fluid">
        </button>
      </div>
      <div class="col-6 col-md-4 mb-3">
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#imageModal">
          <img src="assets-frontend/img/buttondashboard/button3.png" alt="Button 3" class="img-fluid">
        </button>
      </div>
      <div class="col-6 col-md-4 mb-3">
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#imageModal">
          <img src="assets-frontend/img/buttondashboard/button4.png" alt="Button 4" class="img-fluid">
        </button>
      </div>
      <div class="col-6 col-md-4 mb-3">
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#imageModal">
          <img src="assets-frontend/img/buttondashboard/button5.png" alt="Button 5" class="img-fluid">
        </button>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Download Aplikasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img src="assets-frontend/img/popup.png" alt="Aplikasi Preview" class="img-fluid">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <a href="link-to-your-app-download" class="btn btn-primary" download>Download Aplikasi</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Second Section -->
  <section id="second">
    <div class="container text-center">
      <h1 id="buath1" class="feature">
        <i id="bintangnya" class="fa fa-star" aria-hidden="true"></i> Interesting features on MyBimo!
      </h1>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
        <div class="col">
          <div class="card h-100">
            <img src="assets-frontend/img/Card/card1.png" class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title">Vocabulary</h5>
              <p class="card-text">
                <i class="fa fa-star" aria-hidden="true"></i> Interactive and fun vocabulary learning.<br>
                <i class="fa fa-star" aria-hidden="true"></i> Daily new words and quizzes.<br>
                <i class="fa fa-star" aria-hidden="true"></i> Boosts speaking and comprehension skills.
              </p>
              <a href="#" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#imageModal">See Details</a>
            </div>
            <div class="card-footer">
              <small class="text-body-secondary">Last updated 3 mins ago</small>
            </div>
          </div>
        </div>
        <!-- Repeat similar structure for other cards -->
        <div class="col">
          <div class="card h-100">
            <img src="assets-frontend/img/Card/card2.png" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title">Grammar</h5>
              <p class="card-text">
                <i class="fa fa-star" aria-hidden="true"></i> Learn grammar with real-life examples.<br>
                <i class="fa fa-star" aria-hidden="true"></i> Easy-to-understand explanations.<br>
                <i class="fa fa-star" aria-hidden="true"></i> Practical exercises for all levels.
              </p>
              <a href="#" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#imageModal">See Details</a>
            </div>
            <div class="card-footer">
              <small class="text-body-secondary">Last updated 3 mins ago</small>
            </div>
          </div>
        </div>
        <!-- Add more cards as needed -->
      </div>
    </div>
  </section>
  <!-- /Second Section -->

  <!-- Third Section -->
  <section id="third">
    <div class="promo-wrapper d-flex flex-column flex-md-row">
      <div class="left-side text-center text-md-start mb-4 mb-md-0">
        <h5>
          Lihat beragam <br>
          <strong>promo spesial</strong> <br>
          untukmu di <br>
          <strong>bulan November</strong>
        </h5>
        <img src="assets-frontend/img/Component 12.png" alt="Gambar Promo" class="promo-icon img-fluid">
      </div>
      <div class="carousel-container">
        <div class="right-side">
          <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <img src="assets-frontend/img/carousel/carousel1.jpg" class="d-block w-100" alt="Promo 1">
                  </div>
                  <div class="col-12 col-md-6">
                    <img src="assets-frontend/img/carousel/carousel2.png" class="d-block w-100" alt="Promo 2">
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <img src="assets-frontend/img/carousel/carousel3.png" class="d-block w-100" alt="Promo 3">
                  </div>
                  <div class="col-12 col-md-6">
                    <img src="assets-frontend/img/carousel/carousel1.jpg" class="d-block w-100" alt="Promo 4">
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <img src="assets-frontend/img/carousel/carousel2.png" class="d-block w-100" alt="Promo 5">
                  </div>
                  <div class="col-12 col-md-6">
                    <img src="assets-frontend/img/carousel/carousel3.png" class="d-block w-100" alt="Promo 6">
                  </div>
                </div>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /Third Section -->

  <!-- Fourth Section -->
    <section id="fourth">
    <div id="cardCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <div class="card-container">
              <img src="assets-frontend/img/imgbawah/1.png" alt="Card Image" class="card-image img-fluid">
              <a href="#" class="button-overlay" data-bs-toggle="modal" data-bs-target="#imageModal">Lihat Detail</a>
            </div>
            <div class="card-container">
              <img src="assets-frontend/img/imgbawah/2.png" alt="Card Image" class="card-image img-fluid">
              <a href="#" class="button-overlay" data-bs-toggle="modal" data-bs-target="#imageModal">Lihat Detail</a>
            </div>
            <div class="card-container">
              <img src="assets-frontend/img/imgbawah/3.png" alt="Card Image" class="card-image img-fluid">
              <a href="#" class="button-overlay" data-bs-toggle="modal" data-bs-target="#imageModal">Lihat Detail</a>
            </div>
          </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <div class="card-container">
              <img src="assets-frontend/img/imgbawah/1.png" alt="Card Image" class="card-image img-fluid">
              <a href="#" class="button-overlay" data-bs-toggle="modal" data-bs-target="#imageModal">Lihat Detail</a>
            </div>
            <div class="card-container">
              <img src="assets-frontend/img/imgbawah/2.png" alt="Card Image" class="card-image img-fluid">
              <a href="#" class="button-overlay" data-bs-toggle="modal" data-bs-target="#imageModal">Lihat Detail</a>
            </div>
            <div class="card-container">
              <img src="assets-frontend/img/imgbawah/3.png" alt="Card Image" class="card-image img-fluid">
              <a href="#" class="button-overlay" data-bs-toggle="modal" data-bs-target="#imageModal">Lihat Detail</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Navigasi Carousel -->
      <button class="carousel-control-prev" type="button" data-bs-target="#cardCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </section>
  <!-- /Fourth Section -->

  <!-- Fifth Section -->
  <section id="fifth">
    <div class="image-section py-5">
      <div class="container">
        <div class="row text-center">
          <div class="col-md-4 mb-4">
            <div class="image-card">
              <img src="assets-frontend/img/review/1.png" alt="Image 1" class="img-fluid">
              <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#imageModal">Download</a>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="image-card">
              <img src="assets-frontend/img/review/2.png" alt="Image 2" class="img-fluid">
              <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#imageModal">Download</a>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="image-card">
              <img src="assets-frontend/img/review/3.png" alt="Image 3" class="img-fluid">
              <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#imageModal">Download</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /Fifth Section -->

  <!-- Sixth Section -->
  <section id="sixth">
    <div class="background-section py-5">
      <div class="text-center">
        <h1 id="buath1" class="feature">
          <i></i> Why we should learn English according to experts
        </h1>
      </div>
      <div class="container">
        <div class="row text-center">
          <div class="col-md-4 mb-4">
            <div class="image-card">
              <img src="assets-frontend/img/paraahli/1.png" alt="Image 1" class="img-fluid">
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="image-card">
              <img src="assets-frontend/img/paraahli/2.png" alt="Image 2" class="img-fluid">
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="image-card">
              <img src="assets-frontend/img/paraahli/3.png" alt="Image 3" class="img-fluid">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /Sixth Section -->

  <!-- Seventh Section -->
  <section id="seventh" class="py-5">
    <div class="container">
      <div class="image-container text-center position-relative">
        <img src="assets-frontend/img/zoom.png" alt="Zoom Image" class="img-fluid">
      </div>
    </div>
  </section>
  <!-- /Seventh Section -->

  <!-- Eighth Section -->
  <section id="eighth-section" class="ruangguru-section">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-6 text-container text-center d-flex flex-column justify-content-center align-items-center">
          <h1><strong>Coba belajar langsung di aplikasi MyBimo</strong></h1>
          <p>Download sekarang!</p>
          <div class="button-container m-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#imageModal">Download</button>
          </div>
        </div>
        <div class="col-12 col-md-6 image-container d-flex justify-content-center align-items-center">
          <img src="assets-frontend/img/hp.png" alt="Gambar HP" class="img-fluid">
        </div>
      </div>
    </div>
  </section>
  <!-- /Eighth Section -->

  <!-- Ninth Section -->
  <section id="ninth-section">
    <div class="logo-container text-center">
      <img src="assets-frontend/img/mybimo.png" alt="Logo" class="img-fluid">
    </div>
  </section><!-- /Ninth Section -->

  <!-- Bootstrap JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>