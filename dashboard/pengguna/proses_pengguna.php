<?php
session_start();
require_once '../../config/koneksi.php';

// Handle add user
if (isset($_POST['tambah'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    $id_pengguna = $_SESSION['user_id'];

    // Check if username exists
    $check_query = "SELECT username FROM pengguna WHERE username = '$username'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: tambah.php?status=error&message=Username sudah digunakan");
        exit();
    }

    // Insert user
    $query = "INSERT INTO pengguna (nama_lengkap, username, password, level) 
              VALUES ('$nama_lengkap', '$username', '$password', '$level')";

    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $detail = "Menambahkan pengguna baru: $username ($level)";
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$id_pengguna', 'tambah_pengguna', '$detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        header("Location: index.php?status=success&message=Pengguna berhasil ditambahkan");
        exit();
    }
}

// Handle edit user
if (isset($_POST['edit'])) {
    $id_pengguna = mysqli_real_escape_string($koneksi, $_POST['id_pengguna']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    $admin_id = $_SESSION['user_id'];

    // Check if username exists for other users
    $check_query = "SELECT username FROM pengguna WHERE username = '$username' AND id_pengguna != '$id_pengguna'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: edit.php?id=$id_pengguna&status=error&message=Username sudah digunakan");
        exit();
    }

    // Update query
    $query = "UPDATE pengguna SET 
              nama_lengkap = '$nama_lengkap',
              username = '$username',
              level = '$level'";

    // Add password to update if provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query .= ", password = '$password'";
    }

    $query .= " WHERE id_pengguna = '$id_pengguna'";

    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $detail = "Mengubah data pengguna: $username";
        $query_log = "INSERT INTO log_aktivitas (id_pengguna, aktivitas, detail, ip_address) 
                     VALUES ('$admin_id', 'edit_pengguna', '$detail', '{$_SERVER['REMOTE_ADDR']}')";
        mysqli_query($koneksi, $query_log);

        header("Location: index.php?status=success&message=Data pengguna berhasil diperbarui");
        exit();
    }
}

header("Location: index.php");
exit();
