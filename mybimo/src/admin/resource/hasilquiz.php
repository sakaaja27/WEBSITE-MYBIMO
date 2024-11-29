<?php
require_once '../koneksi/koneksi.php';

//query untuk mengambil data dari view_hasil_quiz
$query = "SELECT username, jumlah_benar, jumlah_salah, tanggal FROM view_hasil_quiz";
$result = $conn->query($query);

?>
<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-blok-head-content">
                        <div class="nk-block-des text-soft">
                            <strong>Hasil Quiz</strong>
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
                                    // Pastikan data tidak null
                                    if (
                                        (isset($row['username']) && $row['username'] !== null) 
                                        // || 
                                        // (isset($row['jumlah_benar']) && $row['jumlah_benar'] !== null) || 
                                        // (isset($row['jumlah_salah']) && $row['jumlah_salah'] !== null) || 
                                        // (isset($row['tanggal']) && $row['tanggal'] !== null)
                                    ) {
                                        echo "<tr>";
                                        echo "<td>" . $id++ . "</td>"; 
                                        echo "<td>" . (!empty($row['username']) ? htmlspecialchars($row['username']) : '-') . "</td>";
                                        echo "<td>" . (!empty($row['jumlah_benar']) ? htmlspecialchars($row['jumlah_benar']) : '-') . "</td>";
                                        echo "<td>" . (!empty($row['jumlah_salah']) ? htmlspecialchars($row['jumlah_salah']) : '-') . "</td>";
                                        echo "<td>" . (!empty($row['tanggal']) ? htmlspecialchars($row['tanggal']) : '-') . "</td>";
                                        echo "</tr>";
                                    }
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
