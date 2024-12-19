<?php
session_start();

// Daftar halaman yang diizinkan
$allowed_pages = ['./dashboard.php']; // Ganti dengan halaman yang diizinkan

// Ambil nama file halaman saat ini
$current_page = basename($_SERVER['PHP_SELF']);

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id'])) {
    // Jika tidak ada sesi, redirect ke halaman login
    header("Location: ./dashboard.php");
    exit();
}

// Cek apakah halaman saat ini diizinkan
if (!in_array($current_page, $allowed_pages)) {
    // Jika tidak diizinkan, hapus sesi dan redirect ke halaman login
    session_destroy();
    header("Location: ./dashboard.php");
    exit();
}
?>