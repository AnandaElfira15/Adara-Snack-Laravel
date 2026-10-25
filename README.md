# Adara Snack — Inventory & Sales Management System

Aplikasi web berbasis Laravel untuk membantu pencatatan produk, kategori, supplier, pembelian, penjualan, dan laporan pada usaha makanan ringan dan minuman. Proyek ini dibuat untuk memenuhi UTS Web Programming 2 — Integrated Laravel Project.

## 1. Tujuan dan Ruang Lingkup

Adara Snack mengintegrasikan autentikasi, role-based access, pengelolaan master data, transaksi pembelian dan penjualan, pembaruan stok, validasi input, serta database MySQL melalui Laravel dan Eloquent ORM.

Fitur utama yang menjadi fokus sesuai ketentuan UTS:

- Login dan logout dengan autentikasi Laravel.
- Dashboard ringkas.
- Data produk dengan SKU, kategori, harga, stok, dan minimum stok.
- Data kategori dan supplier.
- Transaksi pembelian dan penjualan.
- Pencarian, filter, dan pagination pada halaman yang mendukungnya.
- Validasi input dan pesan error.
- Pembatasan akses berdasarkan role di sisi server.
- Migration, seeder, model, dan relasi Eloquent.
- Dokumentasi ERD serta skenario pengujian.

## 2. Teknologi

- PHP 8.2+
- Laravel 12
- MySQL/MariaDB
- Eloquent ORM
- Blade
- Composer
- XAMPP (opsional untuk menjalankan MySQL dan phpMyAdmin di Windows)

## 3. Role dan Hak Akses

- **Admin:** mengelola master data, pengguna, serta melihat transaksi dan laporan.
- **Staff:** dapat mengakses fitur operasional sesuai aturan aplikasi. Pengujian pada proyek ini menunjukkan akses ke halaman Pengguna ditolak untuk Staff.
- **User:** role tambahan pada proyek ini untuk melihat produk. Pembatasan akses lainnya perlu diuji melalui aplikasi.

Catatan: aturan role yang diwajibkan dalam brief UTS menekankan bahwa Staff tidak boleh mengelola role atau menghapus data master sensitif. Pemeriksaan akses harus dilakukan di server, bukan hanya dengan menyembunyikan menu.

## 4. Persyaratan

Pastikan perangkat memiliki:

- PHP 8.2 atau lebih baru beserta ekstensi yang diperlukan Laravel.
- Composer.
- MySQL atau MariaDB.
- Git jika ingin mengambil source code dari GitHub.
- Node.js/npm jika proyek memerlukan kompilasi aset frontend.

## 5. Cara Instalasi (Windows + XAMPP)

1. Clone repository atau ekstrak folder proyek.
2. Buka folder `adara-snack` di Visual Studio Code.
3. Jalankan XAMPP dan aktifkan MySQL. Apache dapat diaktifkan untuk menggunakan phpMyAdmin.
4. Buka terminal VS Code pada folder proyek, lalu jalankan:

   ```bash
   composer install
   ```

5. Buat file `.env` dari template.

   PowerShell:

   ```powershell
   Copy-Item .env.example .env
   ```

   macOS/Linux:

   ```bash
   cp .env.example .env
   ```

6. Buat database kosong bernama `adara_snack` melalui phpMyAdmin atau MySQL.
7. Atur koneksi database pada file `.env` lokal. Contoh nilai yang umum untuk XAMPP:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=adara_snack
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Password kosong hanya digunakan jika konfigurasi MySQL lokal memang tidak memakai password.

8. Buat application key:

   ```bash
   php artisan key:generate
   ```

9. Jalankan migration dan seeder:

   ```bash
   php artisan migrate --seed
   ```

10. Jika proyek menggunakan aset npm, jalankan:

    ```bash
    npm install
    npm run build
    ```

11. Jalankan server Laravel:

    ```bash
    php artisan serve
    ```

12. Buka alamat berikut di browser:

    `http://127.0.0.1:8000`

