<?php
session_start();
require_once '../../../config/koneksi.php';

// Get date filter, default to today
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Query for daily transactions
$query = "SELECT 
    t.*,
    l.nama_layanan,
    l.jenis_kendaraan,
    p.nama_lengkap as kasir
FROM transaksi t
LEFT JOIN layanan l ON t.id_layanan = l.id_layanan
LEFT JOIN pengguna p ON t.id_pengguna = p.id_pengguna
WHERE DATE(t.tanggal_transaksi) = '$tanggal'
ORDER BY t.tanggal_transaksi DESC";

$result = mysqli_query($koneksi, $query);

// Calculate daily summary
$query_summary = "SELECT 
    COUNT(*) as total_transaksi,
    SUM(CASE WHEN status_pembayaran = 'sudah_bayar' THEN total_bayar ELSE 0 END) as total_pendapatan,
    COUNT(CASE WHEN status_pembayaran = 'sudah_bayar' THEN 1 END) as transaksi_selesai,
    COUNT(CASE WHEN status_pembayaran = 'belum_bayar' THEN 1 END) as transaksi_pending
FROM transaksi 
WHERE DATE(tanggal_transaksi) = '$tanggal'";

$result_summary = mysqli_query($koneksi, $query_summary);
$summary = mysqli_fetch_assoc($result_summary);

include '../../components/header.php';
include '../../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold">Laporan Harian</h1>
        <p class="text-slate-400">Laporan transaksi per tanggal</p>
    </div>

    <!-- Date Filter -->
    <div class="mb-6">
        <form class="flex gap-4 items-end">
            <div>
                <label class="block text-sm text-slate-400 mb-2">Pilih Tanggal</label>
                <input type="date" name="tanggal" value="<?= $tanggal ?>"
                    class="bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit"
                class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                Tampilkan
            </button>
            <a href="cetak_harian.php?tanggal=<?= $tanggal ?>" target="_blank"
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

    <!-- Transactions Table -->
    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left p-4">Invoice</th>
                        <th class="text-left p-4">Waktu</th>
                        <th class="text-left p-4">Pelanggan</th>
                        <th class="text-left p-4">Layanan</th>
                        <th class="text-left p-4">Total</th>
                        <th class="text-left p-4">Status</th>
                        <th class="text-left p-4">Kasir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="border-b border-slate-700/50 hover:bg-slate-700/20">
                                <td class="p-4">
                                    <a href="../transaksi/detail.php?id=<?= $row['id_transaksi'] ?>"
                                        class="text-blue-500 hover:text-blue-400">
                                        <?= $row['kode_invoice'] ?>
                                    </a>
                                </td>
                                <td class="p-4"><?= date('H:i', strtotime($row['tanggal_transaksi'])) ?></td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-medium"><?= $row['nama_pelanggan'] ?></p>
                                        <p class="text-sm text-slate-400"><?= $row['nomor_plat'] ?></p>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-medium"><?= $row['nama_layanan'] ?></p>
                                        <p class="text-sm text-slate-400 capitalize"><?= $row['jenis_kendaraan'] ?></p>
                                    </div>
                                </td>
                                <td class="p-4">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                                <td class="p-4">
                                    <?php if ($row['status_pembayaran'] == 'sudah_bayar'): ?>
                                        <span class="px-2 py-1 rounded-lg text-sm bg-green-500/10 text-green-500">
                                            Lunas
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 rounded-lg text-sm bg-amber-500/10 text-amber-500">
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4"><?= $row['kasir'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center text-slate-400">
                                Tidak ada transaksi pada tanggal ini
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../components/footer.php'; ?>