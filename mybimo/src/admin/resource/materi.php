<?php
require_once '../koneksi/koneksi.php';

$validImageExtension = ['jpg', 'jpeg', 'png'];

$target_dir = "../getData/storageImage/";  //direktori untuk menyimpan gambar
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Fungsi untuk menambahkan materi
if (isset($_POST['add_materi'])) {
    $judul_materi = $_POST['judul_materi'];
    $status_materi = 1; // Sesuaikan dengan default yang diinginkan

    if ($_FILES["foto_icon"]["error"] != 4) {
        $fileName = $_FILES["foto_icon"]["name"];
        $fileSize = $_FILES["foto_icon"]["size"];
        $tmpName = $_FILES["foto_icon"]["tmp_name"];

        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script>alert('Invalid Image Extension');</script>";
        } 
        elseif ($fileSize > 1000000) {
            echo "<script>alert('Image Size Is Too Large');</script>";
        } 
        else {
            $newImageName = uniqid() . '.' . $imageExtension;
            
            if (move_uploaded_file($tmpName, $target_dir . $newImageName)) {
                $sql = "INSERT INTO materi (judul_materi, foto_icon, created_at, status_materi) 
                        VALUES (?, ?, NOW(), ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $judul_materi, $newImageName, $status_materi);

                if ($stmt->execute()) {
                    echo "<script>alert('Materi berhasil ditambahkan!'); window.location.href='admin/index.php?materi';</script>";
                } 
                else {  
                    echo "<script>alert('Gagal menambahkan materi');</script>";
                }
                $stmt->close();
            }
        }
    }
}

// Fungsi untuk mengedit materi
if (isset($_POST['update_materi'])) {
    $id = $_POST['id'];
    $judul_materi = $_POST['judul_materi'];
    $status_materi = $_POST['status_materi'];
    $newImageName = "";

    if ($_FILES["foto_icon"]["error"] != 4) {
        $fileName = $_FILES["foto_icon"]["name"];
        $fileSize = $_FILES["foto_icon"]["size"];
        $tmpName = $_FILES["foto_icon"]["tmp_name"];

        $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($imageExtension, $validImageExtension) && $fileSize <= 1000000) {
            $newImageName = uniqid() . '.' . $imageExtension;
            move_uploaded_file($tmpName, $target_dir . $newImageName);
        }
    }

    if ($newImageName) {
        $query = "UPDATE materi SET judul_materi=?, foto_icon=?, status_materi=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssii", $judul_materi, $newImageName, $status_materi, $id);
    } 
    else {
        $query = "UPDATE materi SET judul_materi=?, status_materi=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $judul_materi, $status_materi, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Materi berhasil diperbarui'); window.location.href='admin/index.php?materi';</script>";
    } 
    else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fungsi untuk menghapus materi
if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $query = "DELETE FROM materi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
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
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu">
                                <em class="icon ni ni-more-v"></em>
                            </a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
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
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="nk-block">
                <div class="row g-gs">
                    <table class="datatable-init table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul Materi</th>
                                <th>Foto Icon</th>
                                <th>Status Materi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $id = 1;
                            while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $id++ ?></td>
                                <td><?php echo $row['judul_materi']; ?></td>
                                <td>
                                <img src="http://localhost/WEBSITE-MYBIMO/mybimo/src/getData/storageImage/<?php echo htmlspecialchars($row['foto_icon']); ?>" alt="Icon" width="50" height="50">
                                </td>
                                <td><?php echo $row['status_materi']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Materi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Judul Materi</label>
                                                    <input type="text" class="form-control" name="judul_materi" 
                                                           value="<?php echo $row['judul_materi']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Foto Icon</label>
                                                    <input type="file" class="form-control" name="foto_icon" 
                                                           accept=".jpg,.jpeg,.png">
                                                    <small>Biarkan kosong jika tidak ingin mengganti foto.</small>
                                                    <div class="mt-2">
                                                    <img src="http://localhost/WEBSITE-MYBIMO/mybimo/src/getData/storageImage/<?php echo htmlspecialchars($row['foto_icon']); ?>" alt="Icon" width="50" height="50">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status Materi</label>
                                                    <input type="number" class="form-control" name="status_materi" 
                                                           value="<?php echo $row['status_materi']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" 
                                                        data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update_materi" 
                                                        class="btn btn-primary">Update</button>
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

<!-- Modal Add -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Materi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Materi</label>
                        <input type="text" class="form-control" name="judul_materi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Icon</label>
                        <input type="file" class="form-control" name="foto_icon" 
                               accept=".jpg,.jpeg,.png" required>
                        <small class="text-muted">Format: JPG, JPEG, PNG (Max 1MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_materi" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
