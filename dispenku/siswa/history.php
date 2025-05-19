<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

$siswa_id = $_SESSION['user_id'];

// Get student's permission history
$query = "SELECT i.*, k.nama_kelas, g.nama as nama_guru 
          FROM izin i 
          JOIN siswa s ON i.siswa_id = s.id
          JOIN kelas k ON s.id_kelas = k.id 
          JOIN guru g ON i.guru_id = g.id 
          WHERE i.siswa_id = $siswa_id 
          ORDER BY i.tanggal DESC, i.waktu_keluar DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Izin - Sistem Izin Keluar</title>
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
                        <a class="nav-link" href="dashboard.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="history.php">
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
                <h2 class="welcome-text">Riwayat Izin Keluar</h2>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Riwayat Izin</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Waktu Keluar</th>
                                        <th>Guru</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status = 'Pending';
                                        if ($row['status_guru'] == 'rejected' || $row['status_petugas'] == 'rejected') {
                                            $status = 'Ditolak';
                                        } elseif ($row['status_guru'] == 'approved' && $row['status_petugas'] == 'approved') {
                                            $status = 'Disetujui';
                                        }
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['tanggal'] . "</td>";
                                        echo "<td>" . $row['waktu_keluar'] . "</td>";
                                        echo "<td>" . $row['nama_guru'] . "</td>";
                                        echo "<td>" . $row['alasan'] . "</td>";
                                        echo "<td><span class='badge badge-" . strtolower($status) . "'>" . $status . "</span></td>";
                                        echo "<td>";
                                        if ($status == 'Disetujui') {
                                            echo "<a href='cetak.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm' target='_blank'>Cetak</a>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 