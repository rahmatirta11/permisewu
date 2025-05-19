<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

$siswa_id = $_SESSION['user_id'];

// Get student data
$student_query = "SELECT * FROM siswa WHERE id = $siswa_id";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

// Get permission data and petugas data
if (isset($_GET['id'])) {
    $izin_id = $_GET['id'];
    $query = "SELECT i.*, k.nama_kelas, g.nama as nama_guru, p.nama as nama_petugas 
              FROM izin i 
              JOIN siswa s ON i.siswa_id = s.id
              JOIN kelas k ON s.id_kelas = k.id 
              JOIN guru g ON i.guru_id = g.id 
              JOIN petugas p ON p.id = 1
              WHERE i.id = $izin_id 
              AND i.siswa_id = $siswa_id";
    $result = mysqli_query($conn, $query);
    $izin = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Izin</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            background: #f0f0f0;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 16pt;
        }
        .header p {
            margin: 5px 0;
            font-size: 11pt;
        }
        .content {
            margin: 20px 0;
            font-size: 11pt;
        }
        .content p {
            margin: 5px 0;
        }
        .content table {
            margin-left: 30px;
            border-spacing: 0 8px;
        }
        .content table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 11pt;
        }
        .signature-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature p {
            margin: 0;
        }
        .signature .line {
            margin: 70px 0 10px 0;
            border-bottom: 1px solid #000;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                background: white;
            }
            .page {
                width: 100%;
                min-height: auto;
                padding: 20mm;
                margin: 0;
                box-shadow: none;
            }
        }
        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4267B2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        @media screen {
            .page {
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="container">
            <div class="header">
                <h3>SMK NEGERI 5 SURAKARTA</h3>
                <p>Jl. Adi Sucipto No.42, Kerten, Kec. Laweyan, Kota Surakarta</p>
                <p>Telepon: (0271) 713916</p>
            </div>

            <div class="content">
                <p>Yang bertanda tangan di bawah ini:</p>
                <table>
                    <tr>
                        <td style="width: 150px;">Nama</td>
                        <td>: <?php echo $student['nama']; ?></td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>: <?php echo $izin['nama_kelas']; ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Izin</td>
                        <td>: <?php echo date('Y-m-d', strtotime($izin['tanggal'])); ?></td>
                    </tr>
                    <tr>
                        <td>Jam Izin</td>
                        <td>: <?php echo date('H:i', strtotime($izin['waktu_keluar'])); ?></td>
                    </tr>
                    <tr>
                        <td>Alasan</td>
                        <td>: <?php echo $izin['alasan']; ?></td>
                    </tr>
                    <tr>
                        <td>Guru yang Ditinggal</td>
                        <td>: <?php echo $izin['nama_guru']; ?></td>
                    </tr>
                </table>

                <p style="margin-top: 30px; text-align: justify;">Dengan ini mengajukan permohonan izin untuk pulang lebih awal dari sekolah pada tanggal dan waktu tersebut. Surat ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.</p>

                <div class="signature-container">
                    <div class="signature">
                        <p>Mengetahui,</p>
                        <p>Petugas Lobby</p>
                        <div class="line"></div>
                        <p><?php echo $izin['nama_petugas']; ?></p>
                    </div>
                    <div class="signature">
                        <p>Surakarta, <?php echo date('d-m-Y'); ?></p>
                        <p>Yang Membuat Pernyataan</p>
                        <div class="line"></div>
                        <p><?php echo $student['nama']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button onclick="window.print()" class="btn-print no-print">Cetak Surat</button>
</body>
</html> 