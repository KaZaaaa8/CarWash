<?php
session_start();
require_once '../../config/koneksi.php';

// Get transaction details
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $transaksi['kode_invoice'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white p-8" onload="window.print()">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">ZCarWash</h1>
            <p class="text-gray-600">Jl. Cemrara Raya No. 123, Banjarmasin</p>
            <p class="text-gray-600">Telp: (021) 1234567</p>
        </div>

        <!-- Invoice Info -->
        <div class="border-b-2 border-gray-200 pb-4 mb-4">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-600">Invoice:</p>
                    <p class="font-bold"><?= $transaksi['kode_invoice'] ?></p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Tanggal:</p>
                    <p class="font-bold"><?= date('d/m/Y H:i', strtotime($transaksi['tanggal_transaksi'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="mb-6">
            <h2 class="text-lg font-bold mb-2">Informasi Pelanggan</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Nama:</p>
                    <p class="font-bold"><?= $transaksi['nama_pelanggan'] ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Nomor Plat:</p>
                    <p class="font-bold"><?= $transaksi['nomor_plat'] ?></p>
                </div>
            </div>
        </div>

        <!-- Service Details -->
        <div class="mb-6">
            <h2 class="text-lg font-bold mb-2">Detail Layanan</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-2">Layanan</th>
                        <th class="text-left py-2">Durasi</th>
                        <th class="text-right py-2">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2">
                            <?= $transaksi['nama_layanan'] ?>
                            <br>
                            <span class="text-gray-600 text-sm">
                                <?= ucfirst($transaksi['jenis_kendaraan']) ?>
                            </span>
                        </td>
                        <td class="py-2"><?= $transaksi['durasi_menit'] ?> menit</td>
                        <td class="text-right py-2">
                            Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-200">
                        <td colspan="2" class="py-2 font-bold">Total</td>
                        <td class="text-right py-2 font-bold">
                            Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Info -->
        <div class="mb-6">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-600">Status Pembayaran:</p>
                    <p class="font-bold">
                        <?= $transaksi['status_pembayaran'] == 'sudah_bayar' ? 'LUNAS' : 'BELUM LUNAS' ?>
                    </p>
                </div>
                <?php if ($transaksi['status_pembayaran'] == 'sudah_bayar'): ?>
                    <div class="text-right">
                        <p class="text-gray-600">Metode Pembayaran:</p>
                        <p class="font-bold"><?= ucfirst($transaksi['metode_pembayaran']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-600 text-sm mt-8">
            <p>Terima kasih telah menggunakan jasa ZCarWash</p>
            <p>Simpan struk ini sebagai bukti pembayaran yang sah</p>
        </div>

        <?php if ($transaksi['catatan']): ?>
            <!-- Notes -->
            <div class="mt-6 border-t-2 border-gray-200 pt-4">
                <p class="text-gray-600">Catatan:</p>
                <p><?= nl2br($transaksi['catatan']) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>