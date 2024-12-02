<?php
// Koneksi ke database
include 'koneksi.php';

session_start();
$username = $_SESSION['username']; // Ambil username dari sesi login
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
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pemesanan</title>
  <link rel="stylesheet" href="history.css">
</head>
<body>
  <header>
    <bar-app></bar-app>
  </header>
  <main>
    <h2>History Pemesanan</h2>
    <div class="container">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="detail">
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="detail-item">
              <table>
                <tr>
                  <th>Rute</th>
                  <td><?= htmlspecialchars($row['kota_asal'] . " - " . $row['kota_tujuan']); ?></td>
                </tr>
                <tr>
                  <th>Nama Penumpang</th>
                  <td><?= htmlspecialchars($row['nama_penumpang']); ?></td>
                </tr>
                <tr>
                  <th>No WA</th>
                  <td><?= htmlspecialchars($row['no_wa']); ?></td>
                </tr>
                <tr>
                  <th>Jumlah Tiket</th>
                  <td><?= htmlspecialchars($row['jumlah_tiket']); ?></td>
                </tr>
                <tr>
                  <th>Nomor Kursi</th>
                  <td><?= htmlspecialchars($row['nomor_kursi']); ?></td>
                </tr>
                <tr>
                  <th>Status Kursi</th>
                  <td><?= htmlspecialchars($row['status_kursi']); ?></td>
                </tr>
                <tr>
                  <th>Total</th>
                  <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                </tr>
              </table>
              <div class="status berhasil">BERHASIL</div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="error">Tidak ada data pemesanan.</div>
      <?php endif; ?>
    </div>
  </main>
  <footer>
    <footer-app></footer-app>
  </footer>
</body>
</html>
