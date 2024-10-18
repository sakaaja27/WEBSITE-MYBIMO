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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2">
            <?php include '../sidebar.php'; ?>
            <link rel="stylesheet" href="../assets/css/style.css">
        </div>
        
        <!-- Konten Utama -->
        <div class="col-md-10">
            <div class="col-md-12">
                <?php include '../header.php'; ?>
                <link rel="stylesheet" href="../assets/css/style.css">
            </div>
            <h2>Laporan Pembayaran</h2>

            <!-- Form Pilih Rentang Tanggal -->
            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="start_date" class="form-label">Tanggal Awal</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" required>
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
            </form>

            <!-- Form Tambah Pembayaran -->
            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="nama_pembayaran" class="form-label">Nama Pembayaran</label>
                    <input type="text" class="form-control" name="nama_pembayaran" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" step="0.01" class="form-control" name="harga" required>
                </div>
                <div class="mb-3">
                    <label for="nomer_bank" class="form-label">Nomer Bank</label>
                    <input type="text" class="form-control" name="nomer_bank" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
                    <input type="text" class="form-control" id="tanggal" name="tanggal" required>
                </div>
                <button type="submit" name="add_pembayaran" class="btn btn-primary">Tambah Pembayaran</button>
            </form>

            <!-- Tabel Daftar Pembayaran -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pembayaran</th>
                        <th>Harga</th>
                        <th>Nomer Bank</th>
                        <th>Created At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nama_pembayaran']; ?></td>
                        <td><?php echo number_format($row['harga'], 2); ?></td>
                        <td><?php echo $row['nomor_bank']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                            <!-- Tombol Hapus -->
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?');">Hapus</a>
                        </td>
                    </tr>

                    <!-- Modal Edit Pembayaran -->
                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3">
                                            <label for="nama_pembayaran" class="form-label">Nama Pembayaran</label>
                                            <input type="text" class="form-control" name="nama_pembayaran" value="<?php echo $row['nama_pembayaran']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="harga" class="form-label">Harga</label>
                                            <input type="number" step="0.01" class="form-control" name="harga" value="<?php echo $row['harga']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nomer_bank" class="form-label">Nomer Bank</label>
                                            <input type="text" class="form-control" name="nomer_bank" value="<?php echo $row['nomer_bank']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Pembayaran</label>
                                            <input type="text" class="form-control" id="edit_tanggal<?php echo $row['id']; ?>" name="tanggal" value="<?php echo $row['created_at']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" name="update_pembayaran" class="btn btn-primary">Update Pembayaran</button>
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

<!-- Include Bootstrap and Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#start_date", {
        dateFormat: "Y-m-d", // Format tanggal
    });

    flatpickr("#end_date", {
        dateFormat: "Y-m-d", // Format tanggal
    });

    flatpickr("#tanggal", {
        dateFormat: "Y-m-d", // Format tanggal
    });

    // Inisialisasi Flatpickr untuk edit modal
    <?php while ($row = $result->fetch_assoc()): ?>
    flatpickr("#edit_tanggal<?php echo $row['id']; ?>", {
        dateFormat: "Y-m-d", // Format tanggal
    });
    <?php endwhile; ?>
</script>

</body>
</html>