Jika database atau nama koneksi lokal berbeda, sesuaikan file `.env` milik sendiri. Jangan mengunggah file `.env` ke repository.

## 6. Akun Demo

Berikut akun demo yang tersedia pada aplikasi Adara Snack:

- **Admin:** admin@adarasnack.test
- **Staff:** staff@adarasnack.test
- **User:** elfira@adarasnack.test

Setiap akun memiliki hak akses sesuai dengan perannya masing-masing.

## 7. Struktur Folder Penting

| Lokasi | Fungsi |
|---|---|
| `routes/web.php` | Definisi URL, named routes, dan middleware |
| `app/Http/Controllers/` | Logika request, validasi, dan alur aplikasi |
| `app/Http/Middleware/` | Pemeriksaan role dan hak akses |
| `app/Models/` | Model dan relasi Eloquent |
| `database/migrations/` | Struktur tabel dan foreign key |
| `database/seeders/DatabaseSeeder.php` | Data awal untuk demo |
| `resources/views/` | Tampilan Blade |
| `docs/ERD.md` | ERD dan penjelasan relasi |
| `docs/TEST_CASES.md` | Skenario pengujian |
| `screenshot/` | Bukti tampilan aplikasi dan kode |

## 8. ERD dan Relasi Database

ERD disimpan di [`docs/ERD.md`](docs/ERD.md).

Secara ringkas, sistem menggunakan tabel:

- `users`
- `categories`
- `products`
- `suppliers`
- `purchases`
- `purchase_items`
- `sales`
- `sale_items`

Contoh relasi Eloquent yang digunakan atau diharapkan:

- Category `hasMany` Product; Product `belongsTo` Category.
- Supplier `hasMany` Purchase; Purchase `belongsTo` Supplier.
- Purchase `hasMany` PurchaseItem; PurchaseItem `belongsTo` Purchase dan Product.
- Sale `hasMany` SaleItem; SaleItem `belongsTo` Sale dan Product.
- User memiliki relasi ke transaksi yang dibuat.

Tabel item dipisahkan dari header agar satu transaksi dapat menyimpan beberapa produk. Foreign key menghubungkan item dengan transaksi dan produk terkait.

## 9. Pengujian

Daftar skenario pengujian disimpan di [`docs/TEST_CASES.md`](docs/TEST_CASES.md).

Skenario yang relevan dengan brief UTS meliputi:

- Login benar dan password salah.
- Guest tidak dapat membuka halaman yang dilindungi.
- Staff ditolak saat membuka halaman Pengguna.
- SKU duplikat dan input harga atau kuantitas tidak valid ditolak.
- Pembelian berhasil menambah stok.
- Penjualan berhasil mengurangi stok.
- Penjualan dengan stok tidak cukup ditolak tanpa perubahan stok.
- Transaksi multi-item yang gagal harus menjaga konsistensi data.
- Search, filter, dan pagination menampilkan hasil yang sesuai.

Hasil aktual harus dicatat setelah pengujian benar-benar dijalankan. Screenshot merupakan bukti pendukung; screenshot saja tidak menggantikan pengujian fungsi.

### 9.1 Pengujian Otomatis Menggunakan PHPUnit

Pengujian otomatis dilakukan menggunakan PHPUnit untuk memeriksa fitur utama aplikasi Adara Snack, terutama autentikasi, pembatasan akses berdasarkan role, serta alur transaksi dan perubahan stok.

Jalankan perintah berikut dari folder proyek:

```powershell
.\vendor\bin\phpunit
```

Hasil pengujian yang diperoleh sebelumnya:

```text
OK (12 tests, 31 assertions)
```

Hasil tersebut menunjukkan bahwa 12 metode pengujian berhasil dijalankan dengan total 31 assertions atau pemeriksaan.

Pengujian yang telah dijalankan mencakup:

