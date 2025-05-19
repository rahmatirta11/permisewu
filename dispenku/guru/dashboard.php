<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'guru') {
    header("Location: ../login.php");
    exit();
}

$guru_id = $_SESSION['user_id'];

// Get teacher data
$guru_query = "SELECT * FROM guru WHERE id = $guru_id";
$guru_result = mysqli_query($conn, $guru_query);
$guru = mysqli_fetch_assoc($guru_result);

// Get permission requests for this teacher
$query = "SELECT i.*, s.nama as nama_siswa, k.nama_kelas 
          FROM izin i 
          JOIN siswa s ON i.siswa_id = s.id 
          JOIN kelas k ON s.id_kelas = k.id 
          WHERE i.guru_id = $guru_id 
          ORDER BY i.tanggal DESC, i.waktu_keluar DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Sistem Izin Keluar</title>
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
                        <a class="nav-link" href="../logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="welcome-text">Selamat datang, <?php echo htmlspecialchars($guru['nama']); ?></h2>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Pengajuan Izin</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Waktu Keluar</th>
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
                                        if ($row['status_guru'] == 'rejected') {
                                            $status = 'Ditolak';
                                        } elseif ($row['status_guru'] == 'approved') {
                                            $status = 'Disetujui';
                                        }
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['tanggal'] . "</td>";
                                        echo "<td>" . $row['nama_siswa'] . "</td>";
                                        echo "<td>" . $row['nama_kelas'] . "</td>";
                                        echo "<td>" . $row['waktu_keluar'] . "</td>";
                                        echo "<td>" . $row['alasan'] . "</td>";
                                        echo "<td><span class='badge badge-" . strtolower($status) . "'>" . $status . "</span></td>";
                                        echo "<td>";
                                        if ($status == 'Pending') {
                                            echo "<div class='btn-group'>";
                                            echo "<a href='approve.php?id=" . $row['id'] . "&action=approve' class='btn btn-success btn-sm'>Setuju</a>";
                                            echo "<a href='approve.php?id=" . $row['id'] . "&action=reject' class='btn btn-danger btn-sm'>Tolak</a>";
                                            echo "</div>";
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