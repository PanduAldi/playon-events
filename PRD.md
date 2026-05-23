🏃

**PRODUCT REQUIREMENTS DOCUMENT**

**Website Pendaftaran Event Lari Komunitas PlayOn Brebes**

_Sistem Registrasi Multi-Event dengan Manajemen Kuota Peserta_

| **Versi**     | 1.0.0                     |
| ------------- | ------------------------- |
| **Teknologi** | PHP CodeIgniter 4 + MySQL |
| **Tanggal**   | Mei 2026                  |
| **Status**    | **Draft - For Review**    |

# 1\. Ringkasan Eksekutif

Dokumen ini mendefinisikan persyaratan produk untuk website pendaftaran event lari komunitas dari Playon Brebes berbasis web. Sistem dibangun menggunakan PHP CodeIgniter 4 (CI4) dengan database MySQL, dan dirancang untuk mengelola pendaftaran peserta pada satu atau lebih event lari sekaligus, disertai mekanisme pembatasan kuota per event.

# 2\. Latar Belakang & Tujuan

## 2.1 Latar Belakang

Komunitas Playon Brebes sering menyelenggarakan event-event kecil seperti fun run, charity run, atau lomba lari bertahap. Pengelolaan pendaftaran saat ini umumnya dilakukan secara manual (form kertas, WhatsApp, atau spreadsheet), yang rentan terhadap duplikasi data, kelebihan kuota, dan sulitnya pelaporan.

## 2.2 Tujuan Produk

- Menyediakan platform pendaftaran event lari yang mudah diakses peserta.
- Mendukung pendaftaran ke lebih dari satu event dalam satu sesi.
- Membatasi jumlah peserta per event secara otomatis (kuota).
- Memberikan antarmuka administrasi untuk mengelola event, kuota, dan peserta.
- Menghasilkan bukti pendaftaran digital yang dapat diunduh/dicetak.

# 3\. Ruang Lingkup Sistem

## 3.1 In-Scope

- Manajemen event lari (CRUD): nama, tanggal, lokasi, kategori jarak, kuota, biaya.
- Pendaftaran peserta ke satu atau lebih event dalam satu formulir.
- Validasi kuota secara real-time: jika event penuh, pendaftaran ditolak/waitlist.
- Dashboard admin: laporan peserta, ekspor data, manajemen event.
- Konfirmasi email otomatis dengan nomor pendaftaran unik.
- Halaman publik: daftar event aktif, status kuota (tersedia/hampir penuh/penuh).
- Fitur waitlist opsional ketika kuota habis.

## 3.2 Out-of-Scope (V1)

- Integrasi payment gateway (direncanakan di V2).
- Aplikasi mobile native.
- Live tracking pelari selama event.
- Modul chip timing/hasil lomba.

# 4\. Stakeholder & Pengguna

| **Peran**       | **Tipe Akses**        | **Tanggung Jawab Utama**                                             |
| --------------- | --------------------- | -------------------------------------------------------------------- |
| Admin Komunitas | Backend (full access) | Membuat & mengelola event, menetapkan kuota, melihat laporan peserta |
| Panitia Event   | Backend (terbatas)    | Melihat daftar peserta event-event yang ditugaskan, cetak bib number |
| Peserta         | Frontend (publik)     | Melihat event, mendaftar, mengunduh bukti pendaftaran                |
| Pengunjung      | Frontend (read-only)  | Melihat informasi event tanpa mendaftar                              |

# 5\. Persyaratan Fungsional

## 5.1 Modul Manajemen Event

### 5.1.1 CRUD Event

- Admin dapat membuat event baru dengan atribut: nama event, deskripsi, tanggal & waktu, lokasi, URL peta (Google Maps), logo/banner, tipe event (gratis/berbayar), status (draft/aktif/selesai).
- Jika tipe event adalah berbayar, admin dapat mengatur biaya pendaftaran yang berbeda-beda pada masing-masing kategori.
- Setiap event dapat memiliki beberapa kategori (misal: 5K, 10K, 21K) masing-masing dengan kuota independen.
- Admin dapat menonaktifkan event tanpa menghapus data peserta.

