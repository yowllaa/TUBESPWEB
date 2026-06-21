# Sistem Informasi Layanan Darurat & Pelaporan Masyarakat Berbasis Lokasi (SIP-DARURAT)

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC.svg)](https://tailwindcss.com)
[![LeafletJS](https://img.shields.io/badge/Leaflet-JS-Green.svg)](https://leafletjs.com)

**SIP-DARURAT** adalah platform web modern berbasis geolokasi yang dirancang untuk membantu masyarakat mendapatkan bantuan darurat tercepat dari pos instansi terdekat serta melaporkan masalah infrastruktur publik (non-darurat) secara presisi menggunakan peta interaktif.

Aplikasi ini dibangun menggunakan teknologi **Laravel 12**, **Tailwind CSS**, **Alpine.js**, dan **Leaflet.js (OpenStreetMap)**.

---

## 🚀 Fitur Utama

### 1. 🚨 Layanan Darurat Cepat (Quick Emergency Call)
- **Deteksi Geolocation Otomatis**: Mendeteksi koordinat GPS pengguna secara real-time via browser.
- **Penentuan Lokasi Manual (Fallback)**: Pin biru pada peta dapat digeser manual jika GPS browser tidak aktif.
- **Haversine Distance calculation**: Sistem secara dinamis menghitung jarak lurus pos instansi terdekat.
- **Direktori & Peta Rute**: Menampilkan jarak (km), nomor telepon, dan tombol **"HUBUNGI SEKARANG"** (`tel:`) dengan rute visual instansi terdekat.

### 2. 📝 Pelaporan Publik (Non-Darurat)
- **Formulir Laporan**: Judul aduan, Kategori (Jalan Rusak, Lampu Mati, Sampah Menumpuk, dll), Deskripsi, Alamat tekstual, dan Unggah Foto Bukti.
- **Peta Pin-Drop**: Menentukan lokasi persis aduan dengan mengeklik peta Leaflet.js.
- **Timeline Tindak Lanjut**: Laporan diproses melalui status **Menunggu** $\rightarrow$ **Diproses** $\rightarrow$ **Selesai** dengan riwayat catatan instansi.

### 3. 🏢 Dashboard Instansi
- **Manajemen Profil**: Mengubah kontak darurat, alamat, dan menyesuaikan letak koordinat kantor pos di peta.
- **Disposisi & Respon**: Menerima aduan warga sesuai lingkup wilayahnya, menandai sebagai **Diproses**, dan mengunggah **Catatan Tindak Lanjut** untuk menyelesaikannya.

### 4. 🔑 Portal Admin (CRUD & Statistik)
- **CRUD Pengguna**: Mengelola data admin, instansi, dan masyarakat.
- **CRUD Instansi**: Menambah data instansi baru dan menautkan akun petugas login.
- **CRUD Kategori**: Kustomisasi jenis laporan kedaruratan maupun pengaduan masyarakat.
- **Grafik Interaktif (Chart.js)**: Visualisasi total laporan per bulan dan distribusi aduan berdasarkan kategori.

---

## 🛠️ Spesifikasi Teknologi
- **Backend**: PHP 8.4 & Laravel 12
- **Database**: SQLite (default Laravel 12)
- **Frontend**: Blade Template, Alpine.js, Tailwind CSS (Vite Bundler)
- **Peta (Mapping)**: Leaflet.js & OpenStreetMap API
- **Autentikasi**: Laravel Breeze (Multi-Role Support)

---

## 📦 Panduan Instalasi & Setup Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/username/layanan-darurat.git
cd layanan-darurat
```

### 2. Instalasi Dependensi Composer
```bash
composer install
```

### 3. Instalasi Dependensi Node.js & Compile Aset
```bash
npm install
npm run build
```

### 4. Salin Berkas Lingkungan (.env)
```bash
cp .env.example .env
```
*(Secara default Laravel 12 menggunakan SQLite, pastikan `DB_CONNECTION=sqlite` aktif di `.env`)*

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Database Seeder
Jalankan migrasi tabel beserta data awal (admin, user uji coba, pos damkar, rumah sakit, polsek, dll):
```bash
php artisan migrate:fresh --seed
```

### 7. Buat Symbolic Link Storage
Menghubungkan folder upload foto laporan warga agar dapat diakses publik:
```bash
php artisan storage:link
```

### 8. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser Anda dan akses alamat [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 🔑 Kredensial Uji Coba (Credentials)

Semua akun menggunakan kata sandi default: **`password`**

| Peran (Role) | Alamat Email | Nama Akun | Penanganan / Yurisdiksi |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@example.com` | Admin Darurat Lombok | Pusat Layanan Darurat Lombok (Kantor Gubernur) |
| **Masyarakat** | `user@example.com` | Masyarakat Lombok Sejahtera | Jl. Senggigi No. 15, Batu Layar |
| **Damkar (Kebakaran)** | `damkar@example.com` | Petugas Damkar Mataram | Damkar Kota Mataram |
| **Rumah Sakit (Medis)**| `medis@example.com` | Petugas RSUD Provinsi NTB | RSUD Provinsi NTB (IGD/Ambulans) |
| **Polisi (Keamanan)** | `polisi@example.com` | Petugas Polresta Mataram | Polresta Mataram |
| **BPBD (Bencana)** | `bpbd@example.com` | Petugas BPBD NTB | BPBD Provinsi NTB |
| **Dinas PU (Fasilitas)**| `pu@example.com` | Petugas Dinas PUPR NTB | Dinas PUPR Provinsi NTB |

---

## 📖 Cara Penggunaan Sistem

### Alur Warga (Masyarakat)
1. **Daftar/Masuk** menggunakan akun `user@example.com`.
2. **Kondisi Darurat**: Masuk ke menu **Panggilan Darurat**, pilih kategori (misal: *Kebakaran*), sistem akan meminta izin lokasi dan menampilkan Damkar Kota Mataram terdekat lengkap dengan peta rute, jarak, dan tombol hubungi.
3. **Pelaporan Masalah**: Masuk ke menu **Buat Laporan**, isi formulir, klik titik koordinat masalah di peta Leaflet, unggah gambar bukti, lalu kirim.

### Alur Instansi (Petugas)
1. **Masuk** menggunakan akun instansi terkait (misal: `pu@example.com` untuk Dinas PU).
2. Lihat daftar aduan masuk di dashboard.
3. Klik **Tinjau** pada salah satu aduan warga, lalu klik **Proses Laporan Ini** untuk mengubah status menjadi **Diproses**.
4. Jika masalah sudah selesai ditangani di lapangan, isi catatan penanganan pada form dan klik **Selesaikan Laporan Ini**.

### Alur Admin
1. **Masuk** dengan akun `admin@example.com`.
2. Pantau jumlah laporan, grafik statistik bulanan, dan donat kategori.
3. Kelola data wilayah, instansi baru, kategori, maupun aduan warga yang masuk.

---
*SIP-DARURAT - Layanan Darurat & Pelaporan Masyarakat Berbasis Lokasi untuk Masa Depan yang Aman dan Tanggap.*
