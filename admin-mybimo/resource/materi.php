<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan materi
if (isset($_POST['add_materi'])) {
    $judul_materi = $_POST['judul_materi'];
    
    // Menangani upload file gambar
    $foto_icon = $_FILES['foto_icon']['name'];
    $target_dir = "../uploads/"; // Pastikan direktori ini ada dan bisa ditulisi
    $target_file = $target_dir . basename($foto_icon);
    
    // Pindahkan file ke direktori target
    if (move_uploaded_file($_FILES['foto_icon']['tmp_name'], $target_file)) {
        $query = "INSERT INTO materi (judul_materi, foto_icon, created_at, status_materi) VALUES ('$judul_materi', '$target_file', NOW(), '1')";
        $conn->query($query);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Fungsi untuk mengedit materi
if (isset($_POST['update_materi'])) {
    $id = $_POST['id'];
    $judul_materi = $_POST['judul_materi'];
    
    // Menangani upload file gambar
    $foto_icon = $_FILES['foto_icon']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($foto_icon);

    if ($foto_icon) { // Jika ada foto icon yang diunggah
        // Pindahkan file ke direktori target
        if (move_uploaded_file($_FILES['foto_icon']['tmp_name'], $target_file)) {
            $query = "UPDATE materi SET judul_materi='$judul_materi', foto_icon='$target_file', status_materi='1' WHERE id='$id'";
            $conn->query($query);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else { // Jika tidak ada foto icon baru, hanya update judul
        $query = "UPDATE materi SET judul_materi='$judul_materi', status_materi='1' WHERE id='$id'";
        $conn->query($query);
    }
}

// Fungsi untuk menghapus materi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM materi WHERE id='$id'";
    $conn->query($query);
}

// Mengambil data materi
$result = $conn->query("SELECT * FROM materi");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi Management</title>
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
            <h2>Materi Management</h2>

            <!-- Form Tambah Materi -->
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3">
                    <label for="judul_materi" class="form-label">Judul Materi</label>
                    <input type="text" class="form-control" name="judul_materi" required>
                </div>
                <div class="mb-3">
                    <label for="foto_icon" class="form-label">Foto Icon</label>
                    <input type="file" class="form-control" name="foto_icon" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="status_materi" class="form-label">Status Materi</label>
                    <input type="number" class="form-control" name="status_materi" required>
                </div>
                <button type="submit" name="add_materi" class="btn btn-primary">Add Materi</button>
            </form>

            <!-- Tabel Daftar Materi -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Materi</th>
                        <th>Foto Icon</th>
                        <th>Created At</th>
                        <th>Status Materi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['judul_materi']; ?></td>
                        <td><img src="<?php echo $row['foto_icon']; ?>" alt="Icon" width="50" height="50"></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['status_materi']; ?></td>
                        <td>
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                            <!-- Tombol Hapus -->
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">Delete</a>
                        </td>
                    </tr>

                    <!-- Modal Edit Materi -->
                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Materi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3">
                                            <label for="judul_materi" class="form-label">Judul Materi</label>
                                            <input type="text" class="form-control" name="judul_materi" value="<?php echo $row['judul_materi']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto_icon" class="form-label">Foto Icon</label>
                                            <input type="file" class="form-control" name="foto_icon" accept="image/*">
                                            <small>Biarkan kosong jika tidak ingin mengganti foto.</small>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status_materi" class="form-label">Status Materi</label>
                                            <input type="number" class="form-control" name="status_materi" value="<?php echo $row['status_materi']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="update_materi" class="btn btn-primary">Update Materi</button>
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

<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
