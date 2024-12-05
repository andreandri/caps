<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiket User</title>
  <link rel="stylesheet" href="pesantiket-jadwal.css">
  <link rel="icon" href="favicon.png" type="image/png">
  <script type="module" src="scripts/index.js"></script>
</head>

<body>
  <header>
    <bar-tiket-app></bar-tiket-app>
  </header>

  <main class="card-conta">
    <ind-loading-main></ind-loading-main>
    <form action="pesantiket.php" method="POST">
      <div class="opsi">
        <div class="form-asal">
          <label tabindex="0" for="asal">Form/Asal</label>
          <select id="asal" name="asal">
            <option tabindex="0" value="">Pilih Kota</option>
            <option tabindex="0" value="Palangka Raya">Palangka Raya</option>
            <option tabindex="0" value="Sampit">Sampit</option>
            <option tabindex="0" value="Pangkalan Bun">Pangkalan Bun</option>
          </select>
        </div>

        <div class="to-tujuan">
          <label tabindex="0" for="tujuan">To/Tujuan</label>
          <select id="tujuan" name="tujuan">
            <option tabindex="0" value="">Pilih Kota</option>
            <option tabindex="0" value="Palangka Raya">Palangka Raya</option>
            <option tabindex="0" value="Sampit">Sampit</option>
            <option tabindex="0" value="Pangkalan Bun">Pangkalan Bun</option>
          </select>
        </div>
      </div>

      <div class="opsiopsi">
        <div class="date">
          <label tabindex="0" for="date">Pilih Tanggal</label>
          <input tabindex="0" type="date" id="date" name="date">
        </div>

        <div class="search">
          <button tabindex="0" type="submit" name="search">Search</button>
        </div>
      </div>
    </form>

    <?php
    include("koneksi.php");
    session_start();
    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
      $asal = $_POST['asal'];
      $tujuan = $_POST['tujuan'];
      $tanggal = $_POST['date'];

      $sql = "SELECT r.kota_asal, r.kota_tujuan, j.id_jadwal, j.tgl_keberangkatan, 
                    j.jam_keberangkatan, b.no_plat, b.id_bus, j.harga
                FROM tb_busjadwal bj
                JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal
                JOIN tb_bus b ON bj.id_bus = b.id_bus
                JOIN tb_rute r ON j.id_rute = r.id_rute
                WHERE CONCAT(j.tgl_keberangkatan, ' ', j.jam_keberangkatan) >= NOW()";


      $sql .= " AND CONCAT(j.tgl_keberangkatan, ' ', j.jam_keberangkatan) >= NOW()";

      if (!empty($asal)) {
        $sql .= " AND r.kota_asal = '$asal'";
      }
      if (!empty($tujuan)) {
        $sql .= " AND r.kota_tujuan = '$tujuan'";
      }
      if (!empty($tanggal)) {
        $sql .= " AND j.tgl_keberangkatan = '$tanggal'";
      }


      $result = $koneksi->query($sql);
      if ($result->num_rows > 0) {
        echo "<div class='card-container'>";
        while ($row = $result->fetch_assoc()) {
          $id_jadwal = $row['id_jadwal'];
          $id_bus = $row['id_bus'];
          echo "<a href='pilihkursi.php?id_busjadwal={$id_jadwal}' class='card'>
                        <div class='card-content'>
                            <h3>{$row['kota_asal']} - {$row['kota_tujuan']}</h2>
                            <p>Tanggal Keberangkatan : " . date("d F Y", strtotime($row['tgl_keberangkatan'])) . "</p>
                            <p>Jam Keberangkatan : " . date("H.i", strtotime($row['jam_keberangkatan'])) . " WIB</p>
                            <p>Nomor Plat Bus: {$row['no_plat']}</p>
                            <span class='harga'>IDR " . number_format($row['harga'], 0, ',', '.') . "</span>
                        </div>
                      </a>";
        }
        echo "</div>";
      } else {
        echo "<p style='color: red; text-align: center; padding: 2rem; margin: 1rem 0; font-weight: bold;'>Keberangkatan bus tidak ada</p>";
      }
      $koneksi->close();
    }
    ?>
    <section class="map">
      <div class="map-item">
        <h3 tabindex="0">Lokasi Terminal Bus di Palangka Raya</h3>
        <iframe tabindex="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.680872302818!2d113.90657089999999!3d-2.2723635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de34d716132e6d3%3A0x17a6c002897a84df!2sterminal%20bus%20palangka%20raya!5e0!3m2!1sid!2sid!4v1732897767395!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="map-item">
        <h3 tabindex="0">Lokasi Terminal Bus di Sampit</h3>
        <iframe tabindex="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.8956154606135!2d112.95063321085388!3d-2.54091113827444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de2be354be62cad%3A0x92223b4fa28b8a80!2sTerminal%20Bus%20Sampit!5e0!3m2!1sid!2sid!4v1732898127391!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="map-item">
        <h3 tabindex="0">Lokasi Terminal Bus di Pangkalan Bun</h3>
        <iframe tabindex="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.4828236423396!2d111.65357417420705!3d-2.671282597306318!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e08ee1996460431%3A0xd6700ff9c0470fff!2sTerminal%20Natai%20Suka!5e0!3m2!1sid!2sid!4v1732898073660!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </section>
  </main>
</body>

</html>