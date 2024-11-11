<?php
// Include the database connection
require 'koneksi.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $deleteQuery = "DELETE FROM tb_jadwal WHERE id_jadwal = ?";
    $stmt = $koneksi->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        // Show success pop-up notification
        echo "<script>alert('Jadwal dengan ID $delete_id berhasil dihapus.'); window.location.href='adminjadwal.php';</script>";
        exit;
    } else {
        echo "<p>Error: " . $koneksi->error . "</p>";
    }
}

// Fetch schedule data from tb_jadwal
$query = "SELECT j.id_jadwal, r.kota_asal, r.kota_tujuan, j.tgl_keberangkatan, j.jam_keberangkatan, j.harga
          FROM tb_jadwal j
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
    
    <main>
        <div class="main-content">
            <table>
                <thead>
                    <tr>
                        <th>Id Jadwal</th>
                        <th>Kota Asal</th>
                        <th>Kota Tujuan</th>
                        <th>Tanggal Keberangkatan</th>
                        <th>Jam Keberangkatan</th>
                        <th>Harga</th>
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
                            <td class="action-buttons">
                                <a href="editjadwal.php?id_jadwal=<?= $row['id_jadwal']; ?>" class="edit-button">Edit</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= $row['id_jadwal']; ?>">
                                    <button type="submit" class="delete-button" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button class="add-schedule" onclick="location.href='tambahjadwal.php'">Tambah Jadwal</button>
        </div>
    </main>
</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
