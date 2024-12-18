<?php

include '../koneksi/koneksi.php';

    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
  
    if (empty($new_password) || empty($email)){
        echo json_encode(array("status" => false, "message" => "All fields are required."));
        exit();
    }
        $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0){
       
        $newpassword_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt-> bind_param("ss", $newpassword_hashed, $email);
        if ($stmt->execute()) {
            echo json_encode(value: array("status" => true, "message" => "New password successfully updated."));
        } else {
            echo json_encode(array("status" => false, "message" => "Failed to update password."));
        }
    } else {
        echo json_encode(array("status" => false, "message" => "Email does not exist."));
    }

    
    

$stmt->close();
$conn->close();

?>
