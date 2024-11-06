<?php
// Include the database connection
require 'koneksi.php';

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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_rute = $_POST['id_rute'];
    $tgl_keberangkatan = $_POST['tgl_keberangkatan'];
    $jam_keberangkatan = $_POST['jam_keberangkatan'];
    $harga = $_POST['harga'];

    // Update query
    $updateQuery = "UPDATE tb_jadwal SET id_rute = ?, tgl_keberangkatan = ?, jam_keberangkatan = ?, harga = ? WHERE id_jadwal = ?";
    $stmt = $koneksi->prepare($updateQuery);
    $stmt->bind_param("issdi", $id_rute, $tgl_keberangkatan, $jam_keberangkatan, $harga, $id_jadwal);

    if ($stmt->execute()) {
        echo "<p>Data jadwal berhasil diupdate!</p>";
    } else {
        echo "<p>Error: " . $koneksi->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <link rel="stylesheet" href="editjadwal.css">
</head>
<body>
    <div class="container">
        <h1>Edit Jadwal Keberangkatan</h1>
        <form method="POST">
            <label for="id_rute">Rute:</label>
            <input type="text" id="id_rute" name="id_rute" value="<?= $row['id_rute']; ?>" required>

            <label for="tgl_keberangkatan">Tanggal Keberangkatan:</label>
            <input type="date" id="tgl_keberangkatan" name="tgl_keberangkatan" value="<?= $row['tgl_keberangkatan']; ?>" required>

            <label for="jam_keberangkatan">Jam Keberangkatan:</label>
            <input type="time" id="jam_keberangkatan" name="jam_keberangkatan" value="<?= $row['jam_keberangkatan']; ?>" required>

            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" value="<?= $row['harga']; ?>" required>

            <button type="submit">Update Jadwal</button>
            <button type="button" onclick="location.href='adminjadwal.php'">Batal</button>
            <button type="button" onclick="location.href='adminjadwal.php'">Kembali</button>
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
