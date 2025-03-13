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
$currentUsername = $admin['username'];

$successMessage = "";
$errorMessage = "";

// Jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = trim($_POST['username']);
    $newPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validasi input kosong
    if (empty($newUsername)) {
        $errorMessage = "Username tidak boleh kosong!";
    } elseif (!empty($newPassword) && $newPassword !== $confirmPassword) {
        $errorMessage = "Konfirmasi password tidak cocok!";
    } else {
        // Update username
        $updateStmt = $conn->prepare("UPDATE admin_users SET username = ? WHERE id = ?");
        $updateStmt->bind_param("si", $newUsername, $adminId);
        $updateStmt->execute();

        // Jika password diisi, update password juga
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $passStmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
            $passStmt->bind_param("si", $hashedPassword, $adminId);
            $passStmt->execute();
        }

        $successMessage = "Profil berhasil diperbarui!";
        $_SESSION['admin_name'] = $newUsername;
    }
}
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
            <p class="text-center text-gray-300">Halo, <?= htmlspecialchars($currentUsername) ?>!</p>

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
                <p class="text-gray-300 text-sm">Halo, <?= htmlspecialchars($currentUsername) ?>!</p>
            </header>

            <div class="flex-1 p-6 ">
            <h2 class="text-2xl font-bold mb-4 text-center">Edit Profil</h2>
            <div class="bg-white p-6 shadow-lg rounded-lg max-w-lg md:max-w-md sm:max-w-full mx-auto">
                <?php if ($successMessage): ?>
                    <p class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $successMessage ?></p>
                <?php elseif ($errorMessage): ?>
                    <p class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $errorMessage ?></p>
                <?php endif; ?>

                <form action="" method="POST">
                    <!-- Username -->
                    <div class="mb-4">
                        <label class="block font-bold">Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($currentUsername) ?>" class="w-full border p-2 rounded-lg">
                    </div>

                    <!-- Password Baru -->
                    <div class="mb-4">
                        <label class="block font-bold">Password Baru</label>
                        <input type="password" name="password" class="w-full border p-2 rounded-lg" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-4">
                        <label class="block font-bold">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="w-full border p-2 rounded-lg" placeholder="Masukkan kembali password baru">
                    </div>

                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg w-full">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
        </div>
        
    </div>
</body>
</html>
