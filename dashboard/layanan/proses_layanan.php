<?php
session_start();
require_once '../../config/koneksi.php';

if (isset($_POST['tambah'])) {
    // Get form data
    $nama_layanan = mysqli_real_escape_string($koneksi, $_POST['nama_layanan']);
    $jenis_kendaraan = mysqli_real_escape_string($koneksi, $_POST['jenis_kendaraan']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $durasi_menit = mysqli_real_escape_string($koneksi, $_POST['durasi_menit']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $id_pengguna = $_SESSION['user_id'];

    // Insert service
    $query = "INSERT INTO layanan (nama_layanan, jenis_kendaraan, harga, durasi_menit, status) 
              VALUES ('$nama_layanan', '$jenis_kendaraan', '$harga', '$durasi_menit', '$status')";

    if (mysqli_query($koneksi, $query)) {
        $id_layanan = mysqli_insert_id($koneksi);

        // Log activity
        $detail = "Menambahkan layanan baru: $nama_layanan";
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$id_pengguna', 'tambah_layanan', '$detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        header("Location: index.php?status=success&message=Layanan berhasil ditambahkan");
        exit();
    } else {
        header("Location: tambah.php?status=error&message=Gagal menambahkan layanan");
        exit();
    }
}

// Handle service update
if (isset($_POST['edit'])) {
    $id_layanan = mysqli_real_escape_string($koneksi, $_POST['id_layanan']);
    $nama_layanan = mysqli_real_escape_string($koneksi, $_POST['nama_layanan']);
    $jenis_kendaraan = mysqli_real_escape_string($koneksi, $_POST['jenis_kendaraan']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $durasi_menit = mysqli_real_escape_string($koneksi, $_POST['durasi_menit']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $id_pengguna = $_SESSION['user_id'];

    $query = "UPDATE layanan SET 
              nama_layanan = '$nama_layanan',
              jenis_kendaraan = '$jenis_kendaraan',
              harga = '$harga',
              durasi_menit = '$durasi_menit',
              status = '$status',
              updated_at = CURRENT_TIMESTAMP
              WHERE id_layanan = '$id_layanan'";

    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $detail = "Mengubah layanan: $nama_layanan";
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$id_pengguna', 'edit_layanan', '$detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        header("Location: index.php?status=success&message=Layanan berhasil diperbarui");
        exit();
    } else {
        header("Location: edit.php?id=$id_layanan&status=error&message=Gagal memperbarui layanan");
        exit();
    }
}

header("Location: index.php");
exit();
