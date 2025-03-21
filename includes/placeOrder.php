<?php

/*Install Midtrans PHP Library (https://github.com/Midtrans/midtrans-php)
composer require midtrans/midtrans-php
                              
Alternatively, if you are not using **Composer**, you can download midtrans-php library 
(https://github.com/Midtrans/midtrans-php/archive/master.zip), and then require 
the file manually.   

require_once dirname(__FILE__) . '/pathofproject/Midtrans.php'; */
include "db.php";
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

//SAMPLE REQUEST START HERE

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-xqKFnkFh5VFk7tU9dXOi81xr';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

// Ambil data dari request
$postData = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($postData['name'], $postData['email'], $postData['phone'])) {
    echo json_encode(['error' => 'Harap isi semua data yang diperlukan.']);
    exit;
}

// Harga tetap Rp 649.000
$totalHarga = 649000;
$item_details = [
    [
        'id' => 'product-001',
        'price' => 649000,
        'quantity' => 1,
        'name' => 'Digital Satu Persen | NR HOUSE'
    ]
];

// Buat order ID unik
$order_id = "ORDER-" . time();

// Simpan transaksi ke database dengan pengecekan error
try {
    $stmt = $conn->prepare("INSERT INTO transaksi (order_id, nama, email, no_wa, total_harga, status_pembayaran) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("ssssi", $order_id, $postData['name'], $postData['email'], $postData['phone'], $totalHarga);
    $stmt->execute();
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
    exit;
}

// Data transaksi Midtrans
$transaction = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => $totalHarga
    ],
    'item_details' => $item_details,
    'customer_details' => [
        'first_name' => $postData['name'],
        'email' => $postData['email'],
        'phone' => $postData['phone']
    ]
];

try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    echo json_encode(['snapToken' => $snapToken, 'order_id' => $order_id]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Gagal mendapatkan Snap Token: ' . $e->getMessage()]);
}

?>