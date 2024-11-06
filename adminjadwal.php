<?php
// Include the database connection
require 'koneksi.php';

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
    <style>
        /* Your CSS styling goes here, including styles for table, buttons, etc. */
    </style>
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
                                <button class="edit-button">Edit</button>
                                <button class="delete-button">Hapus</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button class="add-schedule">Tambah Jadwal</button>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
