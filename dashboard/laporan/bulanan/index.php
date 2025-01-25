<?php
session_start();
require_once '../../../config/koneksi.php';

// Get month and year filter, default to current month
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query for monthly transactions
$query = "SELECT 
    DATE(t.tanggal_transaksi) as tanggal,
    COUNT(*) as total_transaksi,
    SUM(CASE WHEN t.status_pembayaran = 'sudah_bayar' THEN t.total_bayar ELSE 0 END) as pendapatan,
    COUNT(CASE WHEN t.status_pembayaran = 'sudah_bayar' THEN 1 END) as transaksi_selesai,
    COUNT(CASE WHEN t.status_pembayaran = 'belum_bayar' THEN 1 END) as transaksi_pending
FROM transaksi t
WHERE MONTH(t.tanggal_transaksi) = '$bulan' 
AND YEAR(t.tanggal_transaksi) = '$tahun'
GROUP BY DATE(t.tanggal_transaksi)
ORDER BY tanggal";

$result = mysqli_query($koneksi, $query);

// Calculate monthly summary
$query_summary = "SELECT 
    COUNT(*) as total_transaksi,
    SUM(CASE WHEN status_pembayaran = 'sudah_bayar' THEN total_bayar ELSE 0 END) as total_pendapatan,
    COUNT(CASE WHEN status_pembayaran = 'sudah_bayar' THEN 1 END) as transaksi_selesai,
    COUNT(CASE WHEN status_pembayaran = 'belum_bayar' THEN 1 END) as transaksi_pending
FROM transaksi 
WHERE MONTH(tanggal_transaksi) = '$bulan'
AND YEAR(tanggal_transaksi) = '$tahun'";

$result_summary = mysqli_query($koneksi, $query_summary);
$summary = mysqli_fetch_assoc($result_summary);

include '../../components/header.php';
include '../../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold">Laporan Bulanan</h1>
        <p class="text-slate-400">Laporan transaksi per bulan</p>
    </div>

    <!-- Month Filter -->
    <div class="mb-6">
        <form class="flex gap-4 items-end">
            <div>
                <label class="block text-sm text-slate-400 mb-2">Pilih Bulan</label>
                <select name="bulan" class="bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= sprintf("%02d", $i) ?>" <?= $bulan == sprintf("%02d", $i) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-2">Pilih Tahun</label>
                <select name="tahun" class="bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500">
                    <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                        <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                Tampilkan
            </button>
            <a href="cetak.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" target="_blank"
                class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors inline-flex items-center gap-2">
                <i class='bx bx-printer'></i>
                Cetak Laporan
            </a>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Transaksi -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-blue-500/20">
                    <i class='bx bx-receipt text-2xl text-blue-500'></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Total Transaksi</p>
                    <p class="text-2xl font-bold"><?= $summary['total_transaksi'] ?></p>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-green-500/20">
                    <i class='bx bx-money text-2xl text-green-500'></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Total Pendapatan</p>
                    <p class="text-2xl font-bold">Rp <?= number_format($summary['total_pendapatan'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Transaksi Selesai -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-emerald-500/20">
                    <i class='bx bx-check-circle text-2xl text-emerald-500'></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Transaksi Selesai</p>
                    <p class="text-2xl font-bold"><?= $summary['transaksi_selesai'] ?></p>
                </div>
            </div>
        </div>

        <!-- Transaksi Pending -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-amber-500/20">
                    <i class='bx bx-time text-2xl text-amber-500'></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">Transaksi Pending</p>
                    <p class="text-2xl font-bold"><?= $summary['transaksi_pending'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Transactions Table -->
    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left p-4">Tanggal</th>
                        <th class="text-left p-4">Total Transaksi</th>
                        <th class="text-left p-4">Transaksi Selesai</th>
                        <th class="text-left p-4">Transaksi Pending</th>
                        <th class="text-left p-4">Pendapatan</th>
                        <th class="text-left p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="border-b border-slate-700/50 hover:bg-slate-700/20">
                                <td class="p-4"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td class="p-4"><?= $row['total_transaksi'] ?></td>
                                <td class="p-4"><?= $row['transaksi_selesai'] ?></td>
                                <td class="p-4"><?= $row['transaksi_pending'] ?></td>
                                <td class="p-4">Rp <?= number_format($row['pendapatan'], 0, ',', '.') ?></td>
                                <td class="p-4">
                                    <a href="../harian?tanggal=<?= $row['tanggal'] ?>"
                                        class="text-blue-500 hover:text-blue-400">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-4 text-center text-slate-400">
                                Tidak ada transaksi pada bulan ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>