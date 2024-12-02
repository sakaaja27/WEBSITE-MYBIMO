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
    $status = $_POST['status'] ?? '0';
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

    $query = "INSERT INTO transaksi (id_user, id_pembayaran, status, upload_bukti, created_at) 
              VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $id_user, $id_pembayaran, $status, $upload_bukti);

    if ($stmt->execute()) {
        echo "<script>alert('Transaksi berhasil ditambahkan!'); window.location.href='admin/index.php?transaksi';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fungsi untuk mengupdate transaksi berhasil
if (isset($_POST['update_transaksi'])) {
    $id = $_POST['id'];
    $status = 1;

    try {
        // Update transaksi
        $query = "UPDATE transaksi SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $status, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil dikonfirmasi'); window.location.href='admin/index.php?transaksi';</script>";
        } else {
            throw new Exception("Gagal konfirmasi status transaksi");
        }
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href='index.php?page=transaksi';
        </script>";
    }

    $stmt->close();
}

// Fungsi untuk mengupdate transaksi tolak
if (isset($_POST['tolak_transaksi'])) {
    $id = $_POST['id'];
    $status = 2;

    try {
        // Update transaksi
        $query = "UPDATE transaksi SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $status, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil ditolak'); window.location.href='admin/index.php?transaksi';</script>";
        } else {
            throw new Exception("Gagal tolak status transaksi");
        }
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . $e->getMessage() . "');
            window.location.href='index.php?page=transaksi';
        </script>";
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

//mengambil data transaksi setiap users
$transaksi_users = [];
$result_transaksi = $conn->query("SELECT id_user FROM transaksi");
while ($row_transaksi = $result_transaksi->fetch_assoc()) {
    $transaksi_users[] = $row_transaksi['id_user'];
}

// Mengambil data transaksi
$result = $conn->query("select * from view_transaksi_lengkap");
$users = $conn->query("SELECT * FROM users");
$pembayarans = $conn->query("SELECT * FROM pembayaran");

// Di file config atau functions
function base_url($path = '')
{
    $base_url = 'http://localhost/WEBSITE-MYBIMO/mybimo/src/'; // Sesuaikan dengan domain Anda
    return $base_url . $path;
}
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
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <a href="../src/admin/resource/excel2.php" class="btn btn-success">
                                            <i class="bi bi-file-earmark-excel"></i> Export to Excel
                                        </a>
                                    </div>
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
                            <th>Email</th>
                            <th>Harga</th>
                            <th>Status Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $transaksi_id = 1;
                        while ($row = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= $transaksi_id++ ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['harga']); ?></td>
                                <td><?php
                                    echo $row['status_transaksi'] == '0' ? '<span class="badge bg-warning">Pending</span>' : ($row['status_transaksi'] == '1' ? '<span class="badge bg-success">Konfirmasi</span>' : ($row['status_transaksi'] == '2' ? '<span class="badge bg-danger">Ditolak</span>' :
                                        '<span class="badge bg-secondary">Tidak Aktif</span>'));
                                    ?></td>

                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>"><em class="icon ni ni-eye-fill"></em></button>
                                </td>
                            </tr>

                            <!-- Modal Edit Transaksi -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Bukti Transaksi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="row mt-3">
                                                    <div class="col-md-6 ">
                                                        <div class="mb-3 ms-2">
                                                            <p class="form-label">Username : <?php echo $row['username']; ?></p>
                                                        </div>
                                                        <div class="mb-3 ms-2">
                                                            <p class="form-label">Email : <?php echo $row['email']; ?></p>
                                                        </div>
                                                        <div class="mb-3 ms-2">
                                                            <p class="form-label">Phone : <?php echo $row['phone']; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 ">
                                                        <div class="mb-3">
                                                            <p class="form-label">Harga : <?php echo $row['harga']; ?></p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <p class="form-label">Status transaksi :
                                                                <?php
                                                                echo $row['status_transaksi'] == '0' ? '<span class="badge bg-warning">Pending</span>' : ($row['status_transaksi'] == '1' ? '<span class="badge bg-success">Konfirmasi</span>' : ($row['status_transaksi'] == '2' ? '<span class="badge bg-danger">Ditolak</span>' :
                                                                    '<span class="badge bg-secondary">Tidak Diketahui</span>'));
                                                                ?>
                                                            </p>
                                                        </div>
                                                        <div class="mb-3">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 ms-2">
                                                        <label for="upload_bukti" class="form-label">Bukti Pembayaran</label>
                                                        <div>
                                                            <?php if ($row['upload_bukti'] != ''): ?>
                                                                <!-- Tambahkan link yang membungkus gambar -->
                                                                <a href="<?php echo base_url('getData/storagetransaksi/' . $row['upload_bukti']); ?>"
                                                                    data-lightbox="bukti-pembayaran"
                                                                    data-title="Bukti Pembayaran" alt="Bukti Pembayaran">
                                                                    <img src="<?php echo base_url('getData/storagetransaksi/' . $row['upload_bukti']); ?>"
                                                                        alt="Bukti Pembayaran"
                                                                        width="100"
                                                                        class="mt-2 mx-auto">
                                                                </a>
                                                            <?php else: ?>
                                                                <p class="mt-2">Tidak ada bukti pembayaran</p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> -->
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                                <button type="submit" name="tolak_transaksi" class="btn btn-danger">Tolak</button>
                                                <button type="submit" name="update_transaksi" class="btn btn-info">Konfirmasi</button>

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
                            $users->data_seek(0);
                            while ($user = $users->fetch_assoc()):
                                if (!in_array($user['id'], $transaksi_users)): ?>
                                    <option value="<?= $user['id'] ?>">
                                        <?= $user['username'] ?>
                                    </option>
                            <?php endif;
                            endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_pembayaran" class="form-label">Pembayaran</label>
                        <select name="id_pembayaran" id="id_pembayaran" class="form-control" required>
                            <?php
                            $pembayarans->data_seek(0);
                            while ($pembayaran = $pembayarans->fetch_assoc()): ?>
                                <option value="<?= $pembayaran['id'] ?>">
                                    <?= $pembayaran['nama_pembayaran'] . ' - ' . $pembayaran['harga'] . '' ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" disabled>
                            <option value="0">Pending</option>
                            <!-- <option value="1">Berhasil</option> -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="upload_bukti" class="form-label">Upload Bukti</label>
                        <input type="file" class="form-control" name="upload_bukti" id="upload_bukti" required>
                        <small class="text-muted">Format: JPG, JPEG, PNG (Max 1MB)</small>
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