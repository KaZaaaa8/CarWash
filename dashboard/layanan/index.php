<?php
session_start();
require_once '../../config/koneksi.php';

// Query untuk mengambil data layanan
$query = "SELECT * FROM layanan ORDER BY jenis_kendaraan, nama_layanan";
$result = mysqli_query($koneksi, $query);

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Kelola Layanan</h1>
                <p class="text-slate-400 mt-1">Kelola daftar layanan pencucian kendaraan</p>
            </div>
            <a href="tambah.php" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors inline-flex items-center gap-2">
                <i class='bx bx-plus'></i>
                Tambah Layanan
            </a>
        </div>
    </div>

    <!-- Service Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($layanan = mysqli_fetch_assoc($result)): ?>
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg <?= $layanan['jenis_kendaraan'] == 'mobil' ? 'bg-blue-500/20' : 'bg-purple-500/20' ?> flex items-center justify-center">
                            <i class='bx <?= $layanan['jenis_kendaraan'] == 'mobil' ? 'bx-car text-blue-500' : 'bx-cycling text-purple-500' ?> text-xl'></i>
                        </div>
                        <div>
                            <h3 class="font-medium"><?= $layanan['nama_layanan'] ?></h3>
                            <p class="text-sm text-slate-400 capitalize"><?= $layanan['jenis_kendaraan'] ?></p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="px-2 py-1 text-sm rounded-lg <?= $layanan['status'] == 'aktif' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' ?>">
                            <?= ucfirst($layanan['status']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center py-3 border-t border-slate-700">
                    <div>
                        <p class="text-sm text-slate-400">Harga</p>
                        <p class="text-lg font-semibold text-blue-500">
                            Rp <?= number_format($layanan['harga'], 0, ',', '.') ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-400">Durasi</p>
                        <p class="font-medium"><?= $layanan['durasi_menit'] ?> menit</p>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <a href="edit.php?id=<?= $layanan['id_layanan'] ?>" 
                       class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors text-center">
                        Edit
                    </a>
                    <button onclick="hapusLayanan(<?= $layanan['id_layanan'] ?>, '<?= $layanan['nama_layanan'] ?>')"
                            class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-lg transition-colors">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function hapusLayanan(id, nama) {
    if (confirm(`Apakah Anda yakin ingin menghapus layanan "${nama}"?`)) {
        window.location.href = `hapus.php?id=${id}`;
    }
}
</script>

<?php include '../components/footer.php'; ?>
