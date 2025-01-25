<?php
session_start();
require_once '../../config/koneksi.php';

if (isset($_POST['tambah'])) {
    // Get form data
    $nomor_plat = strtoupper(mysqli_real_escape_string($koneksi, $_POST['nomor_plat']));
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $id_layanan = mysqli_real_escape_string($koneksi, $_POST['id_layanan']);
    $id_pengguna = $_SESSION['user_id'];

    // Get layanan details
    $query_layanan = "SELECT harga FROM layanan WHERE id_layanan = '$id_layanan'";
    $result_layanan = mysqli_query($koneksi, $query_layanan);
    $layanan = mysqli_fetch_assoc($result_layanan);
    $total_bayar = $layanan['harga'];

    // Generate invoice number (format: INV/YYYYMMDD/XXXX)
    $date = date('Ymd');
    $query_last_invoice = "SELECT kode_invoice FROM transaksi WHERE DATE(tanggal_transaksi) = CURDATE() ORDER BY id_transaksi DESC LIMIT 1";
    $result_last_invoice = mysqli_query($koneksi, $query_last_invoice);
    
    if (mysqli_num_rows($result_last_invoice) > 0) {
        $last_invoice = mysqli_fetch_assoc($result_last_invoice);
        $last_number = intval(substr($last_invoice['kode_invoice'], -4));
        $invoice_number = $last_number + 1;
    } else {
        $invoice_number = 1;
    }
    
    $kode_invoice = "INV/" . $date . "/" . str_pad($invoice_number, 4, '0', STR_PAD_LEFT);

    // Insert transaction
    $query = "INSERT INTO transaksi (kode_invoice, nomor_plat, nama_pelanggan, id_layanan, id_pengguna, total_bayar) 
              VALUES ('$kode_invoice', '$nomor_plat', '$nama_pelanggan', '$id_layanan', '$id_pengguna', '$total_bayar')";

    if (mysqli_query($koneksi, $query)) {
        $id_transaksi = mysqli_insert_id($koneksi);
        
        // Log activity
        $detail = "Membuat transaksi baru dengan kode invoice: $kode_invoice";
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$id_pengguna', 'tambah_transaksi', '$detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        // Redirect to transaction detail
        header("Location: detail.php?id=$id_transaksi&status=success");
        exit();
    } else {
        header("Location: tambah.php?status=error");
        exit();
    }
} else {
    header("Location: tambah.php");
    exit();
}
?>
