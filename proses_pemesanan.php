<?php
include("koneksi.php");

if (isset($_GET['id_kursi'])) {
  $id_kursi = $_GET['id_kursi'];
  $id_busjadwal = isset($_GET['id_busjadwal']) ? $_GET['id_busjadwal'] : 0;

  $sql = "UPDATE tb_kursi SET status = 'booked' WHERE id_kursi = '$id_kursi'";
  if ($koneksi->query($sql) === TRUE) {
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
