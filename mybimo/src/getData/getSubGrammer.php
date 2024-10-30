<?php 
include "../koneksi/koneksi.php";

$id_materi = $_POST['id_materi'];
$nama_sub = $_POST['nama_sub'];
$targetDir = "storagesubmateri/"; // Direktori untuk menyimpan gambar

// Cek apakah ada file yang diupload
if(isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == UPLOAD_ERR_OK && $_FILES['upload_file']['size'] > 0) {
    // Mendapatkan nama file asli
    $fileName = $_FILES['upload_file']['name'];
    
    // Membersihkan nama file dari karakter spesial
    $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
    
    // Path lengkap file
    $targetFilePath = $targetDir . $fileName;
    
    // Cek jika file sudah ada, tambahkan number
    $counter = 1;
    $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    
    while(file_exists($targetFilePath)) {
        $fileName = $fileNameOnly . "($counter)." . $extension;
        $targetFilePath = $targetDir . $fileName;
        $counter++;
    }
    
    if(move_uploaded_file($_FILES['upload_file']['tmp_name'], $targetFilePath)){
        $sql = "INSERT INTO sub_materi (id_materi, nama_sub, upload_file, created_at) 
                VALUES ('$id_materi', '$nama_sub', '$targetFilePath', NOW())";
    } else {
        // Jika gagal upload file
        $sql = "INSERT INTO sub_materi (id_materi, nama_sub, upload_file, created_at) 
                VALUES ('$id_materi', '$nama_sub', NULL, NOW())";
    }
} else {
    // Jika tidak ada file yang diupload
    $sql = "INSERT INTO sub_materi (id_materi, nama_sub, upload_file, created_at) 
            VALUES ('$id_materi', '$nama_sub', NULL, NOW())";
}

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "failed";
}

$conn->close();
?>