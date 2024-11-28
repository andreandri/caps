<?php
include '../koneksi.php'; // memastikan koneksi ke database

// Memastikan id_bus ada dalam URL
if (isset($_GET['id_bus'])) {
    $id_bus = $_GET['id_bus'];

    // Cek apakah koneksi berhasil
    if ($koneksi) {
        // Mengambil data bus berdasarkan id_bus
        $query = "SELECT * FROM tb_bus WHERE id_bus = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $id_bus);
        $stmt->execute();
        $result = $stmt->get_result();
        $bus = $result->fetch_assoc();

        // Menutup statement setelah selesai digunakan
        $stmt->close();
    } else {
        echo "Koneksi database gagal.";
        exit;
    }

    // Menangani data yang diubah ketika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $no_plat = $_POST['no_plat'];
        $nama_sopir = $_POST['nama_sopir'];
        $kapasitas = $_POST['kapasitas'];

        // Update query untuk menyimpan perubahan
        $update_query = "UPDATE tb_bus SET no_plat = ?, nama_sopir = ?, kapasitas = ? WHERE id_bus = ?";
        $update_stmt = $koneksi->prepare($update_query);
        $update_stmt->bind_param("ssii", $no_plat, $nama_sopir, $kapasitas, $id_bus);

        // Eksekusi query update
        if ($update_stmt->execute()) {
            $success_message = "Data bus berhasil diperbarui."; // Pesan keberhasilan
        } else {
            $error_message = "Gagal memperbarui data bus."; // Pesan kesalahan
        }

        // Menutup statement setelah digunakan
        $update_stmt->close();
    }
} else {
    echo "ID bus tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bus</title>
    <link rel="stylesheet" href="styles/admin-edit-detail.css">
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
            <li><a href="adminrute.php" style="background-color: #C8ACD6;">Rute</a></li>
            <li><a href="adminjadwal.php">Jadwal</a></li>
            <li><a href="adminpesanan.php">Daftar Pesanan</a></li>
            <li><a href="adminrekap.php">Rekap Pendapatan</a></li>
        </ul>
        <img src="img/EasyBusTix.png" alt="">
    </div>
    <div>
        <a href="../fungsi/logout.php" class="logout">Logout</a>
    </div>
</header>

<main class="main-content">
    <h1>Edit Bus</h1>
    <div class="form-container">
        <form action="edit_bus.php?id_bus=<?= $id_bus ?>" method="POST">
            <label for="no_plat">No. Plat</label>
            <input type="text" id="no_plat" name="no_plat" value="<?= $bus['no_plat'] ?? '' ?>" required>

            <label for="nama_sopir">Nama Sopir</label>
            <input type="text" id="nama_sopir" name="nama_sopir" value="<?= $bus['nama_sopir'] ?? '' ?>" required>

            <label for="kapasitas">Kapasitas</label>
            <input type="number" id="kapasitas" name="kapasitas" value="<?= $bus['kapasitas'] ?? '' ?>" required>
            
            <a href="adminrute.php">Kembali</a>
            <button type="submit">Edit Bus</button>
        </form>
    </div>
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
        window.location.href = 'adminrute.php'; // Redirect ke halaman adminrute
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