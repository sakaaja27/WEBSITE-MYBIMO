<?php 
include "../koneksi/koneksi.php";

$judul_materi = $_POST['judul_materi'];
$targetDir = "storageImage/"; // Direktori untuk menyimpan gambar
$fileName = basename($_FILES['foto_icon']['name']);
$targetFilePath = $targetDir . $fileName;
$status_materi = $_POST['status_materi'] ?? 0;


if(move_uploaded_file($_FILES['foto_icon']['tmp_name'],$targetFilePath)){
    $sql = "INSERT INTO materi (judul_materi, foto_icon, status_materi,created_at) VALUES ('$judul_materi', '$targetFilePath', '$status_materi',NOW())";
} else {
    $sql = "INSERT INTO materi (judul_materi, status_materi,created_at) VALUES ('$judul_materi', '$status_materi',NOW())";
}

$query = $sql;

if ($conn->query($query) === TRUE) {
    echo "success";
} else {
    echo "failed";
}

$conn->close();
?>
