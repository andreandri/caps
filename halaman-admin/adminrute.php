<?php
// Menghubungkan ke database
include("../koneksi.php");

// Logika untuk Hapus Bus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bus_id']) && !empty($_POST['delete_bus_id'])) {
    $id_bus = $_POST['delete_bus_id'];

    $delete_query = "DELETE FROM tb_bus WHERE id_bus = ?";
    $stmt = $koneksi->prepare($delete_query);
    $stmt->bind_param("i", $id_bus);

    if ($stmt->execute()) {
        header("Location: adminrute.php");
        exit;
    } else {
        header("Location: adminrute.php");
        exit;
    }

    $stmt->close();
}

// Logika untuk Hapus Rute
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_route_id']) && !empty($_POST['delete_route_id'])) {
    $id_rute = $_POST['delete_route_id'];

    $delete_query = "DELETE FROM tb_rute WHERE id_rute = ?";
    $stmt = $koneksi->prepare($delete_query);
    $stmt->bind_param("i", $id_rute);

    if ($stmt->execute()) {
        header("Location: adminrute.php");
        exit;
    } else {
        header("Location: adminrute.php");
        exit;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/adminjadwal.css">

    <style>
        /* Style untuk Pop-up */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .popup button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .popup button:hover {
            background-color: #45a049;
        }
        .popup-danger button {
            background-color: #f44336;
        }
        .popup-danger button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

<header class="dashboard">
    <div class="navbar">
        <h1 tabindex="0">Dashboard Admin</h1>
        <ul class="menu">
            <li><a tabindex="0" href="adminrute.php" style="background-color: #C8ACD6;">Rute</a></li>
            <li><a tabindex="0" href="adminjadwal.php">Jadwal</a></li>
            <li><a tabindex="0" href="adminpesanan.php">Daftar Pesanan</a></li>
            <li><a tabindex="0" href="adminrekap.php">Rekap Pendapatan</a></li>
        </ul>
        <img tabindex="0" src="../img/EasyBusTix.png" alt="Logo EasyBusTix">
    </div>
    <div>
        <a tabindex="0" href="../fungsi/logout.php" class="logout">Logout</a>
    </div>
</header>

<main class="main-content">
    <!-- Tabel Bus -->
    <div>
        <h1 tabindex="0">Data Bus</h1>
        <table>
            <thead>
                <tr>
                    <th tabindex="0">Id Bus</th>
                    <th tabindex="0">No Plat</th>
                    <th tabindex="0">Nama Sopir</th>
                    <th tabindex="0">Kapasitas</th>
                    <th tabindex="0">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $bus_query = "SELECT * FROM tb_bus";
            $bus_result = mysqli_query($koneksi, $bus_query);

            if ($bus_result) {
                while ($row = mysqli_fetch_assoc($bus_result)) { ?>
                    <tr>
                        <td tabindex="0"><?= $row['id_bus']; ?></td>
                        <td tabindex="0"><?= $row['no_plat']; ?></td>
                        <td tabindex="0"><?= $row['nama_sopir']; ?></td>
                        <td tabindex="0"><?= $row['kapasitas']; ?></td>
                        <td tabindex="0">
                            <a tabindex="0" href="edit_bus.php?id_bus=<?= $row['id_bus']; ?>"><button class="edit">Edit</button></a>
                            <button tabindex="0" class="hapus" onclick="showDeletePopup('bus', <?= $row['id_bus']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='5'>Data bus tidak dapat diambil. Error: " . mysqli_error($koneksi) . "</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <a tabindex="0" href="tambah_bus.php" class="tambah-rute">Tambah Bus</a>
    </div>

    <!-- Tabel Rute -->
    <div class="table-section">
        <h1 tabindex="0">Data Rute</h1>
        <table>
            <thead>
                <tr>
                    <th tabindex="0">Id Rute</th>
                    <th tabindex="0">Asal</th>
                    <th tabindex="0">Tujuan</th>
                    <th tabindex="0">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $route_query = "SELECT * FROM tb_rute";
            $route_result = mysqli_query($koneksi, $route_query);

            if ($route_result) {
                while ($row = mysqli_fetch_assoc($route_result)) { ?>
                    <tr>
                        <td tabindex="0"><?= $row['id_rute']; ?></td>
                        <td tabindex="0"><?= $row['kota_asal']; ?></td>
                        <td tabindex="0"><?= $row['kota_tujuan']; ?></td>
                        <td tabindex="0">
                            <a tabindex="0" href="edit_rute.php?id_rute=<?= $row['id_rute']; ?>"><button class="edit">Edit</button></a>
                            <button tabindex="0" class="hapus" onclick="showDeletePopup('route', <?= $row['id_rute']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='4'>Data rute tidak dapat diambil. Error: " . mysqli_error($koneksi) . "</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <a tabindex="0" href="tambah_rute.php" class="tambah-rute">Tambah Rute</a>
    </div>
</main>

<!-- Pop-up konfirmasi hapus -->
<div id="popup-delete" class="popup">
    <div class="popup-content popup-danger">
        <h3 tabindex="0" id="popup-message">Apakah Anda yakin ingin menghapus?</h3>
        <form method="POST" id="delete-form" style="display: inline;">
            <!-- Input untuk ID Bus atau Rute -->
            <input tabindex="0" type="hidden" name="delete_bus_id" id="delete_bus_id" value="">
            <input tabindex="0" type="hidden" name="delete_route_id" id="delete_route_id" value="">
            <button tabindex="0" type="submit" id="confirm-delete">Ya, Hapus</button>
        </form>
        <button tabindex="0" onclick="closePopup()">Tidak, Kembali</button>
    </div>
</div>

<script>
    // Menampilkan pop-up konfirmasi hapus
    function showDeletePopup(type, id) {
        // Set ID yang sesuai berdasarkan tipe
        if (type === 'bus') {
            document.getElementById('delete_bus_id').value = id;
            document.getElementById('delete_route_id').value = ''; // Kosongkan id_rute
            document.getElementById('popup-message').innerText = 'Apakah Anda yakin ingin menghapus bus ini?';
        } else if (type === 'route') {
            document.getElementById('delete_route_id').value = id;
            document.getElementById('delete_bus_id').value = ''; // Kosongkan id_bus
            document.getElementById('popup-message').innerText = 'Apakah Anda yakin ingin menghapus rute ini?';
        }
        document.getElementById('popup-delete').style.display = 'flex';
    }

    // Menutup pop-up
    function closePopup() {
        document.getElementById('popup-delete').style.display = 'none';
    }
</script>

</body>
</html>
