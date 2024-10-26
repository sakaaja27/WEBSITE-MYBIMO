    <?php
    include "../koneksi/koneksi.php";

    // Pastikan id user yang login dikirim melalui GET
    if (isset($_GET['id'])) {
        // Menggunakan prepared statements untuk mencegah SQL Injection
        $id = $_GET['id'];
        
        // Menyiapkan pernyataan SQL
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id); // "i" menunjukkan bahwa parameter adalah integer

        // Eksekusi pernyataan
        $stmt->execute();
        
        // Ambil hasilnya
        $result = $stmt->get_result();
        $json = array();

        // Mengambil data pengguna
        while ($row = $result->fetch_assoc()) {
            $json[] = $row;
        }

        // Mengembalikan data dalam format JSON
        echo json_encode($json);
        
        // Menutup pernyataan dan koneksi
        $stmt->close();
    } else {
        echo json_encode(array("error" => "ID pengguna tidak diberikan."));
    }

    mysqli_close($conn);
    ?>