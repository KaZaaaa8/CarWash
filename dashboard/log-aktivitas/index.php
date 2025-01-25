<?php
session_start();
require_once '../../config/koneksi.php';

// Only admin can access logs
if ($_SESSION['level'] !== 'admin') {
    header("Location: ../dashboard");
    exit();
}

// Pagination setup
$limit = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query for logs with pagination
$query = "SELECT l.*, p.nama_lengkap, p.username 
          FROM log_aktivitas l
          LEFT JOIN pengguna p ON l.id_pengguna = p.id_pengguna
          ORDER BY l.created_at DESC
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);

// Get total pages
$query_total = "SELECT COUNT(*) as total FROM log_aktivitas";
$result_total = mysqli_query($koneksi, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_pages = ceil($row_total['total'] / $limit);

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold">Log Aktivitas</h1>
        <p class="text-slate-400">Riwayat aktivitas pengguna sistem</p>
    </div>

    <!-- Log Table -->
    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left p-4">Waktu</th>
                        <th class="text-left p-4">Pengguna</th>
                        <th class="text-left p-4">Aktivitas</th>
                        <th class="text-left p-4">Detail</th>
                        <th class="text-left p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/20">
                            <td class="p-4 whitespace-nowrap">
                                <?= date('d/m/Y H:i:s', strtotime($row['created_at'])) ?>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                        <i class='bx bx-user text-xl text-blue-500'></i>
                                    </div>
                                    <div>
                                        <p class="font-medium"><?= $row['nama_lengkap'] ?></p>
                                        <p class="text-sm text-slate-400"><?= $row['username'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <?php
                                $aktivitas_label = [
                                    'login' => '<span class="px-2 py-1 rounded-lg text-sm bg-green-500/10 text-green-500">Login</span>',
                                    'logout' => '<span class="px-2 py-1 rounded-lg text-sm bg-red-500/10 text-red-500">Logout</span>',
                                    'tambah_transaksi' => '<span class="px-2 py-1 rounded-lg text-sm bg-blue-500/10 text-blue-500">Tambah Transaksi</span>',
                                    'edit_transaksi' => '<span class="px-2 py-1 rounded-lg text-sm bg-amber-500/10 text-amber-500">Edit Transaksi</span>',
                                    'hapus_transaksi' => '<span class="px-2 py-1 rounded-lg text-sm bg-red-500/10 text-red-500">Hapus Transaksi</span>',
                                    'tambah_layanan' => '<span class="px-2 py-1 rounded-lg text-sm bg-blue-500/10 text-blue-500">Tambah Layanan</span>',
                                    'edit_layanan' => '<span class="px-2 py-1 rounded-lg text-sm bg-amber-500/10 text-amber-500">Edit Layanan</span>',
                                    'hapus_layanan' => '<span class="px-2 py-1 rounded-lg text-sm bg-red-500/10 text-red-500">Hapus Layanan</span>',
                                    'tambah_pengguna' => '<span class="px-2 py-1 rounded-lg text-sm bg-blue-500/10 text-blue-500">Tambah Pengguna</span>',
                                    'edit_pengguna' => '<span class="px-2 py-1 rounded-lg text-sm bg-amber-500/10 text-amber-500">Edit Pengguna</span>',
                                    'hapus_pengguna' => '<span class="px-2 py-1 rounded-lg text-sm bg-red-500/10 text-red-500">Hapus Pengguna</span>'
                                ];
                                echo $aktivitas_label[$row['aktivitas']] ?? $row['aktivitas'];
                                ?>
                            </td>
                            <td class="p-4"><?= $row['detail'] ?></td>
                            <td class="p-4 text-slate-400"><?= $row['ip_address'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="p-4 border-t border-slate-700">
                <div class="flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>"
                            class="px-3 py-1 rounded-lg <?= $page == $i ? 'bg-blue-500 text-white' : 'bg-slate-700/50 hover:bg-slate-700' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../components/footer.php'; ?>