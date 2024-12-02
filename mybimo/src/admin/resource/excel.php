<?php
require '../../../vendor/autoload.php';
require_once '../../koneksi/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

try {
    // Mengambil data pembayaran per bulan
    $payments = getPaymentsByMonth($conn);

    // Inisialisasi array bulan
    $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $paymentData = array_fill_keys($months, 0); // Set semua bulan dengan nilai awal 0

    // Memproses data dari database
    while ($data = $payments->fetch_assoc()) {
        $monthIndex = (int)date('n', strtotime($data['bulan'] . '-01')) - 1; // Ambil indeks bulan (0-11)
        $paymentData[$months[$monthIndex]] = (int)$data['total_pembayaran'];
    }

    // Membuat spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Menambahkan header utama dan merge cell
    $sheet->setCellValue('A1', 'Laporan Pembayaran Bulanan'); // Header utama
    $sheet->mergeCells('A1:B1'); // Merge cell A1 hingga B1
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center align
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Bold dan font size lebih besar
    
    // Menulis header kolom
    $sheet->setCellValue('A2', 'Bulan');
    $sheet->setCellValue('B2', 'Total Pembayaran');
    $sheet->getStyle('A2:B2')->getFont()->setBold(true); // Membuat header kolom bold
    
    // Menulis data ke dalam Excel
    $row = 3; // Data dimulai dari baris ke-3
    foreach ($paymentData as $month => $total) {
        $sheet->setCellValue('A' . $row, $month); // Nama bulan
        $sheet->setCellValue('B' . $row, $total); // Total pembayaran
        $row++;
    }
    
    // Menyesuaikan lebar kolom agar pas dengan isinya
    foreach (range('A', 'B') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    

    // Menyiapkan file untuk diunduh
    $filename = 'pembayaran_bulanan.xlsx';

    // Set header untuk download file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Membuat writer dan menulis file
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close(); // Menutup koneksi database
    }
}
