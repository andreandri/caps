<?php
 include"koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Keberangkatan</title>

  <link rel="stylesheet" href="pesantiket-jadwal.css">
</head>
<body>
    <section class="Kembali">
      <a href="tampilan.php">Back</a>
    </section>

    <!-- Container -->
    <section class="jadwal">
    <h2>Jadwal Keberangkatan</h2>
    <div class="container">
        <div class="schedule-grid">
            <?php
            // Query untuk mengambil data jadwal
            $sql = "
                SELECT 
                    r.kota_asal, 
                    r.kota_tujuan, 
                    j.tgl_keberangkatan,
                    j.jam_keberangkatan,
                    j.harga
                FROM 
                    tb_jadwal j
                JOIN 
                    tb_rute r ON j.id_rute = r.id_rute
                WHERE 
                    j.status_jadwal = 'aktif'
                ORDER BY 
                    j.tgl_keberangkatan, j.jam_keberangkatan
            ";



            $result = $koneksi->query($sql);

            // Loop untuk menampilkan data dari hasil query
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="schedule-item">';
                    echo '<h3>' . $row['kota_asal'] . ' - ' . $row['kota_tujuan'] . '</h3>';
                    echo '<p>Tanggal Keberangkatan: ' . date("d-m-Y", strtotime($row['tgl_keberangkatan'])) . '</p>';
                    echo '<p>Jam Keberangkatan: ' . date("H.i", strtotime($row['jam_keberangkatan'])) . ' WIB</p>';
                    echo '<div class="price">IDR ' . number_format($row['harga'], 0, ',', '.') . '</div>';
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
    </section>
</body>
</html>
