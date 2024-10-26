<?php
include "../koneksi/koneksi.php";
// Ambil data dari permintaan POST
$user_id = $_POST['id'];
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Validasi input
if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(array("success" => false, "message" => "All fields are required."));
    exit();
}

if ($new_password !== $confirm_password) {
    echo json_encode(array("success" => false, "message" => "New password and confirm password do not match."));
    exit();
}

// Hash password lama
$old_password_hashed = md5($old_password);

// Cek password lama
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['password'] === $old_password_hashed) {
        $new_password_hashed = md5($new_password);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_password_hashed, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(array("success" => true, "message" => "Password reset successfully."));
        } else {
            echo json_encode(array("success" => false, "message" => "Failed to update password."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Old password is incorrect."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "User  not found."));
}

$stmt->close();
$conn->close();
?>  