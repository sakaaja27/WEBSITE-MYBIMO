<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan soal
if (isset($_POST['add_soal'])) {
    $id_submateri = $_POST['id_submateri'];
    $nama_soal = $_POST['nama_soal'];
    $jawaban_soal = $_POST['jawaban_soal'];

    $query = "INSERT INTO soal_submateri (id_submateri, nama_soal, jawaban_soal, created_at) VALUES ('$id_submateri', '$nama_soal', '$jawaban_soal', NOW())";
    $conn->query($query);
}

// Fungsi untuk mengedit soal
if (isset($_POST['update_soal'])) {
    $id = $_POST['id'];
    $id_submateri = $_POST['id_submateri'];
    $nama_soal = $_POST['nama_soal'];
    $jawaban_soal = $_POST['jawaban_soal'];

    $query = "UPDATE soal_submateri SET id_submateri='$id_submateri', nama_soal='$nama_soal', jawaban_soal='$jawaban_soal' WHERE id='$id'";
    $conn->query($query);
}

// Fungsi untuk menghapus soal
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM soal_submateri WHERE id='$id'";
    $conn->query($query);
}

// Mengambil data soal dan sub_materi
$result = $conn->query("SELECT ss.*, sm.nama_sub FROM soal_submateri ss JOIN sub_materi sm ON ss.id_submateri = sm.id");
$sub_materi = $conn->query("SELECT * FROM sub_materi");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal Sub Materi Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <h2>Soal Sub Materi Management</h2>

    <!-- Form Tambah Soal -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="id_submateri" class="form-label">Sub Materi</label>
            <select name="id_submateri" class="form-select" required>
                <option value="">Pilih Sub Materi</option>
                
                <?php while ($row = $sub_materi->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nama_sub']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="nama_soal" class="form-label">Nama Soal</label>
            <textarea class="form-control" name="nama_soal" required></textarea>
        </div>
        <div class="mb-3">
            <label for="jawaban_soal" class="form-label">Jawaban Soal</label>
            <textarea class="form-control" name="jawaban_soal" required></textarea>
        </div>
        <button type="submit" name="add_soal" class="btn btn-primary">Add Soal</button>
    </form>

    <!-- Tabel Daftar Soal -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sub Materi</th>
                <th>Nama Soal</th>
                <th>Jawaban Soal</th>
                <th>Created At</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nama_sub']; ?></td>
                <td><?php echo $row['nama_soal']; ?></td>
                <td><?php echo $row['jawaban_soal']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                    <!-- Tombol Hapus -->
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">Delete</a>
                </td>
            </tr>

            <!-- Modal Edit Soal -->
            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Soal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <div class="mb-3">
                                    <label for="id_submateri" class="form-label">Sub Materi</label>
                                    <select name="id_submateri" class="form-select" required>
                                        <option value="">Pilih Sub Materi</option>
                                        <?php while ($sub_row = $sub_materi->fetch_assoc()): ?>
                                            <option value="<?php echo $sub_row['id']; ?>" <?php echo $sub_row['id'] == $row['id_submateri'] ? 'selected' : ''; ?>>
                                                <?php echo $sub_row['nama_sub']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_soal" class="form-label">Nama Soal</label>
                                    <textarea class="form-control" name="nama_soal" required><?php echo $row['nama_soal']; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="jawaban_soal" class="form-label">Jawaban Soal</label>
                                    <textarea class="form-control" name="jawaban_soal" required><?php echo $row['jawaban_soal']; ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="update_soal" class="btn btn-primary">Update Soal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
