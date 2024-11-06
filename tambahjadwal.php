<?php
// Include the database connection
require 'koneksi.php';

// Handle form submission for adding a new schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_rute = $_POST['id_rute'];
    $tgl_keberangkatan = $_POST['tgl_keberangkatan'];
    $jam_keberangkatan = $_POST['jam_keberangkatan'];
    $harga = $_POST['harga'];

    // Insert query to add a new schedule
    $insertQuery = "INSERT INTO tb_jadwal (id_rute, tgl_keberangkatan, jam_keberangkatan, harga) 
                    VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($insertQuery);
    $stmt->bind_param("issd", $id_rute, $tgl_keberangkatan, $jam_keberangkatan, $harga);

    if ($stmt->execute()) {
        echo "<script>alert('Jadwal berhasil ditambahkan!'); window.location.href='adminjadwal.php';</script>";
        exit;
    } else {
        echo "<p>Error: " . $koneksi->error . "</p>";
    }
}

// Fetch available routes from tb_rute for the dropdown menu
$routeQuery = "SELECT id_rute, kota_asal, kota_tujuan FROM tb_rute";
$routeResult = $koneksi->query($routeQuery);

if (!$routeResult) {
    die("Query Error: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal</title>
    <link rel="stylesheet" href="tambahjadwal.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1>Dashboard Admin</h1>
            <button onclick="location.href='adminrute.php'">Rute</button>
            <button onclick="location.href='adminjadwal.php'">Jadwal</button>
            <button>Daftar Pesanan</button>
            <button>Rekap Pendapatan</button>
            <button class="logout" onclick="location.href='fungsi/logout.php'">Logout</button>
        </div>
        <div class="main-content">
            <h1>Tambah Jadwal Keberangkatan</h1>
            <form method="POST">
                <label for="id_rute">Rute:</label>
                <select id="id_rute" name="id_rute" required>
                    <option value="">Pilih Rute</option>
                    <?php while($row = $routeResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id_rute']; ?>"><?= $row['kota_asal']; ?> - <?= $row['kota_tujuan']; ?></option>
                    <?php } ?>
                </select>

                <label for="tgl_keberangkatan">Tanggal Keberangkatan:</label>
                <input type="date" id="tgl_keberangkatan" name="tgl_keberangkatan" required>

                <label for="jam_keberangkatan">Jam Keberangkatan:</label>
                <input type="time" id="jam_keberangkatan" name="jam_keberangkatan" required>

                <label for="harga">Harga:</label>
                <input type="number" id="harga" name="harga" required>

                <button type="submit">Tambah Jadwal</button>
                <button type="button" onclick="location.href='adminjadwal.php'">Batal</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
