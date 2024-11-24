<?php
include("koneksi.php");
session_start();
// Ambil username pengguna dari sesi
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Ambil id_jadwal yang dipilih
$id_kursi = isset($_GET['id_kursi']) ? $_GET['id_kursi'] : null;
$id_busjadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : 0;

// Inisialisasi variabel $selectedSeats untuk menghindari error
$selectedSeats = isset($_POST['selectedSeats']) ? $_POST['selectedSeats'] : '';

$nomor_kursi = '';
if ($id_kursi) {
  $query_kursi = "SELECT nomor_kursi FROM tb_kursi WHERE id_kursi = '$id_kursi'";
  $result_kursi = $koneksi->query($query_kursi);

  if ($result_kursi->num_rows > 0) {
      $row = $result_kursi->fetch_assoc();
      $nomor_kursi = $row['nomor_kursi'];
  }
}
// Cek jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_penumpang = $_POST['name'];
    $no_hp = $_POST['nomor'];

    // Query untuk memasukkan data ke tabel tb_pemesanan
    $query = "INSERT INTO tb_pemesanan (username, id_busjadwal, nama_penumpang, no_wa) 
              VALUES ('$username', '$id_busjadwal', '$nama_penumpang', '$no_hp')";

    if ($koneksi->query($query)) {
      header('Location: cetak-tiket.php');
      exit();
    } else {
      echo "Error: " . $query . "<br>" . $koneksi->error;
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
      <h2>Kursi: <?php echo htmlspecialchars($nomor_kursi); ?></h2> <!-- Menampilkan nomor kursi -->

      <input type="hidden" name="selectedSeats" value="<?php echo htmlspecialchars($selectedSeats); ?>"> <!-- Menyimpan kursi yang dipilih ke dalam form -->

      <div class="label">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" placeholder="Masukkan Nama Anda" required>
      </div>

      <div class="label">
        <label for="nomor">No HP</label>
        <input type="number" id="nomor" name="nomor" placeholder="Masukkan Nomor Hp Anda" required>
      </div>

      <div class="search">
        <button type="submit" class="lanjut">Lanjut</button> <!-- Mengubah button menjadi submit -->
      </div>
    </form>
  </main>
</body>
</html>
