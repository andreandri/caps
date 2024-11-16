<?php
include("koneksi.php");

// Pastikan id_rute ada dalam URL
if (isset($_GET['id_rute'])) {
    $id_rute = $_GET['id_rute'];

    // Query untuk mengambil data rute berdasarkan id_rute
    $query = "SELECT * FROM tb_rute WHERE id_rute = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_rute);
    $stmt->execute();
    $result = $stmt->get_result();
    $rute = $result->fetch_assoc();
    $stmt->close();

    // Jika rute tidak ditemukan
    if (!$rute) {
        echo "<script>alert('Rute tidak ditemukan.'); window.location.href = 'adminrute.php';</script>";
        exit;
    }

    // Proses update data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kota_asal = $_POST['kota_asal'];
        $kota_tujuan = $_POST['kota_tujuan'];

        // Validasi input
        if (!empty($kota_asal) && !empty($kota_tujuan)) {
            // Query untuk update data ke tabel tb_rute
            $sql = "UPDATE tb_rute SET kota_asal = ?, kota_tujuan = ? WHERE id_rute = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssi", $kota_asal, $kota_tujuan, $id_rute);

            if ($stmt->execute()) {
                $success_message = "Rute berhasil diperbarui."; // Pesan sukses
            } else {
                $error_message = "Gagal memperbarui rute: " . $stmt->error; // Pesan error
            }

            $stmt->close();
        } else {
            $error_message = "Harap isi semua field."; // Pesan error jika form kosong
        }
    }
} else {
    echo "<script>alert('ID rute tidak ditemukan.'); window.location.href = 'adminrute.php';</script>";
    exit;
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
    <title>Edit Rute</title>

    <link rel="stylesheet" href="admin-edit-detail.css">

    <style>
        /* Style untuk Pop-up */
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
    <form action="edit_rute.php?id_rute=<?= $id_rute ?>" method="POST">
        <label for="kota_asal">Kota Asal</label>
        <select id="kota_asal" name="kota_asal" required>
            <option value="">Pilih Kota Asal</option>
            <?php
            // Menampilkan pilihan kota asal dari ENUM
            foreach ($kota_asal_enum as $kota) {
                $selected = ($rute['kota_asal'] == $kota) ? 'selected' : '';
                echo "<option value=\"$kota\" $selected>$kota</option>";
            }
            ?>
        </select>

        <label for="kota_tujuan">Kota Tujuan</label>
        <select id="kota_tujuan" name="kota_tujuan" required>
            <option value="">Pilih Kota Tujuan</option>
            <?php
            // Menampilkan pilihan kota tujuan dari ENUM
            foreach ($kota_tujuan_enum as $kota) {
                $selected = ($rute['kota_tujuan'] == $kota) ? 'selected' : '';
                echo "<option value=\"$kota\" $selected>$kota</option>";
            }
            ?>
        </select>

        <div>
            <button type="button" onclick="window.location.href='adminrute.php';">Kembali</button>
            <button type="submit">Update Rute</button>
        </div>
    </form>
</main>

<!-- Pop-up untuk sukses -->
<?php if (isset($success_message)): ?>
    <div id="popup-success" class="popup">
        <div class="popup-content">
            <h3><?= $success_message ?></h3>
            <button onclick="closePopup()">Tutup</button>
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
    // Fungsi untuk menutup pop-up
    function closePopup() {
        document.getElementById('popup-success').style.display = 'none';
        document.getElementById('popup-error').style.display = 'none';
    }

    // Menampilkan pop-up jika berhasil update
    <?php if (isset($success_message)): ?>
        document.getElementById('popup-success').style.display = 'flex';
    <?php endif; ?>

    // Menampilkan pop-up jika ada error
    <?php if (isset($error_message)): ?>
        document.getElementById('popup-error').style.display = 'flex';
    <?php endif; ?>
</script>

</body>
</html>
