<?php
// Include the database connection
require 'koneksi.php';

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

// Check if `id_jadwal` is provided in the query string
if (!isset($_GET['id_jadwal'])) {
    die("Id Jadwal tidak ditemukan!");
}

$id_jadwal = $_GET['id_jadwal'];

// Fetch the schedule data for the given id_jadwal
$query = "SELECT j.id_jadwal, r.id_rute, r.kota_asal, r.kota_tujuan, j.tgl_keberangkatan, j.jam_keberangkatan, j.harga
          FROM tb_jadwal j
          JOIN tb_rute r ON j.id_rute = r.id_rute
          WHERE j.id_jadwal = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_jadwal);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data jadwal tidak ditemukan!");
}

$row = $result->fetch_assoc();

// Check if form is submitted to update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_rute = $_POST['id_rute'];
    $tgl_keberangkatan = $_POST['tgl_keberangkatan'];
    $jam_keberangkatan = $_POST['jam_keberangkatan'];
    $harga = $_POST['harga'];

    // Validasi data sebelum di-update
    if (!empty($id_rute) && !empty($tgl_keberangkatan) && !empty($jam_keberangkatan) && is_numeric($harga)) {
        // Update query
        $updateQuery = "UPDATE tb_jadwal 
                        SET id_rute = ?, tgl_keberangkatan = ?, jam_keberangkatan = ?, harga = ? 
                        WHERE id_jadwal = ?";
        $stmt = $koneksi->prepare($updateQuery);
        $stmt->bind_param("issdi", $id_rute, $tgl_keberangkatan, $jam_keberangkatan, $harga, $id_jadwal);

        if ($stmt->execute()) {
            echo "<p>Data jadwal berhasil diupdate!</p>";
            // Redirect untuk mencegah pengisian ulang form
            header("Location: adminjadwal.php");
            exit;
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p>Semua field harus diisi dengan benar!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
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
  
<main class="container">
    <h1>Edit Jadwal Keberangkatan</h1>
    <form method="POST">
        <label for="id_rute">Rute:</label>
        <input type="text" id="id_rute" name="id_rute" value="<?= htmlspecialchars($row['id_rute']); ?>" required>

        <label for="tgl_keberangkatan">Tanggal Keberangkatan:</label>
        <input type="date" id="tgl_keberangkatan" name="tgl_keberangkatan" value="<?= htmlspecialchars($row['tgl_keberangkatan']); ?>" required>

        <label for="jam_keberangkatan">Jam Keberangkatan:</label>
        <input type="time" id="jam_keberangkatan" name="jam_keberangkatan" value="<?= htmlspecialchars($row['jam_keberangkatan']); ?>" required>

        <label for="harga">Harga:</label>
        <input type="number" id="harga" name="harga" value="<?= htmlspecialchars($row['harga']); ?>" required>

        <button type="submit">Update Jadwal</button>
        <button type="button" onclick="location.href='adminjadwal.php'">Batal</button>
    </form>
</main>
</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
