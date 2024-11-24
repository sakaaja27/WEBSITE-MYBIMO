<?php
require_once '../koneksi/koneksi.php';

if (!isset($conn) || !$conn) {
    die("Koneksi database gagal: " . (isset($conn) ? mysqli_connect_error() : "Variabel koneksi tidak terdefinisi"));
}

function getCount($conn, $table, $condition = '1=1')
{
    $query = "SELECT COUNT(*) AS total FROM $table WHERE $condition";
    $result = $conn->query($query);
    return $result ? $result->fetch_assoc()['total'] : 0;
}

function getPaymentsByMonth($conn)
{
    $query = "SELECT 
              DATE_FORMAT(created_at, '%Y-%m') AS bulan,
              COUNT(*) AS jumlah_transaksi,
              SUM(harga) AS total_pembayaran
            FROM view_transaksi_lengkap
            WHERE status_transaksi = 1
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')  
            ORDER BY bulan";

    return $conn->query($query);
}

// Mengambil data total users, subscribers, dan materi
$totalUsers = getCount($conn, 'users');
$totalSubscribers = getCount($conn, 'view_transaksi_lengkap', 'status_transaksi = 1'); // Menghitung hanya yang status_transaksi = 1
$totalCourses = getCount($conn, 'materi');

//Statistik pembayaran
$paymentsStatsQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month,
                        SUM(harga) as total_amount
                        FROM pembayaran
                        GROUP BY DATE_FORMAT (created_at, '%Y-%m')
                        ORDER BY month ASC
                        LIMIT 12";
$resultPaymentStats = $conn->query($paymentsStatsQuery);

$paymentMonths = [];
$totalAmounts = [];

while ($row = $resultPaymentStats->fetch_assoc()) {
    $paymentMothns[] = date('M Y', strtotime($row['month'] . '-01'));
    $totalAmounts[] = $row['total_amount'];
}

//mengambil data pembayaran perbulan
$payments = getPaymentsByMonth($conn);

//menyiapkan data untuk grafik
$labels = array();
$data = array_fill(0, 12, 0);

$months = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];

//mengisi dta dari database
while ($row = $payments->fetch_assoc()) {
    $month_index = (int)date('m', strtotime($row['bulan'])) - 1;
    $data[$month_index] = (int)$row['total_pembayaran'];
}

$labels = array_values($months);

$paymentMothnsJson = json_encode($paymentMothns);
$totalAmountsJson = json_encode($totalAmounts);
$labelsJson = json_encode($labels);
$dataJson = json_encode($data);
?>

<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block">
                <div class="row g-gs">
                    <?php
                    $cardData = [
                        ['title' => 'Users', 'count' => $totalUsers, 'label' => 'Users'],
                        ['title' => 'Subscribers', 'count' => $totalSubscribers, 'label' => 'User Berlangganan'],
                        ['title' => 'Courses', 'count' => $totalCourses, 'label' => 'Total Materi']
                    ];
                    foreach ($cardData as $card) {
                        echo <<<HTML
                        <div class="col-lg-4 col-sm-6">
                            <div class="card h-1oo bg-primary">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1">{$card['count']} {$card['title']}</span>
                                                </div>
                                                <h6 class="text-white">{$card['label']}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                        <div class="nk-cmwg1-ck">
                                            <canvas class="campaign-line-chart-s1 rounded-bottom" id="runningCampaign"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        HTML;
                    }
                    ?>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Laporan Pembayaran Bulanan</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyPaymentChart" style="width: 500px; height: 500px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    //fungsi untuk membuar chart
    function createChart(ctx, type, labels, data, label, color, isCurrency = false) {
        return new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    backgroundColor: color.replace(')', ', 0.2)'),
                    tension: 0.1,
                    fill: type === 'line'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return isCurrency ? 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (isCurrency) {
                                    label += 'Rp ' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // //Chart total pembayaran
    // var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    // createChart(revenueCtx, 'line', <?php echo $paymentMothnsJson; ?>, <?php echo $totalAmountsJson; ?>, 'Total Pembayaran (Rp)', 'rgb(255, 99, 132)', true);

    // chart laporan pembayaran Bulanan
    var monthlyPaymentCtx = document.getElementById('monthlyPaymentChart').getContext('2d');
    createChart(monthlyPaymentCtx, 'bar', <?php echo $labelsJson; ?>, <?php echo $dataJson; ?>, 'Pembayaran Bulanan', 'rgb(54, 162, 235)', true);
</script>