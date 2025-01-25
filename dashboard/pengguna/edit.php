<?php
session_start();
require_once '../../config/koneksi.php';

// Redirect if not admin
if ($_SESSION['level'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Get user data
$id_pengguna = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'";
$result = mysqli_query($koneksi, $query);
$pengguna = mysqli_fetch_assoc($result);

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="min-h-screen p-6">
    <!-- Header Section -->
    <div class="max-w-xl mx-auto">
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6 mb-6">
            <div class="flex items-center gap-4">
                <a href="<?= $baseURL ?>/dashboard/pengguna"
                    class="p-3 bg-slate-900/50 text-slate-400 hover:text-white rounded-xl transition-all hover:scale-105">
                    <i class='bx bx-arrow-back text-xl'></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Pengguna</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <p class="text-slate-400">Edit data pengguna</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
            <form action="proses_pengguna.php" method="POST" class="p-6 space-y-6">
                <input type="hidden" name="id_pengguna" value="<?= $pengguna['id_pengguna'] ?>">

                <div>
                    <label class="block text-sm text-slate-300 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required
                        value="<?= $pengguna['nama_lengkap'] ?>"
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-2">Username</label>
                    <input type="text" name="username" required
                        value="<?= $pengguna['username'] ?>"
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-2">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all"
                        placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-2">Level Akses</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="admin" required
                                <?= $pengguna['level'] == 'admin' ? 'checked' : '' ?>
                                class="hidden peer">
                            <div class="p-4 rounded-xl border-2 border-slate-700 hover:border-purple-500/50 transition-all peer-checked:border-purple-500 peer-checked:bg-purple-500/5">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20">
                                        <i class='bx bx-shield text-2xl text-purple-500'></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Administrator</p>
                                        <p class="text-xs text-slate-400">Akses penuh sistem</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="kasir" required
                                <?= $pengguna['level'] == 'kasir' ? 'checked' : '' ?>
                                class="hidden peer">
                            <div class="p-4 rounded-xl border-2 border-slate-700 hover:border-blue-500/50 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-500/5">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20">
                                        <i class='bx bx-user text-2xl text-blue-500'></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Kasir</p>
                                        <p class="text-xs text-slate-400">Akses terbatas</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-700">
                    <button type="submit" name="edit"
                        class="w-full bg-gradient-to-br from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 px-4 py-3 rounded-xl text-white font-medium transition-all">
                        Simpan Perubahan
                    </button>
                    <a href="<?= $baseURL ?>/dashboard/pengguna"
                        class="block text-center w-full bg-slate-700/50 hover:bg-slate-700 px-4 py-3 rounded-xl mt-3 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>