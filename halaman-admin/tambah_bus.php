<?php
include("../koneksi.php");

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $no_plat = $_POST['no_plat'];
  $nama_sopir = $_POST['nama_sopir'];
  $kapasitas = $_POST['kapasitas'];

  if (!empty($no_plat) && !empty($nama_sopir) && !empty($kapasitas)) {
    $sql = "INSERT INTO tb_bus (no_plat, nama_sopir, kapasitas) VALUES (?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssi", $no_plat, $nama_sopir, $kapasitas);

    if ($stmt->execute()) {
      $success_message = "Data bus berhasil ditambahkan.";
    } else {
      $error_message = "Gagal menambahkan data: " . $stmt->error;
    }

    $stmt->close();
  } else {
    $error_message = "Harap isi semua field.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Bus</title>
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

  <main class="add">
    <ind-loading-admin></ind-loading-admin>
    <h1 tabindex="0">Tambah Bus Baru</h1>
    <form action="tambah_bus.php" method="POST">
      <label tabindex="0" for="no_plat">No. Plat</label>
      <input tabindex="0" type="text" id="no_plat" name="no_plat" placeholder="Masukan Nomor Kendaraan Bus" required>

      <label tabindex="0" for="nama_sopir">Nama Sopir</label>
      <input tabindex="0" type="text" id="nama_sopir" name="nama_sopir" placeholder="Masukan Nama Sopir" required>

      <label tabindex="0" for="kapasitas">Kapasitas</label>
      <input tabindex="0" type="number" id="kapasitas" name="kapasitas" placeholder="Masukan Kapasitas Bus" required>

      <div class="tombol">
        <a tabindex="0" href="adminrute.php">Kembali</a>
        <button tabindex="0" type="submit">Tambah Bus</button>
      </div>
    </form>
  </main>

  <?php if (!empty($success_message)): ?>
    <div id="popup-success" class="popup">
      <div class="popup-content">
        <h3><?= $success_message ?></h3>
        <button tabindex="0" onclick="redirectToRoute()">Tutup</button>
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

    function redirectToRoute() {
      window.location.href = 'adminrute.php';
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

            if (result.includes("Data jadwal bus berhasil ditambahkan")) {
              window.location.href = "adminrute.php";
            } else {}
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