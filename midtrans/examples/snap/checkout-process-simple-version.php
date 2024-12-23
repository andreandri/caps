<?php

namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
Config::$serverKey = 'SB-Mid-server-ZwVxKRABKeqWj-jebaE_OvA3';
Config::$clientKey = 'SB-Mid-client-0EXZ4KcHAlgRlBle';
Config::$isProduction = false; // Sandbox mode
Config::$isSanitized = Config::$is3ds = true;

include "../../../koneksi.php";
session_start();

$id_pemesanan = $_GET['id_pemesanan'] ?? null;

if ($id_pemesanan) {
  if (!isset($_SESSION['pemesanan'][$id_pemesanan])) {
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

      $_SESSION['pemesanan'][$id_pemesanan] = $data;

      $order_id = 'ORDER_' . $id_pemesanan . '_' . bin2hex(random_bytes(5)) . time();

      $update_order_query = $koneksi->prepare("
                UPDATE tb_pemesanan 
                SET order_id = ? 
                WHERE id_pemesanan = ?
            ");
      $update_order_query->bind_param("si", $order_id, $id_pemesanan);
      $update_order_query->execute();

      if ($update_order_query->affected_rows === 0) {
        echo "Gagal memperbarui order_id.";
        exit;
      }

      $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => $data['total'],
      );

      $item_details = array(
        array(
          'id' => 'TIKET_' . $id_pemesanan,
          'price' => $data['total'],
          'quantity' => 1,
          'name' => "Pembayaran Tiket",
        ),
      );

      $customer_details = array(
        'first_name' => $data['nama_penumpang'],
        'last_name' => '',
        'email' => $data['email'],
        'phone' => $data['no_wa'],
      );

      $transaction = array(
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details,
      );

      try {
        $snap_token = Snap::getSnapToken($transaction);

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
  <link rel="icon" href="favicon.png" type="image/png">
  <script type="module" src="bar.js"></script>
  <script type="module" src="../../../scripts/index.js"></script>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    .no-scroll {
      overflow: hidden;
      height: 100vh;
    }

    body {
      height: 100%;
      width: 100%;
      background-color: #f5f5f5;
      font-family: 'Poppins', sans-serif;
    }

    header {
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .card {
      width: 100%;
      max-width: 400px;
      place-self: center;
      margin-top: 2rem;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      padding: 20px;
    }

    .card-body h5 {
      margin-bottom: 20px;
      font-size: 1.25rem;
      font-weight: bold;
      text-align: center;
    }

    .pembayaran td,
    .pembayaran th {
      padding: 10px 5px 10px 0;
      font-size: 1rem;
    }

    .pembayaran tr {
      border-bottom: 1px solid #b3b5b5;
      padding-bottom: 10px;
    }

    .btn-primary {
      width: 100%;
      margin-top: 20px;
      padding: 10px;
      font-size: 1.1rem;
      background-color: #4caf50;
      border: none;
    }

    .card-body button:hover {
      background-color: #45a049;
    }

    .total {
      font-size: 18px;
      font-weight: bold;
    }

    @media (max-width: 576px) {
      .container {
        display: block;
        height: 50vh;
      }

      .card {
        width: 90%;
        max-width: none;
        place-self: center;
      }

      .card-body h5 {
        font-size: 1.3rem;
        margin-bottom: 0;

      }

      .btn-primary {
        font-size: 1rem;
      }
    }

    @media (max-width: 450px) {
      .card {
        background-color: transparent;
        box-shadow: none;
        border: none;
        margin-top: 1rem;
      }

      .card-body {
        padding: 0 10px;
      }

      .btn-primary {
        padding: 10px;
        font-size: 1rem;
      }

      .pembayaran td,
      .pembayaran th {
        font-size: 0.9rem;
      }
    }

    @media (max-width: 350px) {
      .card {
        width: 98%;
      }

      .btn-primary {
        padding: 6px;
        font-size: 1rem;
      }

      .pembayaran td,
      .pembayaran th {
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>
  <header>
    <bar-user-app></bar-user-app>
  </header>
  <main>
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

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= Config::$clientKey; ?>"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function() {
        const snapToken = '<?= $snap_token; ?>';

        snap.pay(snapToken, {
          onSuccess: function(result) {
            console.log("Payment Success:", result);
            window.location.href = "../../../tampilan.php";
          },
          onPending: function(result) {
            console.log("Payment Pending:", result);
            window.location.href = "../../../history.php";
          },
          onError: function(result) {
            console.log("Payment Error:", result);
            window.location.href = "../../../history.php";
          },
          onClose: function() {
            console.log("Payment popup closed");
            window.location.href = "../../../history.php";
          },
        });
      };
    </script>
  </main>
</body>

</html>