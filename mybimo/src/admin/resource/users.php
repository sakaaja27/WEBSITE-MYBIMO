<?php
require_once '../koneksi/koneksi.php';

$validImageExtension = ['jpg', 'jpeg', 'png'];

$target_dir = "../getData/storage/";  // direktori untuk menyimpan gambar
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

//fungsi validasi email
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

//fungsi validasi username
function isValidUsername($username)
{
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
}

// Proses penambahan pengguna baru
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = (int)$_POST['role'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $newImageName = "";

    //validasi username 
    if (!isValidUsername($username)) {
        echo "<script>alert('Username tidak valid. Hanya boleh berisi huruf, angka, dan underscore (3 - 20 karakter).'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    //validasi email
    if (!isValidEmail($email)) {
        echo "<script>alert('Format email tidak valid'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    //validasi nomer telephone
    if (strlen($phone) > 12) {
        echo "<script>alert('Nomor Telepon tidak boleh lebih dari 12.'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    if (!ctype_digit($phone)) {
        echo "<script>alert('Nomor telepon hanya boleh terdiri dari angka.'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    //cek keunikan email
    $emailCheckQuery = "SELECT * FROM users WHERE email = ?";
    $emailCheckStmt = $conn->prepare($emailCheckQuery);
    $emailCheckStmt->bind_param("s", $email);
    $emailCheckStmt->execute();
    $emailCheckResult = $emailCheckStmt->get_result();

    if ($emailCheckResult->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar! Silahkan gunakan email lain.'); window.location.href='admin/index.php?users';</script>";
        $emailCheckStmt->close();
        return;
    }

    //cek keunikan username
    $usernameCheckQuery = "SELECT * FROM users WHERE username = ?";
    $usernameCheckStmt = $conn->prepare($usernameCheckQuery);
    $usernameCheckStmt->bind_param("s", $username);
    $usernameCheckStmt->execute();
    $usernameCheckResult = $usernameCheckStmt->get_result();

    if ($usernameCheckResult->num_rows > 0) {
        echo "<script>alert('Username sudah terdaftar! Silahkan gunakan username lain.'); window.location.href='admin/index.php?users';</script>";
        $usernameCheckStmt->close();
        return;
    }

    if ($_FILES["upload_image"]["error"] == 0) {
        $fileName = $_FILES["upload_image"]["name"];
        $fileSize = $_FILES["upload_image"]["size"];
        $tmpName = $_FILES["upload_image"]["tmp_name"];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = 'storage/' . uniqid() . '.' . $imageExtension;
            $targetFilePath = $target_dir . basename($newImageName);

            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $query = "INSERT INTO users (username, email, role, phone, password, upload_image) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssisss", $username, $email, $role, $phone, $password, $newImageName);

                if ($stmt->execute()) {
                    echo "<script>alert('User  berhasil ditambahkan!'); window.location.href='admin/index.php?users';</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }
                $stmt->close();
            } else {
                echo "<script>alert('Failed to upload image');</script>";
            }
        } else {
            echo "<script>alert('Invalid image extension or size too large');</script>";
        }
    } else {
        echo "<script>alert('Please upload an image');</script>";
    }

    // Tutup hasil query
    $emailCheckResult->close();
    $usernameCheckResult->close();
}

// Proses update pengguna
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = (int)$_POST['role'];
    $phone = $_POST['phone'];

    //validasi username 
    if (!isValidUsername($username)) {
        echo "<script>alert('Username tidak valid. Hanya boleh berisi huruf, angka, dan underscore (3 - 20 karakter).'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    //validasi email
    if (!isValidEmail($email)) {
        echo "<script>alert('Format email tidak valid'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    if (strlen($phone) > 12) {
        echo "<script>alert('Nomor telepon tidak boleh lebih dari 12.'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    if (!ctype_digit($phone)) {
        echo "<script>alert('Nomor telepon hanya boleh terdiri dari angka.'); window.location.href='admin/index.php?users';</script>";
        return;
    }

    if ($_FILES["upload_image"]["error"] == 0) {
        $fileName = $_FILES["upload_image"]["name"];
        $fileSize = $_FILES["upload_image"]["size"];
        $tmpName = $_FILES["upload_image"]["tmp_name"];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = 'storage/' . uniqid() . '.' . $imageExtension;
            $targetFilePath = $target_dir . basename($newImageName);

            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $query = "UPDATE users SET username = ?, email = ?, role = ?, upload_image = ?, phone = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssissi", $username, $email, $role, $newImageName, $phone, $id);
            } else {
                echo "<script>alert('Failed to upload new image');</script>";
                return;
            }
        } else {
            echo "<script>alert('Invalid image extension or size too large');</script>";
            return;
        }
    } else {
        //update tanpa merubah image
        $query = "UPDATE users SET username = ?, email = ?, role = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $username, $email, $role, $phone, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); window.location.href='admin/index.php?users';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Proses hapus pengguna
if (isset($_POST['delete_user_id'])) {
    $id = $_POST['delete_user_id'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User  berhasil dihapus!'); window.location.href='admin/index.php?users';</script>";
    } else {
        echo "<script>alert('User  gagal dihapus!');</script>";
    }
}

// Mengambil data pengguna
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Data Users</strong>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><em class="icon ni ni-plus"></em> Add Data</button>
                                    </li>
                                    <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="row g-gs">
                    <table class="datatable-init table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Upload Image</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = 1;
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $id++; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <?php
                                        echo $row['role'] == 0 ? 'User  ' : 'Admin';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td>
                                        <?php
                                        echo !empty($row['upload_image'])
                                            ? '<img src="http://localhost/WEBSITE-MYBIMO/mybimo/src/getData/' . htmlspecialchars($row['upload_image']) . '" alt="User  Image" width="50" height="50">'
                                            : 'Tidak ada foto';
                                        ?>
                                    </td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                        <!-- Form Hapus -->
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit Pengguna -->
                                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label">Role</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="0" <?php echo ($row['role'] == 0) ? 'selected' : ''; ?>>User </option>
                                                            <option value="1" <?php echo ($row['role'] == 1) ? 'selected' : ''; ?>>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone</label>
                                                        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="upload_image" class="form-label">Upload Image</label>
                                                        <input type="file" class="form-control" name="upload_image" accept=".jpg,.jpeg,.png">
                                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                                                        <div class="mt-2">
                                                            <img src="http://localhost/WEBSITE-MYBIMO/mybimo/src/getData/<?php echo htmlspecialchars($row['upload_image']); ?>" alt="Icon" width="60" height="60">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="0">User </option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="yourPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="yourPassword"
                            placeholder="Password" required
                            minlength="6">
                        <div class="invalid-feedback">Please enter a password (min. 6 characters)!</div>
                    </div>
                    <div class="mb-3">
                        <label for="upload_image" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" name="upload_image" accept=".jpg,.jpeg,.png" required>
                        <small class="text-muted">Format: JPG, JPEG, PNG (Max 1MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>

</html>