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
        $sql = "INSERT INTO pembayaran (nama_pembayaran, harga, nomor_bank, created_at) 
                VALUES ('$nama_pembayaran', '$harga', '$nomor_bank', NOW())";
    }

    // Eksekusi query dan penanganan kesalahan
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil disimpan');</script>";
        echo "<script>window.location.href = 'payment.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fungsi untuk menghapus pembayaran
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM pembayaran WHERE id=$id");
    header("Location: payment.php"); // Redirect setelah menghapus
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
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
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

            <div class="col-md-10">
                <h1 class="my-4">Daftar Pembayaran</h1>

                <!-- Tombol Tambah Pembayaran -->
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    Tambah Pembayaran
                </button>

                <!-- Modal untuk Tambah/Edit Pembayaran -->
                <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Tambah/Edit Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="../resource/payment.php" id="paymentForm">
                                    <input type="hidden" name="id" id="id">
                                    <div class="mb-3">
                                        <label for="nama_pembayaran" class="form-label">Nama Pembayaran</label>
                                        <input type="text" name="nama_pembayaran" class="form-control" id="nama_pembayaran" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <input type="number" name="harga" class="form-control" id="harga" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nomor_bank" class="form-label">Nomor Bank</label>
                                        <input type="number" name="nomor_bank" class="form-control" id="nomor_bank" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $row['id']; ?>" data-nama="<?= $row['nama_pembayaran']; ?>" data-harga="<?= $row['harga']; ?>" data-nomor="<?= $row['nomor_bank']; ?>">Edit</button>
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
    <script>
        // Script untuk menangani modal edit
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const harga = this.getAttribute('data-harga');
                const nomor = this.getAttribute('data-nomor');

                document.getElementById('id').value = id;
                document.getElementById('nama_pembayaran').value = nama;
                document.getElementById('harga').value = harga;
                document.getElementById('nomor_bank').value = nomor;

                const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
                modal.show();
            });
        });
    </script>
</body>
</html>
