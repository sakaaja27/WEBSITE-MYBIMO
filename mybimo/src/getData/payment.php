<?php 
    include "../koneksi/koneksi.php";

    $query = "SELECT * FROM pembayaran where id = 1";
    $msql = mysqli_query($conn, $query);
    $json = array();

    while ($row = mysqli_fetch_assoc($msql)) {
        $json[] = $row;
    }

    echo json_encode($json);
    mysqli_close($conn);

?>