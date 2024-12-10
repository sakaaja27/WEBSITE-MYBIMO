<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk menambahkan soal
if (isset($_POST['add_data'])) {
    $id_materi = $_POST['id_materi'];
    $nama_soal = $conn->real_escape_string($_POST['nama_soal']);
    $pilihan_a = $conn->real_escape_string($_POST['pilihan_a']);
    $pilihan_b = $conn->real_escape_string($_POST['pilihan_b']);
    $pilihan_c = $conn->real_escape_string($_POST['pilihan_c']);
    $jawaban_benar = $conn->real_escape_string($_POST['jawaban_benar']);

    if ($id_materi && $nama_soal && $pilihan_a && $pilihan_b && $pilihan_c && $jawaban_benar) {
        $query = "INSERT INTO soal_submateri (id_materi, nama_soal, pilihan_a, pilihan_b, pilihan_c, jawaban_benar, created_at) 
                  VALUES ('$id_materi', '$nama_soal', '$pilihan_a', '$pilihan_b', '$pilihan_c', '$jawaban_benar', NOW())";
        $conn->query($query);
    } else {
        echo "<script>alert('Mohon lengkapi semua data!');</script>";
    }
}

// Fungsi untuk mengedit soal
if (isset($_POST['update_soal'])) {
    $id = $_POST['id'];
    $id_materi = $_POST['id_materi'];
    $nama_soal = $conn->real_escape_string($_POST['nama_soal']);
    $pilihan_a = $conn->real_escape_string($_POST['pilihan_a']);
    $pilihan_b = $conn->real_escape_string($_POST['pilihan_b']);
    $pilihan_c = $conn->real_escape_string($_POST['pilihan_c']);
    $jawaban_benar = $conn->real_escape_string($_POST['jawaban_benar']);

    $query = "UPDATE soal_submateri SET 
              id_materi='$id_materi', 
              nama_soal='$nama_soal', 
              pilihan_a='$pilihan_a', 
              pilihan_b='$pilihan_b', 
              pilihan_c='$pilihan_c', 
              jawaban_benar='$jawaban_benar' 
              WHERE id='$id'";
    $conn->query($query);
}

// Proses hapus pengguna
if (isset($_POST['delete_soal_id'])) {
    $id = $_POST['delete_soal_id'];

    $sql = "DELETE FROM soal_submateri WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Soal berhasil dihapus!');</script>";
    } else {
        echo "<script>alert('Soal Gagal dihapus!');</script>";
    }
}
// mengambil materi untuk modal add data
$materi_result = $conn->query("SELECT * FROM materi");
$materi_options = '';
while ($materi_row = $materi_result->fetch_assoc()) {
    $materi_options .= '<option value="' . $materi_row['id'] . '">' . htmlspecialchars($materi_row['judul_materi']) . '</option>';
}

// Mengambil data soal dan sub_materi
$result = $conn->query("SELECT ss.*, m.judul_materi 
                        FROM soal_submateri ss 
                        JOIN materi m ON ss.id_materi = m.id");

$sub_materi = $conn->query("SELECT m.*, m.judul_materi 
                            FROM materi m");
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
                                <th>Nama Materi</th>
                                <th>Nama Soal</th>
                                <th>Pilihan A</th>
                                <th>Pilihan B</th>
                                <th>Pilihan C</th>
                                <th>Jawaban Benar</th>
                                <th>Created At</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = 1;
                            while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $id++; ?></td>
                                    <td><?php echo htmlspecialchars($row['judul_materi']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_soal']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pilihan_a']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pilihan_b']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pilihan_c']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jawaban_benar']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                        <!-- Tombol Hapus -->
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_soal_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">Delete</button>
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
                                                        <select name="id_materi" class="form-select" required>
                                                            <option value="">Pilih Materi</option>
                                                            <?php
                                                            $sub_materi->data_seek(0);
                                                            while ($sub_row = $sub_materi->fetch_assoc()): ?>
                                                                <option value="<?php echo $sub_row['id']; ?>"
                                                                    <?php echo $sub_row['id'] == $row['id'] ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($sub_row['judul_materi']); ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama_soal" class="form-label">Nama Soal</label>
                                                        <textarea class="form-control" name="nama_soal" required><?php echo htmlspecialchars($row['nama_soal']); ?></textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="pilihan_a" class="form-label">Pilihan A</label>
                                                            <textarea class="form-control" name="pilihan_a" required><?php echo htmlspecialchars($row['pilihan_a']); ?></textarea>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="pilihan_b" class="form-label">Pilihan B</label>
                                                            <textarea class="form-control" name="pilihan_b" required><?php echo htmlspecialchars($row['pilihan_b']); ?></textarea>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label for="pilihan_c" class="form-label">Pilihan C</label>
                                                            <textarea class="form-control" name="pilihan_c" required><?php echo htmlspecialchars($row['pilihan_c']); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jawaban_benar" class="form-label">Jawaban Benar</label>
                                                        <select name="jawaban_benar" class="form-select" required>
                                                            <option value="A" <?php echo $row['jawaban_benar'] == 'A' ? 'selected' : ''; ?>>a</option>
                                                            <option value="B" <?php echo $row['jawaban_benar'] == 'B' ? 'selected' : ''; ?>>b</option>
                                                            <option value="C" <?php echo $row['jawaban_benar'] == 'C' ? 'selected' : ''; ?>>c</option>
                                                        </select>
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

<!-- Modal Tambah Soal -->
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
                        <label for="id_materi" class="form-label">Materi</label>
                        <select name="id_materi" class="form-select" required>
                            <option value="">Pilih Materi</option>
                            <?php echo $materi_options; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_soal" class="form-label">Nama Soal</label>
                        <textarea class="form-control" name="nama_soal" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pilihan_a" class="form-label">Pilihan A</label>
                            <textarea class="form-control" name="pilihan_a" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pilihan_b" class="form-label">Pilihan B</label>
                            <textarea class="form-control" name="pilihan_b" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pilihan_c" class="form-label">Pilihan C</label>
                            <textarea class="form-control" name="pilihan_c" required></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jawaban_benar" class="form-label">Jawaban Benar</label>
                        <select name="jawaban_benar" class="form-select" required>
                            <option value="">Pilih Jawaban Benar</option>
                            <option value="A">a</option>
                            <option value="B">b</option>
                            <option value="C">c</option>
                        </select>
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