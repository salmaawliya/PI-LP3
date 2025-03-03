<?php
session_start();
$status = isset($_GET['status']) ? $_GET['status'] : ''; // Cek status dari URL
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Register Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen px-4">
    <div class="bg-white p-6 shadow-md rounded-lg w-full max-w-sm md:max-w-md sm:max-w-sm">
        <h2 class="text-2xl font-bold mb-4 text-center">Register Admin</h2>

        <form action="../includes/save_admin.php" method="POST" class="space-y-4" x-data="{ username: '', email: '', password: '', usernameError: false, emailError: false, passwordError: false }" @submit.prevent="usernameError = username === ''; emailError = email === ''; passwordError = password === ''; if (!usernameError && !emailError && !passwordError) $el.submit()">
            <div>
                <input type="text" name="username" x-model="username" placeholder="Username" class="w-full border p-2 rounded">
                <p class="text-red-500 text-sm" x-show="usernameError">Username wajib diisi!</p>
            </div>
            <div>
                <input type="email" name="email" x-model="email" placeholder="Email" class="w-full border p-2 rounded">
                <p class="text-red-500 text-sm" x-show="emailError">Email wajib diisi!</p>
            </div>
            <div>
                <input type="password" name="password" x-model="password" placeholder="Password" class="w-full border p-2 rounded">
                <p class="text-red-500 text-sm" x-show="passwordError">Password wajib diisi!</p>
            </div>
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded">Register</button>
        </form>

        <!-- Pesan Notifikasi -->
        <div x-data="{ status: '<?php echo $status; ?>' }" class="mt-5"> 
            <p class="text-green-600 text-center mb-2" x-show="status === 'success'">Registrasi berhasil!! Silahkan Login.</p>
            <p class="text-red-600 text-center mb-2" x-show="status === 'error'">Registrasi gagal!! Coba lagi.</p>
        </div>

        <p class="text-center mt-4">Sudah punya akun? 
            <a href="index.php" class="text-blue-500 hover:underline">Login di sini</a>
        </p>
    </div>
</body>
</html>