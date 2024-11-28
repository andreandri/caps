<?php
// Koneksi ke database
include 'koneksi.php';

session_start();
$username = $_SESSION['username']; // Ambil username dari sesi login

// Query untuk mengambil data berdasarkan username
$query = "
    SELECT 
        p.id_busjadwal, 
        p.nama_penumpang, 
        p.no_wa, 
        p.jumlah_tiket, 
        p.total, 
        r.kota_asal, 
        r.kota_tujuan, 
        GROUP_CONCAT(k.nomor_kursi SEPARATOR ', ') AS nomor_kursi,
        GROUP_CONCAT(k.status SEPARATOR ', ') AS status_kursi
    FROM tb_pemesanan p
    INNER JOIN tb_busjadwal b ON p.id_busjadwal = b.id_busjadwal
    INNER JOIN tb_jadwal j ON b.id_jadwal = j.id_jadwal
    INNER JOIN tb_rute r ON j.id_rute = r.id_rute
    INNER JOIN tb_pemesanan_kursi pk ON p.id_pemesanan = pk.id_pemesanan
    INNER JOIN tb_kursi k ON pk.id_kursi = k.id_kursi
    WHERE p.username = '$username'
    GROUP BY p.id_pemesanan
";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pemesanan</title>
  <link rel="stylesheet" href="history.css">
  <script type="module" src="scripts/index.js"></script>
</head>

<body>
  <header>
    <bar-app></bar-app>
  </header>

  <main>
    <div class="container">
      <h2>History Pemesanan</h2>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="detail">
            <b>Rute:</b> <?= htmlspecialchars($row['kota_asal'] . " - " . $row['kota_tujuan']); ?>
            <b>Nama Penumpang:</b> <?= htmlspecialchars($row['nama_penumpang']); ?>
            <b>No WA:</b> <?= htmlspecialchars($row['no_wa']); ?>
            <b>Jumlah Tiket:</b> <?= htmlspecialchars($row['jumlah_tiket']); ?>
            <b>Nomor Kursi:</b> <?= htmlspecialchars($row['nomor_kursi']); ?>
            <b>Status Kursi:</b> <?= htmlspecialchars($row['status_kursi']); ?>
            <b>Total:</b> Rp <?= number_format($row['total'], 0, ',', '.'); ?>
            <div class="status berhasil">BERHASIL</div>
            <?php endwhile; ?>
      <?php else: ?>
      <p>Tidak ada data pemesanan.</p>
      <?php endif; ?>
    </div>
  </main>  

  <footer>
    <footer-app></footer-app>
  </footer>

</body>
</html>
