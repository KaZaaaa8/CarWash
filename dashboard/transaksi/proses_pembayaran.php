<?php
session_start();
require_once '../../config/koneksi.php';

if (isset($_POST['bayar'])) {
    $id_transaksi = mysqli_real_escape_string($koneksi, $_POST['id_transaksi']);
    $metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);
    $id_pengguna = $_SESSION['user_id'];

    // Update transaction status
    $query = "UPDATE transaksi SET 
        status_pembayaran = 'sudah_bayar',
        metode_pembayaran = '$metode_pembayaran',
        waktu_bayar = CURRENT_TIMESTAMP,
        catatan = '$catatan'
        WHERE id_transaksi = '$id_transaksi'";

    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $detail = "Memproses pembayaran transaksi ID: $id_transaksi via $metode_pembayaran";
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$id_pengguna', 'proses_pembayaran', '$detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        // Redirect to transaction detail with success message
        header("Location: detail.php?id=$id_transaksi&status=success&message=Pembayaran berhasil diproses");
        exit();
    } else {
        header("Location: bayar.php?id=$id_transaksi&status=error&message=Gagal memproses pembayaran");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
