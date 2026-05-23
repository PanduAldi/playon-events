# Walkthrough: Beranda, Detail Event & Pendaftaran (Checkout)

Sistem pendaftaran event lari telah berhasil diimplementasikan dari hulu ke hilir! Kini pengguna dapat melihat event, membaca detail, dan melakukan pendaftaran (Checkout) hingga selesai. Desain secara konsisten mempertahankan nuansa **Zapier-Inspired** yang premium.

## Yang Telah Diselesaikan

### 1. Struktur Data & Model
Saya menambahkan dua model baru untuk menangani sisi peserta:
- [ParticipantModel.php](file:///d:/laragon/playon-events/app/Models/ParticipantModel.php): Mengelola data diri pendaftar.
- [RegistrationModel.php](file:///d:/laragon/playon-events/app/Models/RegistrationModel.php): Menyimpan riwayat pendaftaran, mengaitkan peserta dengan event dan kategori tertentu, serta menyimpan `payment_status` dan `bib_number`.

### 2. Logika Registrasi Inti (EventController)
Di dalam [EventController.php](file:///d:/laragon/playon-events/app/Controllers/EventController.php), saya mengimplementasikan `processCheckout` dengan mekanisme keamanan tinggi:
- **Validasi Input**: Memeriksa kelengkapan nama, email, no HP, jenis kelamin, dan kontak darurat.
- **Database Transaction & Row-Level Locking**: Menggunakan `getForUpdate()` untuk mengunci baris data kategori tertentu selama proses pendaftaran berlangsung. Hal ini menjamin bahwa meskipun ada ribuan pendaftar yang mengakses secara bersamaan, **kuota tidak akan jebol (overbooking)**.
- **Auto-Generate BIB & QR**: Secara otomatis merakit Nomor BIB (contoh: `BIB-BRE-5K-0001`) dan *hash* token QR untuk setiap pendaftaran yang masuk.

### 3. Antarmuka (UI) Baru
Tiga halaman baru telah ditambahkan ke alur pengguna:
1. **[Halaman Detail Event](file:///d:/laragon/playon-events/app/Views/public/event_detail.php)**: Menampilkan deskripsi lengkap event dengan *sticky sidebar* (layar selalu terikut saat digulir) yang berisi opsi kuota/kategori yang tersedia.
2. **[Halaman Formulir Checkout](file:///d:/laragon/playon-events/app/Views/public/checkout.php)**: Form pendaftaran yang rapi dengan *custom radio buttons* yang berubah menjadi oranye lembut saat dipilih. Dilengkapi ringkasan event di bilah kanan. 
3. **[Halaman Pendaftaran Sukses](file:///d:/laragon/playon-events/app/Views/public/success.php)**: Menampilkan pesan konfirmasi, nomor BIB berukuran besar (*Display Typeface*), dan kotak khusus instruksi pembayaran manual (hanya muncul jika event berbayar).

## Validasi & Uji Coba

Anda kini dapat menguji alur pendaftaran secara penuh:
1. Buka peramban di URL proyek (Beranda).
2. Klik tombol **Daftar Sekarang** pada salah satu event.
3. Di halaman Detail Event, klik **Lanjut Pendaftaran**.
4. Isi form secara sembarang namun dengan format email/nomor yang benar.
5. Klik **Selesaikan Pendaftaran**.
6. Anda akan diarahkan ke Halaman Sukses yang memuat Nomor BIB Anda secara instan!

## Langkah Selanjutnya

Dengan terselesaikannya sistem pendaftaran utama bagi *end-user*, tahap krusial berikutnya adalah membangun pintu gerbang bagi Pengelola/Panitia, yakni **Dashboard Admin**. Apakah Anda siap untuk memulai pengembangan modul Admin (seperti login, tabel pendaftar, dan fitur konfirmasi lunas)?
