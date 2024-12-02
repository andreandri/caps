<?php
// Koneksi ke database
include 'koneksi.php';

// Mulai sesi untuk mengambil username
session_start();
$username = $_SESSION['username'] ?? null; // Ambil username dari sesi login

// Periksa apakah user sudah login
if (!$username) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

// Query untuk mengambil data pemesanan
$query = "
    SELECT 
        p.id_pemesanan, 
        p.id_busjadwal, 
        p.nama_penumpang, 
        p.no_wa, 
        p.jumlah_tiket, 
        p.total, 
        r.kota_asal, 
        r.kota_tujuan, 
        GROUP_CONCAT(k.nomor_kursi SEPARATOR ', ') AS nomor_kursi,
        p.status_pembayaran
    FROM tb_pemesanan p
    INNER JOIN tb_busjadwal b ON p.id_busjadwal = b.id_busjadwal
    INNER JOIN tb_jadwal j ON b.id_jadwal = j.id_jadwal
    INNER JOIN tb_rute r ON j.id_rute = r.id_rute
    INNER JOIN tb_pemesanan_kursi pk ON p.id_pemesanan = pk.id_pemesanan
    INNER JOIN tb_kursi k ON pk.id_kursi = k.id_kursi
    WHERE p.username = ?
    GROUP BY p.id_pemesanan
";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pemesanan</title>
  <link rel="stylesheet" href="history.css">
  <link rel="icon" href="favicon.png" type="image/png">
  <script type="module" src="scripts/index.js"></script>
</head>
<body>
  <header>
    <bar-app></bar-app>
  </header>
  <main>
    <h2 tabindex="0">History Pemesanan</h2>
    <div class="container">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
          // Mapping status pembayaran ke label
          $status_label = match ($row['status_pembayaran']) {
              'lunas' => 'BERHASIL',
              'pending' => 'MENUNGGU PEMBAYARAN',
              'dibatalkan' => 'GAGAL',
              default => 'STATUS TIDAK DIKENAL',
          };
          ?>
          <div class="detail">
            <div class="detail-item">
              <table>
                <tr>
                  <th tabindex="0">Rute</th>
                  <td tabindex="0"><?= htmlspecialchars($row['kota_asal'] . " - " . $row['kota_tujuan']); ?></td>
                </tr>
                <tr>
                  <th tabindex="0">Nama Penumpang</th>
                  <td tabindex="0"><?= htmlspecialchars($row['nama_penumpang']); ?></td>
                </tr>
                <tr>
                  <th tabindex="0">No WA</th>
                  <td tabindex="0"><?= htmlspecialchars($row['no_wa']); ?></td>
                </tr>
                <tr>
                  <th tabindex="0">Jumlah Tiket</th>
                  <td tabindex="0"><?= htmlspecialchars($row['jumlah_tiket']); ?></td>
                </tr>
                <tr>
                  <th tabindex="0">Nomor Kursi</th>
                  <td tabindex="0"><?= htmlspecialchars($row['nomor_kursi']); ?></td>
                </tr>
                <tr>
                  <th tabindex="0">Total</th>
                  <td tabindex="0">Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                </tr>
              </table>
              <div tabindex="0" class="status <?= strtolower($status_label); ?>">
                <?= htmlspecialchars($status_label); ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="error" tabindex="0">Tidak ada data pemesanan.</p>
      <?php endif; ?>
    </div>
  </main>
  <footer>
    <footer-app></footer-app>
  </footer>
</body>
</html>
