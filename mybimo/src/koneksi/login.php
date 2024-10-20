<?php 
include 'koneksi.php';
$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = '".$email."' AND password = '".md5($password)."'";
$msql = mysqli_query($conn, $query);
$result = mysqli_num_rows($msql);

if (!empty($email) && !empty($password)){
    if ($result > 0) {
        // Ambil data pengguna
        $user = mysqli_fetch_assoc($msql);
        // Mengembalikan data dalam format JSON
        echo json_encode(array(
            "status" => "Login Berhasil",
            "id" => $user['id'],
            "username" => $user['username'],
            "email" => $user['email'],
            "phone" => $user['phone'],
            "role" => $user['role'],
            "password" => $user['password']
        ));
    } else {
        echo json_encode(array("status" => "Login Gagal"));
    }
} else {  
    echo json_encode(array("status" => "Data Tidak Boleh Kosong"));
}
?>