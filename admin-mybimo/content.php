<?php 
require_once 'koneksi/koneksi.php'; 

if (!isset($conn) || !$conn) {
    die("Koneksi database gagal: " . (isset($conn) ? mysqli_connect_error() : "Variabel koneksi tidak terdefinisi"));
}
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Admin Dashboard</h2>

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

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <!-- Card Total User -->
        <div class="col">
            <div class="card text-white bg-primary h-100">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalUsers; ?> Users</h5>
                    <p class="card-text">Jumlah total user yang terdaftar di platform ini.</p>
                </div>
            </div>
        </div>

        <!-- Card User Berlangganan -->
        <div class="col">
            <div class="card text-white bg-success h-100">
                <div class="card-header">User Berlangganan</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalSubscribers; ?> Subscribers</h5>
                    <p class="card-text">User yang sudah berlangganan paket premium.</p>
                </div>
            </div>
        </div>

        <!-- Card Total Materi -->
        <div class="col">
            <div class="card text-white bg-info h-100">
                <div class="card-header">Total Materi</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $totalCourses; ?> Courses</h5>
                    <p class="card-text">Total materi yang tersedia di platform ini.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
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
