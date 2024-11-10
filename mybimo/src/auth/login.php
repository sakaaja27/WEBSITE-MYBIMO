<?php
session_start();
require '../koneksi/koneksi.php'; // Pastikan koneksi.php ada dan jalurnya benar

// Initialize a variable to control the alert
$showAlert = isset($_SESSION['showAlert']) && $_SESSION['showAlert'];
unset($_SESSION['showAlert']); // Hapus session setelah digunakan

if (isset($_POST['login'])) {
    $usernameOrEmail = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $usernameOrEmail, $usernameOrEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password sesuai dengan role
        if ($row['role'] == 0) {
            // Role 0 (User) menggunakan password terenkripsi
            if (password_verify($password, $row["password"])) {
                // Password benar
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                header("Location: ../dashboard.php");
                exit();
            }
        } else {
            // Role 1 dan 2 menggunakan password biasa (plain text)
            if ($password == $row["password"]) {
                // Password benar
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                // Arahkan sesuai role
                if ($row['role'] == 1) {
                    header("Location: ../admin/index.php");
                } else if ($row['role'] == 2) {
                    header("Location: ../superadmin/index.php");
                }
                exit();
            }
        }

        // Jika password salah
        $_SESSION['showAlert'] = true;
        header("Location: ../auth/login.php");
        exit();
    } else {
        // Username atau email tidak ditemukan
        $_SESSION['showAlert'] = true;
        header("Location: ../auth/login.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../assets-frontend/css/login.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-container">
        <div class="logo-container">
            <img src="../assets-frontend/img/Component 12.png" alt="MyBimo Logo">
        </div>
        <div class="login-card">
            <div class="logo">
                <img src="../assets-frontend/img/mybimo.png" alt="OTP Logo">
            </div>
            <h2>Sign in to account</h2>
            <p>Enter your email & password to login</p>
            <form action="../auth/login.php" method="POST">
                <div class="input-group">
                    <label for="username">Email or username</label>
                    <input type="text" id="username" name="username" placeholder="Email or username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="actions">
                </div>
                <button type="submit" name="login" class="login-button">Login</button>
                <p class="signup-text">Don't have an account? <a href="../auth/register.php">Create Account</a></p>
            </form>
        </div>
    </div>
</div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Check if the PHP variable is set to show the alert
        <?php if ($showAlert): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email atau Password Anda salah. Silahkan coba login kembali.',
                footer: '<a href="../auth/register.php">Create Account</a>',
                backdrop : false,
                confirmButtonColor: "#1B78F2"
            });
        <?php endif; ?>
    </script> 
</body>
</html>