<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan soal
if (isset($_POST['add_data'])) {
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

// Proses hapus pengguna
if (isset($_POST['delete_soal_id'])) {
    $id = $_POST['delete_soal_id'];

    $sql = "DELETE FROM soal_submateri WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Soal berhasil dihapus!');</script>"; // Pastikan untuk keluar setelah redirect
    } else {
        echo "<script>alert('Soal Gagal dihapus!');</script>"; // Pastikan untuk keluar setelah redirect
    }
}

// Mengambil data soal dan sub_materi
$result = $conn->query("SELECT ss.*, sm.nama_sub FROM soal_submateri ss JOIN sub_materi sm ON ss.id_submateri = sm.id");
$sub_materi = $conn->query("SELECT * FROM sub_materi");
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Soal Materi</strong>
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
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_soal_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Delete</button>
                                        </form>
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
                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_data" class="btn btn-primary">Tambah </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Menambahkan Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>