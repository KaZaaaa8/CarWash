<?php
session_start();
require_once '../../config/koneksi.php';

// Get transaction details
$id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = "SELECT 
    t.*,
    l.nama_layanan,
    l.jenis_kendaraan,
    l.durasi_menit
FROM transaksi t
LEFT JOIN layanan l ON t.id_layanan = l.id_layanan
WHERE t.id_transaksi = '$id_transaksi'";

$result = mysqli_query($koneksi, $query);
$transaksi = mysqli_fetch_assoc($result);

// Redirect if transaction is already paid
if ($transaksi['status_pembayaran'] == 'sudah_bayar') {
    header("Location: detail.php?id=$id_transaksi");
    exit();
}

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="detail.php?id=<?= $transaksi['id_transaksi'] ?>"
                    class="p-2 text-slate-400 hover:text-white bg-slate-800/50 rounded-lg transition-all hover:scale-105">
                    <i class='bx bx-arrow-back text-xl'></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">Proses Pembayaran</h1>
                    <p class="text-slate-400 text-sm mt-1">Invoice: <?= $transaksi['kode_invoice'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="max-w-2xl">
        <form action="proses_pembayaran.php" method="POST" class="space-y-6">
            <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">

            <!-- Transaction Summary -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Ringkasan Transaksi</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-slate-700">
                            <span class="text-slate-400">Pelanggan</span>
                            <span class="font-medium"><?= $transaksi['nama_pelanggan'] ?></span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-slate-700">
                            <span class="text-slate-400">Layanan</span>
                            <span class="font-medium"><?= $transaksi['nama_layanan'] ?></span>
                        </div>
                        <div class="flex justify-between items-center pt-3">
                            <span class="text-lg font-semibold">Total Bayar</span>
                            <span class="text-2xl font-bold text-blue-500">
                                Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Metode Pembayaran</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="group cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="tunai" required class="hidden">
                            <div class="p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all text-center">
                                <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <i class='bx bx-money text-2xl text-blue-500'></i>
                                </div>
                                <p class="font-medium">Tunai</p>
                            </div>
                        </label>
                        <label class="group cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="qris" required class="hidden">
                            <div class="p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all text-center">
                                <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <i class='bx bx-qr text-2xl text-blue-500'></i>
                                </div>
                                <p class="font-medium">QRIS</p>
                            </div>
                        </label>
                        <label class="group cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="transfer" required class="hidden">
                            <div class="p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all text-center">
                                <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <i class='bx bx-credit-card text-2xl text-blue-500'></i>
                                </div>
                                <p class="font-medium">Transfer</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Catatan (Opsional)</h3>
                    <textarea name="catatan" rows="3"
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                        placeholder="Tambahkan catatan jika diperlukan"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="detail.php?id=<?= $transaksi['id_transaksi'] ?>"
                    class="px-6 py-3 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" name="bayar"
                    class="px-6 py-3 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors inline-flex items-center gap-2">
                    <i class='bx bx-check'></i>
                    Proses Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Add active state to payment method selection
    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset all containers
            document.querySelectorAll('[name="metode_pembayaran"]').forEach(r => {
                r.closest('.group').querySelector('div').classList.remove('border-blue-500');
                r.closest('.group').querySelector('div').classList.add('border-slate-700');
            });

            // Style selected container
            const container = this.closest('.group').querySelector('div');
            container.classList.remove('border-slate-700');
            container.classList.add('border-blue-500');
        });
    });
</script>

<?php include '../components/footer.php'; ?>