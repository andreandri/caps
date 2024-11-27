<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tiket User</title>
  <link rel="stylesheet" href="pesantiket-jadwal.css">
</head>
<body>
  <main>
    <div class="Kembali">
      <a href="tampilan.php">Back</a>
    </div>

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
                WHERE 1";

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
                            <h2>{$row['kota_asal']} - {$row['kota_tujuan']}</h2>
                            <p>Tanggal Keberangkatan: " . date("d F Y", strtotime($row['tgl_keberangkatan'])) . "</p>
                            <p>Jam Keberangkatan: " . date("H.i", strtotime($row['jam_keberangkatan'])) . " WIB</p>
                            <p>Nomor Plat Bus: {$row['no_plat']}</p>
                            <span class='harga'>IDR " . number_format($row['harga'], 0, ',', '.') . "</span>
                        </div>
                      </a>";
            }
            echo "</div>";
        } else {
            echo "<p style='color: red; text-align: center;'>Keberangkatan bus tidak ada</p>";
        }
        $koneksi->close();
    }
    ?>
  </main>
</body>
</html>
