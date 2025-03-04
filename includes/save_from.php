<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $whatsapp = trim($_POST["whatsapp"]);

    if (!empty($name) && !empty($email) && !empty($whatsapp)) {
        // Cek apakah email sudah ada
        $stmt = $conn->prepare("SELECT id FROM buyers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: ../index.html?status=email_exists");
            exit();
        }

        // Cek apakah WhatsApp sudah ada
        $stmt = $conn->prepare("SELECT id FROM buyers WHERE whatsapp = ?");
        $stmt->bind_param("s", $whatsapp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: ../index.html?status=whatsapp_exists");
            exit();
        }

        // Jika email & whatsapp belum ada, simpan ke database
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
