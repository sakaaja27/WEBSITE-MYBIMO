<?php
include "../koneksi/koneksi.php";

// Memeriksa apakah file gambar diupload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload_image'])) {
    $userId = $_POST['id']; // Mendapatkan ID pengguna dari parameter
    $targetDir = "storage/"; // Direktori untuk menyimpan gambar
    $fileName = basename($_FILES['upload_image']['name']);
    $targetFilePath = $targetDir . $fileName;

    // Memindahkan file ke direktori target
    if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $targetFilePath)) {
        // Menyimpan path gambar ke database
        // echo json_encode(['success' => true, 'message' => 'Nama Image', 'image_url' => $_FILES['upload_image']]);
        // echo json_encode("Target File : ".$targetFilePath);
        $sql = "UPDATE users SET upload_image = '" . $targetFilePath . "' WHERE id = " . $userId;
        $stmt = $conn->prepare($sql);
        // $stmt->bind_param("si", $targetFilePath, $userId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Image uploaded successfully', 'image_url' => $targetFilePath]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save image path to database']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image uploaded']);
}

$conn->close();
?>
?>