# ZCarWash - Sistem Manajemen Pencucian Kendaraan

ZCarWash adalah sistem manajemen komprehensif yang dirancang khusus untuk bisnis pencucian kendaraan, dilengkapi dengan pemantauan real-time, pelaporan detail, dan penanganan transaksi yang efisien.

---

## 🚀 Gambaran Sistem

### Fitur Utama

#### 🔒 Sistem Autentikasi
- Login aman dengan enkripsi password
- Manajemen sesi
- Kontrol akses berbasis peran
- Pelacakan aktivitas
- Keamanan auto logout

#### 📊 Dashboard
- Metrik bisnis real-time
- Ringkasan pendapatan hari ini
- Transaksi terbaru
- Grafik performa layanan
- Tombol aksi cepat

#### 💰 Manajemen Transaksi
- Pembuatan transaksi multi-langkah
- Pemilihan jenis kendaraan (Mobil/Motor)
- Pemilihan paket layanan
- Pelacakan status pembayaran
- Pembuatan invoice
- Riwayat transaksi
- Pembaruan status

#### 🛠️ Manajemen Layanan
- Pembuatan paket layanan
- Kustomisasi harga
- Pengaturan durasi
- Kategorisasi jenis kendaraan
- Toggle status layanan
- Analisis layanan

#### 👤 Manajemen Pengguna
- Pembuatan dan pengeditan pengguna
- Penugasan peran
- Kontrol akses
- Pemantauan aktivitas
- Manajemen password
- Pelacakan status pengguna

#### 📄 Sistem Pelaporan
- Laporan transaksi harian
- Laporan pendapatan bulanan
- Statistik penggunaan layanan
- Analisis metode pembayaran
- Laporan dapat dicetak
- Opsi filter data

#### 📝 Pencatatan Aktivitas
- Pelacakan tindakan pengguna
- Log akses sistem
- Log transaksi
- Pencatatan error
- Pelacakan alamat IP

---

## 💻 Detail Teknis

### Teknologi yang Digunakan
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, Tailwind CSS
- **JavaScript**: Vanilla JS
- **Ikon**: Boxicons
- **UI**: Tema Gelap Modern

### Struktur Database
- `pengguna`
- `layanan`
- `transaksi`
- `log_aktivitas`

### Kebutuhan Sistem
- **Web Server**: Apache/Nginx
- **Versi PHP**: 7.4+
- **Versi MySQL**: 5.7+
- **Browser**: Browser modern
- **RAM**: Minimal 2GB
- **Penyimpanan**: 500MB

---

## 📦 Panduan Instalasi

### 1. Pengaturan Server
```bash
# Clone repositori
git clone https://github.com/yourusername/zcarwash.git

# Atur perizinan
chmod 755 -R zcarwash/
chmod 777 -R zcarwash/uploads/
```

### 2. Pengaturan Database
```sql
-- Buat database
CREATE DATABASE db_zcarwash;

-- Import skema\mysql -u root -p db_zcarwash < db_zcarwash.sql
```

### 3. Konfigurasi
1. Salin `config/koneksi.example.php` ke `config/koneksi.php`.
2. Perbarui kredensial database.
3. Konfigurasi URL dasar.

### 4. Akses Awal
- **URL**: `http://your-domain/zcarwash`
- **Username**: `admin`
- **Password**: `password`

---

## 🔑 Peran & Izin Pengguna

### Administrator
- Akses penuh sistem
- Manajemen pengguna
- Konfigurasi sistem
- Pembuatan laporan
- Manajemen layanan
- Manajemen transaksi
- Pemantauan aktivitas

### Kasir
- Pembuatan transaksi
- Pemrosesan pembayaran
- Pelaporan dasar
- Pembaruan status layanan
- Manajemen pelanggan

---

## 🔐 Fitur Keamanan
- Enkripsi password (BCrypt)
- Manajemen sesi
- Pencegahan SQL injection
- Perlindungan XSS
- Perlindungan CSRF
- Pencatatan aktivitas
- Pelacakan IP

---

## 🛠️ Pemeliharaan

### Tugas Rutin
- Backup database
- Rotasi log
- Pembersihan cache
- Pembersihan sesi
- Pemantauan log error

### Penyelesaian Masalah
- Periksa log error
- Verifikasi koneksi database
- Validasi izin file
- Pantau sumber daya server
- Tinjau log keamanan

---

## 📞 Dukungan & Pembaruan

### Informasi Versi
- **Versi Saat Ini**: 1.0.0
- **Tanggal Rilis**: 2024
- **Pembaruan Terakhir**: Januari 2024

### Kontak Pengembang
- **Pengembang**: Muhammad Faza Husnan
- **Email**: [Faza Husnan](fazahusnan06@gmail.com)
- **GitHub**: [KaZaaaa8](https://github.com/KaZaaaa8)

---

## 📜 Lisensi & Kredit

### Lisensi
&copy; 2024 ZCarWash. Hak Cipta Dilindungi.

### Ucapan Terima Kasih
- [Tailwind CSS](https://tailwindcss.com/)
- [Boxicons](https://boxicons.com/)
- Komunitas PHP
- Komunitas MySQL

---

## 📅 Pengembangan Mendatang

### Fitur yang Direncanakan
- Aplikasi mobile
- Sistem loyalitas pelanggan
- Pemesanan online
- Notifikasi SMS
- Integrasi payment gateway
- Penjadwalan karyawan
- Manajemen inventaris