1. Guest yang belum login diarahkan ke halaman login ketika membuka dashboard.
2. Admin dapat membuka dashboard.
3. Staff tidak dapat membuka halaman manajemen pengguna.
4. User dapat melihat halaman produk.
5. User tidak dapat membuka dashboard.
6. User tidak dapat membuka halaman tambah produk.
7. User tidak dapat mengakses halaman kategori, supplier, pembelian, penjualan, laporan, dan pengguna.
8. Transaksi pembelian berhasil menambah stok produk.
9. Transaksi penjualan berhasil mengurangi stok produk.
10. Penjualan ditolak ketika stok tidak mencukupi dan stok tidak berubah.
11. Pembatalan pembelian mengembalikan stok ke jumlah semula.
12. Pembatalan penjualan mengembalikan stok ke jumlah semula.

Hasil di atas merupakan hasil pengujian sebelumnya. Jalankan kembali PHPUnit untuk memastikan kondisi terbaru setelah ada perubahan kode.

### 9.2 Bukti Hasil Pengujian Otomatis

Screenshot terminal digunakan sebagai bukti hasil pengujian PHPUnit. Screenshot harus memperlihatkan jumlah tes, jumlah assertions, dan status pengujian.

Nama file yang digunakan dalam dokumentasi:

`screenshot/39 Pengujian Test.png`

![Hasil Pengujian PHPUnit](screenshot/39%20Pengujian%20Test.png)

Pastikan screenshot tersebut benar-benar disimpan di folder `screenshot/` dengan nama yang sesuai. Jika nama file berbeda, sesuaikan tautan gambar pada README.

### 9.3 Skenario yang Perlu Diperiksa Lebih Lanjut

Pengujian otomatis yang tersedia belum membuktikan seluruh kebutuhan UTS. Skenario berikut perlu diperiksa melalui pengujian tambahan jika belum tercakup di bagian lain proyek:

- Login menggunakan password yang salah.
- Penolakan SKU duplikat.
- Penolakan harga negatif.
- Validasi jumlah transaksi nol atau negatif.
- Pencarian, filter, dan pagination.
- Kegagalan transaksi multi-item dan konsistensi data setelah rollback.
- Pembatasan akses Staff pada seluruh fungsi Admin melalui URL langsung.

Hasil setiap skenario tambahan harus dicatat setelah pengujian benar-benar dilakukan. Jangan menyatakan skenario berhasil hanya berdasarkan keberadaan kode validasi.

## 10. Screenshot Tampilan dan Kode

Screenshot berikut digunakan sebagai bukti visual aplikasi dan kode yang didokumentasikan. Seluruh file harus tersedia di folder `screenshot/` dengan nama yang sesuai.

### 01. Login Admin

Halaman autentikasi untuk masuk sebagai Admin.

<img src="screenshot/01%20Login%20Admin.png" alt="Login Admin" width="800">

### 02. Dashboard Admin

Ringkasan utama setelah Admin berhasil login.

<img src="screenshot/02%20Dashboard%20Admin.png" alt="Dashboard Admin" width="800">

### 03. Dashboard Admin (Tampilan Tambahan)

Screenshot tambahan dashboard Admin.

<img src="screenshot/03%20Dashboard%20Admin.png" alt="Dashboard Admin tampilan tambahan" width="800">

### 04. Daftar Produk

Menampilkan daftar produk yang dikelola di aplikasi.

<img src="screenshot/04%20Produk.png" alt="Daftar Produk" width="800">

### 05. Produk (Tampilan Tambahan)

Screenshot tambahan halaman Produk.

<img src="screenshot/05%20Produk.png" alt="Produk tampilan tambahan" width="800">

### 06. Kategori Admin

Daftar kategori yang dapat dikelola Admin.

<img src="screenshot/06%20Kategori-Admin.png" alt="Kategori Admin" width="800">

### 07. Supplier Admin

Daftar pemasok yang tersedia.

<img src="screenshot/07%20Supplier-Admin.png" alt="Supplier Admin" width="800">

### 08. Pembelian Admin

Daftar transaksi pembelian atau penambahan stok.

