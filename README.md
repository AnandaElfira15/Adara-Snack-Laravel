# Adara Snack — UTS Web Programming 2

Aplikasi persediaan dan penjualan untuk toko makanan ringan dan minuman. Dibangun sebagai starter project pembelajaran menggunakan Laravel 12, PHP, MySQL, Eloquent ORM, Blade, autentikasi, dan pembatasan akses Admin/Staff.

## 1. Kebutuhan
- PHP 8.2 atau lebih baru beserta ekstensi yang dibutuhkan Laravel.
- Composer.
- MySQL/MariaDB (bisa menggunakan XAMPP).
- VS Code disarankan.

> Folder `vendor` tidak disertakan. Composer akan mengunduh dependensi saat `composer install` dijalankan.

## 2. Instalasi di Windows (VS Code + XAMPP)
1. Ekstrak folder proyek ke lokasi kerja, misalnya `C:\xampp\htdocs\adara-snack`.
2. Buka XAMPP, lalu aktifkan **MySQL**. Aktifkan Apache jika ingin menggunakan phpMyAdmin.
3. Buka folder proyek melalui VS Code, lalu buka Terminal.
4. Jalankan `composer install` dan tunggu sampai selesai.
5. Salin `.env.example` menjadi `.env` dengan PowerShell: `Copy-Item .env.example .env`.
6. Buka `http://localhost/phpmyadmin`, buat database bernama `adara_snack` dengan collation `utf8mb4_unicode_ci`.
7. Pastikan pengaturan database di `.env`: `DB_DATABASE=adara_snack`, `DB_USERNAME=root`, `DB_PASSWORD=`. Sesuaikan jika MySQL kamu memakai password.
8. Jalankan `php artisan key:generate`.
9. Jalankan `php artisan migrate --seed` untuk membuat tabel dan data demo.
10. Jalankan `php artisan serve`.
11. Buka `http://127.0.0.1:8000` di browser.

## 3. Akun demo
- Admin: `admin@adarasnack.test` / `Admin123!`
- Staff: `staff@adarasnack.test` / `Staff123!`

Akun ini hanya untuk demo lokal. Jangan gunakan password demo untuk sistem nyata dan jangan unggah file `.env` ke GitHub.

## 4. Contoh produk
Seeder menyediakan contoh seperti Es Teh Manis, Es Teh Lemon, Kentang Goreng, Sosis Goreng, Nugget Goreng, dan Keripik Singkong. Kategori awal: Minuman, Makanan Ringan, Gorengan, dan Camilan Kemasan.

## 5. Fitur yang disediakan dalam starter project
- Login/logout dan penyimpanan password dengan hashing.
- Dashboard, kategori, produk, supplier, pembelian, penjualan, dan pengelolaan pengguna untuk Admin.
- Pencarian/filter dan pagination pada halaman yang mendukungnya.
- Transaksi pembelian/penjualan dengan beberapa item.
- Perhitungan total transaksi di sisi server, pengecekan stok, serta transaksi database untuk menjaga konsistensi.
- Daftar stok rendah.

## 6. Struktur teknis
- `routes/web.php`: named routes, auth, dan middleware role.
- `app/Http/Controllers`: logika request, validasi, dan alur aplikasi.
- `app/Models`: model dan relasi Eloquent.
- `database/migrations`: struktur tabel dan foreign key.
- `database/seeders/DatabaseSeeder.php`: akun demo, kategori, supplier, dan contoh produk.
- `resources/views`: halaman Blade.
- `public/css/app.css`: tampilan navy, putih, dan biru.
- `docs/ERD.md`: ERD dan penjelasan relasi.
- `docs/TEST_CASES.md`: checklist pengujian.

## 7. Pengujian sebelum dikumpulkan
1. Jalankan `php artisan test` setelah dependensi dipasang.
2. Uji login Admin dan Staff.
3. Uji tambah/edit produk, SKU duplikat, harga tidak valid, pembelian menambah stok, penjualan mengurangi stok, dan penjualan saat stok tidak cukup.
4. Pastikan Staff tidak bisa membuka pengelolaan pengguna, termasuk dengan mengetik URL langsung.
5. Ambil screenshot dari aplikasi yang benar-benar dijalankan dan isi hasil aktual pada checklist pengujian.

## 8. GitHub
1. Buat repository baru.
2. Pastikan `.env` tidak di-commit (sudah masuk `.gitignore`).
3. Jalankan `git init`, `git add .`, `git commit -m "Initial Adara Snack UTS project"`, lalu tambahkan remote GitHub dan push.
4. Sertakan screenshot, ERD, dan hasil pengujian yang benar-benar dilakukan.

## 9. Catatan penting
ZIP ini adalah starter project, bukan jaminan bahwa semua fitur telah lulus pengujian di semua komputer. Folder `vendor` tidak disertakan dan pengujian Laravel penuh harus dilakukan setelah `composer install` di komputer kamu. Jika ada pesan error, kirim screenshot atau salin teks error agar bisa diperbaiki. Pahami kode karena dosen dapat meminta kamu menjelaskan dan mengubahnya.
