<?php
session_start();
require_once 'config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    $id_pengguna = $_SESSION['user_id'];

    // Log the logout activity
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    mysqli_query($koneksi, "INSERT INTO log_aktivitas (id_pengguna, aktivitas, ip_address, user_agent) 
                          VALUES ('$id_pengguna', 'Logout dari sistem', '$ip', '$user_agent')");
}

// Clear all session data
session_destroy();

// Redirect to login page
header("Location: index.php");
exit();
