<?php
include "../koneksi/koneksi.php";

$query = "SELECT id, id_materi, nama_soal, pilihan_a, pilihan_b, pilihan_c FROM soal_submateri ORDER BY RAND() LIMIT 10";


$result = $conn->query($query);
$quiz = [];

if($result->num_rows >0){
    while ($row = $result->fetch_assoc()){
        $quiz[] = $row;
    }
}
echo json_encode($quiz);

$conn->close();

?>