<?php
session_start();
require_once '../../../config/koneksi.php';

// Get month and year filter
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan - <?= date('F Y', strtotime("$tahun-$bulan-01")) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white p-8" onload="window.print()">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">ZCarWash</h1>
            <p class="text-gray-600">Laporan Bulanan Transaksi</p>
            <p class="text-gray-600">Periode: <?= date('F Y', strtotime("$tahun-$bulan-01")) ?></p>
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

        <!-- Daily Transactions Table -->
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Tanggal</th>
                    <th class="border p-2 text-left">Total Transaksi</th>
                    <th class="border p-2 text-left">Transaksi Selesai</th>
                    <th class="border p-2 text-left">Transaksi Pending</th>
                    <th class="border p-2 text-left">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="border p-2"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td class="border p-2"><?= $row['total_transaksi'] ?></td>
                            <td class="border p-2"><?= $row['transaksi_selesai'] ?></td>
                            <td class="border p-2"><?= $row['transaksi_pending'] ?></td>
                            <td class="border p-2">Rp <?= number_format($row['pendapatan'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <!-- Total Row -->
                    <tr class="font-bold bg-gray-50">
                        <td colspan="4" class="border p-2 text-right">Total Pendapatan:</td>
                        <td class="border p-2">
                            Rp <?= number_format($summary['total_pendapatan'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="border p-2 text-center text-gray-600">
                            Tidak ada transaksi pada bulan ini
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