<img src="screenshot/08%20Pembelian-Admin.png" alt="Pembelian Admin" width="800">

### 09. Penjualan Admin

Daftar transaksi penjualan.

<img src="screenshot/09%20Penjualan-Admin.png" alt="Penjualan Admin" width="800">

### 10. Laporan

Halaman laporan yang tersedia di aplikasi.

<img src="screenshot/10%20Laporan.png" alt="Laporan" width="800">

### 11. Laporan (Tampilan Tambahan)

Screenshot tambahan halaman laporan.

<img src="screenshot/11%20Laporan.png" alt="Laporan tampilan tambahan" width="800">

### 12. Detail Penjualan

Detail item dan informasi transaksi penjualan.

<img src="screenshot/12%20Detail%20Penjualan.png" alt="Detail Penjualan" width="800">

### 13. Pengguna Admin

Daftar akun pengguna dan role yang dikelola Admin.

<img src="screenshot/13%20Pengguna.png" alt="Pengguna Admin" width="800">

### 14. Profil Admin

Halaman profil akun Admin.

<img src="screenshot/14%20Profil-Admin.png" alt="Profil Admin" width="800">

### 15. Login Staff

Halaman login yang digunakan untuk pengujian role Staff.

<img src="screenshot/15%20Login%20Staff.png" alt="Login Staff" width="800">

### 16. Dashboard Staff

Dashboard setelah Staff berhasil login.

<img src="screenshot/16%20Dasboard%20Staff.png" alt="Dashboard Staff" width="800">

### 17. Produk Staff

Daftar produk yang dapat dilihat Staff.

<img src="screenshot/17%20Produk-Staff.png" alt="Produk Staff" width="800">

### 18. Kategori Staff

Pengujian akses Staff ke halaman Kategori. Hasil aktual dapat dilihat pada screenshot.

<img src="screenshot/18%20Kategori-Staff.png" alt="Kategori Staff" width="800">

### 19. Supplier Staff

Pengujian akses Staff ke halaman Supplier. Hasil aktual dapat dilihat pada screenshot.

<img src="screenshot/19%20Supplier-Staff.png" alt="Supplier Staff" width="800">

### 20. Pembelian Staff

Halaman pembelian yang diuji menggunakan akun Staff.

<img src="screenshot/20%20Pembelian-Staff.png" alt="Pembelian Staff" width="800">

### 21. Penjualan Staff

Halaman penjualan yang diuji menggunakan akun Staff.

<img src="screenshot/21%20Penjualan-Staff.png" alt="Penjualan Staff" width="800">

### 22. Laporan Staff

Halaman laporan yang diuji menggunakan akun Staff.

<img src="screenshot/22%20Laporan-Staff.png" alt="Laporan Staff" width="800">

### 23. Profil Staff

Halaman profil akun Staff.

<img src="screenshot/23%20Profil-Staff.png" alt="Profil Staff" width="800">

### 24. Akses Pengguna Ditolak untuk Staff

Bukti pengujian bahwa Staff tidak dapat membuka halaman Pengguna.

<img src="screenshot/24%20Pengguna%20Tidak%20Memiliki%20Akses%20Pada%20Login%20Staff.png" alt="Akses Pengguna ditolak untuk Staff" width="800">

### 25. Validasi Password Salah

Pesan kesalahan ketika proses login menggunakan password yang salah.

<img src="screenshot/25%20validasi%20Error%20Password%20Salah.png" alt="Validasi password salah" width="800">

### 26. Produk User

Halaman produk yang dapat dilihat menggunakan akun User.

<img src="screenshot/26%20Produk-User.png" alt="Produk User" width="800">

### 27. Dashboard User

Halaman dashboard setelah akun User masuk.

<img src="screenshot/27%20Dashboard-User.png" alt="Dashboard User" width="800">

### 28. Profil User

Halaman profil akun User.

<img src="screenshot/28%20Profil-User.png" alt="Profil User" width="800">

### 29. Validasi SKU Wajib Diisi

Contoh validasi ketika SKU produk belum diisi.

