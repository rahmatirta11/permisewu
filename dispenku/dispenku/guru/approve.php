<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'guru') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $izin_id = $_GET['id'];
    $action = $_GET['action'];
    $guru_id = $_SESSION['user_id'];

    // Verify that this permission request belongs to the teacher
    $verify_query = "SELECT * FROM izin WHERE id = $izin_id AND guru_id = $guru_id";
    $verify_result = mysqli_query($conn, $verify_query);

    if (mysqli_num_rows($verify_result) == 1) {
        $status = ($action == 'approve') ? 'approved' : 'rejected';
        
        $update_query = "UPDATE izin SET status_guru = '$status' WHERE id = $izin_id";
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