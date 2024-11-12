<?php
include "../koneksi/koneksi.php";

// Mendapatkan user_id dari parameter GET
$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : 0;

if ($id_user > 0) {
    // Query untuk mengambil status transaksi
    $sql = "SELECT status FROM transaksi WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memeriksa apakah ada hasil
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([$row]); // Mengembalikan status transaksi dalam format JSON
    } else {
        echo json_encode([]); // Tidak ada transaksi ditemukan
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid user ID']);
}

$conn->close();
?>