<?php
// Koneksi ke database
include 'koneksi.php';

// Query untuk mengambil bulan dan total pendapatan
$query = "SELECT DATE_FORMAT(j.tgl_keberangkatan, '%Y-%m') AS bulan, SUM(p.total) AS pendapatan
          FROM tb_pemesanan p
          JOIN tb_busjadwal bj ON p.id_busjadwal = bj.id_busjadwal
          JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal
          GROUP BY bulan
          ORDER BY bulan DESC";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Rekap Pendapatan</title>

    <link rel="stylesheet" href="adminjadwal.css">
</head>
<body>
    <header class="sidebar">
        <h1>Dashboard Admin</h1>
        <ul>
            <li><a href="adminrute.php">Rute</a></li>
            <li><a href="adminjadwal.php">Jadwal</a></li>
            <li><a href="adminpesanan.php">Daftar Pesanan</a></li>
            <li><a href="adminrekap.php">Rekap Pendapatan</a></li>
            <li><a href="fungsi/logout.php" class="logout">Logout</a></li>
        </ul>
    </header>

    <main>
        <h1>Rekap Pendapatan Bulanan</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Pendapatan</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['bulan']; ?></td>
                        <td><?php echo number_format($row['pendapatan'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="detailrekap.php?bulan=<?php echo $row['bulan']; ?>">Detail</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>    
    </main>
</body>
</html>
