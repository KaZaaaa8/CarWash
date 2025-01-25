<?php
session_start();
require_once '../../../config/koneksi.php';

// Get date filter
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - <?= date('d/m/Y', strtotime($tanggal)) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white p-8" onload="window.print()">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">ZCarWash</h1>
            <p class="text-gray-600">Laporan Harian Transaksi</p>
            <p class="text-gray-600">Tanggal: <?= date('d/m/Y', strtotime($tanggal)) ?></p>
        </div>

        <!-- Summary Section -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="border rounded-lg p-4">
                <p class="text-sm text-gray-600">Total Transaksi</p>
                <p class="text-xl font-bold"><?= $summary['total_transaksi'] ?></p>
            </div>
            <div class="border rounded-lg p-4">
                <p class="text-sm text-gray-600">Total Pendapatan</p>
                <p class="text-xl font-bold">Rp <?= number_format($summary['total_pendapatan'], 0, ',', '.') ?></p>
            </div>
            <div class="border rounded-lg p-4">
                <p class="text-sm text-gray-600">Transaksi Selesai</p>
                <p class="text-xl font-bold"><?= $summary['transaksi_selesai'] ?></p>
            </div>
            <div class="border rounded-lg p-4">
                <p class="text-sm text-gray-600">Transaksi Pending</p>
                <p class="text-xl font-bold"><?= $summary['transaksi_pending'] ?></p>
            </div>
        </div>

        <!-- Transactions Table -->
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Invoice</th>
                    <th class="border p-2 text-left">Waktu</th>
                    <th class="border p-2 text-left">Pelanggan</th>
                    <th class="border p-2 text-left">Layanan</th>
                    <th class="border p-2 text-left">Total</th>
                    <th class="border p-2 text-left">Status</th>
                    <th class="border p-2 text-left">Kasir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="border p-2"><?= $row['kode_invoice'] ?></td>
                            <td class="border p-2"><?= date('H:i', strtotime($row['tanggal_transaksi'])) ?></td>
                            <td class="border p-2">
                                <?= $row['nama_pelanggan'] ?><br>
                                <span class="text-sm text-gray-600"><?= $row['nomor_plat'] ?></span>
                            </td>
                            <td class="border p-2">
                                <?= $row['nama_layanan'] ?><br>
                                <span class="text-sm text-gray-600 capitalize"><?= $row['jenis_kendaraan'] ?></span>
                            </td>
                            <td class="border p-2">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                            <td class="border p-2">
                                <?= $row['status_pembayaran'] == 'sudah_bayar' ? 'Lunas' : 'Pending' ?>
                            </td>
                            <td class="border p-2"><?= $row['kasir'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <!-- Total Row -->
                    <tr class="font-bold bg-gray-50">
                        <td colspan="4" class="border p-2 text-right">Total Pendapatan:</td>
                        <td colspan="3" class="border p-2">
                            Rp <?= number_format($summary['total_pendapatan'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="border p-2 text-center text-gray-600">
                            Tidak ada transaksi pada tanggal ini
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-8 text-sm text-gray-600">
            <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
            <p>Oleh: <?= $_SESSION['nama'] ?></p>
        </div>
    </div>
</body>

</html>