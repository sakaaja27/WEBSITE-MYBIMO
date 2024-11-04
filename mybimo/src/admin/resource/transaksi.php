<?php
require_once '../koneksi/koneksi.php';

$validImageExtension = ['jpg', 'png', 'jpeg'];
$target_dir = "../getData/storagetransaksi/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Fungsi untuk menambahkan transaksi
if (isset($_POST['add_transaksi'])) {
    $id_user = $_POST['id_user'];
    $id_pembayaran = $_POST['id_pembayaran'];
    $id_materi = $_POST['id_materi'];
    $status = $_POST['status'];
    $upload_bukti = '';

    if ($_FILES["upload_bukti"]["error"] == 0) {
        $fileName = $_FILES["upload_bukti"]["name"];
        $fileSize = $_FILES["upload_bukti"]["size"];
        $tmpName = $_FILES["upload_bukti"]["tmp_name"];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = uniqid() . '.' . $imageExtension;
            $targetFilePath = $target_dir . $newImageName;

            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $upload_bukti = $newImageName;
            } else {
                echo "<script>alert('Gagal mengunggah gambar'); window.location.href='admin/index.php?transaksi';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Ekstensi gambar tidak valid atau ukuran terlalu besar'); window.location.href='admin/index.php?transaksi';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Silakan unggah gambar'); window.location.href='admin/index.php?transaksi';</script>";
        exit;
    }

    $query = "INSERT INTO transaksi (id_user, id_pembayaran, status, id_materi, upload_bukti, created_at) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiss", $id_user, $id_pembayaran, $status, $id_materi, $upload_bukti);

    if ($stmt->execute()) {
        echo "<script>alert('Transaksi berhasil ditambahkan!'); window.location.href='admin/index.php?transaksi';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
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
    $existing_upload_bukti = $_POST['existing_upload_bukti'];

    $upload_bukti = $existing_upload_bukti; // Default to existing image

    if ($_FILES["upload_bukti"]["error"] == 0) {
        $fileName = $_FILES["upload_bukti"]["name"];
        $fileSize = $_FILES["upload_bukti"]["size"];
        $tmpName = $_FILES["upload_bukti"]["tmp_name"];
        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = uniqid() . '.' . $imageExtension;
            $targetFilePath = $target_dir . $newImageName;

            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $upload_bukti = $newImageName;
                // Delete old file if exists
                if (file_exists($target_dir . $existing_upload_bukti)) {
                    unlink($target_dir . $existing_upload_bukti);
                }
            } else {
                echo "<script>alert('Gagal mengunggah gambar baru'); window.location.href='admin/index.php?transaksi';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Ekstensi gambar tidak valid atau ukuran terlalu besar'); window.location.href='admin/index.php?transaksi';</script>";
            exit;
        }
    }

    $query = "UPDATE transaksi SET id_user = ?, id_pembayaran = ?, status = ?, id_materi = ?, upload_bukti = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissi", $id_user, $id_pembayaran, $status, $id_materi, $upload_bukti, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); window.location.href='admin/index.php?transaksi';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fungsi untuk menghapus transaksi
if (isset($_POST['delete_transaksi'])) {
    $id = $_POST['delete_transaksi'];

    // Get the filename of the image to delete
    $query = "SELECT upload_bukti FROM transaksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $filename = $row['upload_bukti'];

    // Delete the record from the database
    $query = "DELETE FROM transaksi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Delete the file if it exists
        if ($filename && file_exists($target_dir . $filename)) {
            unlink($target_dir . $filename);
        }
        echo "<script>alert('Transaksi berhasil dihapus'); window.location.href='admin/index.php?transaksi';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
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
$materis = $conn->query("SELECT * FROM materi where st");
?>

<!-- HTML untuk Tabel dan Modal -->
<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="nk-block-title">Detail Transaksi</h4>
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
                            <th>Upload Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $id = 1;
                        while ($row = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= $id++ ?></td>
                                <td><?= htmlspecialchars($row['user_name']); ?></td>
                                <td><?= htmlspecialchars($row['harga']); ?></td>
                                <td><?= $row['status'] == '0' ? '<span class="badge bg-warning">Pending</span>' : '<span class="badge bg-success">Berhasil</span>'; ?></td>
                                <td>
                                    <?php
                                    if ($row['status_materi'] == '0') {
                                        echo '<span class="badge bg-danger">Belum aktif</span>';
                                    } elseif ($row['status_materi'] == '1') {
                                        echo '<span class="badge bg-success">Aktif</span>';
                                    } else {
                                        echo '<span class="badge bg-warning">Pending</span>';
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($row['upload_bukti']); ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="delete_transaksi" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Transaksi -->
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
                                                    <select name="id_user" id="id_user" class="form-control">
                                                        <?php
                                                        $users->data_seek(0); // Reset pointer
                                                        while ($user = $users->fetch_assoc()): ?>
                                                            <option value="<?= $user['id'] ?>" <?= $user['id'] == $row['id_user'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($user['username']) ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="id_pembayaran" class="form-label">Pembayaran</label>
                                                    <select name="id_pembayaran" id="id_pembayaran" class="form-control">
                                                        <?php
                                                        $pembayarans->data_seek(0);
                                                        while ($pembayaran = $pembayarans->fetch_assoc()): ?>
                                                            <option value="<?= $pembayaran['id'] ?>" <?= $pembayaran['id'] == $row['id_pembayaran'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($pembayaran['harga']) ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status Pembayaran</label>
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="0" <?= $row['status'] == '0' ? 'selected' : '' ?>>Pending</option>
                                                        <option value="1" <?= $row['status'] == '1' ? 'selected' : '' ?>>Berhasil</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="id_materi" class="form-label">Materi</label>
                                                    <select name="id_materi" id="id_materi" class="form-control" disabled>
                                                        <?php
                                                        $materis->data_seek(0);
                                                        while ($materi = $materis->fetch_assoc()): ?>
                                                            <option value="<?= $materi['id'] ?>" <?= $materi['id'] == $row['id_materi'] ? 'selected' : '' ?>>
                                                                <?php
                                                                if ($materi['status_materi'] == 0) {
                                                                    echo "Belum Aktif";
                                                                } else {
                                                                    echo "Aktif ";
                                                                }
                                                                ?>

                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="upload_bukti" class="form-label">Upload Bukti</label>
                                                    <input type="file" name="upload_bukti" id="upload_bukti" class="form-control">
                                                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> -->
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="update_transaksi" class="btn btn-primary">Simpan</button>
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
                        <select name="id_user" id="id_user" class="form-control">
                            <?php
                            $users->data_seek(0); // Reset pointer
                            while ($user = $users->fetch_assoc()): ?>
                                <option value="<?= $user['id'] ?>">
                                    <?= $user['username'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_pembayaran" class="form-label">Pembayaran</label>
                        <select name="id_pembayaran" id="id_pembayaran" class="form-control" required>
                            <?php
                            $pembayarans->data_seek(0); // Reset pointer
                            while ($pembayaran = $pembayarans->fetch_assoc()): ?>
                                <option value="<?= $pembayaran['id'] ?>">
                                    <?= $pembayaran['nama_pembayaran'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_materi" class="form-label">Materi</label>
                        <select name="id_materi" id="id_materi" class="form-control" required>
                            <option value="">Pilih Materi</option>
                            <?php while ($materi = $materis->fetch_assoc()): ?>
                                <option value="<?= $materi['id']; ?>"><?= htmlspecialchars($materi['id']); ?></option>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="add_transaksi" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk Bootstrap (jika diperlukan) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>

</html>