<?php
require_once '../koneksi/koneksi.php';

//proses tambah data
if (isset($_POST['add_zoom'])) {
    $judul_zoom = $_POST['judul_zoom'];
    $link_zoom = $_POST['link_zoom'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];

    $query = "INSERT INTO zoom (nama_judul, link_zoom, tanggal, waktu) 
              VALUES ('$judul_zoom', '$link_zoom', '$tanggal', '$waktu')";

    if ($conn->query($query)) {
        echo "<script>alert('Data berhasil ditambahkan'); window.location.href='admin/index.php?zoom';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data');</script>";
    }
}

//proses update data
if (isset($_POST['update_zoom'])) {
    $id = $_POST['id'];
    $judul_zoom = $_POST['judul_zoom'];
    $link_zoom = $_POST['link_zoom'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];

    $query = "UPDATE zoom SET 
              nama_judul = '$judul_zoom', 
              link_zoom = '$link_zoom', 
              tanggal = '$tanggal', 
              waktu = '$waktu' 
              WHERE id = '$id'";

    if ($conn->query($query)) {
        echo "<script>alert('Data berhasil diupdate'); window.location.href='admin/index.php?zoom';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data');</script>";
    }
}

//proses hapus data
if (isset($_POST['delete'])) {
    $id = $_POST['delete'];
    $query = "DELETE FROM zoom WHERE id = '$id'";

    if ($conn->query($query)) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='admin/index.php?zoom';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}

//mengambil data
$query = "SELECT * FROM zoom ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Data Zoom</strong>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><em class="icon ni ni-plus"></em> Add Data</button>
                                    </li>
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
                                <th>Nama Judul</th>
                                <th>Link Zoom</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = 1;
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $id++ ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_judul']); ?></td>
                                    <td><?php echo htmlspecialchars($row['link_zoom']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                    <td><?php echo htmlspecialchars($row['waktu']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus zoom ini?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Zoom</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Judul Zoom</label>
                                                        <input type="text" class="form-control" name="judul_zoom"
                                                            value="<?php echo htmlspecialchars($row['nama_judul']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Link Zoom</label>
                                                        <input type="text" class="form-control" name="link_zoom"
                                                            value="<?php echo $row['link_zoom']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="form-label">Tanggal</label>
                                                        <input type="date" class="form-control" name="tanggal" value="<?php echo $row['tanggal']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="form-label">Waktu</label>
                                                        <input type="time" class="form-control" name="waktu" id="waktu"<?php echo $row['waktu']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="update_zoom"
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
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Zoom</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Zoom</label>
                        <input type="text" class="form-control" name="judul_zoom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Zoom</label>
                        <input type="text" class="form-control" name="link_zoom" required>
                    </div>
                    <div class="mb-3">
                        <label for="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?php echo $row['tanggal']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="form-label">Waktu</label>
                        <input type="time" class="form-control" name="waktu" id="waktu" value="<?php echo $row['waktu']; ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_zoom" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>

</html>