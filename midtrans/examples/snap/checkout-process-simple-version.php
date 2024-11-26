<?php
// Konfigurasi API Midtrans
namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
Config::$serverKey = ''; 
Config::$clientKey = '';
Config::$isProduction = false;
Config::$isSanitized = Config::$is3ds = true;

// Include koneksi ke database
include "../../../koneksi.php";

// Validasi ID Pemesanan
$id_pemesanan = $_GET['id_pemesanan'] ?? null;

if ($id_pemesanan) {
    // Query untuk mendapatkan data pemesanan
    $query = $koneksi->prepare("SELECT p.id_pemesanan, p.nama_penumpang, p.total, p.no_wa 
                                FROM tb_pemesanan p 
                                WHERE p.id_pemesanan = ?");
    $query->bind_param("i", $id_pemesanan);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Detail transaksi
        $transaction_details = array(
            'order_id' => 'ORDER_' . $id_pemesanan, // Use order_id instead of id_pemesanan
            'gross_amount' => $data['total'], // Total harga tiket
        );

        // Item detail (opsional)
        $item_details = array(
            array(
                'id' => 'TIKET_' . $id_pemesanan,
                'price' => $data['total'],
                'quantity' => 1,
                'name' => "Pembayaran Tiket"
            ),
        );

        // Informasi customer
        $customer_details = array(
            'first_name' => $data['nama_penumpang'],
            'last_name' => '',
            'email' => null,  // Set to null if no email
            'phone' => $data['no_wa'],
        );

        // Data transaksi ke Midtrans
        $transaction = array(
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
        );

        // Mendapatkan Snap Token
        try {
            $snap_token = Snap::getSnapToken($transaction);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
    } else {
        echo "Pemesanan tidak ditemukan.";
        exit;
    }
} else {
    echo "Order ID tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h5>Detail Pemesanan</h5>
                <p>Nama Penumpang: <?= htmlspecialchars($data['nama_penumpang']); ?></p>
                <p>Total Pembayaran: Rp <?= number_format($data['total'], 0, ',', '.'); ?></p>
                <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= Config::$clientKey; ?>"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('<?= $snap_token; ?>');
        };
    </script>
</body>
</html>