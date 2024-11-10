<?php
session_start();
require '../koneksi/koneksi.php'; 

$showEmailExistsAlert = isset($_SESSION['showEmailExistsAlert']) ? $_SESSION['showEmailExistsAlert'] : false;
$showSuccessAlert = isset($_SESSION['showSuccessAlert']) ? $_SESSION['showSuccessAlert'] : false;

// Hapus session setelah digunakan agar tidak muncul berulang
unset($_SESSION['showEmailExistsAlert']);
unset($_SESSION['showSuccessAlert']);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 0;

    // Validasi input
    if (empty($username) || empty($email) || empty($phone) || empty($_POST['password'])) {
        echo "<script>
            alert('Please fill in all fields.');
            window.location.href='../auth/register.php';
        </script>";
        exit();
    }

    // Cek apakah email sudah ada di database
    $email_check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $result = $email_check_stmt->get_result();

    if ($result->num_rows > 0) {
      $_SESSION['showEmailExistsAlert'] = true; // Set session untuk alert email sudah ada
      header("Location: ../auth/register.php");
      exit();
    } else {
        // Siapkan query untuk memasukkan data baru
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $username, $email, $phone, $password, $role);

        if ($stmt->execute()) {
          $_SESSION['showSuccessAlert'] = true; // Set session untuk alert sukses
          header("Location: ../auth/register.php");
          exit();
        } else {
            echo "<script>
                alert('Error inserting data: " . $stmt->error . "');
                window.location.href='../auth/register.php';
            </script>";
        }
        
        $stmt->close();
    }
}

mysqli_close($conn);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../assets-frontend/css/register.css">
    <link rel="icon" type="image/png" href="otp-icon.png">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../assets-frontend/img/mybimo.png" alt="OTP Logo">
        </div>
        <h2>Create your account</h2>
        <p>Enter your personal details to create account</p>

        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" required>

            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email" required>
            <small>We'll never share your email with anyone else.</small>

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" placeholder="Phone Number" required>

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="register" class="btn-create">Create Account</button>
            <p class="signin-text">Already have an account? <a href="../auth/login.php">Sign in</a></p>
        </form>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if ($showEmailExistsAlert): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email sudah digunakan! Silakan gunakan email lain.',
                confirmButtonText: 'OK',
                backdrop : false
            });
        <?php endif; ?>

        <?php if ($showSuccessAlert): ?>
            Swal.fire({
                icon: 'success',
                title: 'Account created successfully!',
                showConfirmButton: false,
                timer: 1500,
                backdrop : false
            }).then(() => {
                window.location.href = '../auth/login.php';
            });
        <?php endif; ?>
    </script>
    
</body>
</html>

