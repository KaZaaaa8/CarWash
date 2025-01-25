<?php
session_start();
require_once '../../config/koneksi.php';

if (isset($_GET['id'])) {
    $id_layanan = mysqli_real_escape_string($koneksi, $_GET['id']);
    $id_pengguna = $_SESSION['user_id'];

    // Get service name for logging
    $query_layanan = "SELECT nama_layanan FROM layanan WHERE id_layanan = '$id_layanan'";
    $result = mysqli_query($koneksi, $query_layanan);
    $layanan = mysqli_fetch_assoc($result);

    // Check if service is used in any transactions
    $query_check = "SELECT COUNT(*) as total FROM transaksi WHERE id_layanan = '$id_layanan'";
    $result_check = mysqli_query($koneksi, $query_check);
    $row = mysqli_fetch_assoc($result_check);

    if ($row['total'] > 0) {
        // If service is used, just deactivate it
        $query = "UPDATE layanan SET status = 'nonaktif' WHERE id_layanan = '$id_layanan'";
        $log_detail = "Menonaktifkan layanan: {$layanan['nama_layanan']}";
    } else {
        // If service is not used, delete it
        $query = "DELETE FROM layanan WHERE id_layanan = '$id_layanan'";
        $log_detail = "Menghapus layanan: {$layanan['nama_layanan']}";
    }

    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$id_pengguna', 'hapus_layanan', '$log_detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        header("Location: index.php?status=success&message=Layanan berhasil dihapus");
        exit();
    } else {
        header("Location: index.php?status=error&message=Gagal menghapus layanan");
        exit();
    }
}

header("Location: index.php");
exit();
