<?php
require '../../../vendor/autoload.php';
require_once '../../koneksi/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($conn) || !$conn) {
    die("Koneksi database gagal: " . (isset($conn) ? mysqli_connect_error() : "Variabel koneksi tidak terdefinisi"));
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

if (isset($_GET['export_type'])) {
    $exportType = $_GET['export_type']; // Menentukan tipe ekspor (dashboard/transaksi)

    try {
        $spreadsheet = new Spreadsheet();

        if ($exportType === 'dashboard') {
            // === Ekspor Dashboard: Chart Pembayaran Bulanan ===
            $payments = getPaymentsByMonth($conn);
        
            // Inisialisasi array bulan
            $months = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];
            $paymentData = array_fill_keys($months, 0); // Set semua bulan dengan nilai awal 0
        
            // Memproses data dari database
            while ($data = $payments->fetch_assoc()) {
                $monthIndex = (int)date('n', strtotime($data['bulan'] . '-01')) - 1; // Ambil indeks bulan (0-11)
                $paymentData[$months[$monthIndex]] = (int)$data['total_pembayaran'];
            }
        
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Pembayaran Bulanan');
        
            // Menambahkan Header Utama dengan Merge
            $sheet->mergeCells('A1:B1'); // Merge header
            $sheet->setCellValue('A1', 'Laporan Pembayaran Bulanan'); // Isi header
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center'); // Rata tengah horizontal
            $sheet->getStyle('A1')->getAlignment()->setVertical('center'); // Rata tengah vertikal
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Font tebal dan besar
            $sheet->getRowDimension(1)->setRowHeight(30); // Tinggi baris header
        
            // Menambahkan Sub-Header dengan Warna Biru
            $sheet->setCellValue('A2', 'Bulan');
            $sheet->setCellValue('B2', 'Total Pembayaran');
            $sheet->getStyle('A2:B2')->getFont()->setBold(true); // Sub-header tebal
            $sheet->getStyle('A2:B2')->getAlignment()->setHorizontal('center'); // Rata tengah horizontal
            $sheet->getStyle('A2:B2')->getAlignment()->setVertical('center'); // Rata tengah vertikal
            $sheet->getStyle('A2:B2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF87CEEB'); // Warna biru muda
            $sheet->getRowDimension(2)->setRowHeight(20); // Tinggi baris sub-header
        
            // Menulis Data ke dalam Excel
            $row = 3; // Baris awal untuk data
            foreach ($paymentData as $month => $total) {
                $sheet->setCellValue('A' . $row, $month);
                $sheet->setCellValue('B' . $row, $total);
        
                // Rata tengah data
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal('center');
        
                $row++;
            }
        
            // Menyesuaikan lebar kolom
            foreach (range('A', 'B') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        
            // Menambahkan Border pada Tabel
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'], // Warna hitam
                    ],
                ],
            ];
            $sheet->getStyle('A1:B' . ($row - 1))->applyFromArray($styleArray);
        
            // Nama file
            $filename = 'pembayaran_bulanan.xlsx';
        } elseif ($exportType === 'transaksi') {

            // Validasi export_type
if (!isset($_GET['export_type']) || $_GET['export_type'] !== 'transaksi') {
    die("Error: Tipe ekspor tidak valid!");
}

// Ambil nilai tanggal awal dan akhir dari request
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : null;
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : null;

// Query default
$query = "SELECT username, email, harga, status_transaksi, created_at FROM view_transaksi_lengkap";

// Filter data berdasarkan tanggal jika ada input
if ($tanggal_awal && $tanggal_akhir) {
    $query .= " WHERE created_at BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

$result = $conn->query($query);

// Pastikan query berjalan dengan benar
if (!$result) {
    die("Query Error: " . $conn->error);
}

// Buat Spreadsheet Baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tambahkan Header dengan Merge
$sheet->mergeCells('A1:E1'); // Merge header untuk seluruh kolom (A-M)
$sheet->setCellValue('A1', 'Laporan Data Transaksi'); // Isi header utama
$sheet->getStyle('A1')->getAlignment()->setHorizontal('center'); // Rata tengah horizontal
$sheet->getStyle('A1')->getAlignment()->setVertical('center'); // Rata tengah vertikal
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Font tebal dan ukuran besar
$sheet->getRowDimension(1)->setRowHeight(30); // Tinggi baris header

// Tambahkan Sub-Header dengan Warna Biru
$sheet->setCellValue('A2', 'Nama User');
$sheet->setCellValue('B2', 'Email');
$sheet->setCellValue('C2', 'Harga');
$sheet->setCellValue('D2', 'Status Transaksi');
$sheet->setCellValue('E2', 'Tanggal Transaksi');

$sheet->getStyle('A2:E2')->getFont()->setBold(true); // Sub-header tebal
$sheet->getStyle('A2:E2')->getAlignment()->setHorizontal('center'); // Rata tengah horizontal
$sheet->getStyle('A2:E2')->getAlignment()->setVertical('center'); // Rata tengah vertikal
$sheet->getStyle('A2:E2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF87CEEB'); // Warna biru muda
$sheet->getRowDimension(2)->setRowHeight(20); // Tinggi baris sub-header

// Isi Data
$rowNumber = 3; // Baris awal untuk data
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, $row['username']);
    $sheet->setCellValue('B' . $rowNumber, $row['email']);
    $sheet->setCellValue('C' . $rowNumber, $row['harga']);

    // Konversi Status Transaksi
    $status = '';
    switch ($row['status_transaksi']) {
        case '0':
            $status = 'Pending';
            break;
        case '1':
            $status = 'Konfirmasi';
            break;
        case '2':
            $status = 'Ditolak';
            break;
        default:
            $status = 'Tidak Aktif';
            break;
    }
    $sheet->setCellValue('D' . $rowNumber, $status);

    // Tambahkan kolom tanggal
    $sheet->setCellValue('E' . $rowNumber, $row['created_at']);

    // Atur teks untuk data yang panjang
    foreach (range('A', 'E') as $columnID) {
        $sheet->getStyle($columnID . $rowNumber)->getAlignment()->setWrapText(true); // Bungkus teks
        $sheet->getStyle($columnID . $rowNumber)->getAlignment()->setHorizontal('center'); // Rata tengah horizontal
    }

    $rowNumber++;
}

// Menyesuaikan lebar kolom
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Menambahkan border untuk tabel
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Warna hitam
        ],
    ],
];
$sheet->getStyle('A1:E' . ($rowNumber - 1))->applyFromArray($styleArray);

// Nama file dengan rentang tanggal jika ada
$filename = 'data_transaksi';
if ($tanggal_awal && $tanggal_akhir) {
    $filename .= "_{$tanggal_awal}_to_{$tanggal_akhir}";
}
$filename .= '.xlsx';
} else {
throw new Exception('Tipe ekspor tidak valid!');
}

// Header untuk download file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
} catch (Exception $e) {
echo 'Error: ' . $e->getMessage();
} finally {
if (isset($conn)) {
$conn->close();
}
}
} else {
echo 'Parameter export_type tidak ditemukan!';
}