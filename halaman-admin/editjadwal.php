<?php
require '../koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

if (!isset($_GET['id_jadwal'])) {
    die("Id Jadwal tidak ditemukan!");
}

$id_jadwal = $_GET['id_jadwal'];

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

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_rute = $_POST['id_rute'];
    $tgl_keberangkatan = $_POST['tgl_keberangkatan'];
    $jam_keberangkatan = $_POST['jam_keberangkatan'];
    $harga = $_POST['harga'];

    if (!empty($id_rute) && !empty($tgl_keberangkatan) && !empty($jam_keberangkatan) && is_numeric($harga)) {
        // Update query
        $updateQuery = "UPDATE tb_jadwal 
                        SET id_rute = ?, tgl_keberangkatan = ?, jam_keberangkatan = ?, harga = ? 
                        WHERE id_jadwal = ?";
        $stmt = $koneksi->prepare($updateQuery);
        $stmt->bind_param("issdi", $id_rute, $tgl_keberangkatan, $jam_keberangkatan, $harga, $id_jadwal);

        if ($stmt->execute()) {
            $success_message = "Data jadwal berhasil diperbarui!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    } else {
        $error_message = "Semua field harus diisi dengan benar!";
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Jadwal</title>
        <link rel="icon" href="favicon.png" type="image/png">
        <link rel="stylesheet" href="styles/admin-edit-detail.css">
        <script type="module" src="../scripts/index.js"></script>
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
            <h1 tabindex="0">Dashboard Admin</h1>
            <ul class="menu">
                <li><a tabindex="0" href="adminrute.php">Rute</a></li>
                <li><a tabindex="0" href="adminjadwal.php" style="background-color: #C8ACD6;">Jadwal</a></li>
                <li><a tabindex="0" href="adminpesanan.php">Daftar Pesanan</a></li>
                <li><a tabindex="0" href="adminrekap.php">Rekap Pendapatan</a></li>
            </ul> 
            <img tabindex="0" src="../img/EasyBusTix.png" alt="Logo EasyBusTix"> 
        </div>
        <div>
            <a tabindex="0" href="../fungsi/logout.php" class="logout">Logout</a>
        </div>
    </header>
    
    <main class="main-content">
        <h1 tabindex="0">Edit Jadwal Keberangkatan</h1>
        <div class="form-container">
        <form method="POST">
            <label tabindex="0" for="id_rute">Rute:</label>
            <input tabindex="0" type="text" id="id_rute" name="id_rute" value="<?= htmlspecialchars($row['id_rute']); ?>" required>

            <label tabindex="0" for="tgl_keberangkatan">Tanggal Keberangkatan:</label>
            <input tabindex="0" type="date" id="tgl_keberangkatan" name="tgl_keberangkatan" value="<?= htmlspecialchars($row['tgl_keberangkatan']); ?>" required>

            <label tabindex="0" for="jam_keberangkatan">Jam Keberangkatan:</label>
            <input tabindex="0" type="time" id="jam_keberangkatan" name="jam_keberangkatan" value="<?= htmlspecialchars($row['jam_keberangkatan']); ?>" required>

            <label tabindex="0" for="harga">Harga:</label>
            <input tabindex="0" type="number" id="harga" name="harga" value="<?= htmlspecialchars($row['harga']); ?>" required>

            <a tabindex="0" href="adminjadwal.php">Kembali</a>
            <button tabindex="0" type="submit">Update Jadwal</button>
        </form>
        </div>
    </main>

<?php if (!empty($success_message)): ?>
    <div id="popup-success" class="popup">
        <div class="popup-content">
            <h3><?= $success_message ?></h3>
            <button tabindex="0" onclick="redirectToSchedule()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <div id="popup-error" class="popup">
        <div class="popup-content">
            <h3><?= $error_message ?></h3>
            <button tabindex="0" onclick="closePopup()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<script>
    function closePopup() {
        document.getElementById('popup-error').style.display = 'none';
    }

    function redirectToSchedule() {
        document.getElementById('popup-success').style.display = 'none';
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
$koneksi->close();
?>
