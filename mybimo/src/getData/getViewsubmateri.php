<?php 
// Mengimpor file koneksi database
include "../koneksi/koneksi.php";

// Mengambil ID materi dari parameter POST
$id_materi = $_POST['id_materi'];

// Menyiapkan query SQL untuk mengambil data dari view_submateri berdasarkan id_materi
$query = "SELECT * FROM view_submateri WHERE id_materi = ?";

// Mempersiapkan pernyataan SQL untuk eksekusi
$stmt = $conn->prepare($query);

// Mengikat parameter ke pernyataan yang telah disiapkan. "i" menunjukkan bahwa parameter yang diikat adalah integer.
$stmt->bind_param("i", $id_materi);

// Menjalankan pernyataan SQL
$stmt->execute();

// Mengambil hasil dari eksekusi pernyataan
$result = $stmt->get_result();

// Membuat array kosong untuk menyimpan hasil dalam format JSON
$json = array();

// Mengambil setiap baris hasil dan menambahkannya ke array
while ($row = $result->fetch_assoc()) {
    $json[] = $row; // Menambahkan setiap baris hasil ke array $json
}

// Mengubah array menjadi format JSON dan mengirimkannya sebagai respons
echo json_encode($json);

// Menutup pernyataan setelah selesai
$stmt->close();

// Menutup koneksi database
mysqli_close($conn);
?>