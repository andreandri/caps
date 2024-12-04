<?php
include '../koneksi.php';

if (isset($_GET['id_bus'])) {
    $id_bus = $_GET['id_bus'];

    if ($koneksi) {
        $query = "SELECT * FROM tb_bus WHERE id_bus = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $id_bus);
        $stmt->execute();
        $result = $stmt->get_result();
        $bus = $result->fetch_assoc();

        $stmt->close();
    } else {
        echo "Koneksi database gagal.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $no_plat = $_POST['no_plat'];
        $nama_sopir = $_POST['nama_sopir'];
        $kapasitas = $_POST['kapasitas'];

        $update_query = "UPDATE tb_bus SET no_plat = ?, nama_sopir = ?, kapasitas = ? WHERE id_bus = ?";
        $update_stmt = $koneksi->prepare($update_query);
        $update_stmt->bind_param("ssii", $no_plat, $nama_sopir, $kapasitas, $id_bus);

        if ($update_stmt->execute()) {
            $success_message = "Data bus berhasil diperbarui."; 
        } else {
            $error_message = "Gagal memperbarui data bus.";
        }

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
        ind-loading-admin {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
<header class="dashboard">
    <div class="navbar">
        <h1 tabindex="0">Dashboard Admin</h1>
        <ul class="menu">
            <li><a tabindex="0" href="adminrute.php" style="background-color: #C8ACD6;">Rute</a></li>
            <li><a tabindex="0" href="adminjadwal.php">Jadwal</a></li>
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
<ind-loading-admin></ind-loading-admin>
    <h1 tabindex="0">Edit Bus</h1>
    <div class="form-container">
        <form action="edit_bus.php?id_bus=<?= $id_bus ?>" method="POST">
            <label tabindex="0" for="no_plat">No. Plat</label>
            <input tabindex="0" type="text" id="no_plat" name="no_plat" value="<?= $bus['no_plat'] ?? '' ?>" required>

            <label tabindex="0" for="nama_sopir">Nama Sopir</label>
            <input tabindex="0" type="text" id="nama_sopir" name="nama_sopir" value="<?= $bus['nama_sopir'] ?? '' ?>" required>

            <label tabindex="0" for="kapasitas">Kapasitas</label>
            <input tabindex="0" type="number" id="kapasitas" name="kapasitas" value="<?= $bus['kapasitas'] ?? '' ?>" required>
            
            <a tabindex="0" href="adminrute.php">Kembali</a>
            <button tabindex="0" type="submit">Edit Bus</button>
        </form>
    </div>
</main>

<?php if (isset($success_message)): ?>
    <div id="popup-success" class="popup">
        <div class="popup-content">
            <h3><?= $success_message ?></h3>
            <button tabindex="0" onclick="redirectToRoute()">Tutup</button>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
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

    function redirectToRoute() {
        window.location.href = 'adminrute.php';
    }

    <?php if (isset($success_message)): ?>
        document.getElementById('popup-success').style.display = 'flex';
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        document.getElementById('popup-error').style.display = 'flex';
    <?php endif; ?>

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector("form");
        const loadingIndicator = document.querySelector("ind-loading-admin");

        form.addEventListener("submit", (event) => {
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
                    // Sembunyikan indikator loading
                    loadingIndicator.style.display = "none";
                    document.body.classList.remove("no-scroll");

                    // Perbarui tampilan berdasarkan hasil
                    if (result.includes("Data bus berhasil diperbarui")) {
                        document.getElementById('popup-success').style.display = 'flex';
                    } else {
                        document.getElementById('popup-error').style.display = 'flex';
                    }
                })
                .catch((error) => {
                    // Sembunyikan indikator loading
                    loadingIndicator.style.display = "none";
                    document.body.classList.remove("no-scroll");

                    // Tampilkan popup error
                    document.getElementById('popup-error').style.display = 'flex';
                    console.error("Error:", error);
                });
        });
    });
</script>
</body>
</html>

