<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambah atau mengedit pembayaran
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $harga = $_POST['harga'];
    $nomor_bank = $_POST['nomor_bank'];
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    // Cek apakah ini edit atau tambah
    if ($id) {
        // Update data
        $sql = "UPDATE pembayaran SET nama_pembayaran='$nama_pembayaran', harga='$harga', nomor_bank='$nomor_bank' WHERE id=$id";
    } else {
        // Tambah data baru
        $sql = "INSERT INTO pembayaran (nama_pembayaran, harga, nomor_bank) 
                VALUES ('$nama_pembayaran', '$harga', '$nomor_bank')";
    }
}

// Fungsi untuk menghapus pembayaran
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM pembayaran WHERE id=$id");
    exit;
}

// Fungsi untuk mendapatkan data pembayaran yang akan diedit
$pembayaran = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM pembayaran WHERE id=$id");
    $pembayaran = $result->fetch_assoc();
}

$result = $conn->query("SELECT * FROM pembayaran");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                <?php include '../sidebar.php'; ?>
                <link rel="stylesheet" href="../assets/css/style.css">
            </div>

            <div class="col-md-10">
                <h1 class="my-4">Daftar Pembayaran</h1>

                <!-- Form Tambah/Edit Pembayaran -->
                <form method="POST" action="payment.php">
                    <input type="hidden" name="id" value="<?= isset($pembayaran['id']) ? $pembayaran['id'] : ''; ?>">
                    <div class="mb-3">
                        <label for="nama_pembayaran" class="form-label">Nama Pembayaran</label>
                        <input type="text" name="nama_pembayaran" class="form-control" value="<?= isset($pembayaran['nama_pembayaran']) ? $pembayaran['nama_pembayaran'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control" value="<?= isset($pembayaran['harga']) ? $pembayaran['harga'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_bank" class="form-label">Nomor Bank</label>
                        <input type="number" name="nomor_bank" class="form-control" value="<?= isset($pembayaran['nomor_bank']) ? $pembayaran['nomor_bank'] : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

                <hr>

                <!-- Tabel Pembayaran -->
                <table class="table table-bordered">
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
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['nama_pembayaran']; ?></td>
                                <td><?= $row['harga']; ?></td>
                                <td><?= $row['nomor_bank']; ?></td>
                                <td>
                                    <a href="payment.php?edit=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="payment.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
