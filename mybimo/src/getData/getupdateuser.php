<?php 
include '../koneksi/koneksi.php';

// Mengambil data dari permintaan POST
$id = intval($_POST['id']);
$username = $_POST['username'];
$email = $_POST['email']; 
$phone = $_POST['phone']; 
$role = isset($_POST['role']) ? intval($_POST['role']) : 0; // Mengambil role, jika tidak ada, set ke 0

// Menangani upload file
$upload_image = null; // Menginisialisasi variabel upload_image
if (isset($_FILES['upload_image']) && $_FILES['upload_image']['error'] == UPLOAD_ERR_OK) {
    // Jika ada file yang diupload dan tidak ada error, ambil konten file
    $upload_image = file_get_contents($_FILES['upload_image']['tmp_name']);
}

// Validasi input
if (empty($id) || empty($username) || empty($email) || empty($phone) || !is_numeric($id) || !is_numeric($role)) {
    // Jika ada field yang kosong atau tidak valid, kirimkan respons gagal
    echo json_encode(array("success" => false, "message" => "All fields are required and must be valid."));
    exit(); // Hentikan eksekusi skrip
}

// Menyiapkan pernyataan SQL untuk mencegah SQL injection
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql); // Mempersiapkan pernyataan SQL
$stmt->bind_param("i", $id); // Mengikat parameter, 'i' menunjukkan bahwa ini adalah integer
$stmt->execute(); // Menjalankan pernyataan SQL
$result = $stmt->get_result(); // Mengambil hasil eksekusi

if ($result->num_rows > 0) { // Memeriksa apakah pengguna dengan ID tersebut ada
    // Menyiapkan pernyataan pembaruan
    $updateSql = "UPDATE users SET username = ?, email = ?, phone = ?, role = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql); // Mempersiapkan pernyataan pembaruan
    
    // Mengikat parameter untuk pembaruan
    $updateStmt->bind_param("sssis", $username, $email, $phone, $role, $id); // 's' untuk string, 'i' untuk integer

    // Menjalankan pernyataan pembaruan
    if ($updateStmt->execute()) {
        // Jika eksekusi berhasil, kirimkan respons sukses
        echo json_encode(array("success" => true, "message" => "Data updated successfully"));
    } else {
        // Jika gagal melakukan pembaruan, kirimkan respons gagal
        echo json_encode(array("success" => false, "message" => "Failed to update data"));
    }

    // Menutup pernyataan pembaruan
    $updateStmt->close();
} else {
    // Jika pengguna tidak ditemukan, kirimkan respons gagal
    echo json_encode(array("success" => false, "message" => "User  not found"));
}

// Menutup pernyataan yang telah dipersiapkan dan koneksi database
$stmt->close();
$conn->close();

?>