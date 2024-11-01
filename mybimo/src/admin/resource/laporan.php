<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan pembayaran
if (isset($_POST['add_pembayaran'])) {
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $harga = $_POST['harga'];
    $nomor_bank = $_POST['nomor_bank'];
    $tanggal = $_POST['tanggal'];

    $query = "INSERT INTO pembayaran (nama_pembayaran, harga, nomor_bank, created_at) VALUES ('$nama_pembayaran', '$harga', '$nomor_bank', '$tanggal')";
    $conn->query($query);
}

// Fungsi untuk mengedit pembayaran
if (isset($_POST['update_pembayaran'])) {
    $id = $_POST['id'];
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $harga = $_POST['harga'];
    $nomor_bank = $_POST['nomor_bank'];
    $tanggal = $_POST['tanggal'];

    $query = "UPDATE pembayaran SET nama_pembayaran='$nama_pembayaran', harga='$harga', nomor_bank='$nomor_bank', created_at='$tanggal' WHERE id='$id'";
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
                <div class="d-flex justify-content-end">
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
                    </div>
                </div>
            </div>
            <!-- Filter Date Range -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Tanggal Awal</label>
                            <input type="text" class="form-control datepicker" name="start_date" value="<?php echo $start_date; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="text" class="form-control datepicker" name="end_date" value="<?php echo $end_date; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="nk-block">
                <div class="row g-gs">
                    <table class="datatable-init table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama Pembayaran</th>
                                <th>Harga</th>
                                <th>Nomor Bank</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = 1;
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $id++ ?></td>
                                    <td><?php echo $row['nama_pembayaran']; ?></td>
                                    <td><?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $row['nomor_bank']; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Pembayaran</label>
                                                        <input type="text" class="form-control" name="nama_pembayaran" value="<?php echo $row['nama_pembayaran']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Harga</label>
                                                        <input type="number" class="form-control" name="harga" value="<?php echo $row['harga']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nomor Bank</label>
                                                        <input type="text" class="form-control" name="nomor_bank" value="<?php echo $row['nomor_bank']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal</label>
                                                        <input type="text" class="form-control datepicker" name="tanggal" value="<?php echo date('Y-m-d', strtotime($row['created_at'])); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="update_pembayaran" class="btn btn-primary">Update</button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pembayaran</label>
                        <input type="text" class="form-control" name="nama_pembayaran" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Bank</label>
                        <input type="text" class="form-control" name="nomor_bank" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control datepicker" name="tanggal" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_pembayaran" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DatePicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>

</body>

</html>