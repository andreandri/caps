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
                echo "<script>alert('Rute berhasil diperbarui.'); window.location.href = 'adminrute.php';</script>";
            } else {
                echo "<script>alert('Gagal memperbarui rute: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Harap isi semua field.');</script>";
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
</head>
<body>
    <div>
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
    </div>
</body>
</html>
