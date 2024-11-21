<?php
include("koneksi.php");

// Cek apakah ada parameter id_kursi
if (isset($_GET['id_kursi'])) {
    $id_kursi = $_GET['id_kursi'];
    $id_busjadwal = isset($_GET['id_busjadwal']) ? $_GET['id_busjadwal'] : 0;

    // Query untuk memproses pemesanan kursi
    $sql = "UPDATE tb_kursi SET status = 'booked' WHERE id_kursi = '$id_kursi'"; // Update status kursi menjadi booked
    if ($koneksi->query($sql) === TRUE) {
        // Arahkan ke tiketmasuk.php dengan membawa id_kursi dan id_busjadwal
        header("Location: tiketmasuk.php?id_kursi=$id_kursi&id_busjadwal=$id_busjadwal");
        exit();
    } else {
        echo "<p>Gagal memproses pemesanan. Coba lagi nanti.</p>";
    }
} else {
    echo "<p>Pesanan gagal, kursi tidak ditemukan.</p>";
}

$koneksi->close();
?>
