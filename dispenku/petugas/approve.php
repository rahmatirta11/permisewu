<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'petugas') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $izin_id = $_GET['id'];
    $action = $_GET['action'];

    // Verify that this permission request has been approved by the teacher
    $verify_query = "SELECT * FROM izin WHERE id = $izin_id AND status_guru = 'approved'";
    $verify_result = mysqli_query($conn, $verify_query);

    if (mysqli_num_rows($verify_result) == 1) {
        $status = ($action == 'approve') ? 'approved' : 'rejected';
        
        $update_query = "UPDATE izin SET status_petugas = '$status' WHERE id = $izin_id";
        if (mysqli_query($conn, $update_query)) {
            header("Location: dashboard.php?success=1");
        } else {
            header("Location: dashboard.php?error=1");
        }
    } else {
        header("Location: dashboard.php?error=2");
    }
} else {
    header("Location: dashboard.php");
}
?> 