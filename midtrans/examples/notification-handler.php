<?php
// Import Midtrans library
namespace Midtrans;

require_once dirname(__FILE__) . '/../Midtrans.php';

// Konfigurasi Midtrans
Config::$isProduction = false; // Ubah ke true jika sudah dalam mode produksi
Config::$serverKey = 'SB-Mid-server-ZwVxKRABKeqWj-jebaE_OvA3';

// Fungsi untuk menampilkan pesan peringatan jika diakses langsung
function printExampleWarningMessage() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler hanya untuk menangani notifikasi Midtrans via HTTP POST, bukan untuk diakses langsung via browser.';
        exit;
    }
    if (strpos(Config::$serverKey, 'your ') !== false) {
        echo "<code>";
        echo "<h4>Silakan atur server key dari sandbox</h4>";
        echo "Di file: " . __FILE__;
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<Server Key>\';');
        die();
    }
}

// Pastikan script hanya dijalankan melalui HTTP POST
printExampleWarningMessage();

try {
    // Tangkap notifikasi dari Midtrans
    $notif = new Notification();
} catch (\Exception $e) {
    exit($e->getMessage());
}

// Ambil data notifikasi
$transaction = $notif->transaction_status;
$order_id = $notif->order_id; // Gunakan order_id langsung dari notifikasi
$type = $notif->payment_type;
$fraud = $notif->fraud_status;

// Koneksi ke database
include "koneksi.php";

// Debugging: Tampilkan data notifikasi untuk memverifikasi
echo "<pre>";
print_r($notif);
echo "</pre>";

// Debugging: Tampilkan order_id
echo "Order ID: " . $order_id . "<br>";  // Untuk debugging

// Update status pembayaran berdasarkan notifikasi
if ($transaction == 'settlement') {
    // Pembayaran sukses, ubah status menjadi "lunas"
    $sql = "UPDATE tb_pemesanan SET status_pembayaran='lunas' WHERE order_id='$order_id'";
} else if ($transaction == 'pending') {
    // Pembayaran menunggu, ubah status menjadi "pending"
    $sql = "UPDATE tb_pemesanan SET status_pembayaran='pending' WHERE order_id='$order_id'";
} else if ($transaction == 'deny') {
    // Pembayaran ditolak, ubah status menjadi "dibatalkan"
    $sql = "UPDATE tb_pemesanan SET status_pembayaran='dibatalkan' WHERE order_id='$order_id'";
} else if ($transaction == 'expire') {
    // Pembayaran kedaluwarsa, ubah status menjadi "dibatalkan"
    $sql = "UPDATE tb_pemesanan SET status_pembayaran='dibatalkan' WHERE order_id='$order_id'";
} else if ($transaction == 'cancel') {
    // Pembayaran dibatalkan oleh pengguna, ubah status menjadi "dibatalkan"
    $sql = "UPDATE tb_pemesanan SET status_pembayaran='dibatalkan' WHERE order_id='$order_id'";
}

// Debugging: Tampilkan query SQL yang dijalankan
echo "SQL Query: " . $sql . "<br>";  // Untuk debugging

// Jalankan query untuk memperbarui status pembayaran
if (mysqli_query($koneksi, $sql)) {
    echo "Status pembayaran berhasil diperbarui untuk order_id: $order_id";
} else {
    echo "Gagal memperbarui status pembayaran: " . mysqli_error($koneksi);
}
?>
