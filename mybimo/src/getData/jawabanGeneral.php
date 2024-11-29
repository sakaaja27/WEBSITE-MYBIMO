<?php
include "../koneksi/koneksi.php";

// Memastikan bahwa permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid. Harus menggunakan POST.'
    ]);
    exit();
}

// Mengambil input JSON dari body permintaan
$data = json_decode(file_get_contents('php://input'), true);

// Mengambil jawaban user dan user_id dari request
$jawaban_user = $data['jawaban_user'] ?? null; // Menggunakan null coalescing operator
$user_id = intval($data['user_id'] ?? 0); // Menggunakan null coalescing operator

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
    // Query untuk mendapatkan soal dan jawaban benar secara acak
    $query = "SELECT id, jawaban_benar, id_materi FROM soal_submateri ORDER BY RAND() LIMIT 10";
    $result = $conn->query($query);

    // Memeriksa apakah query berhasil dan ada data yang ditemukan
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id_soalmateri = $row['id'];
            $jawaban_benar = $row['jawaban_benar']; // Menggunakan jawaban benar dari database

            // Periksa jawaban user terhadap kunci jawaban dari database
            if (isset($jawaban_user[$id_soalmateri])) {
                if ($jawaban_user[$id_soalmateri] == $jawaban_benar) {
                    $jumlah_benar++;
                } else {
                    $jumlah_salah++;
                }
            }
        }

        // Simpan hasil ke tabel riwayat_jawaban
        $insert_query = "INSERT INTO riwayat_quiz (user_id, jumlah_benar, jumlah_salah,  tanggal) 
                         VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        
        // Simpan hasil jawaban dengan id_materi
        $stmt->bind_param("iii", $user_id, $jumlah_benar, $jumlah_salah); 
        $stmt->execute();

        // Ambil riwayat jawaban per user
        $riwayat_query = "SELECT id, user_id, jumlah_benar, jumlah_salah, tanggal FROM riwayat_quiz WHERE user_id = ? ORDER BY tanggal DESC";
        $stmt_quiz = $conn->prepare($riwayat_query);
        $stmt_quiz->bind_param("i", $user_id);
        $stmt_quiz->execute();
        $result_quiz = $stmt_quiz->get_result();

        $riwayat_quiz = [];
        while ($row_quiz = $result_quiz->fetch_assoc()) {
            $riwayat_quiz[] = $row_quiz;
        }

        // Mengembalikan hasil akhir setelah pengecekan
        echo json_encode([
            'status' => 'selesai',
            'jumlah_benar' => $jumlah_benar,
            'jumlah_salah' => $jumlah_salah,
            'riwayat_quiz' => $riwayat_quiz
        ]);

    } else {    
        echo json_encode([
            'status' => 'error',
            'message' => 'Tidak ada soal yang ditemukan.'
        ]);
    }

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
