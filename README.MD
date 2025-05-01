# Harpa E-Commerce

Panduan langkah demi langkah untuk menginstal dan mengonfigurasi aplikasi Laravel ini.

## Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js dan NPM
- MySQL/MariaDB
- Git

## Langkah-langkah Instalasi

### 1. Clone Repositori

```bash
https://github.com/JOSUA-EDISON/final-harpav2.git
```

### 2. Instalasi Dependensi

Instal semua dependensi PHP yang diperlukan menggunakan Composer:

```bash
composer install
```

Jika mengalami masalah terkait persyaratan platform, gunakan flag `--ignore-platform-reqs`:

```bash
composer install --ignore-platform-reqs
```

Instal semua dependensi JavaScript yang diperlukan menggunakan NPM:

```bash
npm install
```

### 3. Konfigurasi Lingkungan

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Setelah menyalin, buka file `.env` dan sesuaikan konfigurasi berikut:

```
APP_NAME=Harpa
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=harpa-v3
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan untuk membuat database `harpa-v3` di MySQL Anda terlebih dahulu.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Migrasi Database

Jalankan migrasi database untuk membuat semua tabel yang diperlukan:

```bash
php artisan migrate
```

Jika Anda ingin mengisi database dengan data contoh, jalankan:

```bash
php artisan db:seed
```

### 6. Konfigurasi Penyimpanan

Buat symlink untuk penyimpanan:

```bash
php artisan storage:link
```

### 7. Konfigurasi API dan Layanan

Aplikasi ini menggunakan beberapa layanan eksternal:

#### RajaOngkir API

Dapatkan API key RajaOngkir dari [RajaOngkir](https://rajaongkir.com/) dan tambahkan ke `.env`:

```
RAJAONGKIR_API_KEY=your_api_key
RAJAONGKIR_PACKAGE=starter
```

#### Midtrans (Pembayaran)

Dapatkan kredensial Midtrans dari [Midtrans](https://midtrans.com/) dan tambahkan ke `.env`:

```
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

#### Google Login (Opsional)

Jika Anda ingin menggunakan fitur login Google, dapatkan kredensial dari [Google Cloud Console](https://console.cloud.google.com/) dan tambahkan ke `.env`:

```
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 8. Kompilasi Aset

Untuk lingkungan pengembangan:

```bash
npm run dev
```

Untuk lingkungan produksi:

```bash
npm run build
```

### 9. Menjalankan Server Lokal

```bash
php artisan serve
```

Aplikasi sekarang dapat diakses di: `http://localhost:8000`

## Penggunaan Dasar

1. Buka browser dan akses `http://localhost:8000`
2. Login sebagai admin menggunakan kredensial default (jika Anda telah menjalankan seeder):
   - Email: admin@example.com
   - Password: password

## Troubleshooting

### Masalah Umum

1. **Kesalahan Izin**: Pastikan direktori `storage` dan `bootstrap/cache` dapat ditulis oleh server web.

   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

2. **Kesalahan Composer**: Jika Anda mendapatkan kesalahan saat menjalankan `composer install`, coba:

   ```bash
   composer update --no-scripts
   composer install --no-scripts
   ```

3. **Kesalahan Migrasi**: Jika migrasi gagal, coba:

   ```bash
   php artisan migrate:fresh
   ```

4. **Cache Konfigurasi**: Jika Anda mengubah konfigurasi dan perubahan tidak terlihat, hapus cache:

   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Informasi Tambahan

- Aplikasi ini menggunakan Laravel versi 12.x
- Framework frontend menggunakan Vite untuk kompilasi aset
- Untuk informasi lebih lanjut tentang Laravel, lihat [dokumentasi resmi Laravel](https://laravel.com/docs)

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
