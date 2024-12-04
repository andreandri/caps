<?php
include 'koneksi.php';

session_start();
$username = $_SESSION['username'] ?? null;

if (!$username) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

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
  <style>
    .pay-link {
        color: white;
        text-decoration: none;
        font-weight: bold;
        background-color: #4caf50;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
    }

    .pay-link:hover {
        background-color: #45a049;
    }

    .status {
        margin-top: 10px;
        font-weight: bold;
    }
  </style>
</head>
<body>
  <header>
    <bar-app></bar-app>
  </header>
  <main>
  <ind-loading-main></ind-loading-main>
    <h2 tabindex="0">History Pemesanan</h2>
    <div class="container">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
switch ($row['status_pembayaran']) {
    case 'lunas':
        $status_label = 'BERHASIL';
        break;
    case 'pending':
        $status_label = 'MENUNGGU PEMBAYARAN';
        break;
    case 'dibatalkan':
        $status_label = 'GAGAL';
        break;
    default:
        $status_label = 'STATUS TIDAK DIKENAL';
        break;
}
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
                <?php if ($row['status_pembayaran'] === 'pending'): ?>
                  <a tabindex="0" href="midtrans/examples/snap/checkout-process-simple-version.php?id_pemesanan=<?= htmlspecialchars($row['id_pemesanan']); ?>" class="pay-link">
                    <?= htmlspecialchars($status_label); ?> - Klik untuk bayar
                  </a>
                <?php else: ?>
                  <?= htmlspecialchars($status_label); ?>
                <?php endif; ?>
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
