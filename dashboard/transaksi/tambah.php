<?php
session_start();
require_once '../../config/koneksi.php';

// Query untuk layanan
$query_layanan = "SELECT * FROM layanan WHERE status = 'aktif' ORDER BY jenis_kendaraan, nama_layanan";
$result_layanan = mysqli_query($koneksi, $query_layanan);

// Memisahkan layanan berdasarkan jenis kendaraan
$layanan_mobil = [];
$layanan_motor = [];
while ($layanan = mysqli_fetch_assoc($result_layanan)) {
    if ($layanan['jenis_kendaraan'] == 'mobil') {
        $layanan_mobil[] = $layanan;
    } else {
        $layanan_motor[] = $layanan;
    }
}

include '../components/header.php';
include '../components/sidebar.php';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 p-8">
    <!-- Header Section with Enhanced Stepper -->
    <div class="max-w-6xl mx-auto mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="<?= $baseURL ?>/dashboard/transaksi"
                    class="p-2 text-slate-400 hover:text-white bg-slate-800/50 rounded-lg transition-all hover:scale-105">
                    <i class='bx bx-arrow-back text-xl'></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">Tambah Transaksi Baru</h1>
                    <p class="text-slate-400 text-sm mt-1">Isi informasi transaksi dengan lengkap</p>
                </div>
            </div>
        </div>

        <!-- Enhanced Stepper -->
        <div class="relative">
            <div class="flex justify-between">
                <div class="text-center flex-1">
                    <div class="w-10 h-10 mx-auto rounded-full bg-blue-500 flex items-center justify-center mb-2 transition-all">
                        <i class='bx bx-user text-xl'></i>
                    </div>
                    <p class="text-sm font-medium text-blue-500">Informasi Pelanggan</p>
                </div>
                <div class="text-center flex-1">
                    <div class="w-10 h-10 mx-auto rounded-full bg-slate-700 flex items-center justify-center mb-2 transition-all">
                        <i class='bx bx-car text-xl'></i>
                    </div>
                    <p class="text-sm font-medium text-slate-400">Pilih Layanan</p>
                </div>
                <div class="text-center flex-1">
                    <div class="w-10 h-10 mx-auto rounded-full bg-slate-700 flex items-center justify-center mb-2 transition-all">
                        <i class='bx bx-check text-xl'></i>
                    </div>
                    <p class="text-sm font-medium text-slate-400">Konfirmasi</p>
                </div>
            </div>
            <div class="absolute top-5 left-0 right-0 h-[2px] bg-slate-700 -z-10">
                <div class="w-0 h-full bg-blue-500 transition-all duration-300" id="stepperProgress"></div>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="max-w-4xl mx-auto">
        <form action="proses_transaksi.php" method="POST" id="formTransaksi" class="space-y-6">
            <!-- Step 1: Informasi Pelanggan -->
            <div id="step1">
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 overflow-hidden">
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-slate-400 mb-2">Nomor Plat Kendaraan</label>
                                <input type="text" name="nomor_plat" required
                                    class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"
                                    placeholder="Contoh: B 1234 ABC">
                            </div>
                            <div>
                                <label class="block text-sm text-slate-400 mb-2">Nama Pelanggan</label>
                                <input type="text" name="nama_pelanggan" required
                                    class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500 transition-colors"
                                    placeholder="Nama lengkap pelanggan">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 overflow-hidden mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Jenis Kendaraan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="group cursor-pointer">
                                <input type="radio" name="jenis_kendaraan" value="mobil" required class="hidden">
                                <div class="p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class='bx bx-car text-2xl text-blue-500'></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">Mobil</p>
                                            <p class="text-sm text-slate-400">Semua jenis mobil</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="group cursor-pointer">
                                <input type="radio" name="jenis_kendaraan" value="motor" required class="hidden">
                                <div class="p-4 rounded-xl border border-slate-700 hover:border-purple-500 transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class='bx bx-cycling text-2xl text-purple-500'></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">Motor</p>
                                            <p class="text-sm text-slate-400">Semua jenis motor</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Pilih Layanan -->
            <div id="step2" class="hidden">
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 overflow-hidden">
                    <div class="p-6">
                        <!-- Layanan Mobil -->
                        <div id="layananMobil" class="hidden">
                            <h3 class="text-lg font-semibold mb-4">Layanan Cuci Mobil</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <?php foreach ($layanan_mobil as $layanan): ?>
                                    <label class="group cursor-pointer">
                                        <input type="radio" name="id_layanan" value="<?= $layanan['id_layanan'] ?>"
                                            data-harga="<?= $layanan['harga'] ?>" required class="hidden">
                                        <div class="p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-white"><?= $layanan['nama_layanan'] ?></p>
                                                    <p class="text-sm text-slate-400"><?= $layanan['durasi_menit'] ?> menit</p>
                                                </div>
                                                <p class="text-blue-500 font-semibold">
                                                    Rp <?= number_format($layanan['harga'], 0, ',', '.') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Layanan Motor -->
                        <div id="layananMotor" class="hidden">
                            <h3 class="text-lg font-semibold mb-4">Layanan Cuci Motor</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <?php foreach ($layanan_motor as $layanan): ?>
                                    <label class="group cursor-pointer">
                                        <input type="radio" name="id_layanan" value="<?= $layanan['id_layanan'] ?>"
                                            data-harga="<?= $layanan['harga'] ?>" required class="hidden">
                                        <div class="p-4 rounded-xl border border-slate-700 hover:border-purple-500 transition-all">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-white"><?= $layanan['nama_layanan'] ?></p>
                                                    <p class="text-sm text-slate-400"><?= $layanan['durasi_menit'] ?> menit</p>
                                                </div>
                                                <p class="text-purple-500 font-semibold">
                                                    Rp <?= number_format($layanan['harga'], 0, ',', '.') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Konfirmasi -->
            <div id="step3" class="hidden">
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-xl border border-slate-700/50 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-6">Ringkasan Transaksi</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between py-3 border-b border-slate-700">
                                <span class="text-slate-400">Nomor Plat</span>
                                <span id="summary_plat" class="font-medium text-white"></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-700">
                                <span class="text-slate-400">Nama Pelanggan</span>
                                <span id="summary_nama" class="font-medium text-white"></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-700">
                                <span class="text-slate-400">Jenis Kendaraan</span>
                                <span id="summary_kendaraan" class="font-medium text-white capitalize"></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-slate-700">
                                <span class="text-slate-400">Layanan</span>
                                <span id="summary_layanan" class="font-medium text-white"></span>
                            </div>
                            <div class="flex justify-between items-center pt-4">
                                <span class="text-lg font-semibold text-white">Total Bayar</span>
                                <span id="summary_total" class="text-2xl font-bold text-blue-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center">
                <button type="button" id="prevBtn"
                    class="hidden px-6 py-3 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">
                    <i class='bx bx-chevron-left mr-2'></i>
                    Sebelumnya
                </button>
                <div class="ml-auto flex gap-3">
                    <a href="<?= $baseURL ?>/dashboard/transaksi"
                        class="px-6 py-3 bg-slate-700 hover:bg-slate-600 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="button" id="nextBtn"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                        Selanjutnya
                        <i class='bx bx-chevron-right ml-2'></i>
                    </button>
                    <button type="submit" id="submitBtn" name="tambah"
                        class="hidden px-6 py-3 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                        <i class='bx bx-check mr-2'></i>
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;

    // Radio button event listeners with enhanced UI feedback
    document.querySelectorAll('input[name="jenis_kendaraan"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset all radio containers
            document.querySelectorAll('[name="jenis_kendaraan"]').forEach(r => {
                r.closest('.group').querySelector('div').classList.remove('border-blue-500', 'border-purple-500');
                r.closest('.group').querySelector('div').classList.add('border-slate-700');
            });

            // Style selected radio
            const container = this.closest('.group').querySelector('div');
            container.classList.remove('border-slate-700');
            container.classList.add(this.value === 'mobil' ? 'border-blue-500' : 'border-purple-500');

            // Show relevant services
            document.getElementById('layananMobil').classList.add('hidden');
            document.getElementById('layananMotor').classList.add('hidden');

            if (this.value === 'mobil') {
                document.getElementById('layananMobil').classList.remove('hidden');
            } else {
                document.getElementById('layananMotor').classList.remove('hidden');
            }
        });
    });

    // Service selection handlers
    document.querySelectorAll('input[name="id_layanan"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset all service containers
            document.querySelectorAll('[name="id_layanan"]').forEach(r => {
                r.closest('.group').querySelector('div').classList.remove('border-blue-500', 'border-purple-500');
                r.closest('.group').querySelector('div').classList.add('border-slate-700');
            });

            // Style selected service
            const container = this.closest('.group').querySelector('div');
            container.classList.remove('border-slate-700');
            container.classList.add(document.querySelector('input[name="jenis_kendaraan"]:checked').value === 'mobil' ? 'border-blue-500' : 'border-purple-500');
        });
    });

    // Navigation handlers
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            updateFormDisplay();
        }
    });

    document.getElementById('prevBtn').addEventListener('click', function() {
        currentStep--;
        updateFormDisplay();
    });

    function validateStep(step) {
        if (step === 1) {
            const plat = document.querySelector('input[name="nomor_plat"]').value.trim();
            const nama = document.querySelector('input[name="nama_pelanggan"]').value.trim();
            const jenisKendaraan = document.querySelector('input[name="jenis_kendaraan"]:checked');

            if (!plat || !nama || !jenisKendaraan) {
                alert('Mohon lengkapi semua data pelanggan');
                return false;
            }
        } else if (step === 2) {
            const layanan = document.querySelector('input[name="id_layanan"]:checked');
            if (!layanan) {
                alert('Mohon pilih layanan yang diinginkan');
                return false;
            }
        }
        return true;
    }

    function updateFormDisplay() {
        // Update stepper progress
        document.getElementById('stepperProgress').style.width = `${((currentStep - 1) / (totalSteps - 1)) * 100}%`;

        // Update stepper icons
        const stepperIcons = document.querySelectorAll('.rounded-full');
        const stepperTexts = document.querySelectorAll('.text-sm.font-medium');

        stepperIcons.forEach((icon, index) => {
            if (index + 1 === currentStep) {
                icon.classList.remove('bg-slate-700');
                icon.classList.add('bg-blue-500');
            } else if (index + 1 < currentStep) {
                icon.classList.remove('bg-slate-700', 'bg-blue-500');
                icon.classList.add('bg-green-500');
            } else {
                icon.classList.remove('bg-blue-500', 'bg-green-500');
                icon.classList.add('bg-slate-700');
            }
        });

        stepperTexts.forEach((text, index) => {
            text.classList.remove('text-blue-500', 'text-green-500', 'text-slate-400');
            if (index + 1 === currentStep) {
                text.classList.add('text-blue-500');
            } else if (index + 1 < currentStep) {
                text.classList.add('text-green-500');
            } else {
                text.classList.add('text-slate-400');
            }
        });

        // Show/hide steps
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById(`step${i}`).classList.toggle('hidden', i !== currentStep);
        }

        // Update navigation buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        prevBtn.classList.toggle('hidden', currentStep === 1);
        nextBtn.classList.toggle('hidden', currentStep === totalSteps);
        submitBtn.classList.toggle('hidden', currentStep !== totalSteps);

        if (currentStep === totalSteps) {
            updateSummary();
        }
    }

    function updateSummary() {
        const plat = document.querySelector('input[name="nomor_plat"]').value;
        const nama = document.querySelector('input[name="nama_pelanggan"]').value;
        const jenisKendaraan = document.querySelector('input[name="jenis_kendaraan"]:checked').value;
        const layanan = document.querySelector('input[name="id_layanan"]:checked');
        const layananLabel = layanan.closest('label').querySelector('.font-medium').textContent;
        const harga = parseInt(layanan.getAttribute('data-harga'));

        document.getElementById('summary_plat').textContent = plat.toUpperCase();
        document.getElementById('summary_nama').textContent = nama;
        document.getElementById('summary_kendaraan').textContent = jenisKendaraan;
        document.getElementById('summary_layanan').textContent = layananLabel;
        document.getElementById('summary_total').textContent = `Rp ${harga.toLocaleString('id-ID')}`;
    }

    // Form submit handler
    document.getElementById('formTransaksi').addEventListener('submit', function(e) {
        if (!validateStep(currentStep)) {
            e.preventDefault();
        }
    });
</script>

<?php include '../components/footer.php'; ?>