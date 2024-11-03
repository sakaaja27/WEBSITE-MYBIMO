<?php
require_once '../koneksi/koneksi.php';

if (!isset($conn) || !$conn) {
    die("Koneksi database gagal: " . (isset($conn) ? mysqli_connect_error() : "Variabel koneksi tidak terdefinisi"));
}

// Fungsi untuk mengambil jumlah dari tabel
function getCount($conn, $table, $column = '*') {
    $query = "SELECT COUNT($column) AS total FROM $table";
    $result = $conn->query($query);
    return $result ? $result->fetch_assoc()['total'] : 0;
}

// Mengambil total users, subscribers, dan courses
$totalUsers = getCount($conn, 'users');
$totalSubscribers = getCount($conn, 'pembayaran');
$totalCourses = getCount($conn, 'materi');

// Mengambil statistik pengguna berdasarkan bulan
$userGrowthQuery = "SELECT MONTHNAME(created_at) AS month, COUNT(id) AS user_count 
                    FROM materi 
                    WHERE created_at IS NOT NULL
                    GROUP BY MONTH(created_at), MONTHNAME(created_at)
                    ORDER BY MONTH(created_at)";
$resultUserGrowth = $conn->query($userGrowthQuery);

$months = [];
$userCounts = [];

while ($row = $resultUserGrowth->fetch_assoc()) {
    $months[] = $row['month'];
    $userCounts[] = $row['user_count'];
}

// Statistik pembayaran
$paymentStatsQuery = "SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    SUM(harga) as total_amount
    FROM pembayaran 
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC
    LIMIT 12";
$resultPaymentStats = $conn->query($paymentStatsQuery);

$paymentMonths = [];
$totalAmounts = [];

while ($row = $resultPaymentStats->fetch_assoc()) {
    $paymentMonths[] = date('M Y', strtotime($row['month'] . '-01'));
    $totalAmounts[] = $row['total_amount'];
}

// Mengonversi data ke JSON untuk digunakan di JavaScript
$monthsJson = json_encode($months);
$userCountsJson = json_encode($userCounts);
$paymentMonthsJson = json_encode($paymentMonths);
$totalAmountsJson = json_encode($totalAmounts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="path/to/your/css/file.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
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
                                <div class="card h-100 bg-primary">
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
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistik Pengguna</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="userChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Total Pembayaran per Bulan</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Fungsi untuk membuat chart
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

    // Chart Pengguna
    var userCtx = document.getElementById('userChart').getContext('2d');
    createChart(userCtx, 'line', <?php echo $monthsJson; ?>, <?php echo $userCountsJson; ?>, 'Jumlah Pengguna', 'rgb(75, 192, 192)');

    // Chart Total Pembayaran
    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    createChart(revenueCtx, 'line', <?php echo $paymentMonthsJson; ?>, <?php echo $totalAmountsJson; ?>, 'Total Pembayaran (Rp)', 'rgb(255, 99, 132)', true);
    </script>
</body>
</html>