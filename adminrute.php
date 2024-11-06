<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="adminjadwal.css">
</head>
<body>
    <!-- Menggunakan kelas sidebar yang sudah didefinisikan di CSS -->
    <header class="sidebar">
        <h1>Dashboard Admin</h1>
        <ul>
            <li><a href="#">Rute</a></li>
            <li><a href="adminjadwal.php">Jadwal</a></li>
            <li><a href="adminpesanan.php">Daftar Pesanan</a></li>
            <li><a href="#adminrekap.php">Rekap Pendapatan</a></li>
            <li><a href="fungsi/logout.php" class="logout">Logout</a></li>
        </ul> 
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
                                <td class='action-buttons'>
                                    <a href='edit_bus.php?id_bus={$row['id_bus']}'><button class='edit-button'>Edit</button></a>
                                    <button class='delete-button' onclick='confirmDelete({$row['id_bus']})'>Hapus</button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Data bus tidak dapat diambil. Error: " . mysqli_error($koneksi) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button class="add-schedule" onclick="location.href='tambah_bus.php'">Tambah Bus</button>
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
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $route_query = "SELECT tb_rute.id_rute, tb_rute.kota_asal, tb_rute.kota_tujuan, tb_jadwal.harga
                                    FROM tb_rute
                                    JOIN tb_jadwal ON tb_rute.id_rute = tb_jadwal.id_rute";
                    
                    $route_result = mysqli_query($koneksi, $route_query);
                    
                    if ($route_result) {
                        while ($row = mysqli_fetch_assoc($route_result)) {
                            echo "<tr>
                                <td>{$row['id_rute']}</td>
                                <td>{$row['kota_asal']}</td>
                                <td>{$row['kota_tujuan']}</td>
                                <td>{$row['harga']}</td>
                                <td class='action-buttons'>
                                    <button class='edit-button'>Edit</button>
                                    <button class='delete-button' onclick='confirmDelete({$row['id_rute']})'>Hapus</button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Data rute tidak dapat diambil. Error: " . mysqli_error($koneksi) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button class="add-schedule" onclick="location.href='tambah_rute.php'">Tambah Rute</button>
        </div>
    </main>

    <script>
        // Fungsi konfirmasi penghapusan
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus item ini?")) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
    </script>
</body>
</html>
