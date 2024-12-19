<?php
session_start();
require '../koneksi/koneksi.php'; 

$showEmailExistsAlert = isset($_SESSION['showEmailExistsAlert']) ? $_SESSION['showEmailExistsAlert'] : false;
$showSuccessAlert = isset($_SESSION['showSuccessAlert']) ? $_SESSION['showSuccessAlert'] : false;
$showAlert = isset($_SESSION['showAlert']) ? $_SESSION['showAlert'] : false;

unset($_SESSION['showEmailExistsAlert']);
unset($_SESSION['showSuccessAlert']);
unset($_SESSION['showAlert']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty($_POST['username']) || empty($_POST['email']) || 
        empty($_POST['phone']) || empty($_POST['password'])) {
        $_SESSION['showAlert'] = 'Semua field harus diisi';
        header("Location: register.php");
        exit();
    }

    // Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 0;

    // Validasi nomor telepon
    if (!preg_match('/^[0-9]{1,12}$/', $phone)) {
        $_SESSION['showAlert'] = 'Nomor telepon hanya boleh mengandung angka dan maksimal 12 digit.';
        header("Location: register.php");
        exit();
    }

    // Cek apakah username sudah ada di database
    $username_check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $username_check_stmt->bind_param("s", $username);
    $username_check_stmt->execute();
    $username_result = $username_check_stmt->get_result();

    if ($username_result->num_rows > 0) {
        $_SESSION['showAlert'] = 'Username sudah terdaftar. Silakan gunakan username lain.';
        header("Location: register.php");
        exit();
    }

    // Cek apakah email sudah ada di database
    $email_check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $result = $email_check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['showEmailExistsAlert'] = true;
        header("Location: register.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $username, $email, $phone, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['showSuccessAlert'] = true;
        header("Location: register.php");
        exit();
    } else {
        error_log("Registrasi gagal: " . $stmt->error);
        $_SESSION['showAlert'] = 'Registrasi gagal. Silakan coba lagi.';
        header("Location: register.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>My Bimo</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="../assets-frontend/vendor-login/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets-frontend/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="../assets-frontend/img/mybimo.png" alt="" class="w-100 h-auto">
                            </a>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                </div>

                                <form class="row g-3 needs-validation" method="POST" action="register.php" novalidate>
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Username</label>
                                        <div class="input-group has-validation">
                                            <input type="text" name="username" class="form-control" id="yourUsername" 
                                                   placeholder="Username" required 
                                                   pattern="[A-Za-z0-9]+" 
                                                   title="Username hanya boleh mengandung huruf dan angka">
                                            <div class="invalid-feedback">Please enter a valid username!</div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-12">
                                        <label for="yourEmail" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="yourEmail" 
                                               placeholder="Email" required>
                                        <div class="invalid-feedback">Please enter a valid Email address!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPhone" class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" id="yourPhone" 
                                               placeholder="Phone Number" required 
                                               pattern="[0-9]{1,12}" 
                                               maxlength="12" 
                                               title="Nomor telepon hanya boleh berisi angka (maks. 12 digit)">
                                        <div class="invalid-feedback">Please enter a valid phone number (max 12 digits)!</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="yourPassword" 
                                               placeholder="Password" required 
                                               minlength="6">
                                        <div class="invalid-feedback">Please enter a password (min. 6 characters)!</div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Create Account</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Already have an account? <a href="login.php">Log in</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    // Tambahkan validasi client-side
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation to
            var forms = document.getElementsByClassName('needs-validation');
            
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    // Tambahkan event listener untuk input phone
    document.getElementById('yourPhone').addEventListener('input', function(e) {
        // Hapus karakter non-angka
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        // Batasi panjang maksimal 12 karakter
        if (e.target.value.length > 12) {
            e.target.value = e.target.value.slice(0, 12);
        }
    });

    // Tambahkan window.onload untuk memastikan DOM sudah dimuat
    window.onload = function() {
        <?php if ($showEmailExistsAlert): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email sudah terdaftar. Silakan gunakan email lain.',
                confirmButtonColor: "#1B78F2"
            });
        <?php endif; ?>

        <?php if ($showSuccessAlert): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil',
                text: 'Akun Anda telah dibuat. Anda akan dialihkan ke halaman login.',
                confirmButtonColor: "#1B78F2",
                timer: 2000, 
                timerProgressBar: true,
                didClose: () => {
                    window.location.href = 'login.php';
                }
            });
        <?php endif; ?>

        <?php if ($showAlert): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: '<?php echo $showAlert; ?>',
                confirmButtonColor: "#1B78F2"
            });
        <?php endif; ?>
    }
</script>

<!-- Vendor JS Files -->
<script src="../assets-frontend/vendor-login/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets-frontend/js/main.js"></script>
</body>
</html>