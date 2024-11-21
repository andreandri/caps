<?php
include 'koneksi.php';

// Query untuk mengambil data dari view
$sql = "SELECT * FROM v_pemesanan_rute_jadwal";
$result = $koneksi->query($sql);

// Ambil data dari query
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan</title>
    <style>
        * {
          box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            background-color: #E0E0E0;
        }

        header {
          margin-bottom: 4rem;
        }

        main {
          color: #fff;
            display: flex;
            justify-content: center;
            margin: 0;
        }

        .card {
            background-color: #fff;
            color: #000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 40%;
            font-size: 16px;
        }
        .card h1 {
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .card p {
            margin: 5px 0;
        }
        .card .total {
            font-weight: bold;
            font-size: 18px;
        }
        .card button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .card button:hover {
            background-color: #45a049;
        }
    </style>

    <script type="module" src="scripts/index.js"></script>
</head>
<body>

  <header>
    <bar-app></bar-app>
  </header>
  <main>
  <div class="card">
        <h1>Detail Pemesanan</h1>
        <p>No. Kursi: <?= $data['no_kursi'] ?? '-' ?></p>
        <p>Nama: <?= $data['nama_penumpang'] ?? '-' ?></p>
        <p>No HP: <?= $data['no_wa'] ?? '-' ?></p>
        <p>Tiket: <?= $data['jumlah_tiket'] ?? '-' ?></p>
        <p>Tujuan: <?= ($data['kota_asal'] ?? '-') . ' - ' . ($data['kota_tujuan'] ?? '-') ?></p>
        <p>Keberangkatan: <?= ($data['tgl_keberangkatan'] ?? '-') . ', ' . ($data['jam_keberangkatan'] ?? '-') ?></p>
        <p class="total">Total: Rp <?= number_format($data['total'] ?? 0, 0, ',', '.') ?></p>
        <button>Bayar</button>
    </div>
  </main>
</body>
</html>

<?php
$koneksi->close();
?>
