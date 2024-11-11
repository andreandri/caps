<?php
// Include the database connection
require 'koneksi.php';

// Handle delete request
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Delete the order from the tb_pemesanan table
    $delete_query = "DELETE FROM tb_pemesanan WHERE id_pemesanan = ?";
    $stmt = $koneksi->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        // If the deletion is successful, redirect to the list of orders
        header("Location: adminpesanan.php");
        exit(); // Make sure to call exit after header redirect
    } else {
        echo "Error deleting record: " . $koneksi->error;
    }

    $stmt->close();
}

// Fetch the orders data from tb_pemesanan, along with related schedule and user data
$query = "SELECT p.id_pemesanan, p.username, p.nama_penumpang, p.jumlah_tiket, p.total, j.tgl_keberangkatan, j.jam_keberangkatan, r.kota_asal, r.kota_tujuan
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
            <h1>Daftar Pesanan</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID Pemesanan</th>
                        <th>Username</th>
                        <th>Kota Asal</th>
                        <th>Kota Tujuan</th>
                        <th>Tanggal Keberangkatan</th>
                        <th>Jam Keberangkatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['id_pemesanan']; ?></td>
                            <td><?= $row['username']; ?></td>
                            <td><?= $row['kota_asal']; ?></td>
                            <td><?= $row['kota_tujuan']; ?></td>
                            <td><?= $row['tgl_keberangkatan']; ?></td>
                            <td><?= $row['jam_keberangkatan']; ?></td>
                            <td class="action-buttons">
                                <!-- Edit Button -->
                                <a href="editpesanan.php?id_pemesanan=<?= $row['id_pemesanan']; ?>" class="edit-button">Edit</a>
                                <!-- Delete Form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= $row['id_pemesanan']; ?>">
                                    <button type="submit" class="delete-button" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
</body>
</html>