<?php
// Include the database connection
require '../koneksi.php';

// Handle delete request
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Panggil stored procedure untuk menghapus pemesanan
    $delete_query = "CALL hapusPemesanan(?)";
    $stmt = $koneksi->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        header("Location: adminpesanan.php");
        exit();
    } else {
        echo "Error executing procedure: " . $stmt->error;
    }

    $stmt->close();
}


// Fetch the orders data from tb_pemesanan, along with related schedule and user data
$query = "SELECT p.id_pemesanan, p.username, p.nama_penumpang, p.jumlah_tiket, p.total, p.status_pembayaran, j.tgl_keberangkatan, j.jam_keberangkatan, r.kota_asal, r.kota_tujuan
          FROM tb_pemesanan p
          JOIN tb_busjadwal bj ON p.id_busjadwal = bj.id_busjadwal
          JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal
          JOIN tb_rute r ON j.id_rute = r.id_rute";

$result = $koneksi->query($query);

if (!$result) {
    die("Query Error: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Dashboard Admin</title>
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
            <h1 tabindex="0">Dashboard Admin</h1>
            <ul class="menu">
                <li><a tabindex="0" href="adminrute.php">Rute</a></li>
                <li><a tabindex="0" href="adminjadwal.php">Jadwal</a></li>
                <li><a tabindex="0" href="adminpesanan.php" style="background-color: #C8ACD6;">Daftar Pesanan</a></li>
                <li><a tabindex="0" href="adminrekap.php">Rekap Pendapatan</a></li>
            </ul> 
            <img tabindex="0" src="../img/EasyBusTix.png" alt="Logo EasyBusTix"> 
        </div>
        <div>
            <a tabindex="0" href="../fungsi/logout.php" class="logout">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <h1 tabindex="0">Daftar Pesanan</h1>
        <table>
            <thead>
                <tr>
                    <th tabindex="0">ID Pemesanan</th>
                    <th tabindex="0">Username</th>
                    <th tabindex="0">Nama Pemesanan</th>
                    <th tabindex="0">Jumlah Tiket</th>
                    <th tabindex="0">Kota Asal</th>
                    <th tabindex="0">Kota Tujuan</th>
                    <th tabindex="0">Tanggal Keberangkatan</th>
                    <th tabindex="0">Jam Keberangkatan</th>
                    <th tabindex="0">Status Pembayaran</th>
                    <th tabindex="0">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td tabindex="0"><?= $row['id_pemesanan']; ?></td>
                        <td tabindex="0"><?= $row['username']; ?></td>
                        <td tabindex="0"><?= $row['nama_penumpang']; ?></td> 
                        <td tabindex="0"><?= $row['jumlah_tiket']; ?></td>
                        <td tabindex="0"><?= $row['kota_asal']; ?></td>
                        <td tabindex="0"><?= $row['kota_tujuan']; ?></td>
                        <td tabindex="0"><?= $row['tgl_keberangkatan']; ?></td>
                        <td tabindex="0"><?= $row['jam_keberangkatan']; ?></td>
                        <td tabindex="0"><?= $row['status_pembayaran']; ?></td>
                        <td tabindex="0" class="action-buttons">
                            <a tabindex="0" href="editpesanan.php?id_pemesanan=<?= $row['id_pemesanan']; ?>">Edit</a>
                            <button tabindex="0" class="delete-button" onclick="showDeletePopup(<?= $row['id_pemesanan']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <!-- Pop-up konfirmasi hapus -->
    <div id="popup-delete" class="popup">
        <div class="popup-content popup-danger">
            <h3 tabindex="0">Apakah Anda yakin ingin menghapus pesanan ini?</h3>
            <form method="POST" id="delete-form">
                <input tabindex="0" type="hidden" name="delete_id" id="delete_id" value="">
                <button tabindex="0" type="submit">Ya, Hapus</button>
            </form>
            <button tabindex="0" onclick="closePopup()">Tidak, Kembali</button>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan pop-up
        function showDeletePopup(id_pemesanan) {
            document.getElementById('delete_id').value = id_pemesanan;
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
