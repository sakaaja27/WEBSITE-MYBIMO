<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../koneksi/koneksi.php';

// Initialize a variable to control the alert
$showAlert = isset($_SESSION['showAlert']) && $_SESSION['showAlert'] === true;
unset($_SESSION['showAlert']); // Hapus session setelah digunakan

if (isset($_POST['login'])) {
    $usernameOrEmail = $_POST['username'];
    $password = $_POST['password'];

    // Tambahkan logging untuk debugging
    error_log("Login Attempt - Username/Email: $usernameOrEmail");

    // Gunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? OR email = ?");
    if (!$stmt) {
        error_log("Prepare statement error: " . mysqli_error($conn));
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $usernameOrEmail, $usernameOrEmail);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute statement error: " . mysqli_stmt_error($stmt));
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Debugging informasi pengguna
        error_log("User  Found - ID: {$row['id']}, Username: {$row['username']}, Role: {$row['role']}");

        // Variabel untuk verifikasi password
        $passwordVerified = password_verify($password, $row["password"]);

        // Logging verifikasi password
        error_log("Password Verification: " . ($passwordVerified ? "Success" : "Failed"));

        if ($passwordVerified) {
            // Set session
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect berdasarkan role
            switch ($row['role']) {
                case 0: // User
                    header("Location: ../dashboard.php");
                    exit();
                case 1: // Admin
                    header("Location: ../admin/index.php");
                    exit();
                default:
                    error_log("Unknown Role: {$row['role']}");
                    $_SESSION['showAlert'] = true;
                    header("Location: ../auth/login.php");
                    exit();
            }
        } else {
            // Password salah
            error_log("Login Failed - Incorrect Password for {$usernameOrEmail}");
            $_SESSION['showAlert'] = true;
            header("Location: ../auth/login.php");
            exit();
        }
    } else {
        // Pengguna tidak ditemukan
        error_log("Login Failed - User Not Found: {$usernameOrEmail}");
        $_SESSION['showAlert'] = true;
        header("Location: ../auth/login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Pages / Login - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor-login CSS Files -->
    <link href="../assets-frontend/vendor-login/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets-frontend/vendor-login/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets-frontend/vendor-login/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets-frontend/vendor-login/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets-frontend/vendor-login/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets-frontend/vendor-login/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets-frontend/vendor-login/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets-frontend/css/style.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>

<main>
    <div class="login-wrapper">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center min-vh-100">
                <div class="col-lg-10 col-md-12">
                    <div class="row">
                        <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center ">
                            <div class="logo-container">
                                <img src="../assets-frontend/img/Component 12.png" alt="MyBimo Logo">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex justify-content-center py-4">
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="../assets-frontend/img/mybimo.png" alt="" class="w-100 h-auto">
                                </a>
                            </div>
                            <div class="card w-100">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                        <p class="text-center small">Enter your username & password to login</p>
                                    </div>

                                    <form class="row g-3 needs-validation" method="POST" action="" novalidate>
                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username or Email</label>
                                            <div class="input-group has-validation">
                                                <input type="text" name="username" class="form-control" id="yourUsername" 
                                                       placeholder="Username or Email" 
                                                       required 
                                                       pattern="[A-Za-z0-9@.]{3,50}" 
                                                       title="Username atau email harus terdiri dari 3-50 karakter"
                                                       maxlength="50">
                                                <div class="invalid-feedback">Please enter a valid username or email (3-50 characters).</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="yourPassword" 
                                                   placeholder="Password" 
                                                   required 
                                                   minlength="6" 
                                                   maxlength="50"
                                                   title="Password minimal 6 karakter">
                                            <div class="invalid-feedback">Please enter a valid password (min. 6 characters).</div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit" name="login">Login</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Don't have an account? <a href="register.php">Create an account</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>

<!-- Vendor-login JS Files -->
<script src="../assets-frontend/vendor-login/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Validasi form menggunakan JavaScript
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Pilih semua form dengan kelas 'needs-validation'
            var forms = document.getElementsByClassName('needs-validation');
            
            // Loop melalui form dan cegah submit jika tidak valid
            Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    // Cek validitas form
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    // Tambahkan kelas 'was-validated' untuk menampilkan umpan balik validasi
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    // Tampilkan alert login gagal
    window.onload = function() {
        <?php if ($showAlert): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Username/Email atau Password Anda salah.',
                confirmButtonColor: "#1B78F2"
            });
        <?php endif; ?>
    }
</script>

<!-- Template Main JS File -->
<script src="../assets-frontend/js/main2.js"></script>

</body>
</html>