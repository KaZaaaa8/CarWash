<?php
session_start();
require_once '../config/koneksi.php';

// Get today's statistics
$today = date('Y-m-d');
$query_today = "SELECT 
    COUNT(*) as total_transaksi,
    SUM(CASE WHEN status_pembayaran = 'sudah_bayar' THEN total_bayar ELSE 0 END) as total_pendapatan,
    SUM(CASE WHEN status_pembayaran = 'belum_bayar' THEN 1 ELSE 0 END) as pending_pembayaran
FROM transaksi 
WHERE DATE(tanggal_transaksi) = '$today'";
$result_today = mysqli_query($koneksi, $query_today);
$stats_today = mysqli_fetch_assoc($result_today);

// Get vehicle type distribution for today
$query_kendaraan = "SELECT 
    l.jenis_kendaraan,
    COUNT(*) as total
FROM transaksi t
JOIN layanan l ON t.id_layanan = l.id_layanan
WHERE DATE(t.tanggal_transaksi) = '$today'
GROUP BY l.jenis_kendaraan";
$result_kendaraan = mysqli_query($koneksi, $query_kendaraan);
$kendaraan_stats = [];
while ($row = mysqli_fetch_assoc($result_kendaraan)) {
    $kendaraan_stats[$row['jenis_kendaraan']] = $row['total'];
}

// Get recent transactions
$query_recent = "SELECT 
    t.*, l.nama_layanan, l.jenis_kendaraan
FROM transaksi t
JOIN layanan l ON t.id_layanan = l.id_layanan
ORDER BY t.tanggal_transaksi DESC
LIMIT 5";
$result_recent = mysqli_query($koneksi, $query_recent);

include 'components/header.php';
include 'components/sidebar.php';
?>

<!-- Main Content -->
<div class="p-6">
    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">Selamat Datang, <?= $_SESSION['nama'] ?>!</h1>
        <p class="text-slate-400">Overview statistik dan transaksi hari ini</p>
    </div>

    <!-- Quick Actions -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="<?= $baseURL ?>/dashboard/transaksi/tambah.php"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                <i class='bx bx-plus'></i>
                Transaksi Baru
            </a>
            <a href="<?= $baseURL ?>/dashboard/transaksi"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">
                <i class='bx bx-list-ul'></i>
                Lihat Semua Transaksi
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Transaksi -->
        <div class="glass-effect p-6 rounded-xl border border-slate-700/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <i class='bx bx-receipt text-2xl text-blue-500'></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Total Transaksi</p>
                    <p class="text-2xl font-bold"><?= $stats_today['total_transaksi'] ?></p>
                </div>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="glass-effect p-6 rounded-xl border border-slate-700/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <i class='bx bx-money text-2xl text-green-500'></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold">Rp <?= number_format($stats_today['total_pendapatan'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="glass-effect p-6 rounded-xl border border-slate-700/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                    <i class='bx bx-time text-2xl text-yellow-500'></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Pending Pembayaran</p>
                    <p class="text-2xl font-bold"><?= $stats_today['pending_pembayaran'] ?></p>
                </div>
            </div>
        </div>

        <!-- Distribusi Kendaraan -->
        <div class="glass-effect p-6 rounded-xl border border-slate-700/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <i class='bx bx-car text-2xl text-purple-500'></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-400">Kendaraan Hari Ini</p>
                    <div class="flex items-center gap-4 mt-1">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-car text-lg'></i>
                            <span class="font-bold"><?= isset($kendaraan_stats['mobil']) ? $kendaraan_stats['mobil'] : 0 ?></span>
                        </div>
                        <div class="h-4 w-px bg-slate-700"></div>
                        <div class="flex items-center gap-2">
                            <i class='bx bx-cycling text-lg'></i>
                            <span class="font-bold"><?= isset($kendaraan_stats['motor']) ? $kendaraan_stats['motor'] : 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="glass-effect rounded-xl border border-slate-700/50">
        <div class="p-6 border-b border-slate-700/50">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">Transaksi Terbaru</h2>
                <a href="<?= $baseURL ?>/dashboard/transaksi" class="text-blue-500 hover:text-blue-400 transition-colors">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left">
                            <th class="pb-4 text-slate-400 font-medium">Invoice</th>
                            <th class="pb-4 text-slate-400 font-medium">Pelanggan</th>
                            <th class="pb-4 text-slate-400 font-medium">Layanan</th>
                            <th class="pb-4 text-slate-400 font-medium">Total</th>
                            <th class="pb-4 text-slate-400 font-medium">Status</th>
                            <th class="pb-4 text-slate-400 font-medium">Waktu</th>
                            <th class="pb-4 text-slate-400 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php while ($transaksi = mysqli_fetch_assoc($result_recent)): ?>
                            <tr class="border-t border-slate-700/50">
                                <td class="py-4"><?= $transaksi['kode_invoice'] ?></td>
                                <td class="py-4">
                                    <div>
                                        <p class="font-medium"><?= $transaksi['nama_pelanggan'] ?></p>
                                        <p class="text-slate-400"><?= $transaksi['nomor_plat'] ?></p>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div>
                                        <p class="font-medium"><?= $transaksi['nama_layanan'] ?></p>
                                        <p class="text-slate-400 capitalize"><?= $transaksi['jenis_kendaraan'] ?></p>
                                    </div>
                                </td>
                                <td class="py-4 font-medium">
                                    Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?>
                                </td>
                                <td class="py-4">
                                    <?php if ($transaksi['status_pembayaran'] == 'sudah_bayar'): ?>
                                        <span class="px-2 py-1 bg-green-500/10 text-green-500 rounded-lg text-xs">
                                            Lunas
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-yellow-500/10 text-yellow-500 rounded-lg text-xs">
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 text-slate-400">
                                    <?= date('H:i', strtotime($transaksi['tanggal_transaksi'])) ?>
                                </td>
                                <td class="py-4">
                                    <a href="<?= $baseURL ?>/dashboard/transaksi/detail.php?id=<?= $transaksi['id_transaksi'] ?>"
                                        class="text-blue-500 hover:text-blue-400 transition-colors">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>