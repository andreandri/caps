<?php
require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $deleteQuery = "DELETE FROM tb_jadwal WHERE id_jadwal = ?";
    $stmt = $koneksi->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: adminjadwal.php");
        exit;
    } else {
        echo "<p>Error: " . $koneksi->error . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bus_schedule_id']) && !empty($_POST['delete_bus_schedule_id'])) {
    $id_busjadwal = $_POST['delete_bus_schedule_id'];

    $delete_query = "DELETE FROM tb_busjadwal WHERE id_busjadwal = ?";
    $stmt = $koneksi->prepare($delete_query);
    $stmt->bind_param("i", $id_busjadwal);

    if ($stmt->execute()) {
        header("Location: adminjadwal.php");
        exit;
    } else {
        header("Location: adminjadwal.php?error=delete_failed");
        exit;
    }

    $stmt->close();
}

$query = "SELECT j.id_jadwal, r.kota_asal, r.kota_tujuan, j.tgl_keberangkatan, j.jam_keberangkatan, j.harga, j.status_jadwal
          FROM tb_jadwal j
          JOIN tb_rute r ON j.id_rute = r.id_rute
          LEFT JOIN tb_busjadwal bj ON bj.id_jadwal = j.id_jadwal";

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
    <link rel="icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" href="styles/adminjadwal.css">
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
<ind-loading-admin></ind-loading-admin>
    <div>
        <h1 tabindex="0">Data Jadwal</h1>
        <table>
            <thead>
                <tr>
                    <th tabindex="0">Id Jadwal</th>
                    <th tabindex="0">Kota Asal</th>
                    <th tabindex="0">Kota Tujuan</th>
                    <th tabindex="0">Tanggal Keberangkatan</th>
                    <th tabindex="0">Jam Keberangkatan</th>
                    <th tabindex="0">Harga</th>
                    <th tabindex="0">Status Jadwal</th>
                    <th tabindex="0">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td tabindex="0"><?= $row['id_jadwal']; ?></td>
                        <td tabindex="0"><?= $row['kota_asal']; ?></td>
                        <td tabindex="0"><?= $row['kota_tujuan']; ?></td>
                        <td tabindex="0"><?= $row['tgl_keberangkatan']; ?></td>
                        <td tabindex="0"><?= $row['jam_keberangkatan']; ?></td>
                        <td tabindex="0"><?= $row['harga']; ?></td>
                        <td tabindex="0">
                        <?php if ($row['status_jadwal'] == 'aktif') { ?>
                            <span tabindex="0" style="color: green; font-weight: bold;">Aktif</span>
                        <?php } else { ?>
                            <span tabindex="0" style="color: red; font-weight: bold;">Tidak Aktif</span>
                        <?php } ?>
                    </td>
                        <td tabindex="0" class="action-buttons">
                            <a tabindex="0" href="editjadwal.php?id_jadwal=<?= $row['id_jadwal']; ?>" class="edit-button">Edit</a>
                            <button tabindex="0" class="delete-button" onclick="showDeletePopup(<?= $row['id_jadwal']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a tabindex="0" href="tambahjadwal.php" class="tambah-rute">Tambah Jadwal</a>

        <div id="popup-delete" class="popup">
            <div class="popup-content popup-danger">
                <h3 tabindex="0">Apakah Anda yakin ingin menghapus jadwal ini?</h3>
                <form method="POST" id="delete-form">
                    <input type="hidden" name="delete_id" id="delete_id" value="">
                    <button tabindex="0" type="submit">Ya, Hapus</button>
                </form>
                <button tabindex="0" onclick="closePopup()">Tidak, Kembali</button>
            </div>
        </div>
    </div>

    <div>
        <h1 tabindex="0">Data Bus Jadwal</h1>
        <table border="1">
            <thead>
                <tr>
                    <th tabindex="0">Id Bus Jadwal</th>
                    <th tabindex="0">Id Bus</th>
                    <th tabindex="0">Id Jadwal</th>
                    <th tabindex="0">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $bus_schedule_query = "SELECT * FROM tb_busjadwal";
            $bus_schedule_result = mysqli_query($koneksi, $bus_schedule_query);

            if ($bus_schedule_result) {
                while ($row = mysqli_fetch_assoc($bus_schedule_result)) { ?>
                    <tr>
                        <td tabindex="0"><?= $row['id_busjadwal']; ?></td>
                        <td tabindex="0"><?= $row['id_bus']; ?></td>
                        <td tabindex="0"><?= $row['id_jadwal']; ?></td>
                        <td tabindex="0" class="action-buttons">
                            <a tabindex="0" href="editjadwalbus.php?id_busjadwal=<?= $row['id_busjadwal']; ?>" class="edit-button">Edit</a>
                            <button tabindex="0" class="delete-button" onclick="showDeleteBusPopup(<?= $row['id_busjadwal']; ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php }
            } ?>
            </tbody>
        </table>
        <a tabindex="0" href="tambahjadwalbus.php" class="tambah-rute">Tambah Jadwal</a>

        <div id="popup-delete-bus" class="popup">
            <div class="popup-content popup-danger">
                <h3 tabindex="0">Apakah Anda yakin ingin menghapus data bus jadwal ini?</h3>
                <form method="POST" id="delete-bus-form">
                    <input type="hidden" name="delete_bus_schedule_id" id="delete_bus_schedule_id" value="">
                    <button tabindex="0" type="submit">Ya, Hapus</button>
                </form>
                <button tabindex="0" onclick="closeBusPopup()">Tidak, Kembali</button>
            </div>
        </div>
    </div>
</main>

<script>
    const loadingIndicator = document.querySelector("ind-loading-admin");

    function showLoading() {
        loadingIndicator.style.display = "flex";
    }

    function hideLoading() {
        loadingIndicator.style.display = "none";
    }

    function showDeletePopup(id_jadwal) {
        document.getElementById('delete_id').value = id_jadwal;
        document.getElementById('popup-delete').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('popup-delete').style.display = 'none';
    }

    function showDeleteBusPopup(id_busjadwal) {
        document.getElementById('delete_bus_schedule_id').value = id_busjadwal;
        document.getElementById('popup-delete-bus').style.display = 'flex';
    }

    function closeBusPopup() {
        document.getElementById('popup-delete-bus').style.display = 'none';
    }

    // Tangani form penghapusan jadwal
    document.getElementById("delete-form").addEventListener("submit", function (e) {
        e.preventDefault();
        showLoading();

        const formData = new FormData(this);
        fetch("adminjadwal.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert("Gagal menghapus jadwal");
                }
            })
            .catch(() => {
                alert("Terjadi kesalahan saat menghapus jadwal");
            })
            .finally(() => {
                hideLoading();
            });
    });

    // Tangani form penghapusan jadwal bus
    document.getElementById("delete-bus-form").addEventListener("submit", function (e) {
        e.preventDefault();
        showLoading();

        const formData = new FormData(this);
        fetch("adminjadwal.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert("Gagal menghapus bus jadwal");
                }
            })
            .catch(() => {
                alert("Terjadi kesalahan saat menghapus bus jadwal");
            })
            .finally(() => {
                hideLoading();
            });
    });
</script>
</body>
</html>
