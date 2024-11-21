<?php
include("koneksi.php");
session_start();

// Ambil username pengguna dari sesi
$username = $_SESSION['username'];

// Ambil id_kursi dan id_busjadwal dari URL
$id_kursi = isset($_GET['id_kursi']) ? $_GET['id_kursi'] : 0;
$id_busjadwal = isset($_GET['id_busjadwal']) ? $_GET['id_busjadwal'] : 0;

// Ambil nomor kursi berdasarkan id_kursi
$nomor_kursi = '';
if ($id_kursi != 0) {
    $query_kursi = "SELECT nomor_kursi FROM tb_kursi WHERE id_kursi = '$id_kursi'";
    $result_kursi = mysqli_query($koneksi, $query_kursi);
    if ($result_kursi && mysqli_num_rows($result_kursi) > 0) {
        $row_kursi = mysqli_fetch_assoc($result_kursi);
        $nomor_kursi = $row_kursi['nomor_kursi'];
    }
}

// Cek jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_penumpang = $_POST['name'];
    $no_hp = $_POST['nomor'];
    $selectedSeats = $_POST['selectedSeats'];

    // Koneksi ke database
    include('koneksi.php');

    // Query untuk memasukkan data ke tabel tb_pemesanan
    $query = "INSERT INTO tb_pemesanan (username, id_busjadwal, nama_penumpang, no_wa, jumlah_tiket, total) 
              VALUES ('$username', '$id_busjadwal', '$nama_penumpang', '$no_hp', 1, 0)";

    if (mysqli_query($koneksi, $query)) {
        // Mendapatkan id_pemesanan yang baru saja dimasukkan
        $id_pemesanan = mysqli_insert_id($koneksi);

        // Query untuk memasukkan data ke tabel tb_pemesanan_kursi
        $query_kursi = "INSERT INTO tb_pemesanan_kursi (id_pemesanan, id_kursi, id_bus, nomor_kursi) 
                        VALUES ('$id_pemesanan', '$id_kursi', '$id_busjadwal', '$selectedSeats')";

        if (mysqli_query($koneksi, $query_kursi)) {
            // Redirect atau pesan sukses
            header('Location: cetak-tiket.php?id_pemesanan=' . $id_pemesanan);
            exit();
        } else {
            echo "Error: " . $query_kursi . "<br>" . mysqli_error($koneksi);
        }
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiket User</title>

  <script type="module" src="scripts/index.js"></script>
  
  <link rel="stylesheet" href="tiketmasuk.css">
</head>
<body>
  <header>
    <bar-app></bar-app>
  </header>

  <main>
    <h1>Pesan Kursi</h1>
    <form action="tiketmasuk.php?id_kursi=<?php echo $id_kursi; ?>&id_busjadwal=<?php echo $id_busjadwal; ?>" method="POST" class="form">
      <h2>Tiket: <?php echo $nomor_kursi; ?></h2> <!-- Menampilkan nomor kursi yang dipilih -->

      <input type="hidden" name="selectedSeats" value="<?php echo $id_kursi; ?>"> <!-- Menyimpan kursi yang dipilih ke dalam form -->

      <div class="label">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" placeholder="Masukkan Nama Anda" required>
      </div>

      <div class="label">
        <label for="nomor">No HP</label>
        <input type="number" id="nomor" name="nomor" placeholder="Masukkan Nomor Hp Anda" required>
      </div>

      <div class="search">
        <button type="submit" class="lanjut">Lanjut</button>
      </div>
    </form>
  </main>
</body>
</html>
