<?php
require_once '../koneksi/koneksi.php';

// Proses penambahan pengguna baru
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = (int)$_POST['role'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Proses upload gambar
    $upload_dir = '../storageImage'; // Ganti dengan direktori yang sesuai
    $upload_file = $upload_dir . basename($_FILES['upload_image']['name']);
    $upload_image = $_FILES['upload_image']['name'];

    // Pastikan direktori ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $upload_file)) {
        $sql = "INSERT INTO users (username, email, role, phone, password, upload_image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisss", $username, $email, $role, $phone, $password, $upload_image);
        
        if ($stmt->execute()) {
            echo "<script>alert('User berhasil ditambahkan!');</script>";
        } else {
            echo "<script>alert('Gagal menambahkan user');</script>";
        }
    } else {
        echo "<script>alert('Gagal mengupload gambar.');</script>";
    }
}

// Proses update pengguna
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = (int)$_POST['role'];
    $phone = $_POST['phone'];

    // Proses upload gambar (jika ada)
    if ($_FILES['upload_image']['error'] == 0) { // Jika ada gambar yang diupload
        $upload_dir = '../storageImage';
        $upload_file = $upload_dir . basename($_FILES['upload_image']['name']);
        $upload_image = $_FILES['upload_image']['name'];

        // Pastikan direktori ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $upload_file)) {
            $sql = "UPDATE users SET username = ?, email = ?, role = ?, phone = ?, upload_image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissi", $username, $email, $role, $phone, $upload_image, $id);
        } else {
            echo "<script>alert('Gagal mengupload gambar.');</script>";
        }
    } else {
        // Jika tidak ada gambar baru, update tanpa gambar
        $sql = "UPDATE users SET username = ?, email = ?, role = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $username, $email, $role, $phone, $id);
    }

    // Eksekusi statement jika terdefinisi
    if (isset($stmt) && $stmt->execute()) {
        echo "<script>alert('User berhasil diupdate!'); window.location.href='admin/index.php?users';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui user.');</script>";
    }
}

// Proses hapus pengguna
if (isset($_POST['delete_user_id'])) {
    $id = $_POST['delete_user_id'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User berhasil dihapus!'); window.location.href='admin/index.php?users';</script>";
    } else {
        echo "<script>alert('User Gagal dihapus!');</script>";
    }
}

// Mengambil data pengguna
$result = $conn->query("SELECT * FROM users");
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
                            $id = 1; // Inisialisasi ID untuk penomoran
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $id++; ?></td> <!-- Menampilkan ID -->
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <?php
                                        // Mengubah angka role menjadi nama
                                        if ($row['role'] == 0) {
                                            echo 'User';
                                        } elseif ($row['role'] == 1) {
                                            echo 'Admin';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td>
                                        <?php if (!empty($row['upload_image'])): ?>
                                            <img src="../uploads/<?php echo $row['upload_image']; ?>" alt="Image" style="width: 50px; height: 50px;">
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
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
                                                        <input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label">Role</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="0" <?php echo ($row['role'] == 0) ? 'selected' : ''; ?>>User</option>
                                                            <option value="1" <?php echo ($row['role'] == 1) ? 'selected' : ''; ?>>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone</label>
                                                        <input type="number" class="form-control" name="phone" value="<?php echo $row['phone']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="upload_image" class="form-label">Upload Image</label>
                                                        <input type="file" class="form-control" name="upload_image">
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
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="upload_image" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" name="upload_image" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_user" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Javascript untuk Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
