<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siswa_id = $_SESSION['user_id'];
    $guru_id = $_POST['guru_id'];
    $alasan = $_POST['alasan'];
    $waktu_keluar = $_POST['waktu_keluar'];
    $tanggal = date('Y-m-d');

    // Get student's class
    $student_query = "SELECT id_kelas FROM siswa WHERE id = $siswa_id";
    $student_result = mysqli_query($conn, $student_query);
    $student = mysqli_fetch_assoc($student_result);
    
    $query = "INSERT INTO izin (siswa_id, guru_id, waktu_keluar, tanggal, alasan) 
              VALUES ($siswa_id, $guru_id, '$waktu_keluar', '$tanggal', '$alasan')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?> 