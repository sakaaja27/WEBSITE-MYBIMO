<?php 
include "../koneksi/koneksi.php";

 $query = "SELECT * FROM zoom";
 $msl = mysqli_query($conn,$query);
 $json = array();

 while ($row = mysqli_fetch_assoc($msl)) {
     $json[] = $row;
     }

     echo json_encode($json);
    mysqli_close($conn);
?>