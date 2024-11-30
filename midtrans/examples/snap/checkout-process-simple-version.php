<?php
// Konfigurasi API Midtrans
namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
Config::$serverKey = 'SB-Mid-server-ZwVxKRABKeqWj-jebaE_OvA3'; 
Config::$clientKey = 'SB-Mid-client-0EXZ4KcHAlgRlBle';
Config::$isProduction = false; // Sandbox mode
Config::$isSanitized = Config::$is3ds = true;

// Include koneksi ke database
include "../../../koneksi.php";
session_start();

// Validasi ID Pemesanan
$id_pemesanan = $_GET['id_pemesanan'] ?? null;

if ($id_pemesanan) {
    // Cek apakah data pemesanan sudah ada di session
    if (!isset($_SESSION['pemesanan'][$id_pemesanan])) {
        // Query untuk mendapatkan data pemesanan dan email pengguna
        $query = $koneksi->prepare("
            SELECT 
                p.id_pemesanan, 
                p.nama_penumpang, 
                p.total, 
                p.no_wa, 
                u.email 
            FROM 
                tb_pemesanan p 
            JOIN 
                tb_users u 
            ON 
                p.username = u.username
            WHERE 
                p.id_pemesanan = ?
        ");
        $query->bind_param("i", $id_pemesanan);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();

            // Simpan data pemesanan ke session
            $_SESSION['pemesanan'][$id_pemesanan] = $data;

            // Generate order_id
            $order_id = 'ORDER_' . $id_pemesanan . '_' . bin2hex(random_bytes(5)) . time();

            // Detail transaksi
            $transaction_details = array(
                'order_id' => $order_id,
                'gross_amount' => $data['total'],
            );

            // Detail item
            $item_details = array(
                array(
                    'id' => 'TIKET_' . $id_pemesanan,
                    'price' => $data['total'],
                    'quantity' => 1,
                    'name' => "Pembayaran Tiket",
                ),
            );

            // Informasi customer
            $customer_details = array(
                'first_name' => $data['nama_penumpang'],
                'last_name' => '',
                'email' => $data['email'],
                'phone' => $data['no_wa'],
            );

            // Data transaksi ke Midtrans
            $transaction = array(
                'transaction_details' => $transaction_details,
                'item_details' => $item_details,
                'customer_details' => $customer_details,
            );

            try {
                // Dapatkan Snap Token
                $snap_token = Snap::getSnapToken($transaction);

                // Simpan Snap Token ke session
                $_SESSION['snap_token'][$id_pemesanan] = $snap_token;
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
                exit;
            }
        } else {
            echo "Pemesanan tidak ditemukan.";
            exit;
        }
    } else {
        // Ambil data pemesanan dan Snap Token dari session
        $data = $_SESSION['pemesanan'][$id_pemesanan];
        $snap_token = $_SESSION['snap_token'][$id_pemesanan];
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
  <header>
    <bar-user-app></bar-user-app>
  </header>
  <main>
    <div class="container mt-5">
      <div class="card">
        <div class="card-body">
          <h5>Detail Pemesanan</h5>
          <table class="pembayaran">
            <tr>
              <td><strong>Nama Penumpang</strong></td>
              <td> : <?= htmlspecialchars($data['nama_penumpang']); ?></td>
            </tr>
            <tr>
              <td><strong>Total Pembayaran</strong></td>
              <td> : Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>
            </tr>
          </table>
          <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
        </div>
      </div>
    </div>

    <!-- Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= Config::$clientKey; ?>"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function () {
        const snapToken = '<?= $snap_token; ?>'; // Snap Token dari server PHP

        snap.pay(snapToken, {
          onSuccess: function (result) {
            console.log("Payment Success:", result);
          },
          onPending: function (result) {
            console.log("Payment Pending:", result);
          },
          onError: function (result) {
            console.log("Payment Error:", result);
          },
          onClose: function () {
            console.log("Payment popup closed");
          },
        });
      };
    </script>
  </main>
</body>
</html>
