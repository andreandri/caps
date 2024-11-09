<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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

    <main class="main-content">
        <!-- Tabel Bus -->
        <div class="table-section">
            <h2>Bus</h2>
            <table>
                <thead>
                    <tr>
                        <th>Id Bus</th>
                        <th>No Plat</th>
                        <th>Nama Sopir</th>
                        <th>Kapasitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menghubungkan ke database
                include("koneksi.php");
            $bus_query = "SELECT * FROM tb_bus";
            $bus_result = mysqli_query($koneksi, $bus_query);

            if ($bus_result) {
                while ($row = mysqli_fetch_assoc($bus_result)) {
                    echo "<tr>
                        <td>{$row['id_bus']}</td>
                        <td>{$row['no_plat']}</td>
                        <td>{$row['nama_sopir']}</td>
                        <td>{$row['kapasitas']}</td>
                        <td>
                            <a href='edit_bus.php?id_bus={$row['id_bus']}'><button class='edit'>Edit</button></a>
                            <button class='hapus'>Hapus</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Data bus tidak dapat diambil. Error: " . mysqli_error($koneksi) . "</td></tr>";
            }
            ?>
      
                </tbody>
            </table>
            <button class="tambah-bus">Tambah Bus</button>
        </div>

        <!-- Tabel Rute -->
        <div class="table-section">
    <h2>Rute</h2>
    <table>
        <thead>
            <tr>
                <th>Id Rute</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

            <?php
            include("koneksi.php");

            // Periksa apakah ada penghapusan rute
            if (isset($_GET['id_rute'])) {
                $id_rute = $_GET['id_rute'];

                // Query untuk menghapus rute berdasarkan id
                $delete_query = "DELETE FROM tb_rute WHERE id_rute = ?";
                $stmt = $koneksi->prepare($delete_query);
                $stmt->bind_param("i", $id_rute);

                if ($stmt->execute()) {
                    echo "<script>alert('Rute berhasil dihapus.'); window.location.href = 'adminrute.php';</script>";
                } else {
                    echo "<script>alert('Gagal menghapus rute.'); window.location.href = 'adminrute.php';</script>";
                }

                $stmt->close();
            }

            // Ambil data rute
            $route_query = "SELECT * FROM tb_rute";
            $route_result = mysqli_query($koneksi, $route_query);

            if ($route_result) {
                while ($row = mysqli_fetch_assoc($route_result)) {
                    echo "<tr>
                        <td>{$row['id_rute']}</td>
                        <td>{$row['kota_asal']}</td>
                        <td>{$row['kota_tujuan']}</td>
                        <td>
                            <a href='edit_rute.php?id_rute={$row['id_rute']}'><button class='edit'>Edit</button></a>
                            <a href='?id_rute={$row['id_rute']}' onclick='return confirm(\"Apakah Anda yakin ingin menghapus rute ini?\");'><button class='hapus'>Hapus</button></a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Data rute tidak dapat diambil. Error: " . mysqli_error($koneksi) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="tambah_rute.php" class="tambah-rute"><button>Tambah Rute</button></a>
</div>

    </div>
</body>
</html>
