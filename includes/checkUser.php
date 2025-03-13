<?php
include "db.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$response = ["emailExists" => false, "phoneExists" => false];

// Pastikan data yang diterima tidak kosong
if (!isset($data['email']) || !isset($data['phone'])) {
    echo json_encode(["error" => "Data tidak lengkap."]);
    exit;
}

$email = trim($data['email']);
$phone = trim($data['phone']);

// Cek email di database
$stmt = $conn->prepare("SELECT id FROM transaksi WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $response["emailExists"] = true;
}
$stmt->close();

// Cek nomor WhatsApp di database
$stmt = $conn->prepare("SELECT id FROM transaksi WHERE no_wa = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $response["phoneExists"] = true;
}
$stmt->close();

echo json_encode($response);
?>
