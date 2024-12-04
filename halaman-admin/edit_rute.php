<?php
include("../koneksi.php");

if (isset($_GET['id_rute'])) {
    $id_rute = $_GET['id_rute'];

    $query = "SELECT * FROM tb_rute WHERE id_rute = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_rute);
    $stmt->execute();
    $result = $stmt->get_result();
    $rute = $result->fetch_assoc();
    $stmt->close();

    if (!$rute) {
        echo "<script>alert('Rute tidak ditemukan.'); window.location.href = 'adminrute.php';</script>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kota_asal = $_POST['kota_asal'];
        $kota_tujuan = $_POST['kota_tujuan'];

        if (!empty($kota_asal) && !empty($kota_tujuan)) {
            $sql = "UPDATE tb_rute SET kota_asal = ?, kota_tujuan = ? WHERE id_rute = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssi", $kota_asal, $kota_tujuan, $id_rute);

            if ($stmt->execute()) {
                $success_message = "Rute berhasil diperbarui.";
            } else {
                $error_message = "Gagal memperbarui rute: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Harap isi semua field.";
        }
    }
} else {
    echo "<script>alert('ID rute tidak ditemukan.'); window.location.href = 'adminrute.php';</script>";
    exit;
}

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
<h1 tabindex="0">Edit Rute Keberangkatan</h1>
    <div class="form-container">
      <form action="edit_rute.php?id_rute=<?= $id_rute ?>" method="POST">
          <label tabindex="0" for="kota_asal">Kota Asal</label>
          <select id="kota_asal" name="kota_asal" required>
              <option tabindex="0" value="">Pilih Kota Asal</option>
              <?php foreach ($kota_asal_enum as $kota): ?>
                  <option tabindex="0" value="<?= $kota ?>" <?= ($rute['kota_asal'] == $kota) ? 'selected' : '' ?>><?= $kota ?></option>
              <?php endforeach; ?>
          </select>

          <label tabindex="0" for="kota_tujuan">Kota Tujuan</label>
          <select id="kota_tujuan" name="kota_tujuan" required>
              <option tabindex="0" value="">Pilih Kota Tujuan</option>
              <?php foreach ($kota_tujuan_enum as $kota): ?>
                  <option tabindex="0" value="<?= $kota ?>" <?= ($rute['kota_tujuan'] == $kota) ? 'selected' : '' ?>><?= $kota ?></option>
              <?php endforeach; ?>
          </select>

          <div>
            <a tabindex="0" href="adminrute.php">Kembali</a>
            <button tabindex="0" type="submit">Update Rute</button>
          </div>
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
                    // Sembunyikan indikator loading
                    loadingIndicator.style.display = "none";
                    document.body.classList.remove("no-scroll");

                    // Tampilkan pesan sukses/gagal dalam popup
                    if (result.includes("Data jadwal bus berhasil ditambahkan")) {
                        const popup = document.createElement("div");
                        popup.className = "popup";
                        popup.style.display = "flex";

                        const popupContent = document.createElement("div");
                        popupContent.className = "popup-content";

                        const message = document.createElement("h3");
                        message.textContent = "Data jadwal bus berhasil ditambahkan!";

                        const closeButton = document.createElement("button");
                        closeButton.textContent = "Tutup";
                        closeButton.addEventListener("click", () => {
                            popup.style.display = "none";
                            window.location.href = "adminrute.php";
                        });

                        popupContent.appendChild(message);
                        popupContent.appendChild(closeButton);
                        popup.appendChild(popupContent);
                        document.body.appendChild(popup);
                    } else {
                        const popup = document.createElement("div");
                        popup.className = "popup";
                        popup.style.display = "flex";

                        const popupContent = document.createElement("div");
                        popupContent.className = "popup-content";

                        const message = document.createElement("h3");
                        message.textContent = "Terjadi kesalahan. Silakan coba lagi.";

                        const closeButton = document.createElement("button");
                        closeButton.textContent = "Tutup";
                        closeButton.addEventListener("click", () => {
                            popup.style.display = "none";
                        });

                        popupContent.appendChild(message);
                        popupContent.appendChild(closeButton);
                        popup.appendChild(popupContent);
                        document.body.appendChild(popup);
                    }
                })
                .catch((error) => {
                    // Sembunyikan indikator loading
                    loadingIndicator.style.display = "none";
                    document.body.classList.remove("no-scroll");

                    // Tampilkan pesan error dalam popup
                    const popup = document.createElement("div");
                    popup.className = "popup";
                    popup.style.display = "flex";

                    const popupContent = document.createElement("div");
                    popupContent.className = "popup-content";

                    const message = document.createElement("h3");
                    message.textContent = "Gagal mengirim data. Silakan coba lagi.";

                    const closeButton = document.createElement("button");
                    closeButton.textContent = "Tutup";
                    closeButton.addEventListener("click", () => {
                        popup.style.display = "none";
                    });

                    popupContent.appendChild(message);
                    popupContent.appendChild(closeButton);
                    popup.appendChild(popupContent);
                    document.body.appendChild(popup);

                    console.error("Error:", error);
                });
        });
    });
</script>

</body>
</html>
