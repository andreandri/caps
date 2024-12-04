<?php
require '../koneksi.php';

$success_message = '';
$error_message = '';

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
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/tambah.css">
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

<main class="add">
<ind-loading-admin></ind-loading-admin>
    <h1 tabindex="0">Tambah Jadwal Keberangkatan</h1>
    <form method="POST">
        <label tabindex="0" for="id_rute">Rute:</label>
        <select id="id_rute" name="id_rute" required>
            <option tabindex="0" value="">Pilih Rute</option>
            <?php while($row = $routeResult->fetch_assoc()) { ?>
                <option tabindex="0" value="<?= $row['id_rute']; ?>"><?= $row['kota_asal']; ?> - <?= $row['kota_tujuan']; ?></option>
            <?php } ?>
        </select>

        <label tabindex="0" for="tgl_keberangkatan">Tanggal Keberangkatan:</label>
        <input tabindex="0" type="date" id="tgl_keberangkatan" name="tgl_keberangkatan" required>

        <label tabindex="0" for="jam_keberangkatan">Jam Keberangkatan:</label>
        <input tabindex="0" type="time" id="jam_keberangkatan" name="jam_keberangkatan" required>

        <label tabindex="0" for="harga">Harga:</label>
        <input tabindex="0" type="number" id="harga" name="harga" required>

        <a tabindex="0" href="adminjadwal.php">Kembali</a>
        <button tabindex="0" type="submit">Tambah Jadwal</button>
    </form>
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
        window.location.href = 'adminjadwal.php';
    }

    <?php if (!empty($success_message)): ?>
        document.getElementById('popup-success').style.display = 'flex';
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        document.getElementById('popup-error').style.display = 'flex';
    <?php endif; ?>

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector("form");
        const loadingIndicator = document.querySelector("ind-loading-admin");

        form.addEventListener("submit", (event) => {
            // Mencegah form dari reload halaman secara default
            event.preventDefault();

            // Tampilkan indikator loading
            loadingIndicator.style.display = "flex";
            document.body.classList.add("no-scroll");

            // Lakukan pengiriman data ke server
            const formData = new FormData(form);
            fetch(form.action, {
                method: "POST",
                body: formData,
            })
                .then((response) => response.text())
                .then((result) => {
                    loadingIndicator.style.display = "none";
                    document.body.classList.remove("no-scroll");

                    // Tampilkan pesan sukses/gagal
                    if (result.includes("Data jadwal bus berhasil ditambahkan")) {
                        window.location.href = "adminjadwal.php";
                    } else {
                    }
                })
                .catch((error) => {
                    // Sembunyikan indikator loading
                    loadingIndicator.style.display = "none";
                    document.body.classList.remove("no-scroll");

                    console.error("Error:", error);
                });
        });
    });
</script>
</body>
</html>

<?php
// Close the database connection
$koneksi->close();
?>
