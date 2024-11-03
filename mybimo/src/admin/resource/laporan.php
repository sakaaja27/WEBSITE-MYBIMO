<?php
require_once '../koneksi/koneksi.php';

// Fungsi untuk mengambil data pembayaran berdasarkan rentang tanggal
function getPaymentsByDateRange($conn, $start_date, $end_date)
{
    $query = "SELECT 
                DATE(created_at) as tanggal,
                COUNT(*) as jumlah_transaksi,
                SUM(harga) as total_pembayaran
              FROM pembayaran
              WHERE created_at BETWEEN '$start_date' AND '$end_date'
              GROUP BY DATE(created_at)
              ORDER BY DATE(created_at)";

    return $conn->query($query);
}

// Fungsi untuk mengambil detail transaksi per tanggal
function getDailyTransactions($conn, $date)
{
    $query = "SELECT p.id, u.nama, p.nama_pembayaran, p.harga, p.created_at
              FROM pembayaran p
              JOIN users u ON p.user_id = u.id
              WHERE DATE(p.created_at) = '$date'
              ORDER BY p.created_at DESC";

    return $conn->query($query);
}

// Mengambil tanggal dari form atau menggunakan tanggal hari ini
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');

// Mengambil data pembayaran berdasarkan rentang tanggal
$payments = getPaymentsByDateRange($conn, $start_date, $end_date);

// Mengambil tanggal untuk detail transaksi
$selected_date = isset($_GET['tanggal']) ? $_GET['tanggal'] : null;

?>
<!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<div class="container mt-4">
    <h1 class="mb-3">Laporan Pembayaran</h1>

    <!-- Filter Tanggal -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!$selected_date): ?>
        <!-- Ringkasan
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan</h5>
                    <?php
                    $total_periode = 0;
                    $total_transaksi = 0;
                    while ($row = $payments->fetch_assoc()) {
                        $total_periode += $row['total_pembayaran'];
                        $total_transaksi += $row['jumlah_transaksi'];
                    }
                    ?>
                    <p>Total Pembayaran: Rp <?php echo number_format($total_periode, 0, ',', '.'); ?></p>
                    <p>Total Transaksi: <?php echo $total_transaksi; ?></p>
                </div>
            </div>
        </div> -->

        <!-- Tabel Laporan Harian -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Laporan Harian</h5>
                <table id="reportTable" class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Pembayaran</th>
                            <!-- <th>Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $payments->data_seek(0);
                        $no = 1;
                        while ($row = $payments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                <td><?php echo $row['jumlah_transaksi']; ?></td>
                                <td>Rp <?php echo number_format($row['total_pembayaran'], 0, ',', '.'); ?></td>
                                <!-- <td>
                                    <a href="?tanggal=<?php echo $row['tanggal']; ?>" class="btn btn-primary btn-sm">
                                        Detail
                                    </a>
                                </td> -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button onclick="exportToExcel('reportTable', 'Laporan_Harian_<?php echo $start_date; ?>_<?php echo $end_date; ?>')" class="btn btn-success mt-3">Export to Excel</button>
            </div>
        </div>

    <?php else: ?>
        <!-- Detail Transaksi Harian -->
        <h2>Detail Transaksi Tanggal <?php echo date('d-m-Y', strtotime($selected_date)); ?></h2>
        <div class="card">
            <div class="card-body">
                <table id="detailTable" class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Nama Pembayaran</th>
                            <th>Harga</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $transactions = getDailyTransactions($conn, $selected_date);
                        $no = 1;
                        $total = 0;
                        while ($row = $transactions->fetch_assoc()):
                            $total += $row['harga'];
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                                <td><?php echo $row['nama_pembayaran']; ?></td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo date('H:i', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th colspan="2">Rp <?php echo number_format($total, 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-3">
                    <a href="?page=laporan" class="btn btn-secondary">Kembali</a>
                    <button onclick="exportToExcel('detailTable', 'Detail_Transaksi_<?php echo date('d-m-Y', strtotime($selected_date)); ?>')" class="btn btn-success">Export to Excel</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Datepicker Bahasa Indonesia -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'id',
            orientation: 'bottom'
        });

        // Validasi tanggal saat form disubmit
        $('form').on('submit', function(e) {
            var startDate = new Date($('#start_date').val());
            var endDate = new Date($('#end_date').val());

            if (startDate > endDate) {
                e.preventDefault();
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                return false;
            }
        });
    });

    // Fungsi untuk export ke Excel
    function exportToExcel(tableId, fileName) {
        const table = document.getElementById(tableId);
        const wb = XLSX.utils.table_to_book(table, {
            sheet: "Sheet1"
        });
        XLSX.writeFile(wb, `${fileName}.xlsx`);
    }
</script>