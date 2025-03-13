<?php
session_start();
include "db.php";

// Pastikan admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: ../admin/index.php");
    exit();
}

// Pastikan ada parameter ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Hapus dari database
    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: ../admin/dashboard.php?success=deleted");
    } else {
        header("Location: ../admin/dashboard.php?error=failed");
    }
}
?>