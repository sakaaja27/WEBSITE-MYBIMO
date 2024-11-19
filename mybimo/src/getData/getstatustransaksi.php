<?php 
include "../koneksi/koneksi.php";

$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : 0;

if ($id_user > 0) {
    $query = "SELECT status FROM transaksi WHERE id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $status = array();
        while ($row = $result->fetch_assoc()) {
            $status[] = $row;
        }
        echo json_encode(array('success' => true, 'transaksi' => $status));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Data tidak ditemukan'));
        }
} else {
   echo json_encode(array('success' => false, 'message' => 'ID tidak ditemukan'));
}   
$stmt->close();
$conn->close();
?>