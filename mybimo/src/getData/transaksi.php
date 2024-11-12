<?php 
include '../koneksi/koneksi.php';
    $id_user = $_POST['id_user'];
    $id_pembayaran = $_POST['id_pembayaran'];
    $status = $_POST['status'] ?? '0';
    $upload_bukti = '';
    $target_dir = "../getData/storagetransaksi/";
    $validImageExtension = ['jpg', 'png', 'jpeg'];

    if ($_FILES["upload_bukti"]["error"] == 0) {
        $fileName = $_FILES["upload_bukti"]["name"];
        $fileSize  = $_FILES["upload_bukti"]["size"];
        $tmpName = $_FILES["upload_bukti"]["tmp_name"];

        $imageExtension = strtolower(pathinfo($fileName,PATHINFO_EXTENSION) );

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = uniqid(). '.' . $imageExtension;
            $targetFilePath = $target_dir . $newImageName;

            if (move_uploaded_file($tmpName,$targetFilePath)){
                $upload_bukti = $newImageName;
            } else {
                echo json_encode(array("success" => false, "message" => "Gagal mengunggah gambar"));
                exit();
            }
        } else {
            echo json_encode(array("success" => false, "message" => "File yang diunggah bukan gambar"));
            exit();
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Silakan unggah gambar"));
        exit();
    }

    $query = "INSERT INTO transaksi (id_user,id_pembayaran,status,upload_bukti,created_at) VALUES (?,?,?,?,NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss",$id_user, $id_pembayaran, $status, $upload_bukti);

    if ($stmt->execute()){
        echo json_encode(array("success" => true, "message" => "Transaksi berhasil dikonfirmasi"));
    }else {
        echo json_encode(array("success" => false, "message" => "Transaksi gagal dikonfirmasi"));
    }
    $stmt->close();
    


?>