### 5.1.2 Manajemen Kuota

- Setiap event/kategori memiliki nilai max_participants yang dikonfigurasi admin.
- Sistem menghitung slots_remaining = max_participants - confirmed_registrations secara real-time.
- Status kuota ditampilkan publik: Tersedia (>20%), Hampir Penuh (1-20%), Penuh (0).
- Admin dapat mengubah kuota kapan saja; perubahan langsung berlaku.
- Ketika kuota habis, sistem menawarkan waitlist (jika diaktifkan admin).

## 5.2 Modul Pendaftaran Peserta

### 5.2.1 Alur Pendaftaran Multi-Event

Peserta dapat mendaftarkan diri ke lebih dari satu event dalam satu sesi registrasi dengan langkah-langkah berikut:

- Peserta memilih satu atau lebih event dari halaman daftar event aktif.
- Sistem memvalidasi ketersediaan kuota untuk setiap event yang dipilih secara bersamaan.
- Peserta mengisi formulir data diri (berlaku untuk semua event yang dipilih).
- Untuk setiap event yang dipilih, peserta memilih kategori jarak.
- Sistem menampilkan ringkasan pendaftaran dan total biaya (jika event berbayar).
- Untuk event berbayar (karena tidak ada payment gateway di V1), sistem akan menampilkan instruksi pembayaran manual (misalnya nomor rekening bank) sebelum atau sesudah submit.
- Peserta menyetujui syarat & ketentuan, kemudian submit.
- Sistem mengunci slot kuota sementara (reserved) selama proses pendaftaran.
- Setelah submit berhasil, nomor pendaftaran unik dibuat untuk setiap event.

### 5.2.2 Data Formulir Peserta

| **Field**             | **Tipe** | **Wajib**   | **Keterangan**                  |
| --------------------- | -------- | ----------- | ------------------------------- |
| Nama Lengkap          | Text     | Ya          | Min 3 karakter                  |
| Nomor Telepon / WA    | Text     | Ya          | Format +62 atau 08xx            |
| Email                 | Email    | Ya          | Untuk konfirmasi & bukti daftar |
| Tanggal Lahir         | Date     | Ya          | Untuk verifikasi usia kategori  |
| Jenis Kelamin         | Select   | Ya          | Pria / Wanita                   |
| Ukuran Kaos           | Select   | Kondisional | Jika event menyediakan kaos     |
| Komunitas/Klub Lari   | Text     | Tidak       | Nama komunitas peserta          |
| Kontak Darurat        | Text     | Ya          | Nama & nomor telepon            |
| Riwayat Kondisi Medis | Textarea | Tidak       | Informasi medis relevan         |

### 5.2.3 Pencegahan Duplikasi

- Sistem mencegah peserta mendaftar dua kali ke event dan kategori yang sama (berdasarkan kombinasi email + event_id + category_id).
- Validasi dilakukan di server-side menggunakan CI4 Validation Library.
- Peserta dapat mendaftar ke event yang berbeda dengan email yang sama.

### 5.2.4 Nomor Pendaftaran (BIB)

- Format: \[KODE_EVENT\]-\[KODE_KATEGORI\]-\[NOMOR_URUT\], contoh: FR2026-5K-0042.
- Nomor urut auto-increment per kategori per event.
- Nomor bib dapat diatur ulang oleh admin sebelum event dimulai.

## 5.3 Modul Konfirmasi & Notifikasi

- Email konfirmasi otomatis dikirim setelah pendaftaran berhasil via SMTP (konfigurasi CI4 Email library).
- Email berisi: detail event yang didaftarkan, nomor BIB, QR code unik untuk check-in, instruksi teknis.
- Jika mendaftar ke multiple event, satu email berisi ringkasan semua event.
- Peserta dapat mengunduh kartu peserta (PDF) dari halaman konfirmasi atau link di email.

## 5.4 Modul Waitlist

- Ketika kuota penuh dan fitur waitlist diaktifkan admin, peserta dapat masuk daftar tunggu.
- Jika ada peserta yang membatalkan, sistem otomatis menawarkan slot ke peserta waitlist berikutnya via email.
- Peserta waitlist mendapat notifikasi dan batas waktu konfirmasi (default: 24 jam).

