<?php 

include '../koneksi/koneksi.php';

// Get data from POST request
$id = intval($_POST['id']);
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$role = isset($_POST['role']) ? intval($_POST['role']) : 0;

// Handle file upload
$upload_image = null; // Initialize upload_image variable
if (isset($_FILES['upload_image']) && $_FILES['upload_image']['error'] == UPLOAD_ERR_OK) {
    $upload_image = file_get_contents($_FILES['upload_image']['tmp_name']);
}

// Validate input
if (empty($id) || empty($username) || empty($email) || empty($phone) || !is_numeric($id) || !is_numeric($role)) {
    echo json_encode(array("success" => false, "message" => "All fields are required and must be valid."));
    exit();
}

// Prepare SQL statement to prevent SQL injection
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // Use 'i' for integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Prepare the update statement
    $updateSql = "UPDATE users SET username = ?, email = ?, phone = ?, role = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    
    // Bind parameters, including the upload_image as a BLOB
    $updateStmt->bind_param("sssis", $username, $email, $phone, $role, $id);

    // Execute the update statement
    if ($updateStmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Data updated successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to update data"));
    }

    // Close the update statement
    $updateStmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "User  not found"));
}

// Close the prepared statement and connection
$stmt->close();
$conn->close();

?>