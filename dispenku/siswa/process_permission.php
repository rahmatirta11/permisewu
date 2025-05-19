<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siswa_id = $_SESSION['user_id'];
    $kelas_id = $_POST['kelas'];
    $waktu_keluar = $_POST['waktu_keluar'];
    $guru_id = $_POST['guru'];
    $tanggal = $_POST['tanggal'];
    $alasan = $_POST['alasan'];

    // Insert permission request
    $query = "INSERT INTO izin (siswa_id, kelas_id, waktu_keluar, guru_id, tanggal, alasan, status_guru, status_petugas) 
              VALUES (?, ?, ?, ?, ?, ?, 'pending', 'pending')";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iissis", $siswa_id, $kelas_id, $waktu_keluar, $guru_id, $tanggal, $alasan);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php?success=1");
    } else {
        header("Location: dashboard.php?error=1");
    }
} else {
    header("Location: dashboard.php");
}
?> 