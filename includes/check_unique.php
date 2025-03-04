<?php
include "db.php";

$response = ['exists' => false];

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    $stmt = $conn->prepare("SELECT id FROM buyers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['exists'] = $result->num_rows > 0;
}

if (isset($_GET['whatsapp'])) {
    $whatsapp = trim($_GET['whatsapp']);
    $stmt = $conn->prepare("SELECT id FROM buyers WHERE whatsapp = ?");
    $stmt->bind_param("s", $whatsapp);
    $stmt->execute();
    $result = $stmt->get_result();
    $response['exists'] = $result->num_rows > 0;
}

// Set header JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
