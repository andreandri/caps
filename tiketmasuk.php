<?php
$selectedSeats = isset($_GET['seats']) ? htmlspecialchars($_GET['seats']) : 'Tidak ada kursi yang dipilih';
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
    <form action="cetak-tiket.php" method="POST" class="form">
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
