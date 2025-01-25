-- Tabel Pengguna (Admin/Kasir)
CREATE TABLE pengguna (
    id_pengguna INT PRIMARY KEY AUTO_INCREMENT,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    level ENUM('admin', 'kasir') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Tabel Layanan
CREATE TABLE layanan (
    id_layanan INT PRIMARY KEY AUTO_INCREMENT,
    nama_layanan VARCHAR(100) NOT NULL,
    jenis_kendaraan ENUM('motor', 'mobil') NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    durasi_menit INT DEFAULT 30,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Transaksi
CREATE TABLE transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    kode_invoice VARCHAR(20) NOT NULL UNIQUE,
    nomor_plat VARCHAR(20) NOT NULL,
    nama_pelanggan VARCHAR(100) NOT NULL,
    id_layanan INT,
    id_pengguna INT,
    total_bayar DECIMAL(10,2) NOT NULL,
    status_pembayaran ENUM('belum_bayar', 'sudah_bayar') DEFAULT 'belum_bayar',
    metode_pembayaran ENUM('tunai', 'qris', 'transfer') DEFAULT 'tunai',
    tanggal_transaksi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    waktu_mulai TIMESTAMP NULL,
    waktu_selesai TIMESTAMP NULL,
    waktu_bayar TIMESTAMP NULL,
    catatan TEXT,
    FOREIGN KEY (id_layanan) REFERENCES layanan(id_layanan),
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
);

-- Tabel Log Aktivitas
CREATE TABLE log_aktivitas (
    id_log INT PRIMARY KEY AUTO_INCREMENT,
    id_pengguna INT,
    aktivitas VARCHAR(255) NOT NULL,
    detail TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
);

-- Insert Default Admin Account
INSERT INTO pengguna (nama_lengkap, username, password, level) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert Sample Layanan
INSERT INTO layanan (nama_layanan, jenis_kendaraan, harga, durasi_menit) VALUES
('Cuci Motor Basic', 'motor', 15000, 30),
('Cuci Motor Premium', 'motor', 25000, 45),
('Cuci Mobil Basic', 'mobil', 35000, 45),
('Cuci Mobil Premium', 'mobil', 50000, 60);
