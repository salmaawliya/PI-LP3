<?php
include "db.php";

header('Content-Type: application/json'); // Pastikan response dalam format JSON

if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Persiapkan query untuk mencari status pembayaran
    $stmt = $conn->prepare("SELECT status_pembayaran FROM transaksi WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(['status' => $data['status_pembayaran']]);
    } else {
        echo json_encode(['error' => 'Order ID tidak ditemukan']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Order ID tidak valid']);
}
?>
