<?php
// Include the database connection
require 'koneksi.php';

// Variable to store pop-up messages
$success_message = '';
$error_message = '';

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
        $success_message = 'Jadwal berhasil ditambahkan!';
    } else {
        $error_message = 'Error: ' . $koneksi->error;
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
    <link rel="stylesheet" href="tambah.css">
    <style>
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
    </style>
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

<main class="add">
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

        <a href="adminjadwal.php">Kembali</a>
        <button type="submit">Tambah Jadwal</button>
    </form>
</main>

<!-- Pop-up for success -->
<?php if (!empty($success_message)): ?>
    <div id="popup-success" class="popup">
        <div class="popup-content">
            <h3><?= $success_message ?></h3>
            <button onclick="redirectToSchedule()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<!-- Pop-up for error -->
<?php if (!empty($error_message)): ?>
    <div id="popup-error" class="popup">
        <div class="popup-content">
            <h3><?= $error_message ?></h3>
            <button onclick="closePopup()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<script>
    function closePopup() {
        document.getElementById('popup-error').style.display = 'none';
    }

    function redirectToSchedule() {
        window.location.href = 'adminjadwal.php';
    }

    <?php if (!empty($success_message)): ?>
        document.getElementById('popup-success').style.display = 'flex';
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        document.getElementById('popup-error').style.display = 'flex';
    <?php endif; ?>
</script>
</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
