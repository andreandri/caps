<?php
 include"koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Keberangkatan</title>

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

    <!-- Container -->
    <div class="container">
        <h2>Jadwal Keberangkatan</h2>
        
        <div class="schedule">
            <?php
            // Query untuk mengambil data jadwal
            $sql = "
                SELECT 
                    r.kota_asal, 
                    r.kota_tujuan, 
                    j.waktu_keberangkatan, 
                    j.harga_tiket
                FROM 
                    jadwal j
                JOIN 
                    rute r ON j.id_rute = r.id_rute
                ORDER BY 
                    j.waktu_keberangkatan
            ";

            $result = $koneksi->query($sql);

            // Loop untuk menampilkan data dari hasil query
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="schedule-item">';
                    echo '<div>';
                    echo '<h3>' . $row['kota_asal'] . ' - ' . $row['kota_tujuan'] . '</h3>';
                    echo '<p>Jam Keberangkatan : ' . date("H.i", strtotime($row['waktu_keberangkatan'])) . ' WIB</p>';
                    echo '</div>';
                    echo '<div class="price">IDR ' . number_format($row['harga_tiket'], 0, ',', '.') . '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Tidak ada jadwal keberangkatan tersedia.</p>";
            }

            // Menutup koneksi
            $koneksi->close();
            ?>
        </div>
    </div>
  </main> 

</body>
</html>
