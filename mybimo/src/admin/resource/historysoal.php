<?php
require_once '../koneksi/koneksi.php';

//query untuk mengambil data dari view_history_soal
$query = "SELECT username, judul_materi, jumlah_benar, jumlah_salah, tanggal FROM view_history_soal";
$result = $conn->query($query);

?>
<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-blok-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>History Soal</strong>
                        </div><!-- .nk-block-head-content -->
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="row g-gs">
                    <table class="datatable-init table table-bordered table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama Materi</th>
                                <th>Jumlah Benar</th>
                                <th>Jumlah Salah</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $id = 1;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $id++ . "</td>"; // Tampilkan ID
                                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['judul_materi']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['jumlah_benar']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['jumlah_salah']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>