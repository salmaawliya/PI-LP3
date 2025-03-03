<?php
$host = "localhost";
$user = "root";  // Ubah jika MySQL menggunakan user lain
$pass = "";      // Isi jika ada password
$db   = "landing_page_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>