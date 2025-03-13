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

// Ambil daftar transaksi dari database
$transactions = $conn->query("SELECT * FROM transaksi ORDER BY id DESC");
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
                <hr class="border-gray-600 mt-10">
                <a href="dashboard.php" class="block py-2.5 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-list-task mr-2"></i> Daftar Transaksi
                </a>
                <hr class="border-gray-600">
                <a href="edit_profile.php" class="block py-2.5 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-person mr-2"></i> Edit Profil
                </a>
                <hr class="border-gray-600">
                <a href="logout.php" class="block py-2.5 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                </a>
                <hr class="border-gray-600">
            </nav>
        </div>

        <!-- Sidebar (Mobile) -->
        <div class="sm:hidden w-full absolute bg-gray-800 text-white" x-show="open">
            <nav class="space-y-2 p-4">
                <a href="dashboard.php" class="block py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-list-task mr-2"></i> Daftar Transaksi
                </a>
                <hr class="border-gray-600 my-4">
                <a href="edit_profile.php" class="block py-2 px-4 hover:bg-gray-700 rounded">
                    <i class="bi bi-box-arrow-right mr-2"></i> Edit Profile
                </a>
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
                <h2 class="text-2xl font-bold mb-4">Daftar Transaksi</h2>
                <div class="bg-white p-4 shadow-md rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
                            <thead>
                                <tr class="bg-gray-800 text-white">
                                    <th class="border p-2">No</th>
                                    <th class="border p-2">Order ID</th>
                                    <th class="border p-2">Nama</th>
                                    <th class="border p-2">Email</th>
                                    <th class="border p-2">Nomor WhatsApp</th>
                                    <th class="border p-2">Total Harga</th>
                                    <th class="border p-2">Status Pembayaran</th>
                                    <th class="border p-2">Tanggal</th>
                                    <th class="border p-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($transactions->num_rows > 0): ?>
                                    <?php $no = 1; while ($row = $transactions->fetch_assoc()): ?>
                                        <tr class="text-center">
                                            <td class="border p-2"><?= $no++ ?></td>
                                            <td class="border p-2"><?= htmlspecialchars($row['order_id']) ?></td>
                                            <td class="border p-2"><?= htmlspecialchars($row['nama']) ?></td>
                                            <td class="border p-2"><?= htmlspecialchars($row['email']) ?></td>
                                            <td class="border p-2"><?= htmlspecialchars($row['no_wa']) ?></td>
                                            <td class="border p-2">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                            <td class="border p-2">
                                                <span class="px-2 py-1 rounded text-white 
                                                    <?php if ($row['status_pembayaran'] == 'Success') echo 'bg-green-500'; ?>
                                                    <?php if ($row['status_pembayaran'] == 'Pending') echo 'bg-orange-500'; ?>
                                                    <?php if ($row['status_pembayaran'] == 'Failed') echo 'bg-red-500'; ?>">
                                                    <?= htmlspecialchars($row['status_pembayaran']) ?>
                                                </span>
                                            </td>
                                            <td class="border p-2"><?= htmlspecialchars($row['tanggal']) ?></td>
                                            <td class="border p-2 text-center">
                                                <a href="../includes/delete_transaction.php?id=<?= $row['id']; ?>" 
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs sm:text-sm"
                                                onclick="return confirm('Yakin ingin menghapus transaksi ini?');">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="border p-4 text-center text-gray-500">Belum ada transaksi.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
