<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $whatsapp = trim($_POST["whatsapp"]);

    if (!empty($name) && !empty($email) && !empty($whatsapp)) {
        $stmt = $conn->prepare("INSERT INTO buyers (name, email, whatsapp) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $whatsapp);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil disimpan.'); window.location.href='../index.html';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.'); window.location.href='../index.html';</script>";
        }
    } else {
        echo "<script>alert('Semua field harus diisi!'); window.location.href='../index.html';</script>";
    }
}
?>