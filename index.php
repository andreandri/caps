<?php
include("koneksi.php");

// Cek apakah ada parameter id_bus di URL
if (isset($_GET['id_bus'])) {
    $id_bus = $_GET['id_bus'];

    // Query untuk mendapatkan informasi kursi berdasarkan id_bus
    $sql = "SELECT * FROM tb_kursi WHERE id_bus = '$id_bus' AND status = 'available'";  // Mengambil kursi yang tersedia
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Pilih Kursi</h2>";
        echo "<div class='kursi-container'>";
        while ($row = $result->fetch_assoc()) {
            // Menampilkan kursi yang tersedia
            $id_kursi = $row['id_kursi'];
            echo "<a href='proses_pemesanan.php?id_kursi={$id_kursi}' class='kursi-card'>
                    <div class='kursi'>
                        <p>Kursi: {$row['nomor_kursi']}</p>
                    </div>
                  </a>";
        }
        echo "</div>";
    } else {
        echo "<p style='color: red; text-align: center;'>Tidak ada kursi tersedia untuk bus ini.</p>";
    }
} else {
    echo "<p style='color: red; text-align: center;'>Bus tidak ditemukan.</p>";
}

$koneksi->close();
?>
