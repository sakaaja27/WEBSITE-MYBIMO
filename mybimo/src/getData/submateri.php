<?php 
    include "../koneksi/koneksi.php";

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        // query untuk mengambil data sub materi berdasarkan id materi
        $query = "SELECT * FROM sub_materi WHERE id_materi = ?";
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
    } else{
        echo json_encode(array("error" => "No id found."));
    }
    mysqli_close($conn);
?>