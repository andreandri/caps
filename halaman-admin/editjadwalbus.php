<?php
require '../koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

if (!isset($_GET['id_busjadwal'])) {
    die("Id Bus Jadwal tidak ditemukan!");
}

$id_busjadwal = $_GET['id_busjadwal'];

$query = "SELECT bj.id_busjadwal, bj.id_bus, bj.id_jadwal
          FROM tb_busjadwal bj
          WHERE bj.id_busjadwal = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_busjadwal);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Data jadwal tidak ditemukan!");
}

$row = $result->fetch_assoc();

$busQuery = "SELECT id_bus, no_plat FROM tb_bus";
$busResult = $koneksi->query($busQuery);

$jadwalQuery = "SELECT id_jadwal, tgl_keberangkatan, jam_keberangkatan FROM tb_jadwal";
$jadwalResult = $koneksi->query($jadwalQuery);

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bus = $_POST['id_bus'];
    $id_jadwal = $_POST['id_jadwal'];

    if (!empty($id_bus) && !empty($id_jadwal)) {
        $updateQuery = "UPDATE tb_busjadwal 
                        SET id_bus = ?, id_jadwal = ? 
                        WHERE id_busjadwal = ?";
        $stmt = $koneksi->prepare($updateQuery);
        $stmt->bind_param("iii", $id_bus, $id_jadwal, $id_busjadwal);

        if ($stmt->execute()) {
            $success_message = "Data jadwal bus berhasil diperbarui!";
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
        <h1 tabindex="0">Dashboard Admin</h1>
        <ul class="menu">
            <li><a href="adminrute.php">Rute</a></li>
            <li><a href="adminjadwal.php" style="background-color: #C8ACD6;">Jadwal</a></li>
            <li><a href="adminpesanan.php">Daftar Pesanan</a></li>
            <li><a href="adminrekap.php">Rekap Pendapatan</a></li>
        </ul> 
        <img tabindex="0" src="../img/EasyBusTix.png" alt="Logo EasyBusTix"> 
    </div>
    <div>
        <a tabindex="0" href="../fungsi/logout.php" class="logout">Logout</a>
    </div>
</header>

<main class="main-content">
    <h1 tabindex="0">Edit Jadwal Bus</h1>
    <div class="form-container">
        <form method="POST">
            <label tabindex="0" for="id_busjadwal">Id Bus Jadwal</label>
            <input tabindex="0" type="text" id="id_busjadwal" name="id_busjadwal" value="<?= htmlspecialchars($row['id_busjadwal']); ?>" readonly>

            <label tabindex="0" for="id_bus">Id Bus</label>
            <select id="id_bus" name="id_bus" required>
                <option tabindex="0" value="">-- Pilih Bus --</option>
                <?php while ($busRow = $busResult->fetch_assoc()): ?>
                    <option tabindex="0" value="<?= $busRow['id_bus'] ?>" <?= $busRow['id_bus'] == $row['id_bus'] ? 'selected' : '' ?>>
                        <?= $busRow['id_bus'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label tabindex="0" for="id_jadwal">Id Jadwal</label>
            <select id="id_jadwal" name="id_jadwal" required>
                <option tabindex="0" value="">-- Pilih Jadwal --</option>
                <?php while ($jadwalRow = $jadwalResult->fetch_assoc()): ?>
                    <option tabindex="0" value="<?= $jadwalRow['id_jadwal'] ?>" <?= $jadwalRow['id_jadwal'] == $row['id_jadwal'] ? 'selected' : '' ?>>
                        <?= $jadwalRow['id_jadwal']  ?>
                    </option>
                <?php endwhile; ?>
            </select>

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

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector("form");
        const loadingIndicator = document.querySelector("ind-loading-admin");

        form.addEventListener("submit", (event) => {
            event.preventDefault();

            loadingIndicator.style.display = "flex";
            document.body.classList.add("no-scroll");

            const formData = new FormData(form);
            fetch(form.action, {
                method: "POST",
                body: formData,
            })
                .then((response) => response.text())
                .then((result) => {
                    loadingIndicator.style.display = "none";
                    document.body.classList.remove("no-scroll");

                    if (result.includes("Data jadwal bus berhasil diperbarui")) {
                        window.location.href = "adminjadwal.php";
                    } else {
                        alert("Terjadi kesalahan: " + result);
                    }
                })
                .catch((error) => {
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
$koneksi->close();
?>
