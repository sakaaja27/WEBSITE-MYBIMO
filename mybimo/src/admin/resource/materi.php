<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan materi
if (isset($_POST['add_materi'])) {
    $judul_materi = $_POST['judul_materi'];

    // Mengambil data base64 dari hidden input yang sudah diisi oleh JavaScript
    $foto_icon = isset($_POST['foto_icon']) ? $_POST['foto_icon'] : null;

    if ($foto_icon) {
        // Menghapus prefix data base64
        $foto_icon = preg_replace('#^data:image/\w+;base64,#i', '', $foto_icon);

        // Insert ke database
        $query = "INSERT INTO materi (judul_materi, foto_icon, created_at, status_materi) VALUES (?, ?, NOW(), '1')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $judul_materi, $foto_icon);

        if ($stmt->execute()) {
            echo "Data berhasil ditambahkan";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Gambar tidak valid atau tidak ada.";
    }
}


// Fungsi untuk mengedit materi
if (isset($_POST['update_materi'])) {
    $id = $_POST['id'];
    $judul_materi = $_POST['judul_materi'];
    $status_materi = $_POST['status_materi'];

    // Mengambil gambar sebagai base64 jika ada
    $foto_icon = isset($_POST['foto_icon']) ? $_POST['foto_icon'] : null;

    if ($foto_icon) { 
        $foto_icon = preg_replace('#^data:image/\w+;base64,#i', '', $foto_icon); 
        $foto_icon = base64_decode($foto_icon); 
        
        // Gunakan prepared statement dengan parameter blob
        $query = "UPDATE materi SET judul_materi=?, foto_icon=?, status_materi=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $null = NULL;
        $stmt->bind_param("sbsi", $judul_materi, $null, $status_materi, $id);
        $stmt->send_long_data(1, $foto_icon);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        // Update tanpa mengubah foto_icon
        $query = "UPDATE materi SET judul_materi=?, status_materi=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $judul_materi, $status_materi, $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
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

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Data Materi</strong>
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
                                <th>Judul Materi</th>
                                <th>Foto Icon</th>
                                <th>Status Materi</th>
                                <!-- <th>Created At</th> -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['judul_materi']; ?></td>
                                    <td><img src="data:image/png;base64,<?php echo base64_encode($row['foto_icon']); ?>" alt="Icon" width="50" height="50"></td>
                                    <td><?php echo $row['status_materi']; ?></td>
                                    <!--  -->
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
                                                        <input type="file" class="form-control" accept="image/*" onchange="previewImage(this)">
                                                        <input type="hidden" id="foto_icon" name="foto_icon">
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
                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="judul_materi" class="form-label">Judul Materi</label>
                        <input type="text" class="form-control" name="judul_materi" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto_icon" class="form-label">Foto Icon</label>
                        <input type="file" class="form-control" accept="image/*" onchange="previewImage(this)" required>
                        <input type="hidden" id="foto_icon" name="foto_icon">
                        <img id="imagePreview" src="#" alt="Image Preview" style="display:none; width: 50px; height: 50px;" />
                    </div>
                    <!-- <div class="mb-3">
                        <label for="status_materi" class="form-label">Status Materi</label>
                        <input type="number" class="form-control" name="status_materi" required>
                    </div> -->
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
<script>
    function previewImage(input) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onloadend = function() {
            document.getElementById('imagePreview').src = reader.result;
            document.getElementById('foto_icon').value = reader.result; // Menyimpan data base64 ke input tersembunyi
        };
        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
