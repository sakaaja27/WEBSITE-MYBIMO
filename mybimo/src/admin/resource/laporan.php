<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan pembayaran
if (isset($_POST['add_pembayaran'])) {
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $harga = $_POST['harga'];
    $nomer_bank = $_POST['nomer_bank'];
    $tanggal = $_POST['tanggal'];

    $query = "INSERT INTO pembayaran (nama_pembayaran, harga, nomer_bank, created_at) VALUES ('$nama_pembayaran', '$harga', '$nomer_bank', '$tanggal')";
    $conn->query($query);
}

// Fungsi untuk mengedit pembayaran
if (isset($_POST['update_pembayaran'])) {
    $id = $_POST['id'];
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $harga = $_POST['harga'];
    $nomer_bank = $_POST['nomer_bank'];
    $tanggal = $_POST['tanggal'];

    $query = "UPDATE pembayaran SET nama_pembayaran='$nama_pembayaran', harga='$harga', nomer_bank='$nomer_bank', created_at='$tanggal' WHERE id='$id'";
    $conn->query($query);
}

// Fungsi untuk menghapus pembayaran
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM pembayaran WHERE id='$id'";
    $conn->query($query);
}

// Mengambil data pembayaran berdasarkan rentang tanggal
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$query = "SELECT * FROM pembayaran";
if ($start_date && $end_date) {
    $query .= " WHERE created_at BETWEEN '$start_date' AND '$end_date'";
}

$result = $conn->query($query);
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Data Laporan Keuangan</strong>
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
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <?php
                                        // Mengubah angka role menjadi nama
                                        if ($row['role'] == 0) {
                                            echo 'User';
                                        } elseif ($row['role'] == 1) {
                                            echo 'Admin';
                                        } elseif ($row['role'] == 2) {
                                            echo 'Super Admin';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['phone']; ?></td>
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
                                            <form method="POST">
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
                                                            <option value="2" <?php echo ($row['role'] == 2) ? 'selected' : ''; ?>>Super Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone</label>
                                                        <input type="number" class="form-control" name="phone" value="<?php echo $row['phone']; ?>" required>
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
                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
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
                        <label for="phone" class="form-label">Phone</label>
                        <input type="number" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                            <option value="2">Super Admin</option>
                        </select>
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

<!-- Menambahkan Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>