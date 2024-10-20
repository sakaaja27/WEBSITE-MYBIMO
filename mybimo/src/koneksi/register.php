<?php 
include 'koneksi.php';
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$role = $_POST['role'] ?? "0";
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE  username = '".$username."'";
$msql = mysqli_query($conn, $query);
$result = mysqli_num_rows($msql);

if (!empty($username) && !empty($email) && !empty($phone) && !empty($password)){
    if ($result == 0) {
        $regis = "INSERT INTO users (username, email, phone,role, password) VALUES ('".$username."', '".$email."', '".$phone."','".$role."', '".md5($password)."')";
        $mslregis = mysqli_query($conn,$regis);

        echo "Daftar Berhasil";
    }else{
        echo "Username Sudah Terdaftar";
    }
}else{
    echo "Data Tidak Boleh Kosong";
}
?>