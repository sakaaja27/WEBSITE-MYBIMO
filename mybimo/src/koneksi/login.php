<?php
include 'koneksi.php';
$email = $_POST['email'];
$password = $_POST['password'];

// ini query untuk hanya mengambil data berdasarkan email
$query = "SELECT * FROM users WHERE email = ? ";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!empty($email) && !empty($password)) {
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password menggunakan password_verify
        if (password_verify($password, $user['password'])) {
            // Password cocok, login berhasil
            echo json_encode(array(
                "status" => "Login Berhasil",
                "id" => $user['id'],
                "username" => $user['username'],    
                "email" => $user['email'],
                "phone" => $user['phone'],
                "role" => $user['role'],
                "upload_image" => $user['upload_image'],
                "password" => $user['password']
                // kirim data ke client
            ));
        } else {
            // Password tidak cocok
            echo json_encode(array("status" => "Login Gagal"));
        }
    } else {
        // Email tidak ditemukan
        echo json_encode(array("status" => "Login Gagal"));
    }
} else {
    echo json_encode(array("status" => "Data Tidak Boleh Kosong"));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);