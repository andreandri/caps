<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiket User</title>

  <script type="module" src="scripts/index.js"></script>
  <link rel="stylesheet" href="pesantiket.css">
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
    // Mengimpor file koneksi
    include 'koneksi.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mengambil input dari form
        $asal = $_POST['asal'];
        $tujuan = $_POST['tujuan'];
        $tanggal = $_POST['date'];
    
        // Query untuk mencari jadwal sesuai input
        $sql = "SELECT jadwal.id_jadwal, rute.kota_asal, rute.kota_tujuan, 
                       jadwal.tanggal_keberangkatan, jadwal.waktu_keberangkatan, jadwal.harga_tiket 
                FROM jadwal 
                INNER JOIN rute ON jadwal.id_rute = rute.id_rute 
                WHERE rute.kota_asal = ? 
                  AND rute.kota_tujuan = ? 
                  AND jadwal.tanggal_keberangkatan = ?";
    
        // Siapkan statement
        $stmt = $koneksi->prepare($sql);
    
        if (!$stmt) {
            die("Query gagal dipersiapkan: " . $koneksi->error);
        }
    
        $stmt->bind_param("sss", $asal, $tujuan, $tanggal);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            echo "<div class='card-container'>";
            while ($row = $result->fetch_assoc()) {
                echo "<a href='tiketmasuk.php?id_jadwal={$row['id_jadwal']}' class='card'>
                        <div class='card-content'>
                            <h2>{$row['kota_asal']} - {$row['kota_tujuan']}</h2>
                            <p>Tanggal Keberangkatan : " . date("d F Y", strtotime($row['tanggal_keberangkatan'])) . "</p>
                            <p>Jam Keberangkatan : " . date("H.i", strtotime($row['waktu_keberangkatan'])) . " WIB</p>
                            <span class='harga'>IDR " . number_format($row['harga_tiket'], 0, ',', '.') . "</span>
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