## 5.5 Modul Admin Dashboard

### 5.5.1 Overview Dashboard

- Total event aktif, total peserta terdaftar, kuota terisi vs tersisa.
- Grafik pendaftaran per hari (7 hari terakhir).
- Daftar event dengan indikator status kuota.

### 5.5.2 Manajemen Peserta

- Tabel peserta per event dengan filter: kategori, status, tanggal daftar.
- Pencarian peserta berdasarkan nama, email, atau nomor BIB.
- Fitur ekspor data ke Excel (.xlsx) dan PDF.
- Admin dapat memverifikasi dan mengubah status pembayaran peserta (Belum Lunas -> Lunas) khusus untuk event berbayar.
- Admin dapat mengubah status peserta: Terdaftar, Hadir, Tidak Hadir, Dibatalkan.
- Check-in peserta via scan QR code (menggunakan kamera browser).

### 5.5.3 Laporan

- Laporan ringkasan per event: total peserta, hadir, tidak hadir.
- Laporan distribusi ukuran kaos per event.
- Laporan demografis: sebaran usia, jenis kelamin, komunitas.

# 6\. Arsitektur & Spesifikasi Teknis

## 6.1 Stack Teknologi

| **Layer**         | **Teknologi**              | **Keterangan**                             |
| ----------------- | -------------------------- | ------------------------------------------ |
| Backend Framework | PHP 8.1+ / CodeIgniter 4.x | MVC pattern, built-in validation, routing  |
| Database          | MySQL 8.0+                 | Relational DB, InnoDB engine, foreign keys |
| Frontend          | Bootstrap 5 + Vanilla JS   | Responsive, mobile-first                   |
| Email             | PHPMailer / CI4 Email      | SMTP, dukungan HTML email                  |
| PDF Generator     | DOMPDF / mPDF              | Kartu peserta & laporan                    |
| QR Code           | phpqrcode / BaconQrCode    | Generate QR unik per peserta               |
| Session/Auth      | CI4 Session + Shield       | RBAC untuk admin & panitia                 |
| Server            | Apache/Nginx + PHP-FPM     | VPS / Shared Hosting                       |

## 6.2 Struktur Direktori CI4

| **Path**          | **Keterangan**                                                                |
| ----------------- | ----------------------------------------------------------------------------- |
| app/Controllers/  | EventController, RegistrationController, AdminController, AuthController      |
| app/Models/       | EventModel, CategoryModel, RegistrationModel, ParticipantModel, WaitlistModel |
| app/Views/        | public/ (event list, form, konfirmasi), admin/ (dashboard, laporan)           |
| app/Filters/      | AuthFilter (cek login), AdminFilter (cek role)                                |
| app/Libraries/    | QrCodeGenerator, PdfGenerator, EmailSender                                    |
| public/assets/    | CSS, JS, images, uploaded banners                                             |
| writable/uploads/ | Banner event, export files                                                    |

## 6.3 Skema Database

### Tabel: events

| **Kolom**               | **Tipe**     | **Null** | **Keterangan**                       |
| ----------------------- | ------------ | -------- | ------------------------------------ |
| id                      | INT PK AI    | No       | Primary key                          |
| slug                    | VARCHAR(100) | No       | URL-friendly, unik                   |
| name                    | VARCHAR(200) | No       | Nama event                           |
| description             | TEXT         | Yes      | Deskripsi lengkap event              |
| event_date              | DATETIME     | No       | Tanggal & waktu pelaksanaan          |
| location                | VARCHAR(300) | No       | Nama lokasi                          |
| maps_url                | TEXT         | Yes      | Link Google Maps                     |
| banner_image            | VARCHAR(255) | Yes      | Path banner event                    |
| registration_open       | DATETIME     | No       | Waktu buka pendaftaran               |
| registration_close      | DATETIME     | No       | Waktu tutup pendaftaran              |
| status                  | ENUM         | No       | 'draft','active','closed','finished' |
| event_type              | ENUM         | No       | 'free','paid'                        |
| allow_waitlist          | TINYINT(1)   | No       | 0/1 - aktifkan fitur waitlist        |
| created_at / updated_at | TIMESTAMP    | No       | Timestamps otomatis CI4              |

