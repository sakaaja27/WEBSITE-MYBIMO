<?php
require '../koneksi/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug koneksi database
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        echo "Connection to database successful!<br>"; // Debug untuk memastikan koneksi berhasil
    }

    // Mengambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = 0; // Set default role user ke 0

    // Debug input form
    echo "Username: $username, Email: $email, Phone: $phone, Password Hash: $password <br>";

    if (empty($username) || empty($email) || empty($phone) || empty($_POST['password'])) {
        echo "<script>alert('Please fill in all fields.');</script>";
        exit(); // Hentikan eksekusi script
    }

    // Cek apakah email sudah ada di database
    $email_check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $result = $email_check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah digunakan! Silakan gunakan email lain.');</script>";
        
        exit(); // Hentikan eksekusi script
    }

    

    // Siapkan query
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $username, $email, $phone, $password, $role); // 'i' untuk integer

    // Eksekusi query dan cek apakah berhasil
    if ($stmt->execute()) {
        echo "<script>alert('Account created successfully!');</script>";
        header("Location: auth/login.php"); // Redirect ke halaman login
        exit();
    } else {
        // Debug query error jika gagal
        echo "Error inserting data: " . $stmt->error; // Menampilkan error dari statement
    }
    

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi setelah selesai
mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../assets-frontend/css/register.css">
    <link rel="icon" type="image/png" href="otp-icon.png"> <!-- Add your favicon here -->
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../assets-frontend/img/mybimo.png" alt="OTP Logo"> <!-- Add your logo image file -->
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
</body>
</html>
