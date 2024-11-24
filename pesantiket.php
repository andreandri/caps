<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiket User</title>

  <script type="module" src="scripts/index.js"></script>
  <link rel="stylesheet" href="pesantiket-jadwal.css">
</head>
<body>
  <header>
    <bar-app></bar-app>
  </header>
  
  <main>
    <div class="Kembali">
      <a href="tampilan.php">Kembali</a>
    </div>

    <!-- Form Pencarian Jadwal -->
    <!-- Form Pencarian Jadwal -->
<form action="pesantiket.php" method="POST">
    <div class="opsi">
        <div class="form-asal">
            <label for="asal">Form/Asal</label>
            <select id="asal" name="asal">
                <option value="">Pilih Kota</option>
                <option value="Palangka Raya">Palangka Raya</option>
                <option value="Sampit">Sampit</option>
                <option value="Pangkalan Bun">Pangkalan Bun</option>
            </select>
        </div>

        <div class="to-tujuan">
            <label for="tujuan">To/Tujuan</label>
            <select id="tujuan" name="tujuan">
                <option value="">Pilih Kota</option>
                <option value="Palangka Raya">Palangka Raya</option>
                <option value="Sampit">Sampit</option>
                <option value="Pangkalan Bun">Pangkalan Bun</option>
            </select>
        </div>
    </div>

    <div class="opsiopsi">
        <div class="date">
            <label for="date">Pilih Tanggal</label>
            <input type="date" id="date" name="date">
        </div>

        <div class="search">
            <button type="submit" name="search">Search</button>
        </div>
    </div>
</form>

<?php
include ("koneksi.php");
session_start();
$username = $_SESSION['username'];
// Handle the form submission and display the available schedules
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Get the search parameters
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $tanggal = $_POST['date'];

    // Query to find schedules
    $sql = "SELECT tb_jadwal.id_jadwal, tb_rute.kota_asal, tb_rute.kota_tujuan, 
                   tb_jadwal.tgl_keberangkatan, tb_jadwal.jam_keberangkatan, tb_jadwal.harga 
            FROM tb_jadwal
            INNER JOIN tb_rute ON tb_jadwal.id_rute = tb_rute.id_rute 
            WHERE tb_rute.kota_asal = ? 
              AND tb_rute.kota_tujuan = ? 
              AND tb_jadwal.tgl_keberangkatan = ?";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sss", $asal, $tujuan, $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='card-container'>";
        while ($row = $result->fetch_assoc()) {
            $id_jadwal = $row['id_jadwal'];  // Get the schedule ID
            echo "<a href='pilihkursi.php?id_jadwal={$id_jadwal}' class='card'>
                    <div class='card-content'>
                        <h2>{$row['kota_asal']} - {$row['kota_tujuan']}</h2>
                        <p>Tanggal Keberangkatan : " . date("d F Y", strtotime($row['tgl_keberangkatan'])) . "</p>
                        <p>Jam Keberangkatan : " . date("H.i", strtotime($row['jam_keberangkatan'])) . " WIB</p>
                        <span class='harga'>IDR " . number_format($row['harga'], 0, ',', '.') . "</span>
                    </div>
                  </a>";
        }
        echo "</div>";
    } else {
        echo "<p style='color: red; text-align: center;'>Keberangkatan bus tidak ada</p>";
    }

    $stmt->close();
}
?>

      </main>
</body>
</html>