<img src="screenshot/29%20SKU%20Produk%20Wajib%20Di%20Isi.png" alt="Validasi SKU wajib diisi" width="800">

### 30. Validasi Supplier Wajib Diisi

Contoh validasi ketika data supplier wajib belum diisi.

<img src="screenshot/30%20Supplier%20Wajib%20Di%20Isi.png" alt="Validasi Supplier wajib diisi" width="800">

### 31. Routes web.php

Bukti kode routing aplikasi Laravel.

<img src="screenshot/31%20Routes-Web%20PHP.png" alt="Routes web.php" width="800">

### 32. Controller dan Middleware

Bukti kode controller dan middleware untuk alur aplikasi serta pembatasan akses.

<img src="screenshot/32%20Controllers%20%26%20Middleware.png" alt="Controller dan Middleware" width="800">

### 33. AuthController.php

Bukti kode autentikasi login dan logout.

<img src="screenshot/33%20Auth%20Controller%20PHP.png" alt="AuthController.php" width="800">

### 34. ProductController.php

Bukti kode pengelolaan produk dan validasi input.

<img src="screenshot/34%20Product%20Controller%20PHP.png" alt="ProductController.php" width="800">

### 35. PurchaseController.php

Bukti kode proses pembelian.

<img src="screenshot/35%20Purchase%20Controller%20PHP.png" alt="PurchaseController.php" width="800">

### 36. SaleController.php

Bukti kode proses penjualan.

<img src="screenshot/36%20Sale%20Controller%20PHP.png" alt="SaleController.php" width="800">

### 37. Model Sale.php

Bukti model Sale dan relasi Eloquent terkait transaksi.

<img src="screenshot/37%20Sale%20PHP.png" alt="Model Sale.php" width="800">

### 38. DatabaseSeeder.php

Bukti data awal atau dummy untuk menjalankan demo.

<img src="screenshot/38%20Database%20Seeder%20PHP.png" alt="DatabaseSeeder.php" width="800">

### 39. Hasil Pengujian PHPUnit

Bukti hasil pengujian otomatis yang dijalankan melalui terminal VS Code.

<img src="screenshot/39%20Pengujian%20Test.png" alt="Hasil Pengujian PHPUnit" width="800">

## 11. Git dan Keamanan Repository

Sebelum melakukan commit dan push:

1. Pastikan file `.env` tidak ikut di-stage atau diunggah. File `.env` berisi konfigurasi lokal dan mungkin kredensial; repository cukup menyertakan `.env.example`.
2. Pastikan folder `vendor/` dan `node_modules/` tidak diunggah.
3. Pastikan migration dan seeder tersedia sehingga database dapat dibuat ulang.
4. Pastikan README, ERD, test cases, dan folder screenshot ikut di-commit.

Contoh perintah dari folder proyek:

```bash
git status
git add README.md docs/ screenshot/
git add app routes database resources
git status
git commit -m "Update README, screenshots, and Laravel UTS implementation"
git push origin main
```

Periksa hasil `git status` sebelum commit. Jika branch lokal bukan `main`, gunakan nama branch yang ditampilkan oleh Git.

Jangan menjalankan `git add .env`. Jika ada perubahan kode lain yang belum di-commit, tinjau terlebih dahulu sebelum memasukkannya ke commit.

## 12. Catatan

Dokumentasi ini menjelaskan fitur dan bukti yang ada pada proyek Adara Snack. Pastikan setiap fungsi benar-benar diuji di lingkungan lokal sebelum menyatakan seluruh skenario berhasil.

Untuk demo UTS, mahasiswa perlu mampu menjelaskan:

- Route dan named routes.
- Middleware dan pembatasan akses berdasarkan role.
- Controller dan validasi input.
- Relasi Eloquent.
- Proses pembelian dan penjualan.
- Cara stok berubah ketika transaksi berhasil, ditolak, atau dibatalkan.
- Cara menjalankan migration, seeder, dan pengujian otomatis.
