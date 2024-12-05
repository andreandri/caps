<?php
include '../koneksi.php';

$query = "SELECT DATE_FORMAT(j.tgl_keberangkatan, '%Y-%m') AS bulan, SUM(p.total) AS pendapatan
          FROM tb_pemesanan p
          JOIN tb_busjadwal bj ON p.id_busjadwal = bj.id_busjadwal
          JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal
          WHERE p.status_pembayaran = 'lunas'
          GROUP BY bulan
          ORDER BY bulan DESC";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.png" type="image/png">
  <title>Admin Rekap Pendapatan</title>

  <link rel="stylesheet" href="styles/adminjadwal.css">
  <script type="module" src="../scripts/index.js"></script>
</head>

<body>
  <header class="dashboard">
    <div class="navbar">
      <h1 tabindex="0">Dashboard Admin</h1>
      <ul class="menu">
        <li><a tabindex="0" href="adminrute.php">Rute</a></li>
        <li><a tabindex="0" href="adminjadwal.php">Jadwal</a></li>
        <li><a tabindex="0" href="adminpesanan.php">Daftar Pesanan</a></li>
        <li><a tabindex="0" href="adminrekap.php" style="background-color: #C8ACD6;">Rekap Pendapatan</a></li>
      </ul>
      <img tabindex="0" src="../img/EasyBusTix.png" alt="Logo EasyBusTix">
    </div>
    <div>
      <a tabindex="0" href="../fungsi/logout.php" class="logout">Logout</a>
    </div>
  </header>

  <main class="main-content">
    <ind-loading-admin></ind-loading-admin>
    <h1 tabindex="0">Rekap Pendapatan Bulanan</h1>
    <table border="1">
      <thead>
        <tr>
          <th tabindex="0">Bulan</th>
          <th tabindex="0">Pendapatan</th>
          <th tabindex="0">Detail</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td tabindex="0"><?php echo $row['bulan']; ?></td>
            <td tabindex="0"><?php echo number_format($row['pendapatan'], 0, ',', '.'); ?></td>
            <td tabindex="0" class="rekap">
              <a tabindex="0" href="detailrekap.php?bulan=<?php echo $row['bulan']; ?>">Detail</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</body>

</html>