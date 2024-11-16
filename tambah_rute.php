<?php
include("koneksi.php");

// Proses penyimpanan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kota_asal = $_POST['kota_asal'];
    $kota_tujuan = $_POST['kota_tujuan'];

    // Validasi input
    if (!empty($kota_asal) && !empty($kota_tujuan)) {
        // Query untuk menyimpan data ke tabel tb_rute
        $sql = "INSERT INTO tb_rute (kota_asal, kota_tujuan) VALUES (?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ss", $kota_asal, $kota_tujuan);

        if ($stmt->execute()) {
            $success_message = "Rute berhasil ditambahkan.";
        } else {
            $error_message = "Gagal menambahkan rute: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Harap isi semua field.";
    }
}

// Query untuk mengambil nilai ENUM dari tabel rute untuk opsi kota asal dan tujuan
$kota_query = "SHOW COLUMNS FROM tb_rute LIKE 'kota_asal'";
$result = mysqli_query($koneksi, $kota_query);
$kota_asal_enum = '';
if ($result) {
    $row = mysqli_fetch_assoc($result);
    preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
    $kota_asal_enum = explode("','", $matches[1]);
}

$kota_query_tujuan = "SHOW COLUMNS FROM tb_rute LIKE 'kota_tujuan'";
$result_tujuan = mysqli_query($koneksi, $kota_query_tujuan);
$kota_tujuan_enum = '';
if ($result_tujuan) {
    $row_tujuan = mysqli_fetch_assoc($result_tujuan);
    preg_match("/^enum\(\'(.*)\'\)$/", $row_tujuan['Type'], $matches_tujuan);
    $kota_tujuan_enum = explode("','", $matches_tujuan[1]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Rute</title>
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
    <h1>Tambah Rute Perjalanan</h1>
    <form action="tambah_rute.php" method="POST">
        <label for="kota_asal">Kota Asal</label>
        <select id="kota_asal" name="kota_asal" required>
            <option value="">Pilih Kota Asal</option>
            <?php
            foreach ($kota_asal_enum as $kota) {
                echo "<option value=\"$kota\">$kota</option>";
            }
            ?>
        </select>

        <label for="kota_tujuan">Kota Tujuan</label>
        <select id="kota_tujuan" name="kota_tujuan" required>
            <option value="">Pilih Kota Tujuan</option>
            <?php
            foreach ($kota_tujuan_enum as $kota) {
                echo "<option value=\"$kota\">$kota</option>";
            }
            ?>
        </select>

        <div>
            <a href="adminrute.php">Kembali</a>
            <button type="submit">Tambah Rute</button>
        </div>
    </form>
</main>

<!-- Pop-up untuk sukses -->
<?php if (isset($success_message)): ?>
    <div id="popup-success" class="popup">
        <div class="popup-content">
            <h3><?= $success_message ?></h3>
            <button onclick="redirectToRoute()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<!-- Pop-up untuk error -->
<?php if (isset($error_message)): ?>
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

    function redirectToRoute() {
        window.location.href = 'adminrute.php';
    }

    <?php if (isset($success_message)): ?>
        document.getElementById('popup-success').style.display = 'flex';
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        document.getElementById('popup-error').style.display = 'flex';
    <?php endif; ?>
</script>
</body>
</html>