### Tabel: event_categories

| **Kolom**         | **Tipe**      | **Null** | **Keterangan**                 |
| ----------------- | ------------- | -------- | ------------------------------ |
| id                | INT PK AI     | No       | Primary key                    |
| event_id          | INT FK        | No       | Referensi ke events.id         |
| name              | VARCHAR(100)  | No       | Nama kategori: 5K, 10K, dsb    |
| code              | VARCHAR(20)   | No       | Kode singkat: 5K, 10K, 21K     |
| max_participants  | INT           | No       | Batas maksimal peserta         |
| registered_count  | INT           | No       | Counter terdaftar (cached)     |
| fee               | DECIMAL(10,2) | No       | Biaya pendaftaran (0 = gratis) |
| min_age / max_age | INT           | Yes      | Batasan usia (opsional)        |

### Tabel: participants

| **Kolom**         | **Tipe**     | **Null** | **Keterangan**       |
| ----------------- | ------------ | -------- | -------------------- |
| id                | INT PK AI    | No       | Primary key          |
| full_name         | VARCHAR(200) | No       | Nama lengkap         |
| phone             | VARCHAR(20)  | No       | Nomor WA/telepon     |
| email             | VARCHAR(150) | No       | Email peserta        |
| birth_date        | DATE         | No       | Tanggal lahir        |
| gender            | ENUM         | No       | 'M','F'              |
| shirt_size        | VARCHAR(5)   | Yes      | XS/S/M/L/XL/XXL      |
| club_name         | VARCHAR(150) | Yes      | Nama komunitas/klub  |
| emergency_contact | VARCHAR(200) | No       | Nama & nomor darurat |
| medical_notes     | TEXT         | Yes      | Riwayat medis        |
| created_at        | TIMESTAMP    | No       | Waktu data dibuat    |

### Tabel: registrations (tabel utama hubungan peserta & event)

| **Kolom**      | **Tipe**                                                     | **Null** | **Keterangan**                               |
| -------------- | ------------------------------------------------------------ | -------- | -------------------------------------------- |
| id             | INT PK AI                                                    | No       | Primary key                                  |
| participant_id | INT FK                                                       | No       | Referensi ke participants.id                 |
| event_id       | INT FK                                                       | No       | Referensi ke events.id                       |
| category_id    | INT FK                                                       | No       | Referensi ke event_categories.id             |
| bib_number     | VARCHAR(30)                                                  | No       | Nomor BIB unik per event                     |
| qr_token       | VARCHAR(64)                                                  | No       | Token unik untuk QR check-in                 |
| payment_status | ENUM                                                         | No       | 'free','unpaid','paid'                       |
| status         | ENUM                                                         | No       | 'pending','confirmed','attended','cancelled' |
| registered_at  | TIMESTAMP                                                    | No       | Waktu pendaftaran                            |
| attended_at    | TIMESTAMP                                                    | Yes      | Waktu check-in                               |
| **UNIQUE KEY** | participant_id + event_id + category_id (mencegah duplikasi) |

### Tabel: registration_sessions (multi-event checkout)

| **Kolom**               | **Tipe**    | **Null** | **Keterangan**                  |
| ----------------------- | ----------- | -------- | ------------------------------- |
| id                      | INT PK AI   | No       | Primary key                     |
| session_token           | VARCHAR(64) | No       | Token unik sesi pendaftaran     |
| participant_id          | INT FK      | No       | Peserta yang mendaftar          |
| total_events            | INT         | No       | Jumlah event yang didaftarkan   |
| status                  | ENUM        | No       | 'pending','completed','expired' |
| created_at / expires_at | TIMESTAMP   | No       | Waktu dibuat & expired          |

## 6.4 Logika Manajemen Kuota

Mekanisme kuota menggunakan kombinasi database transaction dan row-level locking untuk mencegah race condition (overbooking) saat pendaftaran bersamaan:

