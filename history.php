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
    <ind-loading-main></ind-loading-main>
    <div class="container">
      <h2 tabindex="0">History Pemesanan</h2>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="detail">
          <div class="detail-item">
            <table>
              <tr>
                <th tabindex="0">Rute</th>
                <td tabindex="0">: <?= htmlspecialchars($row['kota_asal'] . " - " . $row['kota_tujuan']); ?></td>
              </tr>
              <tr>
                <th tabindex="0">Nama</th>
                <td tabindex="0">: <?= htmlspecialchars($row['nama_penumpang']); ?></td>
              </tr>
              <tr>
                <th tabindex="0">No WA</th>
                <td tabindex="0">: <?= htmlspecialchars($row['no_wa']); ?></td>
              </tr>
              <tr>
                <th tabindex="0">Jumlah Tiket</th>
                <td tabindex="0">: <?= htmlspecialchars($row['jumlah_tiket']); ?></td>
              </tr>
              <tr>
                <th tabindex="0">Nomor Kursi</th>
                <td tabindex="0">: <?= htmlspecialchars($row['nomor_kursi']); ?></td>
              </tr>
              <tr>
                <th tabindex="0">Status Kursi</th>
                <td tabindex="0">: <?= htmlspecialchars($row['status_kursi']); ?></td>
              </tr>
              <tr>
                <th tabindex="0">Total</th>
                <td tabindex="0">: Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
              </tr>
            </table>
            <div tabindex="0" class="status berhasil">BERHASIL</div>
          </div>
            <?php endwhile; ?>
      <?php else: ?>
      <p tabindex="0">Tidak ada data pemesanan.</p>
      <?php endif; ?>
    </div>
    </div>
  </main>  
</body>
</html>
