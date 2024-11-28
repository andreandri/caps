<?php
// Koneksi ke database
include 'koneksi.php';

session_start();
$username = $_SESSION['username']; // Ambil username dari sesi login

// Query untuk mengambil data berdasarkan username
$query = "SELECT id_busjadwal, nama_penumpang, no_wa, jumlah_tiket, total FROM tb_pemesanan WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Pemesanan</title>

  <script type="module" src="scripts/index.js"></script>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: Arial, sans-serif;
        height: 100%;
        width: 100%;
        background-color: #f5f5f5;
      }

      main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
      }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
            color: black;
        }
        .detail {
            text-align: left;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .detail b {
            display: block;
            margin-top: 10px;
        }
    </style>
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
            <b>ID Jadwal Bus:</b> <?= htmlspecialchars($row['id_busjadwal']); ?>
            <b>Nama Penumpang:</b> <?= htmlspecialchars($row['nama_penumpang']); ?>
            <b>No WA:</b> <?= htmlspecialchars($row['no_wa']); ?>
            <b>Jumlah Tiket:</b> <?= htmlspecialchars($row['jumlah_tiket']); ?>
            <b>Total:</b> Rp <?= number_format($row['total'], 0, ',', '.'); ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
      <p>Tidak ada data pemesanan.</p>
      <?php endif; ?>
    </div>
  </main>  

</body>
</html>
