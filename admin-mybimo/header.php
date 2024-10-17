<?php
require_once 'koneksi/koneksi.php'; 


$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Jika user_id ada, ambil data pengguna dari database
if ($id) {
    $query = "SELECT username, email, role FROM users WHERE id = '$id'";
    $result = $conn->query($query);

    // Jika pengguna ditemukan
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        // Jika pengguna tidak ditemukan (fallback)
        $userData = [
            'username' => 'Guest',
            'email' => 'guest@example.com',
            'role' => '0' // Default sebagai User
        ];
    }
} else {
    // Jika sesi tidak ada (misal pengguna belum login)
    $userData = [
        'username' => 'Guest',
        'email' => 'guest@example.com',
        'role' => '0' // Default sebagai User
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Bagian Header -->
    <header>
        <div class="container-fluid p-3 bg-light">
            <div class="row align-items-center">
                <!-- Search bar -->
                <div class="col-md-8">
                    <form class="d-flex">
                        <span class="me-2" style="font-size: 1.5rem;"><i class="bi bi-search"></i></span>
                        <input class="form-control me-2" type="search" placeholder="Search anything" aria-label="Search">
                    </form>
                </div>

                <!-- Profile section -->
                <div class="col-md-4 d-flex justify-content-end align-items-center">
                    <div class="text-end">
                        <span class="badge rounded-pill bg-primary p-2">
                            <i class="bi bi-person"></i>
                        </span>
                    </div>
                    <div class="ms-3 text-end">
                        <small class="d-block text-muted">
                            <?php 
                                // Tampilkan role sesuai dengan angka
                                if ($userData['role'] == 2) {
                                    echo 'Super Admin';
                                } elseif ($userData['role'] == 1) {
                                    echo 'Admin';
                                } else {
                                    echo 'User';
                                }
                            ?>
                        </small>
                        <strong><?php echo htmlspecialchars($userData['username']); ?></strong> <i class="bi bi-caret-down-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Utama -->
    <div class="container mt-4">
        <h2>Welcome, <?php echo htmlspecialchars($userData['username']); ?></h2>
    </div>

    <!-- Menambahkan Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
