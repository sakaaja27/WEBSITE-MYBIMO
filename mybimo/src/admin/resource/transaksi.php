<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan transaksi
if (isset($_POST['add_transaksi'])) {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $pembayaran_id = isset($_POST['pembayaran_id']) ? $_POST['pembayaran_id'] : '';
    $materi_id = isset($_POST['materi_id']) ? $_POST['materi_id'] : '';
    $status_pembayaran = isset($_POST['status_pembayaran']) ? $_POST['status_pembayaran'] : '';

    // Validasi input
    if (empty($user_id) || empty($pembayaran_id) || empty($materi_id) || !isset($status_pembayaran)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
    } else {
        // Validasi status pembayaran
        if ($status_pembayaran !== '0' && $status_pembayaran !== '1') {
            echo "<script>alert('Status pembayaran tidak valid. Gunakan 0 untuk pending atau 1 untuk berhasil.');</script>";
        } else {
            // Proses pengunggahan bukti
            $upload_bukti = ''; 
            if (isset($_FILES['upload_bukti']) && $_FILES['upload_bukti']['error'] == 0) {
                $file_tmp = $_FILES['upload_bukti']['tmp_name'];
                $file_name = basename($_FILES['upload_bukti']['name']);
                $target_dir = '../getData/storage/';
                $target_file = $target_dir . $file_name;

                // Validasi tipe file
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

            // Konversi status pembayaran ke string yang sesuai
            $status_text = ($status_pembayaran === '0') ? 'Pending' : 'Berhasil';

            $query = "INSERT INTO transaksi (id_user, id_pembayaran, status, id_materi, upload_bukti, created_at) 
                      VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iisss", $user_id, $pembayaran_id, $status_text, $materi_id, $upload_bukti);
            if ($stmt->execute()) {
                echo "<script>alert('Transaksi berhasil ditambahkan dengan status: " . $status_text . "');</script>";
            } else {
                echo "<script>alert('Gagal menambahkan transaksi: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
}

/// Fungsi untuk menambahkan transaksi
if (isset($_POST['add_transaksi'])) {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $pembayaran_id = isset($_POST['pembayaran_id']) ? $_POST['pembayaran_id'] : '';
    $materi_id = isset($_POST['materi_id']) && $_POST['materi_id'] !== '' ? $_POST['materi_id'] : null; // Pastikan tidak kosong
    $status_pembayaran = isset($_POST['status_pembayaran']) ? $_POST['status_pembayaran'] : '';

    // Validasi input
    if (empty($user_id) || empty($pembayaran_id) || is_null($materi_id) || !isset($status_pembayaran)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
    } else {
        // Validasi status pembayaran
        if ($status_pembayaran !== '0' && $status_pembayaran !== '1') {
            echo "<script>alert('Status pembayaran tidak valid. Gunakan 0 untuk pending atau 1 untuk berhasil.');</script>";
        } else {
            // Proses pengunggahan bukti
            $upload_bukti = ''; 
            if (isset($_FILES['upload_bukti']) && $_FILES['upload_bukti']['error'] == 0) {
                $file_tmp = $_FILES['upload_bukti']['tmp_name'];
                $file_name = basename($_FILES['upload_bukti']['name']);
                $target_dir = '../getData/storage/';
                $target_file = $target_dir . $file_name;

                // Validasi tipe file
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

            // Gunakan status_pembayaran langsung (0 atau 1) untuk kolom status
            $query = "INSERT INTO transaksi (id_user, id_pembayaran, status, id_materi, upload_bukti, created_at) 
                      VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiiss", $user_id, $pembayaran_id, $status_pembayaran, $materi_id, $upload_bukti);
            if ($stmt->execute()) {
                echo "<script>alert('Transaksi berhasil ditambahkan dengan status: " . ($status_pembayaran === '0' ? 'Pending' : 'Berhasil') . "');</script>";
            } else {
                echo "<script>alert('Gagal menambahkan transaksi: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
}

// Fungsi untuk menghapus transaksi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM transaksi WHERE id='$id'";
    $conn->query($query);
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
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addModal"><em class="icon ni ni-plus"></em>
                                            Tambah Transaksi
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
                <div class="row g-gs">
                    <table class="datatable-init table table-bordered table-hover" style="width: 100%;">
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
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['user_name']; ?></td>
                                    <td><?= $row['harga']; ?></td>
                                    <td><?= $row['status']; ?></td>
                                    <td><?= $row['status_materi']; ?></td>
                                    <td><?= $row['upload_bukti']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- Modal Edit Transaksi -->
                                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Transaksi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="user_id" class="form-label">Nama User</label>
                                                        <input type="text" class="form-control" name="username" value="<?php echo $row['user_name']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga" class="form-label">Harga</label>
                                                        <input type="number" class="form-control" name="harga" value="<?php echo $row['harga']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                                                        <input type="text" class="form-control" name="status_pembayaran" value="<?php echo $row['status']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status_materi" class="form-label">Status Materi</label>
                                                        <input type="text" class="form-control" name="status_materi" value="<?php echo $row['status_materi']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="upload_bukti" class="form-label">Upload Bukti</label>
                                                        <input type="file" class="form-control" name="upload_bukti" accept="image/*,application/pdf">
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Nama User</label>
                        <select class="form-control" name="user_id" required>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <option value="<?= $user['id']; ?>"><?= $user['username']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pembayaran_id" class="form-label">Harga</label>
                        <select class="form-control" name="pembayaran_id" required>
                            <?php while ($pembayaran = $pembayarans->fetch_assoc()): ?>
                                <option value="<?= $pembayaran['id']; ?>"><?= $pembayaran['harga']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="materi_id" class="form-label">Status Materi</label>
                        <select class="form-control" name="materi_id" required>
                            <?php while ($materi = $materis->fetch_assoc()): ?>
                                <option value="<?= $materi['id']; ?>"><?= $materi['status_materi']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                        <select class="form-control" name="status_pembayaran" required>
                            <option value="0">Pending</option>
                            <option value="1">Berhasil</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="upload_bukti" class="form-label">Upload Bukti</label>
                        <input type="file" class="form-control" name="upload_bukti" accept="image/*,application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_transaksi" class="btn btn-primary">Tambah Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

