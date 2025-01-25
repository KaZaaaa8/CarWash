<?php
session_start();
require_once '../../config/koneksi.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$where = "";
if ($search) {
    $where = "WHERE 
        t.kode_invoice LIKE '%$search%' OR 
        t.nomor_plat LIKE '%$search%' OR 
        t.nama_pelanggan LIKE '%$search%'";
}

// Get total records for pagination
$query_total = "SELECT COUNT(*) as total FROM transaksi t $where";
$result_total = mysqli_query($koneksi, $query_total);
$total_records = mysqli_fetch_assoc($result_total)['total'];
$total_pages = ceil($total_records / $limit);

// Get transactions with related data
$query = "SELECT 
    t.*,
    l.nama_layanan,
    l.jenis_kendaraan,
    l.durasi_menit,
    p.nama_lengkap as kasir
FROM transaksi t
LEFT JOIN layanan l ON t.id_layanan = l.id_layanan
LEFT JOIN pengguna p ON t.id_pengguna = p.id_pengguna
$where
ORDER BY t.tanggal_transaksi DESC
LIMIT $limit OFFSET $offset";

$result = mysqli_query($koneksi, $query);

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">Transaksi</h1>
        <p class="text-slate-400">Kelola semua transaksi pencucian kendaraan</p>
    </div>

    <!-- Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <!-- Search -->
        <div class="flex items-center gap-3">
            <form class="flex items-center gap-2">
                <div class="relative">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400'></i>
                    <input type="text" name="search" value="<?= $search ?>"
                        class="pl-10 pr-4 py-2 rounded-lg bg-slate-800/50 border border-slate-700/50 focus:outline-none focus:border-blue-500 w-64"
                        placeholder="Cari transaksi...">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <!-- Add Transaction -->
        <a href="tambah.php" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
            <i class='bx bx-plus'></i>
            Transaksi Baru
        </a>
    </div>

    <!-- Transactions Table -->
    <div class="glass-effect rounded-xl border border-slate-700/50">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-700/50">
                        <th class="text-left p-4 text-slate-400 font-medium">Invoice</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Pelanggan</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Layanan</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Total</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Status</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Pembayaran</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Kasir</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Tanggal</th>
                        <th class="text-left p-4 text-slate-400 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="border-b border-slate-700/50 hover:bg-slate-800/50 transition-colors">
                            <td class="p-4">
                                <span class="font-medium"><?= $row['kode_invoice'] ?></span>
                            </td>
                            <td class="p-4">
                                <div>
                                    <p class="font-medium"><?= $row['nama_pelanggan'] ?></p>
                                    <p class="text-sm text-slate-400"><?= $row['nomor_plat'] ?></p>
                                </div>
                            </td>
                            <td class="p-4">
                                <div>
                                    <p class="font-medium"><?= $row['nama_layanan'] ?></p>
                                    <p class="text-sm text-slate-400 capitalize">
                                        <?= $row['jenis_kendaraan'] ?> • <?= $row['durasi_menit'] ?> menit
                                    </p>
                                </div>
                            </td>
                            <td class="p-4 font-medium">
                                Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?>
                            </td>
                            <td class="p-4">
                                <?php if ($row['status_pembayaran'] == 'sudah_bayar'): ?>
                                    <span class="px-2 py-1 bg-green-500/10 text-green-500 rounded-lg text-xs">Lunas</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-yellow-500/10 text-yellow-500 rounded-lg text-xs">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <span class="capitalize"><?= str_replace('_', ' ', $row['metode_pembayaran']) ?></span>
                            </td>
                            <td class="p-4"><?= $row['kasir'] ?></td>
                            <td class="p-4 text-slate-400">
                                <?= date('d/m/Y H:i', strtotime($row['tanggal_transaksi'])) ?>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <a href="detail.php?id=<?= $row['id_transaksi'] ?>"
                                        class="p-2 bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-colors">
                                        <i class='bx bx-detail'></i>
                                    </a>
                                    <?php if ($row['status_pembayaran'] == 'belum_bayar'): ?>
                                        <a href="bayar.php?id=<?= $row['id_transaksi'] ?>"
                                            class="p-2 bg-green-500/10 text-green-500 rounded-lg hover:bg-green-500/20 transition-colors">
                                            <i class='bx bx-money'></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center justify-between">
                    <p class="text-slate-400">
                        Menampilkan <?= ($offset + 1) ?> - <?= min($offset + $limit, $total_records) ?> dari <?= $total_records ?> data
                    </p>
                    <div class="flex items-center gap-2">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?><?= $search ? "&search=$search" : "" ?>"
                                class="px-3 py-1 rounded-lg <?= $i == $page ? 'bg-blue-500 text-white' : 'text-slate-400 hover:bg-slate-700/50' ?> transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../components/footer.php'; ?>