| **Step** | **Proses (dalam satu DB Transaction)**                                                             |
| -------- | -------------------------------------------------------------------------------------------------- |
| 1        | SELECT max_participants, registered_count FROM event_categories WHERE id = ? FOR UPDATE (lock row) |
| 2        | Cek: registered_count < max_participants. Jika false → rollback, tampilkan error 'Kuota penuh'     |
| 3        | INSERT INTO registrations dengan status = 'confirmed'                                              |
| 4        | UPDATE event_categories SET registered_count = registered_count + 1 WHERE id = ?                   |
| 5        | COMMIT. Jika gagal di step manapun → ROLLBACK otomatis                                             |

# 7\. User Flow & Wireframe Deskripsi

## 7.1 Alur Pendaftaran Peserta (Happy Path)

| **#** | **Halaman**            | **Aksi & Sistem**                                                                                         |
| ----- | ---------------------- | --------------------------------------------------------------------------------------------------------- |
| 1     | Beranda / Daftar Event | Peserta melihat kartu event aktif dengan badge status kuota (Tersedia/Hampir Penuh/Penuh)                 |
| 2     | Pilih Event            | Klik tombol 'Daftar Sekarang' pada satu atau lebih event. Pilihan masuk ke keranjang sementara (session). |
| 3     | Detail Event           | Melihat info lengkap event, pilih kategori jarak, lanjut ke formulir.                                     |
| 4     | Formulir Data Diri     | Isi data pribadi satu kali; berlaku untuk semua event yang dipilih.                                       |
| 5     | Pilih Kategori         | Untuk setiap event, pilih kategori yang tersedia beserta info sisa kuota.                                 |
| 6     | Ringkasan              | Review semua event + kategori + biaya total. Centang persetujuan.                                         |
| 7     | Submit & Proses        | Sistem memproses dengan DB transaction. Loading indicator ditampilkan.                                    |
| 8     | Halaman Sukses         | Tampil nomor BIB semua event, link download kartu peserta, info email konfirmasi.                         |
| 9     | Email Konfirmasi       | Email HTML dikirim ke peserta berisi semua detail dan QR code.                                            |

## 7.2 Alur Jika Kuota Habis di Tengah Proses

- Jika salah satu event dalam multi-event pilihan kehabisan kuota saat submit, sistem menginformasikan event mana yang gagal.
- Event lain yang kuotanya masih tersedia tetap diproses.
- Peserta ditawarkan untuk masuk waitlist untuk event yang penuh.

# 8\. Persyaratan Non-Fungsional

| **Kategori**  | **Target**                       | **Catatan**                                     |
| ------------- | -------------------------------- | ----------------------------------------------- |
| Performa      | Halaman load < 2 detik           | Dengan caching CI4 dan query optimization       |
| Konkurrens    | Min. 50 pendaftar simultan       | Gunakan DB locking untuk kuota                  |
| Ketersediaan  | 99% uptime                       | Hosting reliable, backup harian                 |
| Keamanan      | OWASP Top 10 compliance          | CSRF token CI4, prepared statements, XSS filter |
| Responsivitas | Mobile-first, semua ukuran layar | Bootstrap 5 grid system                         |
| Aksesibilitas | WCAG 2.1 Level AA dasar          | Label form, contrast ratio, keyboard nav        |
| SEO           | Meta tags dasar                  | Open Graph untuk sharing social media event     |

# 9\. Keamanan & Validasi

## 9.1 Keamanan Aplikasi

- Semua form menggunakan CSRF token bawaan CI4 (csrf_token).
- Input disanitasi menggunakan CI4 built-in XSS filtering.
- Query menggunakan Query Builder CI4 (prepared statements) untuk mencegah SQL injection.
- Password admin di-hash menggunakan bcrypt melalui CI4 Shield.
- Rate limiting pada endpoint pendaftaran: max 5 request per menit per IP.
- HTTPS wajib; HTTP redirect ke HTTPS.

## 9.2 Validasi Data

