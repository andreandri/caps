<?php
include("koneksi.php");

// Proses penyimpanan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_plat = $_POST['no_plat'];
    $nama_sopir = $_POST['nama_sopir'];
    $kapasitas = $_POST['kapasitas'];

    // Validasi input
    if (!empty($no_plat) && !empty($nama_sopir) && !empty($kapasitas)) {
        // Query untuk menyimpan data ke tabel tb_bus
        $sql = "INSERT INTO tb_bus (no_plat, nama_sopir, kapasitas) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssi", $no_plat, $nama_sopir, $kapasitas);

        if ($stmt->execute()) {
            echo "<script>alert('Data bus berhasil ditambahkan.'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Harap isi semua field.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bus</title>

    <link rel="stylesheet" href="adminjadwal.css">
</head>
<body>
  <header class="dashboard">
    <div class="navbar">
      <h1>Dashboard Admin</h1>
        <ul class="menu">
          <li><a href="adminrute.php">Rute</a></li>
          <li><a href="adminjadwal.php">Jadwal</a></li>
          <li><a href="adminpesanan.php">Daftar Pesanan</a></li>
          <li><a href="adminrekap.php">Rekap Pendapatan</a></li>
        </ul> 
      <img src="img/EasyBusTix.png" alt=""> 
    </div>
    <div>
      <a href="fungsi/logout.php" class="logout">Logout</a>
    </div>
  </header>
    
  <main class="add">
    <h1>Tambah Bus Baru</h1>
        <form action="tambah_bus.php" method="POST">
            <label for="no_plat">No. Plat</label>
            <input type="text" id="no_plat" name="no_plat" placeholder="Masukan Nomor Kendaraan Bus" required>

            <label for="nama_sopir">Nama Sopir</label>
            <input type="text" id="nama_sopir" name="nama_sopir" placeholder="Masukan Nama Sopir" required>

            <label for="kapasitas">Kapasitas</label>
            <input type="number" id="kapasitas" name="kapasitas" placeholder="Masukan Kapasitas Bus" required>

            <div>
                <button type="button" onclick="window.location.href='tambah_bus.php';">Kembali</button>
                <button type="submit">Tambah Bus</button>
            </div>
        </form>
    </main>
</body>
</html>
