<?php
session_start();
include "../includes/db.php";

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Ambil data admin yang login
$adminId = $_SESSION['admin'];
$stmt = $conn->prepare("SELECT username FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$adminName = $admin['username'];

// Ambil daftar pembeli dari database
$buyers = $conn->query("SELECT * FROM buyers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100">
    <div x-data="{ open: false }" class="flex min-h-screen">
        
        <!-- Sidebar (Desktop) -->
        <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 hidden sm:block">
            <h2 class="text-2xl font-bold text-center">Admin Panel</h2>
            <p class="text-center text-gray-300">Halo, <?= htmlspecialchars($adminName) ?>!</p>

            <nav>
                <!-- Garis Pembatas -->
                <hr class="border-gray-600 mt-10">
                <a href="dashboard.php" class="block py-2.5 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-list-task mr-2"></i> Daftar Pembeli
                </a>
                <!-- Garis Pembatas -->
                <hr class="border-gray-600">
                <a href="logout.php" class="block py-2.5 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                </a>
                <!-- Garis Pembatas -->
                <hr class="border-gray-600">
            </nav>
        </div>

        <!-- Sidebar (Mobile) -->
        <div class="sm:hidden w-full absolute bg-gray-800 text-white" x-show="open">
            <nav class="space-y-2 p-4">
                <a href="dashboard.php" class="block py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-list-task mr-2"></i> Daftar Pembeli
                </a>

                <!-- Garis Pembatas -->
                <hr class="border-gray-600 my-4">

                <a href="logout.php" class="block py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Konten -->
        <div class="flex-1">
            
            <!-- Navbar Mobile -->
            <header class="sm:hidden bg-gray-800 text-white p-4 flex justify-between items-center">
                <button @click="open = !open">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h2 class="text-xl font-bold">Admin Panel</h2>
                <p class="text-gray-300 text-sm">Halo, <?= htmlspecialchars($adminName) ?>!</p>
            </header>

            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Daftar Pembeli</h2>
                <div class="bg-white p-4 shadow-md rounded-lg">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-800 text-white">
                                <th class="border p-2">No</th>
                                <th class="border p-2">Nama</th>
                                <th class="border p-2">Email</th>
                                <th class="border p-2">Nomor WhatsApp</th>
                                <th class="border p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($buyers->num_rows > 0): ?>
                                <?php $no = 1; while ($row = $buyers->fetch_assoc()): ?>
                                    <tr class="text-center">
                                        <td class="border p-2"><?= $no++ ?></td>
                                        <td class="border p-2"><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="border p-2"><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="border p-2"><?= htmlspecialchars($row['whatsapp']) ?></td>
                                        <td class="border p-2 text-center">
                                            <a href="../includes/delete_buyer.php?id=<?= $row['id']; ?>" 
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                            onclick="return confirm('Yakin ingin menghapus pembeli ini?');">
                                            <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="border p-4 text-center text-gray-500">Belum ada pembeli.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>