- Validasi server-side menggunakan CI4 Validation dengan rules: required, min_length, max_length, valid_email, regex_match (nomor HP), valid_date.
- Validasi tambahan: cek usia minimum/maksimum kategori berdasarkan birth_date.
- Validasi kepemilikan: peserta tidak dapat mengakses data orang lain.

# 10\. Rencana Pengembangan & Milestone

| **Sprint** | **Durasi** | **Deliverable**                                                                |
| ---------- | ---------- | ------------------------------------------------------------------------------ |
| Sprint 1   | 2 minggu   | Setup CI4, struktur database, migrasi, seeder, autentikasi admin (CI4 Shield)  |
| Sprint 2   | 2 minggu   | CRUD event & kategori, manajemen kuota, halaman publik daftar event            |
| Sprint 3   | 2 minggu   | Formulir pendaftaran multi-event, logika kuota dengan DB transaction, validasi |
| Sprint 4   | 1 minggu   | QR code, generate PDF kartu peserta, integrasi email konfirmasi                |
| Sprint 5   | 1 minggu   | Dashboard admin, laporan, ekspor Excel/PDF, fitur check-in QR                  |
| Sprint 6   | 1 minggu   | Fitur waitlist, notifikasi otomatis, rate limiting, security hardening         |
| Sprint 7   | 1 minggu   | UAT (User Acceptance Testing), bug fixes, performance tuning, deployment       |

# 11\. Kriteria Penerimaan (Acceptance Criteria)

## 11.1 Pendaftaran Multi-Event

- Peserta dapat memilih 2 atau lebih event dan mendaftar sekaligus dalam satu sesi.
- Masing-masing event menghasilkan nomor BIB dan QR code yang berbeda.
- Satu email konfirmasi berisi ringkasan semua event yang didaftarkan.

## 11.2 Manajemen Kuota

- Ketika kuota event mencapai max_participants, tombol daftar berubah menjadi 'Penuh' dan tidak dapat diklik.
- Tidak ada overbooking: registered_count tidak pernah melebihi max_participants meskipun ada concurrent requests.
- Admin dapat mengubah nilai max_participants kapan saja dari dashboard.

## 11.3 Keamanan

- Semua endpoint admin hanya dapat diakses setelah login.
- Form pendaftaran publik terlindungi CSRF token.
- SQL injection test pada semua form input: tidak ada celah.

# 12\. Risiko & Mitigasi

| **Risiko**                                   | **Dampak** | **Mitigasi**                                                        |
| -------------------------------------------- | ---------- | ------------------------------------------------------------------- |
| Race condition kuota (pendaftaran bersamaan) | Tinggi     | DB transaction + FOR UPDATE locking di MySQL                        |
| Email konfirmasi tidak terkirim              | Sedang     | Queue email, retry logic, log error, fallback tampilkan di halaman  |
| Data peserta bocor (kebocoran privasi)       | Tinggi     | HTTPS, enkripsi data sensitif, access control ketat                 |
| Server down saat pendaftaran ramai           | Sedang     | Load testing sebelum event populer, hosting cloud dengan auto-scale |
| Peserta mendaftar ganda (bypass validasi)    | Sedang     | UNIQUE constraint di DB sebagai last-resort validation              |

# 13\. Glosarium

| **Istilah**      | **Definisi**                                                                                      |
| ---------------- | ------------------------------------------------------------------------------------------------- |
| CI4              | CodeIgniter 4 - PHP framework MVC yang digunakan sebagai backend                                  |
| Kuota            | Jumlah maksimal peserta yang dapat mendaftar pada suatu event/kategori                            |
| BIB / Bib Number | Nomor identifikasi unik peserta yang dikenakan saat berlari                                       |
| Waitlist         | Daftar tunggu untuk peserta yang ingin mendaftar ketika kuota sudah penuh                         |
| QR Token         | String unik yang di-encode ke QR code untuk keperluan check-in peserta                            |
| Race Condition   | Kondisi di mana dua proses bersamaan mengakses dan memodifikasi data yang sama                    |
| FOR UPDATE       | SQL clause untuk mengunci baris terpilih agar tidak bisa dimodifikasi concurrent transaction lain |

**_- Akhir Dokumen -_**
