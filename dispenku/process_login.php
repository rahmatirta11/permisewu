<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query based on user type
    $table = '';
    switch ($user_type) {
        case 'siswa':
            $table = 'siswa';
            break;
        case 'guru':
            $table = 'guru';
            break;
        case 'petugas':
            $table = 'petugas';
            break;
    }

    $query = "SELECT * FROM $table WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user_type;
        $_SESSION['username'] = $username;

        // Redirect based on user type
        switch ($user_type) {
            case 'siswa':
                header("Location: siswa/dashboard.php");
                break;
            case 'guru':
                header("Location: guru/dashboard.php");
                break;
            case 'petugas':
                header("Location: petugas/dashboard.php");
                break;
        }
    } else {
        header("Location: login.php?error=1");
    }
} else {
    header("Location: login.php");
}
?> 