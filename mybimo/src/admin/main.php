<?php
require_once '../koneksi/koneksi.php';

if (!isset($conn) || !$conn) {
    die("Koneksi database gagal: " . (isset($conn) ? mysqli_connect_error() : "Variabel koneksi tidak terdefinisi"));
}
?>

<?php
// Mengambil total users
$resultTotalUsers = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$totalUsers = $resultTotalUsers ? $resultTotalUsers->fetch_assoc()['total_users'] : 0;

// Mengambil total user berlangganan
$resultSubscribers = $conn->query("SELECT COUNT(*) AS total_subscribers FROM pembayaran");
$totalSubscribers = $resultSubscribers ? $resultSubscribers->fetch_assoc()['total_subscribers'] : 0;

// Mengambil total materi
$resultTotalCourses = $conn->query("SELECT COUNT(*) AS total_courses FROM materi");
$totalCourses = $resultTotalCourses ? $resultTotalCourses->fetch_assoc()['total_courses'] : 0;

// Mengambil statistik pengguna berdasarkan bulan
$userGrowthQuery = "SELECT MONTHNAME(created_at) AS month, COUNT(id) AS user_count 
                    FROM materi 
                    WHERE created_at IS NOT NULL
                    GROUP BY MONTH(created_at), MONTHNAME(created_at)";
$resultUserGrowth = $conn->query($userGrowthQuery);

$months = [];
$userCounts = [];

while ($row = $resultUserGrowth->fetch_assoc()) {
    $months[] = $row['month'];
    $userCounts[] = $row['user_count'];
}

$months = json_encode($months);
$userCounts = json_encode($userCounts);
?>
<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block">
                <div class="row g-gs">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card h-100 bg-primary">
                            <div class="nk-cmwg nk-cmwg1">
                                <div class="card-inner pt-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-item">
                                            <div class="text-white d-flex flex-wrap">
                                                <span class="fs-2 me-1"><?php echo $totalUsers; ?> Users</span>

                                            </div>
                                            <h6 class="text-white">Users</h6>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                    <div class="nk-cmwg1-ck">
                                        <canvas class="campaign-line-chart-s1 rounded-bottom"
                                            id="runningCampaign"></canvas>
                                    </div>
                                </div>
                            </div><!-- .nk-cmwg -->
                        </div><!-- .card -->
                    </div><!-- .col -->

                    <div class="col-lg-4 col-sm-6">
                        <div class="card h-100 bg-primary">
                            <div class="nk-cmwg nk-cmwg1">
                                <div class="card-inner pt-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-item">
                                            <div class="text-white d-flex flex-wrap">
                                                <span class="fs-2 me-1"><?php echo $totalSubscribers; ?> Subscribers</span>
                                            </div>
                                            <h6 class="text-white">User Berlangganan</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                    <div class="nk-cmwg1-ck">
                                        <canvas class="campaign-line-chart-s1 rounded-bottom"
                                            id="runningCampaign"></canvas>
                                    </div>
                                </div>
                            </div><!-- .nk-cmwg -->
                        </div><!-- .card -->
                    </div><!-- .col -->

                    <div class="col-lg-4 col-sm-6">
                        <div class="card h-100 bg-primary">
                            <div class="nk-cmwg nk-cmwg1">
                                <div class="card-inner pt-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-item">
                                            <div class="text-white d-flex flex-wrap">
                                                <span class="fs-2 me-1"><?php echo $totalCourses; ?> Courses</span>

                                            </div>
                                            <h6 class="text-white">Total Materi</h6>
                                        </div>
                                        <div class="card-tools me-n1">
                                            <div class="dropdown">
                                                <a href="#"
                                                    class="dropdown-toggle btn btn-icon btn-sm btn-trigger on-dark"
                                                    data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="#" class="active"><span>15 Days</span></a>
                                                        </li>
                                                        <li><a href="#"><span>30 Days</span></a></li>
                                                        <li><a href="#"><span>3 Months</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                    <div class="nk-cmwg1-ck">
                                        <canvas class="campaign-line-chart-s1 rounded-bottom"
                                            id="runningCampaign"></canvas>
                                    </div>
                                </div>
                            </div><!-- .nk-cmwg -->
                        </div><!-- .card -->
                    </div><!-- .col -->

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    Statistik Pengguna
                                </div>
                                <div class="card-body">
                                    <canvas id="userChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .col -->
                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Pengguna
var userCtx = document.getElementById('userChart').getContext('2d');
var userChart = new Chart(userCtx, {
    type: 'line',
    data: {
        labels: <?php echo $months; ?>,
        datasets: [{
            label: 'Jumlah Pengguna',
            data: <?php echo $userCounts; ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>