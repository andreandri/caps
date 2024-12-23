<?php
include 'koneksi.php';
session_start();

$username = $_SESSION['username'];
$id_pemesanan = $_GET['id_pemesanan'] ?? null;

if ($id_pemesanan) {
  $sql = $koneksi->prepare("SELECT p.id_pemesanan, p.nama_penumpang, p.no_wa, p.total, j.harga, 
                                       r.kota_asal, r.kota_tujuan, j.tgl_keberangkatan, j.jam_keberangkatan
                              FROM tb_pemesanan p
                              JOIN tb_busjadwal bj ON p.id_busjadwal = bj.id_busjadwal  
                              JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal  
                              JOIN tb_rute r ON j.id_rute = r.id_rute
                              WHERE p.id_pemesanan = ?");
  $sql->bind_param("i", $id_pemesanan);
  $sql->execute();
  $result = $sql->get_result();

  if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    $kursi_sql = $koneksi->prepare("SELECT k.nomor_kursi
                                        FROM tb_pemesanan_kursi pk
                                        JOIN tb_kursi k ON pk.id_kursi = k.id_kursi
                                        WHERE pk.id_pemesanan = ?");
    $kursi_sql->bind_param("i", $id_pemesanan);
    $kursi_sql->execute();
    $kursi_result = $kursi_sql->get_result();

    $kursi_list = [];
    while ($kursi = $kursi_result->fetch_assoc()) {
      $kursi_list[] = $kursi['nomor_kursi'];
    }

    $jumlah_tiket = count($kursi_list);
  } else {
    echo "Pemesanan tidak ditemukan.";
    exit;
  }
} else {
  echo "ID Pemesanan tidak diberikan.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pemesanan</title>
  <link rel="icon" href="favicon.png" type="image/png">
  <style>
    * {
      box-sizing: border-box;
    }

    [tabindex="0"]:focus {
      outline: 2px solid #243642;
      border-radius: 0.4rem;
    }

    h2[id] {
      scroll-margin-top: 100px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      height: 100%;
      width: 100%;
      background-color: #f5f5f5;
    }

    header {
      margin-bottom: 2rem;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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
      text-align: right;
    }

    .card .total {
      font-weight: bold;
      font-size: 18px;
    }

    .total {
      padding-top: 8px;
    }

    .cetak td {
      padding: 8px 2px 5px;
      border-bottom: 1px solid #b3b5b5;
    }

    .pay-link {
      display: block;
      text-decoration: none;
      text-align: center;
      margin-top: 1rem;
      padding: 0.8rem;
      font-size: 16px;
      color: white;
      background-color: #4caf50;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .pay-link:hover {
      background-color: #45a049;
    }

    .catatan {
      margin-top: 20px;
      border-top: 1px solid #ddd;
      padding-top: 15px;
    }

    .catatan h2 {
      font-size: 18px;
      margin-bottom: 10px;
      color: #333;
    }

    .catatan .catat {
      width: 100%;
      border-collapse: collapse;
    }

    .catatan .catat td {
      padding: 8px 0;
      vertical-align: top;
      text-align: justify;
    }

    .catatan .catat td:first-child {
      width: 5%;
      text-align: left;
    }

    .catatan .catat td:last-child {
      width: 95%;
      text-align: left;
    }

    @media (max-width: 450px) {
      .catatan {
        margin-top: 10px;
        padding-top: 10px;
      }

      .catatan h2 {
        font-size: 16px;
      }

      .catatan .catat td {
        font-size: 14px;
        padding: 6px 0;
      }
    }

    @media (max-width: 1100px) {
      .card {
        padding: 15px;
        width: 60%;
        font-size: 15px;
      }

      .card .total {
        font-size: 17px;
      }
    }

    @media (max-width: 768px) {
      .card {
        padding: 12px;
        width: 80%;
        font-size: 14px;
      }

      .card .total {
        font-size: 16px;
      }
    }

    @media (max-width: 534px) {
      .card {
        width: 90%;
        font-size: 14px;
      }

      .card .total {
        font-size: 15px;
      }
    }

    @media (max-width: 450px) {
      header {
        margin-bottom: 0;
      }

      main {
        margin: 0;
      }

      .card h1 {
        margin-bottom: 5px;
      }

      .card {
        width: 100%;
        padding: 0 1rem;
        border-radius: 10px;
        box-shadow: none;
        background: none;
      }

      .card h1 {
        font-size: 18px;
      }

      .pay-link {
        font-size: 14px;
        padding: 8px;
      }

      .catatan {
        margin-top: 10px;
        padding-top: 10px;
      }

      .catatan h2 {
        font-size: 14px;
        margin-bottom: 8px;
      }

      .catatan .catat td {
        font-size: 12px;
        padding: 6px 0;
      }

      .catatan .catat td:first-child {
        width: 10%;
      }

      .catatan .catat td:last-child {
        width: 90%;
      }
    }

    @media (max-width: 323px) {
      .card {
        padding: 0 6px;
        width: 95%;
        font-size: 12px;
      }

      .card .total {
        font-size: 14px;
      }
    }
  </style>
  <script type="module" src="scripts/index.js"></script>
</head>

<body>
  <header>
    <bar-user-app></bar-user-app>
  </header>

  <main>
    <ind-loading-main></ind-loading-main>
    <div class="card">
      <h1 id="home" tabindex="0">Detail Pemesanan</h1>
      <table class="cetak">
        <tr>
          <td tabindex="0">No. Kursi</td>
          <td tabindex="0"> : <?= !empty($kursi_list) ? implode(", ", $kursi_list) : '-' ?></td>
        </tr>
        <tr>
          <td tabindex="0">Nama</td>
          <td tabindex="0"> : <?= htmlspecialchars($data['nama_penumpang'] ?? '-') ?></td>
        </tr>
        <tr>
          <td tabindex="0">No HP</td>
          <td tabindex="0"> : <?= htmlspecialchars($data['no_wa'] ?? '-') ?></td>
        </tr>
        <tr>
          <td tabindex="0">Tiket</td>
          <td tabindex="0"> : <?= $jumlah_tiket ?? '-' ?></td>
        </tr>
        <tr>
          <td tabindex="0">Tujuan:</td>
          <td tabindex="0"> : <?= htmlspecialchars($data['kota_asal'] ?? '-') . ' - ' . htmlspecialchars($data['kota_tujuan'] ?? '-') ?></td>
        </tr>
        <tr>
          <td tabindex="0">Keberangkatan</td>
          <td tabindex="0"> : <?= htmlspecialchars($data['tgl_keberangkatan'] ?? '-') . ', ' . htmlspecialchars($data['jam_keberangkatan'] ?? '-') ?></td>
        </tr>
      </table>
      <p tabindex="0" class="total">Total: Rp <?= number_format($data['total'] ?? 0, 0, ',', '.') ?></p>
      <div class="catatan">
        <h2>Catatan Penting</h2>
        <table class="catat">
          <tbody>
            <tr>
              <td>1.</td>
              <td>Penumpang wajib datang 60 menit sebelum Keberangkatan</td>
            </tr>
            <tr>
              <td>2.</td>
              <td>Bagasi penumpang yang lebih dari 5kg akan dikenakan biaya tambahan</td>
            </tr>
            <tr>
              <td>3.</td>
              <td>Membawa kartu identitas asli</td>
            </tr>
          </tbody>
        </table>
      </div>
      <a tabindex="0" href="./midtrans/examples/snap/checkout-process-simple-version.php?id_pemesanan=<?= $id_pemesanan ?>"
        class="pay-link">Bayar</a>
    </div>
  </main>

</body>

</html>

<?php
$koneksi->close();
?>