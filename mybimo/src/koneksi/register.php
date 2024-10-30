<?php 
include 'koneksi.php';
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$role = $_POST['role'] ?? "0";

// Set upload_image ke NULL jika tidak ada data
$upload_image = !empty($_POST['upload_image']) ? $_POST['upload_image'] : NULL;
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = '".$email."'";
$msql = mysqli_query($conn, $query);
$result = mysqli_num_rows($msql);

if (!empty($username) && !empty($email) && !empty($phone) && !empty($password)){
    if ($result == 0) {
        // Hash password menggunakan PASSWORD_DEFAULT
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Gunakan 'NULL' tanpa tanda kutip untuk menyisipkan NULL ke database
        $regis = "INSERT INTO users (username, email, phone, role, upload_image, password) VALUES ('".$username."', '".$email."', '".$phone."', '".$role."', ".($upload_image === NULL ? 'NULL' : "'".$upload_image."'").", '".$hashed_password."')";
        $mslregis = mysqli_query($conn, $regis);
        echo "Daftar Berhasil";
    } else {
        echo "Email Sudah Terdaftar";
    }
} else {
    echo "Data Tidak Boleh Kosong";
}
?>