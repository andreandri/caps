<?php
// Include the database connection
require '../koneksi.php';

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

// Ambil data `id_bus` untuk dropdown
$busQuery = "SELECT id_bus FROM tb_bus";
$busResult = $koneksi->query($busQuery);

// Ambil data `id_jadwal` untuk dropdown
$jadwalQuery = "SELECT id_jadwal FROM tb_jadwal";
$jadwalResult = $koneksi->query($jadwalQuery);

// Variable untuk pesan
$success_message = "";
$error_message = "";

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_bus = $_POST['id_bus'];
    $id_jadwal = $_POST['id_jadwal'];

    // Validasi data
    if (!empty($id_bus) && !empty($id_jadwal)) {
        // Query untuk insert data baru
        $insertQuery = "INSERT INTO tb_busjadwal (id_bus, id_jadwal) VALUES (?, ?)";
        $stmt = $koneksi->prepare($insertQuery);
        $stmt->bind_param("ii", $id_bus, $id_jadwal);

        if ($stmt->execute()) {
            $success_message = "Data jadwal bus berhasil ditambahkan!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    } else {
        $error_message = "Semua field harus diisi dengan benar!";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="styles/admin-edit-detail.css">
    <style>
        /* Style for pop-up confirmation */
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
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .popup button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .popup button:hover {
            background-color: #45a049;
        }
        .popup-danger button {
            background-color: #f44336;
        }
        .popup-danger button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

<header class="dashboard">
    <div class="navbar">
        <h1 tabindex="0" >Dashboard Admin</h1>
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
    <h1 tabindex="0">Tambah Jadwal Bus</h1>
    <div class="form-container">
        <form method="POST">
            <label tabindex="0" for="id_bus">Id Bus</label>
            <select id="id_bus" name="id_bus" required>
                <option tabindex="0" value="">-- Pilih Bus --</option>
                <?php while ($busRow = $busResult->fetch_assoc()): ?>
                    <option tabindex="0" value="<?= $busRow['id_bus'] ?>">
                        <?= $busRow['id_bus'] ?> 
                    </option>
                <?php endwhile; ?>
            </select>

            <label tabindex="0" for="id_jadwal">Id Jadwal</label>
            <select tabindex="0" id="id_jadwal" name="id_jadwal" required>
                <option tabindex="0" value="">-- Pilih Jadwal --</option>
                <?php while ($jadwalRow = $jadwalResult->fetch_assoc()): ?>
                    <option tabindex="0" value="<?= $jadwalRow['id_jadwal'] ?>">
                        <?= $jadwalRow['id_jadwal'] ?> 
                    </option>
                <?php endwhile; ?>
            </select>

            <a tabindex="0" href="adminjadwal.php">Kembali</a>
            <button tabindex="0" type="submit">Tambah Jadwal</button>
        </form>
    </div>
</main>

<!-- Pop-up sukses -->
<?php if (!empty($success_message)): ?>
    <div id="popup-success" class="popup">
        <div class="popup-content">
            <h3><?= $success_message ?></h3>
            <button tabindex="0" onclick="redirectToSchedule()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<!-- Pop-up error -->
<?php if (!empty($error_message)): ?>
    <div id="popup-error" class="popup">
        <div class="popup-content">
            <h3><?= $error_message ?></h3>
            <button tabindex="0" onclick="closePopup()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<script>
    // Fungsi untuk menutup pop-up
    function closePopup() {
        document.getElementById('popup-error').style.display = 'none';
    }

    // Fungsi untuk redirect setelah pop-up sukses ditutup
    function redirectToSchedule() {
        document.getElementById('popup-success').style.display = 'none';
        window.location.href = 'adminjadwal.php'; // Redirect ke halaman jadwal
    }

    // Tampilkan pop-up jika ada pesan
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
