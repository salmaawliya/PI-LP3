<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen px-4">
    <div class="bg-white p-6 shadow-md rounded-lg w-full max-w-sm md:max-w-md sm:max-w-sm">
        <h2 class="text-2xl font-bold mb-4 text-center">Login Admin</h2>
        <form action="../includes/auth.php" method="POST" class="space-y-4" 
              x-data="{ username: '', password: '', usernameError: false, passwordError: false }" 
              @submit.prevent="usernameError = username === ''; passwordError = password === ''; if (!usernameError && !passwordError) $el.submit()">
            
            <div>
                <input type="text" name="username" x-model="username" placeholder="Username/Email" 
                       class="w-full border p-2 rounded focus:ring focus:ring-blue-300">
                <p class="text-red-500 text-sm mt-1" x-show="usernameError">Username atau Email wajib diisi!</p>
            </div>
            
            <div>
                <input type="password" name="password" x-model="password" placeholder="Password" 
                       class="w-full border p-2 rounded focus:ring focus:ring-blue-300">
                <p class="text-red-500 text-sm mt-1" x-show="passwordError">Password wajib diisi!</p>
            </div>
            
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded">
                Login
            </button>
        </form>

        <p class="text-center mt-4">Belum punya akun? 
            <a href="register.php" class="text-blue-500 hover:underline">Daftar di sini</a>
        </p>
    </div>
</body>
</html>