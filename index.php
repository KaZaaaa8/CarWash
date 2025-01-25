<?php
session_start();
require_once 'config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/");
    exit();
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM pengguna WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['nama'] = $user['nama_lengkap'];
            $_SESSION['level'] = $user['level'];

            // Update last login
            $id_pengguna = $user['id_pengguna'];
            mysqli_query($koneksi, "UPDATE pengguna SET last_login = NOW() WHERE id_pengguna = '$id_pengguna'");

            // Log activity
            $ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            mysqli_query($koneksi, "INSERT INTO log_aktivitas (id_pengguna, aktivitas, ip_address, user_agent) 
                                  VALUES ('$id_pengguna', 'Login ke sistem', '$ip', '$user_agent')");

            header("Location: dashboard/");
            exit();
        }
    }
    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ZCarWash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-effect {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center p-4">
    <div class="glass-effect p-8 rounded-2xl shadow-xl w-full max-w-md border border-slate-700/50">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <i class='bx bx-car text-3xl text-blue-500'></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white">ZCarWash</h1>
            <p class="text-slate-400 mt-2">Sistem Manajemen Pencucian Kendaraan</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500/50 rounded-lg p-4 mb-6">
                <div class="flex gap-3 items-center text-red-500">
                    <i class='bx bx-error-circle'></i>
                    <p class="text-sm"><?php echo $error; ?></p>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2" for="username">
                    Username
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class='bx bx-user'></i>
                    </div>
                    <input type="text" id="username" name="username" required
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg bg-slate-800/50 border border-slate-700/50 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan username">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2" for="password">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class='bx bx-lock-alt'></i>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg bg-slate-800/50 border border-slate-700/50 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan password">
                </div>
            </div>

            <button type="submit" name="login"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class='bx bx-log-in'></i>
                Masuk ke Sistem
            </button>
        </form>

        <div class="mt-8 text-center text-slate-400 text-sm">
            © <?php echo date('Y'); ?> ZCarWash. All rights reserved.
        </div>
    </div>
</body>

</html>