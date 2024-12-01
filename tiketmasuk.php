<?php
include("koneksi.php");
session_start();

$username = $_SESSION['username'];
$id_busjadwal = $_GET['id_busjadwal'] ?? $_POST['id_busjadwal'];

$kursi = $_SESSION['kursi_terpilih'] ?? [];

if (empty($kursi)) {
    header("Location: pilihkursi.php?id_busjadwal=$id_busjadwal");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['nomor'])) {
        $nama_penumpang = $_POST['name'];
        $no_hp = $_POST['nomor'];
        $jumlah_tiket = count($kursi);

        $query_harga = $koneksi->prepare("SELECT harga FROM tb_jadwal WHERE id_jadwal = ?");
        $query_harga->bind_param("i", $id_busjadwal);
        $query_harga->execute();
        $result_harga = $query_harga->get_result();
        $row_harga = $result_harga->fetch_assoc();
        $harga_tiket = $row_harga['harga'];
        $total = $harga_tiket * $jumlah_tiket;

        $koneksi->begin_transaction();

        try {
          $query = $koneksi->prepare("INSERT INTO tb_pemesanan (username, id_busjadwal, nama_penumpang, no_wa, jumlah_tiket, total) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
          $query->bind_param("sissii", $username, $id_busjadwal, $nama_penumpang, $no_hp, $jumlah_tiket, $total);
          if ($query->execute()) {
              $id_pemesanan = $koneksi->insert_id;
              $query_kursi = $koneksi->prepare("INSERT INTO tb_pemesanan_kursi (id_pemesanan, id_kursi) VALUES (?, ?)");

              foreach ($kursi as $k) {
                  $query_kursi->bind_param("ii", $id_pemesanan, $k);
                  $query_kursi->execute();

                  // Update status kursi menjadi 'booked'
                  $query_update = $koneksi->prepare("UPDATE tb_kursi SET status = 'booked' WHERE id_kursi = ?");
                  $query_update->bind_param("i", $k);
                  $query_update->execute();
              }

              $koneksi->commit();
              header('Location: cetak-tiket.php?id_pemesanan=' . $id_pemesanan);
              exit();
          } else {
              $koneksi->rollback();
              echo "Error: " . $query->error;
          }
      } catch (Exception $e) {
          $koneksi->rollback();
          echo "Error: " . $e->getMessage();
      }
  } else {
      echo "Nama dan nomor WA harus diisi!";
  }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemesanan Tiket</title>
  <script type="module" src="scripts/index.js"></script>
  <link rel="stylesheet" href="tiketmasuk.css">
</head>
<body>
  <a href="#home" class="skip-link">Skip To Content</a>
  <header>
    <bar-user-app></bar-user-app>
  </header>

  <main id="home">
    <h1 tabindex="0">Pesan Kursi</h1>
    
    <form action="tiketmasuk.php" method="POST" class="form">
      <h2 tabindex="0">Informasi Penumpang</h2>
      
      <!-- Input hidden untuk menyimpan id_busjadwal dan kursi yang dipilih -->
      <input tabindex="0" type="hidden" name="id_busjadwal" value="<?php echo htmlspecialchars($id_busjadwal); ?>">
      <input tabindex="0" type="hidden" name="kursi[]" value="<?php echo implode(',', $kursi); ?>"> <!-- Menyimpan kursi yang dipilih dalam form -->

      <div class="label">
        <label tabindex="0" for="name">Nama</label>
        <input tabindex="0" type="text" id="name" name="name" placeholder="Masukkan Nama Anda" required>
      </div>

      <div class="label">
        <label tabindex="0" for="nomor">No HP</label>
        <input tabindex="0" type="number" id="nomor" name="nomor" placeholder="Masukkan Nomor Hp Anda" required>
      </div>

      <!-- Menampilkan kursi yang dipilih -->
      <h3 tabindex="0">Nomor Kursi yang Dipilih:</h3>
      <ul tabindex="0">
        <?php
          if (!empty($kursi)) {
            foreach ($kursi as $k) {
              // Mengambil nomor kursi berdasarkan ID kursi yang dipilih
              $sql_kursi = $koneksi->prepare("SELECT nomor_kursi FROM tb_kursi WHERE id_kursi = ?");
              $sql_kursi->bind_param("i", $k);
              $sql_kursi->execute();
              $result_kursi = $sql_kursi->get_result();
              $row_kursi = $result_kursi->fetch_assoc();
              echo "<li>Kursi {$row_kursi['nomor_kursi']}</li>";
            }
          } else {
            echo "<p>Belum ada kursi yang dipilih.</p>";
          }
        ?>
      </ul>

      <h3 tabindex="0">Total Kursi yang Dipilih: <?php echo count($kursi); ?></h3>

      <div class="search">
        <button tabindex="0" type="submit" class="lanjut">Lanjutkan</button>
      </div>
    </form>
  </main>
</body>
</html>