# Laporan Harian - 22 Mei 2026

## Ringkasan

Hari ini pekerjaan yang dilakukan berfokus pada pembuatan fitur CRUD (Create, Read, Update, Delete) untuk manajemen event di panel admin, termasuk integrasi unggah gambar banner (uploads) secara aman, serta penulisan dokumen laporan harian proyek.

## Aktivitas & Perubahan

1. **Rute Administrasi Baru**
   - Menambahkan rute administrasi lengkap untuk manajemen event (`events`, `events/new`, `events/create`, `events/edit/(:num)`, `events/update/(:num)`, `events/delete/(:num)`) di dalam file [app/Config/Routes.php](app/Config/Routes.php).

2. **Pembaruan Navigasi Sidebar**
   - Menambahkan menu navigasi "Events" di sidebar halaman admin agar terintegrasi dengan modul lainnya di [dashboard.php](app/Views/admin/dashboard.php), [participants.php](app/Views/admin/participants.php), dan [scanner.php](app/Views/admin/scanner.php).

3. **Logika CRUD & Unggah File di Controller**
   - Mengimplementasikan logika CRUD pada [app/Controllers/AdminController.php](app/Controllers/AdminController.php).
   - Menambahkan fitur pembuatan slug unik yang aman secara otomatis untuk setiap event baru.
   - Menambahkan validasi form (nama, lokasi, tanggal event, serta tipe pendaftaran).
   - Menambahkan fitur unggah gambar banner lari ke folder `public/uploads/` dengan penamaan file acak yang unik (`getRandomName()`), serta logika pembersihan otomatis file banner lama dari server saat event dihapus atau diubah.

4. **Pembuatan Antarmuka Admin (View)**
   - Membuat file-file view baru di bawah folder `app/Views/admin/events/`:
     - `index.php`: Menampilkan tabel daftar seluruh event lengkap dengan thumbnail banner yang diunggah.
     - `create.php`: Form pembuatan event baru dengan input tipe berkas untuk banner (`enctype="multipart/form-data"`).
     - `edit.php`: Form perubahan event dengan preview gambar banner yang aktif saat ini.

5. **Dokumentasi Proyek**
   - Membuat file [laporan.md](laporan.md) di root direktori sebagai berkas log aktivitas hari ini.

## Catatan Verifikasi

- Direktori `public/uploads/` akan otomatis terbuat secara aman saat pertama kali admin mengunggah file banner lari.
- Ketika banner lama diganti dengan berkas baru atau event dihapus, file gambar lama akan otomatis terhapus menggunakan fungsi `unlink` guna menghemat ruang penyimpanan server.
