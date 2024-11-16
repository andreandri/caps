<?php
// Include the database connection
require 'koneksi.php';

// Check if the 'id_pemesanan' is passed in the URL
if (isset($_GET['id_pemesanan'])) {
    $id_pemesanan = $_GET['id_pemesanan'];

    // Fetch the current data for the booking to be edited
    $query = "SELECT p.id_pemesanan, p.username, p.nama_penumpang, p.jumlah_tiket, p.total, 
                     j.tgl_keberangkatan, j.jam_keberangkatan, r.kota_asal, r.kota_tujuan, bj.id_busjadwal
              FROM tb_pemesanan p
              JOIN tb_busjadwal bj ON p.id_busjadwal = bj.id_busjadwal
              JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal
              JOIN tb_rute r ON j.id_rute = r.id_rute
              WHERE p.id_pemesanan = ?";
    
    // Prepare statement to avoid SQL injection
    if ($stmt = $koneksi->prepare($query)) {
        $stmt->bind_param("i", $id_pemesanan);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch the result
        $row = $result->fetch_assoc();
        
        if (!$row) {
            die("Pesanan tidak ditemukan.");
        }
    } else {
        die("Query Error: " . $koneksi->error);
    }
}

// Handle the form submission for editing the booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama_penumpang = $_POST['nama_penumpang'];
    $jumlah_tiket = $_POST['jumlah_tiket'];
    $total = $_POST['total'];
    $id_busjadwal = $_POST['id_busjadwal'];

    // Update the booking details in the database
    $update_query = "UPDATE tb_pemesanan SET 
                         username = ?, 
                         nama_penumpang = ?, 
                         jumlah_tiket = ?, 
                         total = ?, 
                         id_busjadwal = ? 
                     WHERE id_pemesanan = ?";
    
    if ($stmt = $koneksi->prepare($update_query)) {
        $stmt->bind_param("ssdiis", $username, $nama_penumpang, $jumlah_tiket, $total, $id_busjadwal, $id_pemesanan);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: adminpesanan.php"); // Redirect back to the booking list
            exit;
        } else {
            echo "Gagal memperbarui pesanan.";
        }
    } else {
        die("Query Error: " . $koneksi->error);
    }
}

// Fetch the list of available bus schedules for the dropdown, now using `tb_jadwal` for the date and time columns
$busjadwal_query = "SELECT bj.id_busjadwal, j.tgl_keberangkatan, j.jam_keberangkatan 
                    FROM tb_busjadwal bj
                    JOIN tb_jadwal j ON bj.id_jadwal = j.id_jadwal";
$busjadwal_result = $koneksi->query($busjadwal_query);

if (!$busjadwal_result) {
    die("Query Error: " . $koneksi->error);
}


// Close the database connection
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan - Dashboard Admin</title>
    <link rel="stylesheet" href="admin-edit-detail.css">
</head>
<body>
<header class="dashboard">
    <div class="navbar">
      <h1>Dashboard Admin</h1>
        <ul class="menu">
          <li><a href="adminrute.php" style="background-color: #C8ACD6;">Rute</a></li>
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
            <h1>Edit Pesanan</h1>
            <form action="editpesanan.php?id_pemesanan=<?= $row['id_pemesanan']; ?>" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= $row['username']; ?>" required><br>

                <label for="nama_penumpang">Nama Penumpang:</label>
                <input type="text" id="nama_penumpang" name="nama_penumpang" value="<?= $row['nama_penumpang']; ?>" required><br>

                <label for="jumlah_tiket">Jumlah Tiket:</label>
                <input type="number" id="jumlah_tiket" name="jumlah_tiket" value="<?= $row['jumlah_tiket']; ?>" required><br>

                <label for="total">Total:</label>
                <input type="number" id="total" name="total" value="<?= $row['total']; ?>" required><br>

                <label for="id_busjadwal">ID Bus Jadwal:</label>
                <select id="id_busjadwal" name="id_busjadwal" required>
                    <?php while ($busjadwal = $busjadwal_result->fetch_assoc()) { ?>
                        <option value="<?= $busjadwal['id_busjadwal']; ?>"
                            <?= $row['id_busjadwal'] == $busjadwal['id_busjadwal'] ? 'selected' : ''; ?>>
                            <?= $busjadwal['tgl_keberangkatan'] . ' - ' . $busjadwal['jam_keberangkatan']; ?>
                        </option>
                    <?php } ?>
                </select><br>

                <button type="submit">Update Pesanan</button>
            </form>
        </div>
    </main>
</body>
</html>
