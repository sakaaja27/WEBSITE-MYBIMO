<?php
include "../koneksi/koneksi.php";

// Periksa apakah parameter user_id tersedia
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query untuk mengambil data berdasarkan user_id
    $query = "SELECT * FROM view_hasil_quiz WHERE user_id = ? ORDER BY tanggal DESC";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(array("error" => "Kesalahan dalam persiapan statement."));
        exit();
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah ada data
    if ($result->num_rows > 0) {
        $history = array();
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }

        // Berikan respons berupa JSON Array
        header('Content-Type: application/json');
        echo json_encode($history);
    } else {
        // Tidak ada data untuk user_id yang diberikan
        header('Content-Type: application/json');
       
    }

    $stmt->close();
} else {
    // Parameter user_id tidak diberikan
    header('Content-Type: application/json');
    echo json_encode(array("error" => "ID pengguna tidak diberikan."));
}

$conn->close();
?>
