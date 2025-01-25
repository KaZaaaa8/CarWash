<?php
session_start();
require_once '../../config/koneksi.php';

// Get transaction details with related data
$id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = "SELECT 
    t.*,
    l.nama_layanan,
    l.jenis_kendaraan,
    l.durasi_menit,
    p.nama_lengkap as kasir
FROM transaksi t
LEFT JOIN layanan l ON t.id_layanan = l.id_layanan
LEFT JOIN pengguna p ON t.id_pengguna = p.id_pengguna
WHERE t.id_transaksi = '$id_transaksi'";

$result = mysqli_query($koneksi, $query);
$transaksi = mysqli_fetch_assoc($result);

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="<?= $baseURL ?>/dashboard/transaksi"
                    class="p-2 text-slate-400 hover:text-white bg-slate-800/50 rounded-lg transition-all hover:scale-105">
                    <i class='bx bx-arrow-back text-xl'></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">Detail Transaksi</h1>
                    <p class="text-slate-400 text-sm mt-1">Invoice: <?= $transaksi['kode_invoice'] ?></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <?php if ($transaksi['status_pembayaran'] == 'belum_bayar'): ?>
                    <a href="bayar.php?id=<?= $transaksi['id_transaksi'] ?>"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg transition-colors inline-flex items-center gap-2">
                        <i class='bx bx-money'></i>
                        Proses Pembayaran
                    </a>
                <?php endif; ?>
                <a href="cetak.php?id=<?= $transaksi['id_transaksi'] ?>" target="_blank"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors inline-flex items-center gap-2">
                    <i class='bx bx-printer'></i>
                    Cetak Invoice
                </a>
            </div>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="grid grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="col-span-2 space-y-6">
            <!-- Customer Info -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Pelanggan</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Nama Pelanggan</p>
                            <p class="font-medium"><?= $transaksi['nama_pelanggan'] ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Nomor Plat</p>
                            <p class="font-medium"><?= $transaksi['nomor_plat'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Info -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Detail Layanan</h3>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium"><?= $transaksi['nama_layanan'] ?></p>
                            <p class="text-sm text-slate-400 mt-1">
                                <?= ucfirst($transaksi['jenis_kendaraan']) ?> • <?= $transaksi['durasi_menit'] ?> menit
                            </p>
                        </div>
                        <p class="text-lg font-semibold text-blue-500">
                            Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Transaction Timeline -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Timeline Transaksi</h3>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                <i class='bx bx-receipt text-blue-500'></i>
                            </div>
                            <div>
                                <p class="font-medium">Transaksi Dibuat</p>
                                <p class="text-sm text-slate-400">
                                    <?= date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?>
                                </p>
                            </div>
                        </div>

                        <?php if ($transaksi['waktu_mulai']): ?>
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                    <i class='bx bx-time text-yellow-500'></i>
                                </div>
                                <div>
                                    <p class="font-medium">Pencucian Dimulai</p>
                                    <p class="text-sm text-slate-400">
                                        <?= date('d/m/Y H:i', strtotime($transaksi['waktu_mulai'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($transaksi['waktu_selesai']): ?>
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                                    <i class='bx bx-check text-green-500'></i>
                                </div>
                                <div>
                                    <p class="font-medium">Pencucian Selesai</p>
                                    <p class="text-sm text-slate-400">
                                        <?= date('d/m/Y H:i', strtotime($transaksi['waktu_selesai'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($transaksi['waktu_bayar']): ?>
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                                    <i class='bx bx-money text-green-500'></i>
                                </div>
                                <div>
                                    <p class="font-medium">Pembayaran Lunas</p>
                                    <p class="text-sm text-slate-400">
                                        <?= date('d/m/Y H:i', strtotime($transaksi['waktu_bayar'])) ?>
                                    </p>
                                    <p class="text-sm text-slate-400">
                                        Via <?= ucfirst(str_replace('_', ' ', $transaksi['metode_pembayaran'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Side Info -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Status Transaksi</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Status Pembayaran</p>
                            <?php if ($transaksi['status_pembayaran'] == 'sudah_bayar'): ?>
                                <span class="px-3 py-1 bg-green-500/10 text-green-500 rounded-lg text-sm">
                                    Lunas
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-yellow-500/10 text-yellow-500 rounded-lg text-sm">
                                    Belum Bayar
                                </span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400 mb-1">Kasir</p>
                            <p class="font-medium"><?= $transaksi['kasir'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($transaksi['catatan']): ?>
                <!-- Notes -->
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Catatan</h3>
                        <p class="text-slate-400"><?= nl2br($transaksi['catatan']) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>