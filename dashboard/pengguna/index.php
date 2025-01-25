<?php
session_start();
require_once '../../config/koneksi.php';

// Query untuk mengambil data pengguna
$query = "SELECT * FROM pengguna ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Pengguna</h1>
            <p class="text-slate-400">Kelola akun pengguna sistem</p>
        </div>
        <?php if ($_SESSION['level'] == 'admin'): ?>
            <a href="tambah.php"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors inline-flex items-center gap-2">
                <i class='bx bx-plus'></i>
                Tambah Pengguna
            </a>
        <?php endif; ?>
    </div>

    <!-- Users Table -->
    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left p-4">Nama Lengkap</th>
                        <th class="text-left p-4">Username</th>
                        <th class="text-left p-4">Level</th>
                        <th class="text-left p-4">Status</th>
                        <th class="text-left p-4">Terakhir Login</th>
                        <?php if ($_SESSION['level'] == 'admin'): ?>
                            <th class="text-left p-4">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/20">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                        <i class='bx bx-user text-xl text-blue-500'></i>
                                    </div>
                                    <?= $row['nama_lengkap'] ?>
                                </div>
                            </td>
                            <td class="p-4"><?= $row['username'] ?></td>
                            <td class="p-4">
                                <?php if ($row['level'] == 'admin'): ?>
                                    <span class="px-2 py-1 rounded-lg text-sm bg-purple-500/10 text-purple-500">
                                        Administrator
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded-lg text-sm bg-blue-500/10 text-blue-500">
                                        Kasir
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?php if ($row['last_login']): ?>
                                    <span class="px-2 py-1 rounded-lg text-sm bg-green-500/10 text-green-500">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded-lg text-sm bg-slate-500/10 text-slate-500">
                                        Belum Aktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <?= $row['last_login'] ? date('d/m/Y H:i', strtotime($row['last_login'])) : '-' ?>
                            </td>
                            <?php if ($_SESSION['level'] == 'admin'): ?>
                                <td class="p-4">
                                    <div class="flex gap-2">
                                        <a href="edit.php?id=<?= $row['id_pengguna'] ?>"
                                            class="p-2 bg-amber-500/10 text-amber-500 rounded-lg hover:bg-amber-500/20">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <?php if ($row['id_pengguna'] != $_SESSION['user_id']): ?>
                                            <a href="hapus.php?id=<?= $row['id_pengguna'] ?>"
                                                class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>