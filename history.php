<?php
// Koneksi ke database
include 'koneksi.php';

// Fungsi untuk mendapatkan status pembayaran dari Midtrans
function getPaymentStatus($order_id) {
    $serverKey = 'SB-Mid-server-ZwVxKRABKeqWj-jebaE_OvA3'; // Ganti dengan Server Key Anda
    $url = "https://api.sandbox.midtrans.com/v2/{$order_id}/status";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . base64_encode($serverKey . ":"),
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

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
        GROUP_CONCAT(k.nomor_kursi SEPARATOR ', ') AS nomor_kursi
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
    <div class="container">
      <h2 tabindex="0">History Pemesanan</h2>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
          // Ambil order_id untuk Midtrans
          $order_id = 'ORDER_' . $row['id_pemesanan'];

          // Ambil status pembayaran dari Midtrans
          $payment_status = getPaymentStatus($order_id);
          $status_pembayaran = $payment_status['transaction_status'] ?? 'unknown';

          // Mapping status pembayaran ke label
          $status_label = match ($status_pembayaran) {
              'capture', 'settlement' => 'BERHASIL',
              'pending' => 'MENUNGGU PEMBAYARAN',
              'deny', 'expire', 'cancel' => 'GAGAL',
              default => 'STATUS TIDAK DIKENAL',
          };
          ?>
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
                  <th tabindex="0">Total</th>
                  <td tabindex="0">: Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
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
</body>
</html>
