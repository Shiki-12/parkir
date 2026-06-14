# SIJA Parking Laravel - Fatar Gaza

SIJA Parking adalah aplikasi parkir kendaraan berbasis Laravel. Aplikasi ini dibuat untuk mengelola lokasi parkir, jenis kendaraan, transaksi kendaraan masuk, tiket parkir PDF, kendaraan keluar, perhitungan biaya parkir otomatis, kapasitas parkir, dan riwayat transaksi.

## Fitur Singkat

* Kelola data Location atau lokasi parkir.
* Kelola data Vehicle Type atau jenis kendaraan.
* Pilih jenis kendaraan dan lokasi parkir.
* Proses kendaraan masuk.
* Generate tiket parkir PDF.
* Proses kendaraan keluar.
* Hitung biaya parkir otomatis.
* Kapasitas parkir berkurang saat kendaraan masuk.
* Kapasitas parkir bertambah saat kendaraan keluar.
* View All untuk melihat riwayat transaksi.

## Kebutuhan Sistem

Pastikan laptop sudah terinstall:

* PHP
* Composer
* MySQL atau MariaDB
* Laravel support environment, seperti Laragon, XAMPP, atau sejenisnya

## Cara Menjalankan Project di Local

### 1. Extract Project

Extract file project ke folder server lokal, contoh:

```
C:\laragon\www\SIJA_Parking
```

atau jika memakai XAMPP:

```
C:\xampp\htdocs\SIJA_Parking
```

### 2. Masuk ke Folder Project

Buka terminal di folder project:

```
cd SIJA_Parking
```

### 3. Install Dependency Composer

Jalankan:

```
composer install
```

### 4. Buat File .env

Copy file `.env.example` menjadi `.env`.

Jika lewat terminal:

```
cp .env.example .env
```

Jika di Windows dan command di atas tidak bisa, copy manual file `.env.example`, lalu rename menjadi `.env`.

### 5. Edit Konfigurasi Database di .env

Sesuaikan bagian database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sija_parking
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan database lokal masing-masing.

### 6. Tambahkan Konfigurasi Timezone dan Testing Mode

Di file `.env`, pastikan ada konfigurasi berikut:

```
APP_TIMEZONE=Asia/Jakarta
PARKING_TEST_MODE=true
```

Keterangan:

* `APP_TIMEZONE=Asia/Jakarta` dipakai agar waktu tiket dan transaksi sesuai WIB.
* `PARKING_TEST_MODE=true` dipakai untuk testing. Dalam mode ini, 1 menit nyata dihitung sebagai 1 jam parkir.

Jika ingin memakai waktu normal, ubah menjadi:

```
PARKING_TEST_MODE=false
```

### 7. Generate Application Key

Jalankan:

```
php artisan key:generate
```

### 8. Buat Database

Buat database baru di MySQL/phpMyAdmin dengan nama sesuai `.env`, contoh:

```
sija_parking
```

### 9. Jalankan Migration

Jalankan:

```
php artisan migrate
```

Jika project memiliki seeder, jalankan:

```
php artisan db:seed
```

Atau bisa juga langsung:

```
php artisan migrate --seed
```

### 10. Buat Storage Link

Jalankan:

```
php artisan storage:link
```

Perintah ini diperlukan agar file tiket PDF bisa diakses dari browser.

### 11. Clear Cache

Jalankan:

```
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 12. Jalankan Server Laravel

Jalankan:

```
php artisan serve
```

Lalu buka browser:

```
http://127.0.0.1:8000
```

## Cara Testing Singkat

1. Buka halaman Location.
2. Tambahkan lokasi parkir, misalnya Gedung A, Gedung B, dan Gedung C.
3. Buka halaman Vehicle Type.
4. Tambahkan jenis kendaraan, misalnya Motorcycle, Car, dan Other.
5. Buka halaman Transaction.
6. Pilih jenis kendaraan.
7. Pilih lokasi parkir.
8. Klik ENTER VEHICLE.
9. Tiket akan muncul dan bisa dibuka sebagai PDF.
10. Klik tiket aktif untuk mengisi form keluar.
11. Klik EXIT VEHICLE.
12. Sistem akan menghitung total biaya parkir otomatis.

## Aturan Perhitungan Parkir

Untuk durasi 1 sampai 24 jam:

```
Total Bayar = perjam_pertama + perjam_berikutnya × (total_jam - 1)
```

Jika hasil perhitungan melebihi `max_perhari`, maka total bayar menjadi `max_perhari`.

Untuk durasi lebih dari 24 jam:

```
Total Hari = floor(total_jam / 24)
Tarif Per Hari = max_perhari × 60%
Total Bayar = Total Hari × Tarif Per Hari
```

## Catatan

Jika waktu tiket berbeda dengan jam laptop, pastikan `.env` sudah memakai:

```
APP_TIMEZONE=Asia/Jakarta
```

Setelah mengubah `.env`, jalankan ulang:

```
php artisan config:clear
php artisan cache:clear
```

Jika ada transaksi lama saat timezone belum benar, sebaiknya hapus transaksi testing lama atau buat transaksi baru untuk pengujian.
