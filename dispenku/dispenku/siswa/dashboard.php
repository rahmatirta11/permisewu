<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

// Get student data with class
$student_id = $_SESSION['user_id'];
$query = "SELECT s.*, k.nama_kelas 
          FROM siswa s 
          JOIN kelas k ON s.id_kelas = k.id 
          WHERE s.id = $student_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// Get statistics data
$total_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status_guru = 'approved' AND status_petugas = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status_guru = 'pending' OR (status_guru = 'approved' AND status_petugas = 'pending') THEN 1 ELSE 0 END) as pending
FROM izin WHERE siswa_id = $student_id";

$stats_result = mysqli_query($conn, $total_query);
$stats = mysqli_fetch_assoc($stats_result);

$total_izin = $stats['total'] ?? 0;
$approved_izin = $stats['approved'] ?? 0;
$pending_izin = $stats['pending'] ?? 0;

// Get teachers for dropdown
$guru_query = "SELECT * FROM guru";
$guru_result = mysqli_query($conn, $guru_query);

// Remove unnecessary query since we already have class info from first query
// $kelas_query = "SELECT k.* FROM kelas k JOIN siswa s ON s.kelas_id = k.id WHERE s.id = $student_id";
// $kelas_result = mysqli_query($conn, $kelas_query);
// $kelas = mysqli_fetch_assoc($kelas_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Sistem Izin Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="mountain-logo">
                    <i class="bi bi-triangle-fill"></i>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">
                            <i class="bi bi-clock-history"></i> Riwayat Izin
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="welcome-text">Selamat datang, <?php echo htmlspecialchars($student['nama']); ?></h2>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-card">
                            <h3>Total Izin</h3>
                            <p><?php echo $total_izin; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <h3>Izin Disetujui</h3>
                            <p><?php echo $approved_izin; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card">
                            <h3>Izin Pending</h3>
                            <p><?php echo $pending_izin; ?></p>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Form Pengajuan Izin</h4>
                    </div>
                    <div class="card-body">
                        <form action="process_izin.php" method="POST">
                            <div class="mb-3">
                                <label for="guru" class="form-label">Guru Mata Pelajaran</label>
                                <select class="form-select" id="guru" name="guru_id" required>
                                    <option value="">Pilih guru...</option>
                                    <?php while ($row = mysqli_fetch_assoc($guru_result)): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="waktu_keluar" class="form-label">Waktu Keluar</label>
                                <input type="time" class="form-control" id="waktu_keluar" name="waktu_keluar" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajukan Izin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 