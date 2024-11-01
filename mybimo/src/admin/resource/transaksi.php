<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan transaksi
if (isset($_POST['add_transaksi'])) {
    $id_user = $_POST['id_user'];
    $id_pembayaran = $_POST['id_pembayaran'];
    $id_materi = $_POST['id_materi'];
    $status = $_POST['status'];
    $upload_bukti = '';

    // Proses pengunggahan file
    if (isset($_FILES['upload_bukti']) && $_FILES['upload_bukti']['error'] == 0) {
        $file_tmp = $_FILES['upload_bukti']['tmp_name'];
        $file_name = basename($_FILES['upload_bukti']['name']);
        $target_dir = '../getData/storagetransaksi/';
        $target_file = $target_dir . $file_name;

        $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'application/pdf'];
        if (in_array($_FILES['upload_bukti']['type'], $allowed_types)) {
            if (move_uploaded_file($file_tmp, $target_file)) {
                $upload_bukti = $target_file;
            } else {
                echo "<script>alert('Gagal mengunggah file bukti.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Tipe file tidak diperbolehkan untuk upload bukti.');</script>";
            exit;
        }
    }

    // Menyimpan data ke dalam database
    $query = "INSERT INTO transaksi (id_user, id_pembayaran, status, id_materi, upload_bukti, created_at) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiss", $id_user, $id_pembayaran, $status, $id_materi, $upload_bukti); // Pastikan urutan dan jumlah parameter sesuai
    if ($stmt->execute()) {
        echo "<script>alert('Transaksi berhasil ditambahkan dengan status: " . ($status === '0' ? 'Pending' : 'Berhasil') . "');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan transaksi: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fungsi untuk menghapus transaksi
if (isset($_POST['delete_transaksi'])) {
    $id_transaksi = $_POST['delete_transaksi'];
    $query = "DELETE FROM transaksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_transaksi);
    if ($stmt->execute()) {
        echo "<script>alert('Transaksi berhasil dihapus.');</script>";
    } else {
        echo "<script>alert('Gagal menghapus transaksi: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fungsi untuk mengupdate transaksi
if (isset($_POST['update_transaksi'])) {
    $id = $_POST['id'];
    $id_user = $_POST['id_user'];
    $id_pembayaran = $_POST['id_pembayaran'];
    $id_materi = $_POST['id_materi'];
    $status = $_POST['status'];
    $upload_bukti = $_POST['existing_upload_bukti']; // Gunakan yang sudah ada jika tidak ada upload baru

    // Proses pengunggahan file (jika ada)
    if (isset($_FILES['upload_bukti']) && $_FILES['upload_bukti']['error'] == 0) {
        $file_tmp = $_FILES['upload_bukti']['tmp_name'];
        $file_name = basename($_FILES['upload_bukti']['name']);
        $target_dir = '../getData/storagetransaksi/';
        $target_file = $target_dir . $file_name;

        $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'application/pdf'];
        if (in_array($_FILES['upload_bukti']['type'], $allowed_types)) {
            if (move_uploaded_file($file_tmp, $target_file)) {
                $upload_bukti = $target_file; // Ganti dengan yang baru
            } else {
                echo "<script>alert('Gagal mengunggah file bukti.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Tipe file tidak diperbolehkan untuk upload bukti.');</script>";
            exit;
        }
    }

    // Menyimpan data yang diupdate ke dalam database
    $query = "UPDATE transaksi SET id_user = ?, id_pembayaran = ?, status = ?, id_materi = ?, upload_bukti = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiisi", $id_user, $id_pembayaran, $status, $id_materi, $upload_bukti, $id); // Pastikan urutan dan jumlah parameter sesuai
    if ($stmt->execute()) {
        echo "<script>alert('Transaksi berhasil diupdate.');</script>";
    } else {
        echo "<script>alert('Gagal mengupdate transaksi: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Mengambil data transaksi
$result = $conn->query("SELECT t.*, u.username AS user_name, u.email, p.harga, m.status_materi 
                        FROM transaksi t 
                        JOIN users u ON t.id_user = u.id 
                        JOIN pembayaran p ON t.id_pembayaran = p.id 
                        JOIN materi m ON t.id_materi = m.id");

$users = $conn->query("SELECT * FROM users");
$pembayarans = $conn->query("SELECT * FROM pembayaran");
$materis = $conn->query("SELECT * FROM materi");
?>

<!-- HTML untuk Tabel dan Modal -->
<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Detail Transaksi</strong>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
                                <em class="icon ni ni-more-v"></em>
                            </a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addModal"><em class="icon ni ni-plus"></em>
                                            Add Data
                                        </button>
                                    </li>
                                    <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em
                                                class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <table class="datatable-init table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama User</th>
                            <th>Harga</th>
                            <th>Status Pembayaran</th>
                            <th>Status Materi</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $id = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $id++ ?></td>
                                <td><?= $row['user_name']; ?></td>
                                <td><?= $row['harga']; ?></td>
                                <td><?= $row['status'] == '0' ? '<span class="badge bg-warning">Pending</span>' : '<span class="badge bg-success">Berhasil</span>'; ?></td>
                                <td><?= $row['status_materi']; ?></td>
                                <td><?= $row['upload_bukti']; ?></td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                    <!-- Tombol Hapus -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="delete_transaksi" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Transaksi -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_user" class="form-label">User</label>
                        <select name="id_user" id="id_user" class="form-control" required>
                            <option value="">Pilih User</option>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <option value="<?= $user['id']; ?>"><?= $user['username']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_pembayaran" class="form-label">Pembayaran</label>
                        <select name="id_pembayaran" id="id_pembayaran" class="form-control" required>
                            <option value="">Pilih Pembayaran</option>
                            <?php while ($pembayaran = $pembayarans->fetch_assoc()): ?>
                                <option value="<?= $pembayaran['id']; ?>"><?= $pembayaran['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_materi" class="form-label">Materi</label>
                        <select name="id_materi" id="id_materi" class="form-control" required>
                            <option value="">Pilih Materi</option>
                            <?php while ($materi = $materis->fetch_assoc()): ?>
                                <option value="<?= $materi['id']; ?>"><?= $materi['judul']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="0">Pending</option>
                            <option value="1">Berhasil</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="upload_bukti" class="form-label">Upload Bukti</label>
                        <input type="file" class="form-control" name="upload_bukti" id="upload_bukti" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_transaksi" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Transaksi -->
<?php while ($row = $result->fetch_assoc()): ?>
<div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="existing_upload_bukti" value="<?php echo $row['upload_bukti']; ?>">
                    <div class="mb-3">
                        <label for="id_user" class="form-label">User</label>
                        <select name="id_user" id="id_user" class="form-control" required>
                            <option value="">Pilih User</option>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <option value="<?= $user['id']; ?>" <?= ($user['id'] == $row['id_user']) ? 'selected' : ''; ?>><?= $user['username']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_pembayaran" class="form-label">Pembayaran</label>
                        <select name="id_pembayaran" id="id_pembayaran" class="form-control" required>
                            <option value="">Pilih Pembayaran</option>
                            <?php while ($pembayaran = $pembayarans->fetch_assoc()): ?>
                                <option value="<?= $pembayaran['id']; ?>" <?= ($pembayaran['id'] == $row['id_pembayaran']) ? 'selected' : ''; ?>><?= $pembayaran['nama']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_materi" class="form-label">Materi</label>
                        <select name="id_materi" id="id_materi" class="form-control" required>
                            <option value="">Pilih Materi</option>
                            <?php while ($materi = $materis->fetch_assoc()): ?>
                                <option value="<?= $materi['id']; ?>" <?= ($materi['id'] == $row['id_materi']) ? 'selected' : ''; ?>><?= $materi['judul']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="0" <?= ($row['status'] == '0') ? 'selected' : ''; ?>>Pending</option>
                            <option value="1" <?= ($row['status'] == '1') ? 'selected' : ''; ?>>Berhasil</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="upload_bukti" class="form-label">Upload Bukti (jika baru)</label>
                        <input type="file" class="form-control" name="upload_bukti" id="upload_bukti">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_transaksi" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>
