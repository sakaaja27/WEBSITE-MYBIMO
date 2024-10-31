<?php
require '../koneksi/koneksi.php'; // Pastikan koneksi.php ada dan jalurnya benar

// Initialize a variable to control the alert
$showAlert = false;

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa username dan password
    $query_sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        // Mendapatkan data user yang login
        $row = mysqli_fetch_assoc($result);
        $role = $row['role']; // Ambil role dari database

        // Arahkan ke halaman yang sesuai berdasarkan role
        if ($role == 0) {
            header("Location: ../dashboard.php"); // Redirect ke halaman dashboard user
            exit();
        } elseif ($role == 1) {
            header("Location: ../admin/index.php"); // Redirect ke halaman dashboard admin
            exit();
        } elseif ($role == 2) {
            header("Location: ../admin/index.php"); // Redirect ke halaman dashboard super admin
            exit();
        } else {
            echo "<center><h1>Role tidak dikenali.</h1></center>";
        }
    } else {
        // Set the alert variable to true
        $showAlert = true;
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