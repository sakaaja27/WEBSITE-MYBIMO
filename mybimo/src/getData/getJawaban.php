<?php
include "../koneksi/koneksi.php";

// Validasi apakah parameter jawaban_user ada dan tidak kosong
if (!isset($_POST['jawaban_user']) || empty($_POST['jawaban_user'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parameter jawaban_user tidak ditemukan atau kosong.'
    ]);
    exit();
}

// Mengambil jawaban user dari request POST (pastikan data diterima sebagai JSON)
$jawaban_user = json_decode($_POST['jawaban_user'], true); // Menggunakan json_decode untuk memastikan data terkonversi ke array

// Validasi apakah jawaban_user adalah array
if (!is_array($jawaban_user)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format jawaban_user tidak valid, harus berupa array.'
    ]);
    exit();
}

$jumlah_benar = 0;
$jumlah_salah = 0;

try {
    // Query untuk mendapatkan semua kunci jawaban dari database
    $query = "SELECT id, jawaban_soal FROM soal_submateri WHERE id IN (" . implode(",", array_map('intval', array_keys($jawaban_user))) . ")";
    $result = $conn->query($query);

    // Memeriksa apakah query berhasil dan ada data yang ditemukan
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $jawaban_soal = $row['jawaban_soal'];

            // Periksa jawaban user terhadap kunci jawaban dari database
            if (isset($jawaban_user[$id])) {
                if ($jawaban_user[$id] == $jawaban_soal) {
                    $jumlah_benar++;
                } else {
                    $jumlah_salah++;
                }
            }
        }
    }

    // Mengembalikan hasil akhir setelah pengecekan
    echo json_encode([
        'status' => 'selesai',
        'jumlah_benar' => $jumlah_benar,
        'jumlah_salah' => $jumlah_salah
    ]);

} catch (Exception $e) {
    // Tangani error database atau query
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}

// Tutup koneksi
$conn->close();
?>
