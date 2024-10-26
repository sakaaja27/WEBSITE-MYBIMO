<?php
include "../koneksi/koneksi.php";

// Memeriksa apakah permintaan POST diterima
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $imagePath = $_POST['upload_image'];

    // Menyimpan path gambar ke database
    $sql = "UPDATE users SET upload_image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $imagePath, $userId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Image path saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save image path to database']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>