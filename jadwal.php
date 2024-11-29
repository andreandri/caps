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

    <section class="map">
      <div class="map-item">
        <h3>Lokasi Terminal Bus di Palangka Raya</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.680872302818!2d113.90657089999999!3d-2.2723635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de34d716132e6d3%3A0x17a6c002897a84df!2sterminal%20bus%20palangka%20raya!5e0!3m2!1sid!2sid!4v1732897767395!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="map-item">
        <h3>Lokasi Terminal Bus di Sampit</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.8956154606135!2d112.95063321085388!3d-2.54091113827444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2be354be62cad%3A0x92223b4fa28b8a80!2sTerminal%20Bus%20Sampit!5e0!3m2!1sid!2sid!4v1732898127391!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="map-item">
        <h3>Lokasi Terminal Bus di Pangkalan Bun</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.4828236423396!2d111.65357417420705!3d-2.671282597306318!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e08ee1996460431%3A0xd6700ff9c0470fff!2sTerminal%20Natai%20Suka!5e0!3m2!1sid!2sid!4v1732898073660!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </section>
</body>
</html>
