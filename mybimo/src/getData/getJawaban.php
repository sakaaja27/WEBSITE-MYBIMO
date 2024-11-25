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
$id_materi = null; // Variabel untuk menyimpan id_materi

try {
    // Query untuk mendapatkan semua kunci jawaban dari database
    $query = "SELECT id, jawaban_benar, id_materi FROM soal_submateri WHERE id IN (" . 
             implode(",", array_map('intval', array_keys($jawaban_user))) . ")";
    $result = $conn->query($query);

    // Memeriksa apakah query berhasil dan ada data yang ditemukan
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id_soalmateri = $row['id'];
            $jawaban_benar = $row['jawaban_benar']; // Menggunakan jawaban
            $id_materi = $row['id_materi']; // Ambil id_materi, ambil dari soal pertama

            // Periksa jawaban user terhadap kunci jawaban dari database
            if (isset($jawaban_user[$id_soalmateri])) {
                if ($jawaban_user[$id_soalmateri] == $jawaban_benar) {
                    $jumlah_benar++;
                } else {
                    $jumlah_salah++;
                }
            }
        }

        // Simpan satu hasil ke tabel riwayat_jawaban
        $insert_query = "INSERT INTO riwayat_jawaban (user_id, jumlah_benar, jumlah_salah, id_materi, tanggal) 
                         VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        
        // Simpan hasil jawaban dengan id_materi
        $stmt->bind_param("iiis", $user_id, $jumlah_benar, $jumlah_salah, $id_materi); // Menggunakan id_materi dari soal pertama
        $stmt->execute();

        // Ambil riwayat jawaban per user
        $riwayat_query = "SELECT id, user_id, id_materi, jumlah_benar, jumlah_salah, tanggal FROM riwayat_jawaban WHERE user_id = ? ORDER BY tanggal DESC";
        $stmt_riwayat = $conn->prepare($riwayat_query);
        $stmt_riwayat->bind_param("i", $user_id);
        $stmt_riwayat->execute();
        $result_riwayat = $stmt_riwayat->get_result();

        $riwayat_jawaban = [];
        while ($row_riwayat = $result_riwayat->fetch_assoc()) {
            $riwayat_jawaban[] = $row_riwayat;
        }

        // Mengembalikan hasil akhir setelah pengecekan
        echo json_encode([
            'status' => 'selesai',
            'jumlah_benar' => $jumlah_benar,
            'jumlah_salah' => $jumlah_salah,
            'riwayat_jawaban' => $riwayat_jawaban
        ]);

    } else {    
        echo json_encode([
            'status' => 'error',
            'message' => 'Tidak ada soal yang ditemukan.'
        ]);
    }

} catch (Exception $e) {
    // Tangani error database atau query
        // Tangani error database atau query
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
    
    // Tutup koneksi
    $conn->close();
    ?>