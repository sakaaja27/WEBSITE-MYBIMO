<?php
include "../koneksi/koneksi.php";

$id = $_POST['id'];
$judul_materi = $_POST['judul_materi'];
$targetDir = "storageImage/"; // Direktori untuk menyimpan gambar
$fileName = basename($_FILES['foto_icon']['name']);
$targetFilePath = $targetDir . $fileName;
$status_materi = $_POST['status_materi'] ?? 0;

// Memindahkan file ke direktori target
if (move_uploaded_file($_FILES['foto_icon']['tmp_name'], $targetFilePath)) {
    // Menyimpan path gambar ke database
    $sql = "UPDATE materi SET judul_materi = '$judul_materi', foto_icon = '$targetFilePath', status_materi = '$status_materi' WHERE id = $id";
} else {
    $sql = "UPDATE materi SET judul_materi = '$judul_materi', status_materi = '$status_materi' WHERE id = $id";
}

$query = $sql;

if ($conn->query($query) === TRUE) {
    echo "success";
} else {
    echo "failed";
}

$conn->close();
?>
