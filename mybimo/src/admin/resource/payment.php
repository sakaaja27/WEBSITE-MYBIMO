<?php
require_once '../koneksi/koneksi.php';

// Proses penambahan pengguna baru
if (isset($_POST['add_payment'])) {
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $harga = $_POST['harga'];
    $nomor_bank = $_POST['nomor_bank'];

    // Modify the SQL to include created_at
    $sql = "INSERT INTO pembayaran (nama_pembayaran, harga, nomor_bank, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    // Adjust the bind_param to match the number of placeholders
    $stmt->bind_param("sss", $nama_pembayaran, $harga, $nomor_bank); // All three are strings

    if ($stmt->execute()) {
        echo "<script>alert('User  berhasil ditambahkan!');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan user');</script>";
    }
}


// Proses update pengguna
if (isset($_POST['update_payment'])) {
    $id = $_POST['id'];
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $harga = $_POST['harga'];
    $nomor_bank = $_POST['nomor_bank'];
    $sql = "UPDATE pembayaran SET nama_pembayaran = ?, harga = ?, nomor_bank = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama_pembayaran, $harga, $nomor_bank, $id);

    if ($stmt->execute()) {;
        echo "<script>alert('User berhasil diupdate!'); window.location.href='admin/index.php?payment';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui user.');</script>";
    }
}

// Proses hapus 
if (isset($_POST['delete_user_id'])) {
    $id = $_POST['delete_user_id'];

    $sql = "DELETE FROM pembayaran WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User berhasil dihapus!');</script>"; // Pastikan untuk keluar setelah redirect
    } else {
        echo "<script>alert('User Gagal dihapus!');</script>"; // Pastikan untuk keluar setelah redirect
    }
}

$result = $conn->query("SELECT * FROM pembayaran");
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
                    </div><!-- .nk-block-head-content -->
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
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
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->
            <div class="nk-block">
                <div class="row g-gs">
                    <table class="datatable-init table table-bordered table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Pembayaran</th>
                                <th>Harga</th>
                                <th>Nomor Bank</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $id = 1;
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $id++ ?></td>
                                    <td><?= $row['nama_pembayaran']; ?></td>
                                    <td><?= $row['harga']; ?></td>
                                    <td><?= $row['nomor_bank']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
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
                                            <form method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Payment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="nama_pembayaran" class="form-label">nama pembayaran</label>
                                                        <input type="text" class="form-control" name="nama_pembayaran" value="<?php echo $row['nama_pembayaran']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga" class="form-label">harga</label>
                                                        <input type="number" class="form-control" name="harga" value="<?php echo $row['harga']; ?>" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nomor_bank" class="form-label">nomor_bank</label>
                                                        <input type="number" class="form-control" name="nomor_bank" value="<?php echo $row['nomor_bank']; ?>" required>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="update_payment" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>



<!-- Modal untuk Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_pembayaran" class="form-label">nama pembayaran</label>
                        <input type="text" class="form-control" name="nama_pembayaran" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">harga</label>
                        <input type="number" class="form-control" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_bank" class="form-label">nomor bank</label>
                        <input type="number" class="form-control" name="nomor_bank" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_payment" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>