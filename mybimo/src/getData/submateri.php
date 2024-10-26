<?php 
    include "../auth/koneksi.php";

    $query = "SELECT * FROM sub_materi where id = 1";
    $msql = mysqli_query($connect, $query);
    $json = array();

    while ($row = mysqli_fetch_assoc($msql)) {
        $json[] = $row;
    }

    echo json_encode($json);
    mysqli_close($connect);

?>