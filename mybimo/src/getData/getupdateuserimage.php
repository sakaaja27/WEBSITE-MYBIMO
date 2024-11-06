<?php
// Mengimpor file koneksi database
include "../koneksi/koneksi.php";

// Memeriksa apakah permintaan yang diterima adalah metode POST dan apakah ada file gambar yang diupload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload_image'])) {
    $userId = $_POST['id']; // Mendapatkan ID pengguna dari parameter POST
    $targetDir = "storage/"; // Menentukan direktori tujuan untuk menyimpan gambar
    $fileName = basename($_FILES['upload_image']['name']); // Mengambil nama file gambar yang diupload
    $targetFilePath = $targetDir . $fileName; // Menentukan jalur lengkap untuk file yang akan disimpan

    // Memindahkan file gambar dari lokasi sementara ke direktori 
    if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $targetFilePath)) {
        // Jika pemindahan file berhasil, lakukan pembaruan pada database
        $sql = "UPDATE users SET upload_image = '" . $targetFilePath . "' WHERE id = " . $userId; // Query SQL untuk memperbarui jalur gambar
        $stmt = $conn->prepare($sql); // Menyiapkan pernyataan SQL untuk dieksekusi

        // Menjalankan pernyataan SQL dan memeriksa hasilnya
        if ($stmt->execute()) {
            // Jika eksekusi berhasil, kirimkan respons sukses dalam format JSON
            echo json_encode(['success' => true, 'message' => 'Image uploaded successfully', 'image_url' => $targetFilePath]);
        } else {
            // Jika gagal menyimpan jalur gambar ke database, kirimkan respons gagal
            echo json_encode(['success' => false, 'message' => 'Failed to save image path to database']);
        }

        $stmt->close(); // Menutup pernyataan setelah selesai
    } else {
        // Jika pemindahan file gagal, kirimkan respons gagal
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
    }
} else {
    // Jika tidak ada gambar yang diupload, kirimkan respons gagal
    echo json_encode(['success' => false, 'message' => 'No image uploaded']);
}

// Menutup koneksi database
$conn->close();
?>