<?php
include '../koneksi.php';

$bulan = $_GET['bulan'];

$query = "SELECT 
            j.tgl_keberangkatan, 
            r.kota_asal, 
            r.kota_tujuan, 
            SUM(p.total) AS pendapatan
          FROM tb_pemesanan p
          JOIN tb_busjadwal bj ON p.id_busjadwal = bj.id_busjadwal
          JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal
          JOIN tb_rute r ON j.id_rute = r.id_rute
          WHERE DATE_FORMAT(j.tgl_keberangkatan, '%Y-%m') = '$bulan'
          GROUP BY j.tgl_keberangkatan, r.kota_asal, r.kota_tujuan
          ORDER BY j.tgl_keberangkatan";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Bulan <?php echo $bulan; ?></title>
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/detailrekap.css">
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

  <main class="detail-rekap">
    <div>
    <h1 tabindex="0">Detail Pesanan</h1>

    <section class="Kembali">
      <a tabindex="0" href="adminrekap.php">Kembali</a>
    </section>
        
        <table border="1">
          <thead>
            <tr>
              <th tabindex="0">Tanggal Keberangkatan</th>
              <th tabindex="0">Rute</th>
              <th tabindex="0">Pendapatan</th>
            </tr>
          </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td tabindex="0"><?php echo $row['tgl_keberangkatan']; ?></td>
                  <td tabindex="0"><?php echo $row['kota_asal'] . ' - ' . $row['kota_tujuan']; ?></td>
                  <td tabindex="0"><?php echo number_format($row['pendapatan'], 0, ',', '.'); ?></td>
                </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
</body>
</html>
