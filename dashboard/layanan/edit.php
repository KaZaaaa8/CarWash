<?php
session_start();
require_once '../../config/koneksi.php';

// Get service details
$id_layanan = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = "SELECT * FROM layanan WHERE id_layanan = '$id_layanan'";
$result = mysqli_query($koneksi, $query);
$layanan = mysqli_fetch_assoc($result);

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6 mb-6">
            <div class="flex items-center gap-4">
                <a href="<?= $baseURL ?>/dashboard/layanan"
                    class="p-3 bg-slate-900/50 text-slate-400 hover:text-white rounded-xl transition-all hover:scale-105">
                    <i class='bx bx-arrow-back text-xl'></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Layanan</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <p class="text-slate-400">Edit informasi layanan</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="proses_layanan.php" method="POST">
            <input type="hidden" name="id_layanan" value="<?= $layanan['id_layanan'] ?>">

            <div class="grid grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="col-span-2 space-y-6">
                    <!-- Service Info -->
                    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
                        <div class="space-y-6">
                            <div>
                                <label class="text-sm font-medium text-slate-300">Nama Layanan</label>
                                <input type="text" name="nama_layanan" required
                                    value="<?= $layanan['nama_layanan'] ?>"
                                    class="mt-2 w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-300">Jenis Kendaraan</label>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="jenis_kendaraan" value="mobil" required
                                            <?= $layanan['jenis_kendaraan'] == 'mobil' ? 'checked' : '' ?>
                                            class="hidden peer">
                                        <div class="p-4 rounded-xl border-2 border-slate-700 hover:border-blue-500/50 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-500/5">
                                            <div class="flex items-center gap-3">
                                                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20">
                                                    <i class='bx bx-car text-2xl text-blue-500'></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">Mobil</p>
                                                    <p class="text-xs text-slate-400">Layanan cuci mobil</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="jenis_kendaraan" value="motor" required
                                            <?= $layanan['jenis_kendaraan'] == 'motor' ? 'checked' : '' ?>
                                            class="hidden peer">
                                        <div class="p-4 rounded-xl border-2 border-slate-700 hover:border-purple-500/50 transition-all peer-checked:border-purple-500 peer-checked:bg-purple-500/5">
                                            <div class="flex items-center gap-3">
                                                <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20">
                                                    <i class='bx bx-cycling text-2xl text-purple-500'></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">Motor</p>
                                                    <p class="text-xs text-slate-400">Layanan cuci motor</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price & Duration -->
                    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-slate-300">Harga Layanan</label>
                                <div class="relative mt-2">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400">Rp</span>
                                    </div>
                                    <input type="number" name="harga" required min="0"
                                        value="<?= $layanan['harga'] ?>"
                                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-300">Durasi (Menit)</label>
                                <div class="relative mt-2">
                                    <input type="number" name="durasi_menit" required min="1"
                                        value="<?= $layanan['durasi_menit'] ?>"
                                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400">min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
                        <h3 class="font-medium mb-4">Status Layanan</h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 rounded-xl border border-slate-700 cursor-pointer hover:bg-slate-700/30 transition-all">
                                <input type="radio" name="status" value="aktif"
                                    <?= $layanan['status'] == 'aktif' ? 'checked' : '' ?>
                                    class="text-blue-500 focus:ring-blue-500 border-slate-700 bg-slate-900/50">
                                <div class="ml-3">
                                    <p class="font-medium">Aktif</p>
                                    <p class="text-xs text-slate-400">Layanan dapat dipilih</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-xl border border-slate-700 cursor-pointer hover:bg-slate-700/30 transition-all">
                                <input type="radio" name="status" value="nonaktif"
                                    <?= $layanan['status'] == 'nonaktif' ? 'checked' : '' ?>
                                    class="text-blue-500 focus:ring-blue-500 border-slate-700 bg-slate-900/50">
                                <div class="ml-3">
                                    <p class="font-medium">Non-Aktif</p>
                                    <p class="text-xs text-slate-400">Layanan disembunyikan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 p-6">
                        <button type="submit" name="edit"
                            class="w-full bg-gradient-to-br from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 px-4 py-3 rounded-xl text-white font-medium transition-all">
                            Simpan Perubahan
                        </button>
                        <a href="<?= $baseURL ?>/dashboard/layanan"
                            class="block text-center w-full bg-slate-700/50 hover:bg-slate-700 px-4 py-3 rounded-xl mt-3 transition-all">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../components/footer.php'; ?>