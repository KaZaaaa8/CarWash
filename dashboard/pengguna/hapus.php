<?php
session_start();
require_once '../../config/koneksi.php';

if (isset($_GET['id']) && $_SESSION['level'] == 'admin') {
    $id_pengguna = mysqli_real_escape_string($koneksi, $_GET['id']);
    $admin_id = $_SESSION['user_id'];

    // Get user info for logging
    $query_user = "SELECT username, level FROM pengguna WHERE id_pengguna = '$id_pengguna'";
    $result = mysqli_query($koneksi, $query_user);
    $user = mysqli_fetch_assoc($result);

    // Check if user has any transactions
    $query_check = "SELECT COUNT(*) as total FROM transaksi WHERE id_pengguna = '$id_pengguna'";
    $result_check = mysqli_query($koneksi, $query_check);
    $row = mysqli_fetch_assoc($result_check);

    if ($row['total'] > 0) {
        header("Location: index.php?status=error&message=Pengguna tidak dapat dihapus karena memiliki transaksi");
        exit();
    }

    // Delete user
    $query = "DELETE FROM pengguna WHERE id_pengguna = '$id_pengguna'";
    
    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $detail = "Menghapus pengguna: {$user['username']} ({$user['level']})";
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$admin_id', 'hapus_pengguna', '$detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        header("Location: index.php?status=success&message=Pengguna berhasil dihapus");
        exit();
    }
}

header("Location: index.php");
exit();
?>
