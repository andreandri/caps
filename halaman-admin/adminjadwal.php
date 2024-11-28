<?php
// Include the database connection
require '../koneksi.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $deleteQuery = "DELETE FROM tb_jadwal WHERE id_jadwal = ?";
    $stmt = $koneksi->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        // Redirect to the same page after delete without showing alert
        header("Location: adminjadwal.php");
        exit;
    } else {
        echo "<p>Error: " . $koneksi->error . "</p>";
    }
}

$query = "SELECT j.id_jadwal, r.kota_asal, r.kota_tujuan, j.tgl_keberangkatan, j.jam_keberangkatan, j.harga, j.status_jadwal
          FROM tb_jadwal j
          JOIN tb_rute r ON j.id_rute = r.id_rute
          LEFT JOIN tb_busjadwal bj ON bj.id_jadwal = j.id_jadwal";

$result = $koneksi->query($query);

if (!$result) {
    die("Query Error: " . $koneksi->error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bus_schedule_id']) && !empty($_POST['delete_bus_schedule_id'])) {
    $id_busjadwal = $_POST['delete_bus_schedule_id'];

    $delete_query = "DELETE FROM tb_busjadwal WHERE id_busjadwal = ?";
    $stmt = $koneksi->prepare($delete_query);
    $stmt->bind_param("i", $id_busjadwal);

    if ($stmt->execute()) {
        header("Location: adminjadwal.php");
        exit;
    } else {
        header("Location: adminjadwal.php?error=delete_failed");
        exit;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="styles/adminjadwal.css">
    <style>
        /* Style for pop-up confirmation */
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
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .popup button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
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
        <h1>Dashboard Admin</h1>
        <ul class="menu">
            <li><a href="adminrute.php">Rute</a></li>
            <li><a href="adminjadwal.php" style="background-color: #C8ACD6;">Jadwal</a></li>
            <li><a href="adminpesanan.php">Daftar Pesanan</a></li>
            <li><a href="adminrekap.php">Rekap Pendapatan</a></li>
        </ul> 
        <img src="img/EasyBusTix.png" alt=""> 
    </div>
    <div>
        <a href="../fungsi/logout.php" class="logout">Logout</a>
    </div>
</header>
    
<main class="main-content">
    <div>
        <h1>Data Jadwal</h1>
        <table>
            <thead>
                <tr>
                    <th>Id Jadwal</th>
                    <th>Kota Asal</th>
                    <th>Kota Tujuan</th>
                    <th>Tanggal Keberangkatan</th>
                    <th>Jam Keberangkatan</th>
                    <th>Harga</th>
                    <th>Status Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id_jadwal']; ?></td>
                        <td><?= $row['kota_asal']; ?></td>
                        <td><?= $row['kota_tujuan']; ?></td>
                        <td><?= $row['tgl_keberangkatan']; ?></td>
                        <td><?= $row['jam_keberangkatan']; ?></td>
                        <td><?= $row['harga']; ?></td>
                        <td>
    <?php if ($row['status_jadwal'] == 'aktif') { ?>
        <span style="color: green; font-weight: bold;">Aktif</span>
    <?php } else { ?>
        <span style="color: red; font-weight: bold;">Tidak Aktif</span>
    <?php } ?>
</td>
                        <td class="action-buttons">
                            <a href="editjadwal.php?id_jadwal=<?= $row['id_jadwal']; ?>" class="edit-button">Edit</a>
                            <button class="delete-button" onclick="showDeletePopup(<?= $row['id_jadwal']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="tambahjadwal.php" class="tambah-rute">Tambah Jadwal</a>
    </div>

    <div>
        <h1>Data Bus Jadwal</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Id Bus Jadwal</th>
                    <th>Id Bus</th>
                    <th>Id Jadwal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $bus_schedule_query = "SELECT * FROM tb_busjadwal";
            $bus_schedule_result = mysqli_query($koneksi, $bus_schedule_query);

            if ($bus_schedule_result) {
                while ($row = mysqli_fetch_assoc($bus_schedule_result)) { ?>
                    <tr>
                        <td><?= $row['id_busjadwal']; ?></td>
                        <td><?= $row['id_bus']; ?></td>
                        <td><?= $row['id_jadwal']; ?></td>
                        <td>
                            <button onclick="showDeletePopup('bus_schedule', <?= $row['id_busjadwal']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Pop-up konfirmasi hapus -->
<div id="popup-delete" class="popup">
    <div class="popup-content popup-danger">
        <h3>Apakah Anda yakin ingin menghapus jadwal ini?</h3>
        <form method="POST" id="delete-form">
            <input type="hidden" name="delete_id" id="delete_id" value="">
            <button type="submit">Ya, Hapus</button>
        </form>
        <button onclick="closePopup()">Tidak, Kembali</button>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan pop-up
    function showDeletePopup(id_jadwal) {
        document.getElementById('delete_id').value = id_jadwal;
        document.getElementById('popup-delete').style.display = 'flex';
    }

    // Fungsi untuk menutup pop-up
    function closePopup() {
        document.getElementById('popup-delete').style.display = 'none';
    }
</script>

</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
