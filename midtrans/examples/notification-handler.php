<?php
namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
Config::$serverKey = 'SB-Mid-server-ZwVxKRABKeqWj-jebaE_OvA3';
Config::$isProduction = false;
Config::$isSanitized = Config::$is3ds = true;

include "../../../koneksi.php";

// Ambil notifikasi JSON dari Midtrans
$input = file_get_contents("php://input");
$notification = json_decode($input, true);

$order_id = $notification['order_id'];
$transaction_status = $notification['transaction_status'];

if ($transaction_status === 'settlement' || $transaction_status === 'capture') {
    // Pembayaran berhasil, ubah status pembayaran menjadi lunas
    $id_pemesanan = explode('_', $order_id)[1]; // Ekstrak ID Pemesanan dari order_id
    
    $query = $koneksi->prepare("UPDATE tb_pemesanan SET status_pembayaran = 'lunas' WHERE id_pemesanan = ?");
    $query->bind_param("i", $id_pemesanan);
    if ($query->execute()) {
        http_response_code(200); // Beri respons sukses ke Midtrans
        echo "OK";
    } else {
        http_response_code(500); // Jika gagal
        echo "Database update error.";
    }
} elseif ($transaction_status === 'cancel' || $transaction_status === 'expire') {
    // Pembayaran dibatalkan atau kedaluwarsa
    $id_pemesanan = explode('_', $order_id)[1];
    $query = $koneksi->prepare("UPDATE tb_pemesanan SET status_pembayaran = 'dibatalkan' WHERE id_pemesanan = ?");
    $query->bind_param("i", $id_pemesanan);
    $query->execute();
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(200); // Status lain
    echo "Unhandled status.";
}
