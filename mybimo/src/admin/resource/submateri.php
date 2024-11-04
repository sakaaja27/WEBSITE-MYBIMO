<?php
require_once '../koneksi/koneksi.php';


$upload_dir = "../getData/storagesubmateri/"; //direktori tempat untuk menyimpan filenya

//Menampilkan file pdf dan word
if (isset($_POST['file'])) {
    $file = $_POST['file'];
    $filepath = $upload_dir . $file;
    if (file_exists($filepath)) {
        if (file_exists($filepath)) {
            $file_exstension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
            $content_type = 'application/octet-stream';
            if ($file_exstension == 'pdf') {
                $content_type = "application/pdf";
            } elseif (in_array($file_exstension, ['doc', 'docx'])) {
                $content_type = 'application/msword';
            }
        }

        header('Content-Type: ' . $content_type);
        header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        echo ("File Tidak Ditemukan");
        exit;
    }
}

// Create (Tambah Data)
if (isset($_POST['add_submateri'])) {
    $id_materi = $_POST['id_materi'];
    $nama_sub = $_POST['nama_sub'];

    $upload_file = '';
    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
        $upload_file = basename($_FILES['upload_file']['name']);
        move_uploaded_file($_FILES['upload_file']['tmp_name'], $upload_dir . $upload_file);
    }
    

    $sql = "INSERT INTO sub_materi (id_materi, nama_sub, upload_file, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id_materi, $nama_sub, $upload_file);

    if ($stmt->execute()) {
        echo "<script>alert('Sub Materi berhasil ditambahkan'); window.location.href='admin/index.php?submateri';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan Sub Materi');</script>";
    }
}

$sql = "SELECT * FROM sub_materi";
$result = $conn->query($sql);

// Edit Data Sub Materi
if (isset($_POST['update_submateri'])) {
    $id = $_POST['id'];
    $id_materi = $_POST['id_materi'];
    $nama_sub = $_POST['nama_sub'];

    // Untuk mengupload file
    $upload_file = '';
    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
        $upload_file = basename($_FILES['upload_file']['name']);
        move_uploaded_file($_FILES['upload_file']['tmp_name'], $upload_dir . $upload_file);
    }    

    $sql = "UPDATE sub_materi SET id_materi=?, nama_sub=?, upload_file=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $id_materi, $nama_sub, $upload_file, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Sub Materi berhasil diupdate'); window.location.href='admin/index.php?submateri';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate Sub Materi');</script>";
    }
}

// Menghaapus Data Sub 
if (isset($_POST['delete'])) {
    $id = $_POST['delete'];

    $query = "DELETE FROM sub_materi WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Sub Materi berhasil dihapus'); window.location.href='admin/index.php?submateri</script>";
    } else {
        echo "<script>alert('Gagal menghapus Sub Materi');</script>";
    }
    $stmt->close();
}
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Data Sub Materi</strong>
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
                                <th>ID Materi</th>
                                <th>Nama Sub Materi</th>
                                <th>Upload File</th>
                                <th>Created At</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = 1;
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $id++ ?></td>
                                    <td><?php echo $row['id_materi']; ?></td>
                                    <td><?php echo $row['nama_sub']; ?></td>
                                    <td>
                                        <?php if (!empty($row['upload_file'])): ?>
                                            <?php
                                            $file_name = basename($row['upload_file']);
                                            $file_url = 'http://localhost/WEBSITE-MYBIMO/mybimo/src/getData/storagesubmateri/' . rawurlencode($file_name);
                                            ?>
                                            <a href="<?php echo $file_url; ?>" target="_blank"><?php echo $file_name; ?></a>
                                        <?php else: ?>
                                            No File
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Delete</button>
                                    </form>
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
                                                        <label for="id_materi" class="form-label">Id Materi</label>
                                                        <input type="number" class="form-control" name="id_materi" value="<?php echo $row['id_materi']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama_sub" class="form-label">Nama Sub Materi</label>
                                                        <input type="text" class="form-control" name="nama_sub" value="<?php echo $row['nama_sub']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="upload_file" class="form-label">Upload File</label>
                                                        <input type="file" id="upload_file" name="upload_file">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="update_submateri" class="btn btn-primary">Update Sub Materi</button>
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
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Sub Materi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_materi" class="form-label">ID Materi</label>
                        <input type="text" class="form-control" name="id_materi" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_sub" class="form-label">Nama Sub Materi</label>
                        <input type="text" class="form-control" name="nama_sub" required>
                    </div>
                    <div class="mb-3">
                        <label for="upload_file" class="form-label">Upload File</label>
                        <input type="file" name="upload_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_submateri" class="btn btn-primary">Tambah Sub Materi</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

</html>