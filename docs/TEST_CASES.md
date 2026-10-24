# Test cases minimum UTS

| No | Skenario | Langkah | Hasil yang diharapkan |
|---|---|---|---|
| 1 | Login benar | Masuk memakai akun demo | Dashboard terbuka |
| 2 | Password salah | Masukkan password salah | Login ditolak |
| 3 | Proteksi guest | Logout, buka `/dashboard` | Dialihkan ke login |
| 4 | Role staff | Login staff, buka `/users` | Akses ditolak (403) |
| 5 | SKU duplikat | Tambah produk dengan SKU yang sudah ada | Validasi menolak |
| 6 | Harga negatif | Isi harga kurang dari nol | Validasi menolak |
| 7 | Pembelian sukses | Buat pembelian 3 unit | Stok naik 3 unit |
| 8 | Penjualan sukses | Jual 2 unit yang tersedia | Stok turun 2 unit |
| 9 | Stok tidak cukup | Jual lebih banyak dari stok | Transaksi gagal dan stok tetap |
| 10 | Item gagal | Buat transaksi multi-item dengan salah satu stok kurang | Semua perubahan rollback |
| 11 | Search/filter | Cari SKU/nama, filter tanggal/status | Data sesuai filter |
| 12 | Pagination | Buka daftar data lebih dari satu halaman | Halaman dan filter tetap sesuai |
| 13 | Pembatalan sale | Admin membatalkan sale selesai | Stok dipulihkan satu kali |
| 14 | Pembatalan purchase | Coba membatalkan pembelian yang stoknya sudah terpakai | Pembatalan ditolak jika stok tidak cukup |

Isi kolom hasil aktual/tanggal setelah pengujian benar-benar dilakukan. Lampirkan screenshot sebagai bukti.
