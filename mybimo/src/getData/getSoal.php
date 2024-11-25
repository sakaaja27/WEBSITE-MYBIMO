<?php
include "../koneksi/koneksi.php";

if (isset($_POST['id'])){
    $id = $_POST['id'];

    $query = "SELECT id, id_materi, nama_soal, pilihan_a, pilihan_b, pilihan_c FROM soal_submateri WHERE id_materi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $json = array();
    while ($row = $result->fetch_assoc()) {
        $json[] = $row;
    }
    echo json_encode($json);
    $stmt->close();
}else{
    echo json_encode(array("error" => "id not found"));
}
mysqli_close($conn);
?>