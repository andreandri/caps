<?php
include("koneksi.php");
session_start();

$id_busjadwal = $_GET['id_busjadwal'];

$sql = "SELECT id_kursi, nomor_kursi, status 
        FROM tb_kursi 
        WHERE id_bus = (SELECT id_bus FROM tb_busjadwal WHERE id_busjadwal = ?) 
        AND status = 'available'";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_busjadwal);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pilih Kursi</title>
</head>
<body>
    <h2>Pilih Kursi</h2>
    <form action="tiketmasuk.php" method="POST">
        <input type="hidden" name="id_busjadwal" value="<?php echo $id_busjadwal; ?>">
        
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<label>
                        <input type='checkbox' name='kursi[]' value='{$row['id_kursi']}'>
                        Kursi {$row['nomor_kursi']}
                      </label><br>";
            }
        } else {
            echo "<p>Semua kursi untuk jadwal ini sudah terisi.</p>";
        }
        ?>
        
        <button type="submit">Lanjutkan ke Data Penumpang</button>
    </form>
</body>
</html>
