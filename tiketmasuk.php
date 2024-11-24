<?php
include("koneksi.php");
session_start();
// Ambil username pengguna dari sesi
$username = $_SESSION['username'];

// Ambil id_jadwal yang dipilih
$id_busjadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : 0;

// Cek jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_penumpang = $_POST['name'];
    $no_hp = $_POST['nomor'];
    $selectedSeats = $_POST['selectedSeats'];

    // Koneksi ke database
    include('koneksi.php');

    // Query untuk memasukkan data ke tabel tb_pemesanan
    $query = "INSERT INTO tb_pemesanan (username, id_busjadwal, nama_penumpang, no_wa) 
              VALUES ('$username', '$id_busjadwal', '$nama_penumpang', '$no_hp')";

    if (mysqli_query($koneksi, $query)) {
            // Redirect atau pesan sukses
        header('Location: cetak-tiket.php');
    } else {
        // Pesan error jika gagal memasukkan data
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
    <form action="tiketmasuk.php?id_jadwal=<?php echo $id_busjadwal; ?>" method="POST" class="form">
      <h2>Tiket: <?php echo $selectedSeats; ?></h2> <!-- Menampilkan kursi yang dipilih -->

      <input type="hidden" name="selectedSeats" value="<?php echo $selectedSeats; ?>"> <!-- Menyimpan kursi yang dipilih ke dalam form -->

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
