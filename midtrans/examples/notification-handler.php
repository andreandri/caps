<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for sample HTTP notifications:
// https://docs.midtrans.com/en/after-payment/http-notification?id=sample-of-different-payment-channels

namespace Midtrans;

require_once dirname(__FILE__) . '/../Midtrans.php';
Config::$isProduction = false;
Config::$serverKey = 'SB-Mid-server-ZwVxKRABKeqWj-jebaE_OvA3';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

try {
    $notif = new Notification();
} catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$transaction_id = $notif->transaction_id;

$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

include "koneksi.php";

// Adjusting the SQL queries to match your `tb_pemesanan` table
if ($transaction == 'settlement') {
    // Update status to 'lunas' (paid) for the given order
    mysqli_query($koneksi, "UPDATE tb_pemesanan SET status_pembayaran='lunas', transaction_id='$transaction_id' WHERE id_pemesanan='$order_id'");
} else if ($transaction == 'pending') {
    // Update status to 'pending' for the given order
    mysqli_query($koneksi, "UPDATE tb_pemesanan SET status_pembayaran='pending' WHERE id_pemesanan='$order_id'");
} else if ($transaction == 'deny') {
    // Update status to 'dibatalkan' (canceled) for the given order
    mysqli_query($koneksi, "UPDATE tb_pemesanan SET status_pembayaran='dibatalkan' WHERE id_pemesanan='$order_id'");
} else if ($transaction == 'expire') {
    // Update status to 'dibatalkan' (canceled) for the given order due to expiration
    mysqli_query($koneksi, "UPDATE tb_pemesanan SET status_pembayaran='dibatalkan' WHERE id_pemesanan='$order_id'");
} else if ($transaction == 'cancel') {
    // Update status to 'dibatalkan' (canceled) for the given order due to user cancellation
    mysqli_query($koneksi, "UPDATE tb_pemesanan SET status_pembayaran='dibatalkan' WHERE id_pemesanan='$order_id'");
}

function printExampleWarningMessage() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler are not meant to be opened via browser / GET HTTP method. It is used to handle Midtrans HTTP POST notification / webhook.';
    }
    if (strpos(Config::$serverKey, 'your ') !== false) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<Server Key>\';');
        die();
    }   
}
