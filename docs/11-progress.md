# docs/11-progress.md

# Progress Project Sistem Manajemen KP/KPL

## 1. Tujuan Dokumen

Dokumen ini digunakan untuk mencatat progres pengerjaan project.

Setiap agent atau developer yang mengerjakan project wajib memperbarui dokumen ini setelah menyelesaikan bagian penting, menemukan bug, mengambil keputusan teknis, atau mengubah struktur project.

Dokumen ini berfungsi sebagai catatan riwayat kerja agar project mudah dilanjutkan, mudah diaudit, dan tidak kehilangan konteks.

---

## 2. Ringkasan Status Project

Status saat ini:

```text
Pondasi Inti, Database MVP, Autentikasi Multi-role, Layout Premium SaaS, SELURUH Modul Admin, SELURUH Modul Koordinator, dan SELURUH Modul Mahasiswa (Dashboard, Profil Saya, Registrasi KP/KPL, Status Registrasi, Penempatan KP/KPL, Rekomendasi Mitra TOPSIS, Tempat Mandiri, Surat & Dokumen, Upload Balasan, Pembimbing Saya, Logbook Mingguan & Harian, Catatan Dosen, Laporan Akhir, dan Penilaian Saya) selesai diimplementasikan.
```

Proyek berada pada tahap di mana Admin Panel, Koordinator Akademik, dan Mahasiswa Step-based Workflows sudah fungsional secara penuh. Langkah selanjutnya adalah mengembangkan peran Dosen dan Instansi.

Dokumen utama yang sudah direncanakan:

```text
AGENTS.md
README.md
templates.md
docs/01-prd.md
docs/02-current-system.md
docs/03-roles.md
docs/04-workflow.md
docs/05-sidebar-dashboard.md
docs/06-database.md
docs/07-technology.md
docs/08-ui-ux-rules.md
docs/09-business-rules.md
docs/10-project-structure.md
docs/11-progress.md
```

---

## 3. Checklist Dokumentasi

| No  | File                           | Status  | Catatan                                           |
| --- | ------------------------------ | ------- | ------------------------------------------------- |
| 1   | `AGENTS.md`                    | Selesai | Instruksi utama agent sudah disusun.              |
| 2   | `README.md`                    | Selesai | Ringkasan project sudah disusun.                  |
| 3   | `templates.md`                 | Selesai | Standar penulisan docs sudah disusun.             |
| 4   | `docs/01-prd.md`               | Selesai | Product requirements sudah disusun.               |
| 5   | `docs/02-current-system.md`    | Selesai | Sistem manual kampus sudah dijelaskan.            |
| 6   | `docs/03-roles.md`             | Selesai | Role dan hak akses sudah dikunci.                 |
| 7   | `docs/04-workflow.md`          | Selesai | Workflow utama sudah disusun.                     |
| 8   | `docs/05-sidebar-dashboard.md` | Selesai | Sidebar, dashboard, topbar, footer sudah dikunci. |
| 9   | `docs/06-database.md`          | Selesai | Rancangan database awal sudah disusun.            |
| 10  | `docs/07-technology.md`        | Selesai | Stack teknologi sudah dikunci.                    |
| 11  | `docs/08-ui-ux-rules.md`       | Selesai | Aturan UI/UX sudah disusun.                       |
| 12  | `docs/09-business-rules.md`    | Selesai | Aturan bisnis utama sudah disusun.                |
| 13  | `docs/10-project-structure.md` | Selesai | Struktur folder CI4 sudah disusun.                |
| 14  | `docs/11-progress.md`          | Selesai | File progress awal sudah dibuat.                  |

---

## 4. Keputusan Penting Project

| Tanggal    | Keputusan                                                                         | Alasan                                                                           |
| ---------- | --------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| 2026-06-12 | Framework utama menggunakan CodeIgniter 4.                                        | Cocok untuk skripsi, mudah dijelaskan, dan sesuai kebutuhan sistem akademik.     |
| 2026-06-12 | Database menggunakan MySQL/MariaDB.                                               | Umum digunakan, mudah diintegrasikan dengan CI4, dan cocok untuk sistem kampus.  |
| 2026-06-12 | Frontend menggunakan Tailwind CSS CDN.                                            | Memudahkan pembuatan UI custom modern tanpa template berat.                      |
| 2026-06-12 | Tidak menggunakan Vuexy/Bootstrap sebagai pondasi utama.                          | Agar tampilan tidak menjadi admin panel CRUD biasa.                              |
| 2026-06-12 | UI menggunakan gaya modern SaaS dashboard compact.                                | Sesuai referensi visual: clean, mini, rapi, dan profesional.                     |
| 2026-06-12 | Sidebar harus role-based dan workflow-aware.                                      | Setiap role punya kebutuhan berbeda dan mahasiswa harus mengikuti alur bertahap. |
| 2026-06-12 | Sidebar desktop bisa collapse.                                                    | Agar tampilan dashboard terasa modern dan hemat ruang.                           |
| 2026-06-12 | Sidebar mobile menjadi hamburger drawer.                                          | Agar nyaman digunakan di HP.                                                     |
| 2026-06-12 | Menu mahasiswa yang belum terbuka tetap ditampilkan dengan icon gembok.           | Agar mahasiswa tahu tahap berikutnya dan tidak bingung.                          |
| 2026-06-12 | Locked menu wajib diproteksi juga di backend.                                     | Keamanan tidak boleh hanya bergantung pada UI.                                   |
| 2026-06-12 | Semua role memakai layout global dengan partials.                                 | Agar konsisten dan mudah maintenance.                                            |
| 2026-06-12 | File upload disimpan di `writable/uploads`.                                       | Lebih aman daripada menyimpan langsung di public.                                |
| 2026-06-12 | Dokumen generated disimpan di `writable/generated`.                               | Agar dokumen PDF tersimpan rapi dan aman.                                        |
| 2026-06-12 | URL harus bersih tanpa `index.php`.                                               | Agar sistem terlihat profesional dan mudah digunakan.                            |
| 2026-06-12 | TOPSIS hanya rekomendasi, bukan keputusan final.                                  | Keputusan akademik tetap berada pada Koordinator KP/KPL.                         |
| 2026-06-12 | Tempat mandiri tidak wajib memiliki akun instansi.                                | Lebih realistis dengan kondisi kampus dan instansi luar.                         |
| 2026-06-12 | Dokumen yang butuh tanda tangan/stempel tetap memakai alur download-print-upload. | Menyesuaikan sistem manual kampus saat ini.                                      |
| 2026-06-12 | Logbook menggunakan model mingguan dengan detail harian.                          | Lebih rapi untuk monitoring dan tetap mudah diisi mahasiswa.                     |
| 2026-06-12 | Validasi harus menggunakan Bahasa Indonesia.                                      | Agar mudah dipahami user kampus.                                                 |
| 2026-06-12 | Semua aksi penting wajib masuk audit log.                                         | Penting untuk keamanan, transparansi, dan riwayat keputusan.                     |
| 2026-06-12 | Arsip periode bersifat read-only.                                                 | Agar data lama aman dan tidak berubah sembarangan.                               |
| 2026-06-12 | `docs/03-roles.md` diperbaiki menjadi dokumen role dan hak akses final.            | File sebelumnya tidak berisi detail role yang benar.                             |
| 2026-06-12 | Status workflow utama MVP disederhanakan di `docs/04-workflow.md`.                | Agar `kp_registrations.current_status` tidak terlalu granular.                   |
| 2026-06-12 | Tabel MVP awal dikunci di `docs/06-database.md`.                                  | Agar implementasi fondasi tidak terlalu besar dan tetap realistis.               |

---

## 5. Keputusan Role

Role final:

```text
Admin
Koordinator KP/KPL
Mahasiswa
Dosen Pembimbing
Instansi Mitra
```

Pembagian utama:

```text
Admin = pengelola sistem dan administrasi
Koordinator KP/KPL = pengambil keputusan akademik
Mahasiswa = mengikuti alur step-based
Dosen Pembimbing = membimbing dan menilai akademik
Instansi Mitra = menerima dan menilai kegiatan lapangan
```

---

## 6. Keputusan Workflow

Workflow utama:

```text
Login / Daftar Akun
↓
Lengkapi Profil
↓
Registrasi KP/KPL
↓
Upload Dokumen Persyaratan
↓
Verifikasi Registrasi
↓
Penempatan KP/KPL
↓
Pilih Mitra atau Ajukan Tempat Mandiri
↓
Validasi Penempatan
↓
Generate Surat dan Dokumen
↓
Konfirmasi Penerimaan Instansi
↓
Penetapan Dosen Pembimbing
↓
KP/KPL Berjalan
↓
Logbook Mingguan
↓
Laporan Akhir
↓
Penilaian Instansi
↓
Penilaian Dosen
↓
Rekap Nilai Akhir
↓
Validasi Nilai Akhir
↓
Selesai
↓
Arsip Periode
```

---

## 7. Keputusan Teknologi

Stack final:

```text
Backend:
- CodeIgniter 4
- PHP 8.2+
- Composer

Database:
- MySQL / MariaDB
- Migration
- Seeder

Frontend:
- Tailwind CSS CDN
- Inter Font
- Tabler Icons
- Alpine.js
- SweetAlert2

Library halaman tertentu:
- DataTables
- Tom Select
- Flatpickr
- Chart.js

Dokumen:
- Dompdf
- PhpSpreadsheet

Security:
- CSRF
- password_hash()
- password_verify()
- AuthFilter
- RoleFilter
- WorkflowAccessFilter
- Upload validation
- Audit log

Storage:
- writable/uploads
- writable/generated

URL:
- Clean URL tanpa index.php
```

---

## 8. Keputusan Database

Nama database default:

```text
db_kp_pkl
```

Tabel inti yang wajib ada:

```text
users
student_profiles
lecturer_profiles
institution_profiles
study_programs
kp_periods
kp_registrations
registration_status_logs
document_requirements
student_documents
generated_documents
placement_requests
placement_choices
institution_quotas
topsis_criteria
topsis_weights
topsis_scores
topsis_results
supervisor_assignments
logbook_weeks
logbook_daily_entries
logbook_reviews
final_reports
assessment_scores
final_scores
audit_logs
```

Catatan penting:

1. Status utama mahasiswa berada di `kp_registrations.current_status`.
2. Satu mahasiswa hanya boleh memiliki satu registrasi dalam satu periode.
3. Email user harus unique.
4. NPM mahasiswa harus unique.
5. Bobot TOPSIS disimpan per periode.
6. Bobot nilai akhir disimpan per periode.
7. Dokumen harus memiliki versi.
8. Arsip tidak diedit langsung.
9. Form builder, notifikasi database, dan koreksi arsip detail dapat menyusul setelah workflow utama berjalan.

---

## 9. Struktur Folder yang Dikunci

Struktur utama:

```text
app/
├── Config/
├── Controllers/
│   ├── Auth/
│   ├── Admin/
│   ├── Koordinator/
│   ├── Mahasiswa/
│   ├── Dosen/
│   └── Instansi/
├── Models/
├── Services/
├── Filters/
├── Helpers/
├── Validation/
├── Language/id/
└── Views/
    ├── layouts/
    ├── partials/
    ├── components/
    ├── auth/
    ├── admin/
    ├── koordinator/
    ├── mahasiswa/
    ├── dosen/
    ├── instansi/
    └── pdf/
```

Storage:

```text
writable/
├── uploads/
│   └── kp-pkl/
└── generated/
    └── documents/
```

Public assets:

```text
public/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── index.php
└── .htaccess
```

---

## 10. Progress Implementasi

## 10.1 Selesai

- [x] Menentukan konsep sistem berbasis workflow.
- [x] Menentukan 5 role utama.
- [x] Menentukan alur manual kampus sebagai dasar digitalisasi.
- [x] Menentukan penggunaan CI4 dan MySQL.
- [x] Menentukan penggunaan Tailwind CSS CDN.
- [x] Menentukan gaya UI modern SaaS dashboard compact.
- [x] Menentukan sidebar role-based.
- [x] Menentukan sidebar collapse desktop.
- [x] Menentukan sidebar drawer mobile.
- [x] Menentukan locked menu mahasiswa.
- [x] Menentukan topbar dan footer global.
- [x] Menentukan struktur dokumentasi.
- [x] Menentukan struktur folder CI4.
- [x] Menentukan struktur database awal.
- [x] Menentukan folder writable upload dan generated.
- [x] Menentukan clean URL tanpa `index.php`.
- [x] Menentukan aturan CDN.
- [x] Menentukan aturan validasi Bahasa Indonesia.
- [x] Menentukan aturan audit log.
- [x] Menentukan aturan arsip periode.
- [x] Memperbaiki `docs/03-roles.md` agar berisi role dan hak akses final.
- [x] Menyederhanakan status workflow utama untuk MVP.
- [x] Mengunci tabel MVP awal di dokumentasi database.
- [x] Setup project CodeIgniter 4.
- [x] Konfigurasi `.env` dan koneksi database `db_kp_pkl`.
- [x] Implementasi 11 berkas migrasi database skema MVP.
- [x] Implementasi data seeder awal (program studi & 5 role user profile).

- [x] Membuat layout dashboard global dan partials (sidebar, topbar, footer, flash-message).
- [x] Implementasi sistem autentikasi multi-role (login & logout manual).
- [x] Implementasi AuthFilter dan RoleFilter untuk pembatasan hak akses route.
- [x] Implementasi dashboard dasar untuk kelima role (Admin, Koordinator, Mahasiswa, Dosen, Instansi).
- [x] Implementasi locked menu dan progress stepper pada dashboard Mahasiswa.
- [x] Implementasi profil mahasiswa edit view.

---

## 10.2 Sedang Dikerjakan

- [ ] Implementasi periode KP/KPL.
- [ ] Implementasi registrasi KP/KPL.

---

## 10.3 Belum Dikerjakan

- [ ] Implementasi login Google mahasiswa.
- [ ] Implementasi WorkflowAccessFilter.
- [ ] Implementasi upload dokumen.
- [ ] Implementasi verifikasi registrasi.
- [ ] Implementasi penempatan mitra.
- [ ] Implementasi tempat mandiri.
- [ ] Implementasi TOPSIS.
- [ ] Implementasi generate PDF.
- [ ] Implementasi upload dokumen balasan.
- [ ] Implementasi penetapan dosen pembimbing.
- [ ] Implementasi logbook mingguan.
- [ ] Implementasi review logbook dosen.
- [ ] Implementasi laporan akhir.
- [ ] Implementasi penilaian instansi.
- [ ] Implementasi penilaian dosen.
- [ ] Implementasi rekap nilai akhir.
- [ ] Implementasi arsip periode.
- [ ] Implementasi audit log.
- [ ] Implementasi export Excel/PDF.
- [ ] Testing black box.
- [ ] Penyusunan laporan hasil pengujian.

---

## 11. Bug dan Masalah

| Tanggal | Masalah | Status | Catatan |
| ------- | ------- | ------ | ------- |
| - | Belum ada bug karena implementasi belum dimulai. | - | - |

---

## 12. Risiko Project

| Risiko | Dampak | Solusi |
| ------ | ------ | ------ |
| Struktur database terlalu besar untuk MVP | Implementasi menjadi lama | Mulai dari tabel inti, fitur lanjutan bisa menyusul. |
| Agent membuat sistem seperti CRUD biasa | Workflow tidak sesuai tujuan | Selalu ikuti `AGENTS.md`, `04-workflow.md`, dan `09-business-rules.md`. |
| UI terlalu besar dan boros ruang | Tidak sesuai konsep SaaS compact | Ikuti `05-sidebar-dashboard.md` dan `08-ui-ux-rules.md`. |
| Validasi hanya frontend | Akses/data tidak aman | Semua validasi wajib backend. |
| Menu mahasiswa terbuka semua | Workflow rusak | Gunakan `WorkflowAccessFilter`. |
| File upload disimpan di public | Risiko keamanan | Simpan file di `writable/uploads`. |
| TOPSIS dianggap keputusan final | Salah secara konsep akademik | TOPSIS hanya rekomendasi, Koordinator final. |
| Instansi mandiri dipaksa punya akun | Tidak realistis | Gunakan alur download-print-upload. |
| Arsip bisa diedit langsung | Data lama tidak aman | Arsip read-only dan koreksi wajib audit log. |

---

## 13. Catatan untuk Agent Berikutnya

Sebelum mulai coding, agent wajib:

1. Baca `AGENTS.md`.
2. Baca `README.md`.
3. Baca `docs/01-prd.md`.
4. Baca `docs/02-current-system.md`.
5. Baca `docs/03-roles.md`.
6. Baca `docs/04-workflow.md`.
7. Baca `docs/05-sidebar-dashboard.md`.
8. Baca `docs/06-database.md`.
9. Baca `docs/07-technology.md`.
10. Baca `docs/08-ui-ux-rules.md`.
11. Baca `docs/09-business-rules.md`.
12. Baca `docs/10-project-structure.md`.
13. Baca file ini sebelum melanjutkan.

Agent tidak boleh langsung coding tanpa membaca konteks.

---

## 14. Urutan Implementasi yang Disarankan

Urutan implementasi awal:

```text
1. Setup CodeIgniter 4
2. Setup .env
3. Setup database
4. Setup migration user, role, profile, periode
5. Setup auth manual
6. Setup layout dashboard global
7. Setup sidebar role-based
8. Setup RoleFilter
9. Setup dashboard dasar setiap role
10. Setup profil mahasiswa
11. Setup periode KP/KPL
12. Setup registrasi digital
13. Setup upload dokumen
14. Setup verifikasi registrasi
15. Setup workflow access mahasiswa
16. Setup penempatan
17. Setup TOPSIS
18. Setup generate dokumen
19. Setup pembimbing
20. Setup logbook
21. Setup penilaian
22. Setup arsip
23. Testing dan perbaikan
```

---

## 15. Format Update Progress Berikutnya

Gunakan format berikut saat menambah progress baru:

```md
## Update YYYY-MM-DD

### Selesai

- [x] Item yang selesai.

### Sedang Dikerjakan

- [ ] Item yang sedang dikerjakan.

### Masalah

- Masalah yang ditemukan.

### Keputusan Baru

- Keputusan baru jika ada.

### Catatan

- Catatan tambahan.
```

Contoh:

```md
## Update 2026-06-13

### Selesai

- [x] Setup CodeIgniter 4.
- [x] Membuat file `.env`.
- [x] Membuat database `db_kp_pkl`.

### Sedang Dikerjakan

- [ ] Membuat migration users dan profiles.

### Masalah

- Belum ada.

### Keputusan Baru

- Menggunakan custom auth terlebih dahulu sebelum Google login.

### Catatan

- Login Google akan dibuat setelah login manual stabil.
```

## Update 2026-06-13 (Penyelesaian Modul Manajemen & Arsip Koordinator)

### Selesai

- [x] **Routing & Controllers**: Menghubungkan 8 rute Manajemen & Arsip Koordinator di `app/Config/Routes.php` ke 4 controller baru di bawah `App\Controllers\Koordinator\`: `PeriodeController`, `ArsipController`, `LaporanController`, dan `KeputusanController`.
- [x] **Manajemen Periode**:
  - Tampilan grid daftar periode lengkap dengan statistika pendaftaran dan keaktifan mahasiswa.
  - Alur siklus status periode: draft $\rightarrow$ aktif $\rightarrow$ ditutup $\rightarrow$ diarsipkan.
  - Validasi ketat saat mengarsipkan periode: mendeteksi dan memblokir pengarsipan jika terdapat mahasiswa yang belum menyelesaikan workflow akademik.
  - Transaksi database aman (`transStart`/`transComplete`) untuk mengarsipkan data periode dan registrasi mahasiswa secara bersamaan.
- [x] **Arsip KP/KPL**:
  - Halaman arsip terkunci read-only yang memuat profil mahasiswa, instansi, dosen pembimbing, skor akhir, grade huruf, dan berkas laporan akhir.
- [x] **Laporan Rekapitulasi**:
  - Dasbor statistik distribusi grade nilai (A-E) dan grafik/progress bar persentase.
  - Implementasi download file Excel (`phpspreadsheet`) dan PDF (`dompdf`) landscape A4 yang rapi dan aman.
- [x] **Catatan Keputusan**:
  - Log audit khusus personal Koordinator yang aktif (`user_id = session('user_id')`) dilengkapi inspect modal Alpine.js untuk membandingkan payload JSON data lama dan baru secara real-time.
- [x] **Verifikasi Sintaks**: Syntax lint check (`php -l`) pada semua berkas lolos 100%.

### Sedang Dikerjakan

- [ ] Implementasi workflow Mahasiswa (Pendaftaran, Pengajuan Penempatan, Logbook, dll).

---

## Update 2026-06-13 (Penyelesaian Modul Akademik Koordinator)

### Selesai

- [x] **Routing & Controller**: Menghubungkan seluruh 10 rute akademik Koordinator di `app/Config/Routes.php` ke `app/Controllers/Koordinator/AkademikController.php`.
- [x] **TOPSIS Service**: Mengimplementasikan `TopsisService.php` dengan rumus normalisasi matriks, bobot kriteria, ideal positif/negatif, dan closeness coefficient. Dilengkapi penanganan fallback otomatis (matriks default) jika data matriks keputusan belum lengkap.
- [x] **15 View Templates Akademik Koordinator**:
  - `validasi-registrasi` (`index.php`, `review.php`)
  - `pengajuan-penempatan` (`index.php`, `review.php` dengan validasi kuota)
  - `topsis` (`index.php` kalkulator real-time & override bobot/matriks keputusan)
  - `validasi-mandiri` (`index.php`, `review.php` dengan pembuatan instansi otomatis jika disetujui)
  - `penetapan-pembimbing` (`index.php` dengan monitoring kuota bimbingan dosen aktif)
  - `monitoring-mahasiswa` (`index.php`)
  - `monitoring-logbook` (`index.php`, `view.php` timeline logbook harian)
  - `monitoring-laporan` (`index.php`)
  - `validasi-penilaian` (`index.php`, `review.php` form sahkan nilai dengan SweetAlert2)
  - `rekap-nilai` (`index.php` ringkasan statistik dan tombol cetak/PDF/Excel)
- [x] **Validasi & Audit**: Validasi backend lengkap berbahasa Indonesia, transaksi aman (`db->transStart()`), dan pencatatan audit log (`AuditService::log()`) untuk setiap aksi penting.
- [x] **Verifikasi Sintaks**: Pemeriksaan sintaks PHP menggunakan `php -l` pada semua berkas yang dibuat (lulus 100%).

### Sedang Dikerjakan

- [ ] Implementasi workflow Mahasiswa (Pendaftaran, Pengajuan Penempatan, Logbook, dll).

---

## Update 2026-06-13 (Status Evaluasi Peran & Fitur Terkini)

### Selesai (Developed)

- [x] **Pondasi & Autentikasi Multi-role**: Login/logout manual, `AuthFilter` & `RoleFilter` untuk perlindungan hak akses rute, serta layout global responsif SaaS dashboard dengan sidebar collapsible (desktop) dan drawer overlay (mobile) serta toggle Dark Mode/Light Mode.
- [x] **Peran Admin (100% Fitur Selesai)**:
  - **Data Master**: CRUD Mahasiswa, CRUD Dosen, CRUD Instansi Mitra, CRUD Program Studi, CRUD Akun Pengguna.
  - **Pelaksanaan**: CRUD Periode Akademik, Review Registrasi, View Penempatan, Verifikasi Dokumen Syarat Administrasi, Review Logbook Mingguan (accordion Alpine.js), View Laporan Akhir, Monitoring & Rekap Penilaian Akhir.
  - **Konfigurasi & Pengaturan**: CRUD Dokumen Syarat, CRUD Template Surat Resmi, Form Builder Dinamis (tambah/hapus field kustom), Kriteria & Bobot TOPSIS (real-time validator total bobot 100%), Visualisasi Audit Log (detail JSON Diff modal), Pengaturan Sistem (kelayakan IPK/SKS, SMTP disimpan di `writable/settings.json`), Arsip Periode (mengunci periode pasca-kegiatan), Laporan & Export (PhpSpreadsheet Excel & Dompdf landscape PDF).
- [x] **Peran Koordinator (100% Fitur Selesai)**:
  - **Validasi Registrasi**: Review dokumen kelayakan pendaftaran mahasiswa (SKS, IPK, MK Wajib).
  - **Validasi Penempatan**: Menyetujui/menolak pengajuan penempatan mitra kampus (berbasis kuota) atau mandiri (otomatis membuat profil instansi dan kuota baru).
  - **Rekomendasi TOPSIS**: Perhitungan matriks keputusan, ideal positif/negatif, kriteria cost/benefit, dan perankingan kecocokan instansi.
  - **Penetapan Pembimbing**: Pembagian dosen bimbingan dengan penghitungan real-time sisa kuota bimbingan aktif dosen.
  - **Monitoring Progres**: Memantau perkembangan mahasiswa, logbook mingguan (timeline terperinci), dan laporan akhir.
  - **Validasi Penilaian**: Mengesahkan nilai akhir gabungan (Dosen, Instansi, Admin) dan mengunci status kegiatan mahasiswa menjadi selesai.
  - **Rekap Nilai**: Halaman ringkasan rekapitulasi nilai akhir per periode yang dapat diekspor langsung ke Excel, PDF, atau dicetak.
  - **Manajemen Periode**: Toggling status periode (draft -> aktif -> ditutup -> diarsipkan) dengan pengaman validasi workflow mahasiswa.
  - **Arsip KP/KPL**: Dashboard riwayat periode akademik yang terkunci secara permanen (Read-Only).
  - **Laporan Rekapitulasi**: Dasbor statistik distribusi grade nilai serta ekspor berkas Excel dan PDF resmi.
  - **Catatan Keputusan**: Halaman personal audit logs khusus Koordinator dengan fitur JSON Diff viewer.
  - **Profil Saya**: Pengaturan data profil pribadi (nama, email, telepon) dan pergantian kata sandi masuk secara aman.

### Belum Dikerjakan / Belum Selesai (Pending/Next Roles)

- [ ] **Peran Mahasiswa (Sedang Dikerjakan/Menunggu)**:
  - Alur pendaftaran/registrasi digital dan upload dokumen persyaratan
  - Alur penempatan (pemilihan mitra dengan rekomendasi TOPSIS / pengajuan tempat mandiri)
  - Pengunduhan surat pengantar instansi resmi hasil generate
  - Upload dokumen balasan penerimaan instansi
  - Pengisian logbook mingguan & kegiatan harian
  - Upload laporan akhir
  - Visualisasi nilai akhir dan progress stepper dashboard
- [ ] **Peran Dosen Pembimbing (Belum Dikerjakan)**:
  - Monitoring mahasiswa bimbingan aktif
  - Review & komentar logbook mingguan bimbingan
  - Pemeriksaan/revisi laporan akhir mahasiswa
  - Pengisian nilai bimbingan akademik
- [ ] **Peran Instansi Mitra (Belum Dikerjakan)**:
  - Konfirmasi penerimaan mahasiswa penempatan
  - Pengisian nilai evaluasi/lapangan instansi
  - Monitoring logbook bimbingan instansi

### Masalah

- Tidak ada.

### Keputusan Baru

- Mengunggah seluruh codebase yang telah diselesaikan ke repositori Git jarak jauh: `https://github.com/skimatt/Sistem-Manajemen-KP-KPL.git` untuk kolaborasi pengembangan antar-agent berikutnya.

---

## Update 2026-06-13 (Penyelesaian Modul Konfigurasi & Pengaturan Admin)

### Selesai

- [x] Instalasi dependensi PHP untuk cetak/export: `dompdf/dompdf` dan `phpoffice/phpspreadsheet`.
- [x] Membuat dan menjalankan berkas migrasi `2026_06_13_000012_CreateDynamicFormAndTemplatesTables.php` untuk mendukung formulir dinamis (form_templates, form_fields, form_responses, form_response_values) dan template surat (document_templates).
- [x] Membuat seeder `TopsisCriteriaSeeder.php` untuk data master kriteria TOPSIS (C1 s/d C6) secara idempotent dan didaftarkan ke `DatabaseSeeder`.
- [x] Membuat 7 Models baru pendukung konfigurasi dan template.
- [x] Mengganti rute placeholder dengan rute aslinya yang terhubung ke 8 controller konfigurasi baru di `app/Config/Routes.php`.
- [x] Membuat 8 controller pelaksana pengaturan dan CRUD konfigurasi.
- [x] Membuat view templates untuk 8 sub-modul Konfigurasi & Pengaturan (Dokumen Syarat, Template Surat, Form Builder & Fields, Kriteria TOPSIS, Audit Log, Pengaturan Sistem, Arsip Periode, Laporan & Export) lengkap dengan integrasi DataTables, SweetAlert2, modal detail JSON, dan unduhan file Excel/PDF.
- [x] Validasi sintaks PHP (`php -l`) pada semua berkas models, controllers, dan views baru (lulus 100%).

### Sedang Dikerjakan

- [ ] Implementasi workflow mahasiswa (registrasi digital, upload berkas persyaratan, pengajuan penempatan).

### Masalah

- Tidak ada.

### Keputusan Baru

- Menyimpan konfigurasi umum sistem pada berkas `writable/settings.json` agar konfigurasi SMTP dan kelayakan IPK/SKS dapat dimodifikasi oleh Admin secara langsung dari UI web tanpa membahayakan data lingkungan `.env`.
- Validasi bobot TOPSIS mengharuskan jumlah total bobot kriteria untuk periode terpilih bernilai tepat 100% menggunakan kalkulator real-time dari Alpine.js di halaman view.

---

## Update 2026-06-13 (Penyelesaian Modul Pelaksanaan Admin)

### Selesai

- [x] Pembuatan view templates Logbook Mahasiswa (`app/Views/admin/logbook/index.php`, `view.php`) dengan layout responsif DataTables, progress bar kelulusan logbook, dan detail accordion Alpine.js untuk logbook harian.
- [x] Pembuatan view templates Laporan Akhir Mahasiswa (`app/Views/admin/laporan/index.php`) dengan tautan unduhan dokumen aman.
- [x] Pembuatan view templates Monitoring Penilaian (`app/Views/admin/penilaian/index.php`, `view.php`) dengan layout visual rekap nilai akhir, kalkulasi bobot snapshot nilai (Instansi, Dosen, Admin), dan rincian komponen penilaian.
- [x] Verifikasi sintaks PHP (`php -l`) pada semua file view yang ditambahkan.

### Sedang Dikerjakan

- [ ] Implementasi fitur Koordinator (validasi registrasi, TOPSIS, penetapan pembimbing, validasi nilai).

### Masalah

- Tidak ada.

### Keputusan Baru

- Menggunakan library `@alpinejs/collapse` untuk mempermudah visualisasi logbook harian mahasiswa per minggu agar tampilan rapi dan tidak terlalu panjang.
- Menggunakan fallback default bobot nilai (Instansi 40%, Dosen 50%, Admin/Logbook 10%) jika format `weight_snapshot` di `final_scores` kosong atau tidak valid.

---

## Update 2026-06-13 (Penyelesaian Data Master Admin CRUD)

### Selesai

- [x] Pembuatan view templates Program Studi (`app/Views/admin/prodi/index.php`, `create.php`, `edit.php`) dengan layout responsif DataTables, penanganan validasi, dan SweetAlert konfirmasi hapus data.
- [x] Pembuatan view templates Manajemen Akun (`app/Views/admin/akun/index.php`, `create.php`, `edit.php`) dengan layout responsif DataTables, avatar placeholder dinamis, dan reset password opsional.
- [x] Integrasi route master data CRUD di `app/Config/Routes.php` (menghapus placeholder dummy redirect dan menggantinya dengan handler index, create, store, edit, update, dan delete untuk 5 sub-modul).
- [x] Validasi sintaks PHP (`php -l`) pada semua file view dan routing yang baru ditambahkan/diubah.
- [x] Pencatatan aksi di audit log dan implementasi soft delete pada data users/profile yang didukung.

### Sedang Dikerjakan

- [ ] Implementasi periode KP/KPL dan pendaftaran/registrasi digital mahasiswa.

### Masalah

- Tidak ada.

### Keputusan Baru

- Mengarahkan rute administratif Master Data dari action placeholder dummy langsung ke controller CRUD terkait (MahasiswaController, DosenController, InstansiController, ProdiController, AkunController).

---

## Update 2026-06-13 (Layout Overhaul, Dark Mode, & Sidebar Menu Completion)

### Selesai

- [x] Implementasi Tailwind CSS Class-based Dark Mode (`darkMode: 'class'`) di `head.php`.
- [x] Penambahan immediate theme script di `<head>` untuk mencegah visual flicker tema (Light/Dark) saat dimuat.
- [x] Mendesain ulang tata letak utama (`app.php`) menjadi split-screen sticky layout: Sidebar terfiksasi di kiri (independen scroll) dan Main Content ter-scroll secara internal di kanan.
- [x] Implementasi menu sidebar collapsible grup (Data Master, Bimbingan) menggunakan Alpine.js & plugin `@alpinejs/collapse`.
- [x] Mendesain ulang Topbar dengan mockup-style left tabs, Sun/Moon theme toggle, dan profile menu.
- [x] Penyesuaian responsivitas: Sidebar disembunyikan sepenuhnya di mobile dan dialihkan menjadi hamburger menu drawer overlay.
- [x] Penyelarasan seluruh 8 halaman dashboard/fitur agar fully compatible dan adaptif dengan Dark Mode: Login, Admin Index, Koordinator Index, Dosen Index, Instansi Index, Mahasiswa Index (stepper & action cards), Profil Mahasiswa, dan Placeholder Page.
- [x] Penambahan semua item menu sidebar yang kurang lengkap di `sidebar.php` untuk kelima role (Admin, Koordinator, Mahasiswa, Dosen, Instansi) sesuai spesifikasi dokumen `docs/05-sidebar-dashboard.md`.
- [x] Mapping route grup baru di `Routes.php` untuk seluruh menu tambahan ke controller action `placeholder()` masing-masing agar mengantisipasi error 404.
- [x] Verifikasi sintaks parser PHP dengan menjalankan `php -l` untuk seluruh berkas views dan routes yang disesuaikan.

### Sedang Dikerjakan

- [ ] Implementasi periode akademik baru dan registrasi digital mahasiswa.

### Masalah

- Tidak ada.

### Keputusan Baru

- Menggunakan plugin `@alpinejs/collapse` yang dimuat sebelum file core script Alpine.js agar transisi ekspansi collapsible menu berjalan mulus tanpa merusak tinggi layout.
- Desain antarmuka global menggunakan split-screen height `h-screen overflow-hidden` di wrapper terluar untuk memastikan independen scrolling di panel menu dan konten.
- Mengarahkan menu-menu baru di sidebar ke action generic placeholder controller dengan menyematkan argumen nama menu agar proses verifikasi alur/prototyping visual tetap optimal tanpa menghasilkan halaman kosong (404).

---

## Update 2026-06-13

### Selesai

- [x] Konfigurasi file `.env` dan koneksi database MySQL `db_kp_pkl`.
- [x] Membuat dan menjalankan 11 berkas migrasi database untuk skema MVP lengkap (users, study_programs, profiles, periods, registrations, documents, placements, topsis, supervisors, logbooks, assessments/scores, audit logs, notifications).
- [x] Membuat `StudyProgramSeeder.php` dan `UserSeeder.php` untuk populasi data awal multi-role satu per satu (admin, koordinator, mahasiswa, dosen, instansi mitra).
- [x] Mengubah `StudyProgramSeeder` dan `UserSeeder` agar sepenuhnya *idempotent* (mencegah duplikasi saat dijalankan berulang kali).
- [x] Sukses menjalankan seeder dengan `php spark db:seed DatabaseSeeder` dan memverifikasi data di database.
- [x] Membuat layout dashboard global dan partials (`app.php`, `auth.php`, `head.php`, `scripts.php`, `sidebar.php`, `topbar.php`, `footer.php`, `flash-message.php`).
- [x] Implementasi sistem autentikasi manual multi-role (`LoginController` dan view login).
- [x] Implementasi `AuthFilter` dan `RoleFilter` untuk pengamanan route group berdasarkan status login dan role.
- [x] Membuat controller dan view dashboard dasar untuk kelima role (`admin`, `koordinator`, `mahasiswa`, `dosen`, `instansi`).
- [x] Menambahkan visualisasi alur tahapan progress stepper 9 langkah dan locked menu dinamis dengan interaktivitas SweetAlert2 pada dashboard Mahasiswa.
- [x] Membuat halaman Profil Mahasiswa edit view.

### Sedang Dikerjakan

- [ ] Implementasi data master periode akademik dan prodi.
- [ ] Implementasi formulir pendaftaran/registrasi digital mahasiswa.

### Masalah

- Tidak ada.

### Keputusan Baru

- Menggunakan data seeder yang aman (*idempotent*) dengan melakukan pengecekan data sebelum menyisipkan record ke database.
- Menggunakan parameter array pada group route untuk mempermudah pemetaan multiple filter (`auth` dan `role:group`).
- Menyediakan drawer helper demo credentials langsung pada halaman login untuk mempermudah perpindahan antar-role selama pengujian.

---

## Update 2026-06-12

### Selesai

- [x] Memperbaiki `docs/03-roles.md` dari duplikasi dokumen sistem berjalan menjadi dokumen role dan hak akses.
- [x] Mengunci role final: Admin, Koordinator KP/KPL, Mahasiswa, Dosen Pembimbing, dan Instansi Mitra.
- [x] Menambahkan data scope dan permission matrix ringkas per role.
- [x] Menyederhanakan `kp_registrations.current_status` menjadi status tahap besar MVP.
- [x] Memindahkan status detail dokumen, penempatan, logbook, laporan, dan nilai ke tabel masing-masing.
- [x] Mengunci daftar tabel MVP awal di `docs/06-database.md`.

### Sedang Dikerjakan

- [ ] Setup CodeIgniter 4.
- [ ] Setup `.env`.
- [ ] Membuat database `db_kp_pkl`.
- [ ] Membuat migration awal.

### Masalah

- Belum ada masalah implementasi karena coding aplikasi belum dimulai.

### Keputusan Baru

- `profil_belum_lengkap` dan `siap_registrasi` tidak disimpan di `kp_registrations.current_status`.
- `registrasi_draft` digabung menjadi `draft`.
- Status granular seperti `logbook_berjalan`, `nilai_instansi_masuk`, dan `nilai_dosen_masuk` dikelola oleh tabel subfitur.
- Form builder dinamis, notifikasi database, dan koreksi arsip detail boleh menyusul setelah workflow utama berjalan.

### Catatan

- Dokumentasi sekarang lebih siap untuk memulai fondasi CI4: auth, layout, role filter, workflow service, dan migration inti.

---

## Update 2026-06-13 (Penyelesaian Modul Pelaksanaan Mahasiswa)

### Selesai

- [x] **Routing & Controller**: Menghubungkan seluruh 20 rute mahasiswa di `app/Config/Routes.php` ke `app/Controllers/Mahasiswa/MahasiswaController.php`.
- [x] **Mahasiswa Controller**:
  - Mengimplementasikan `getStudentData()` dan `checkAccess($stage)` untuk membatasi akses URL sekuensial berdasarkan status registrasi dan profil mahasiswa.
  - Mengimplementasikan validasi data masukan dalam Bahasa Indonesia lengkap dengan penanganan error.
  - Menyusun log perubahan status pendaftaran dan audit log aktivitas (`AuditService::log()`) secara transaksional (`transStart`/`transComplete`).
  - Mengelola upload berkas persyaratan (bukti bayar, KHS, surat rekomendasi), bukti komunikasi mandiri, surat balasan instansi, kegiatan logbook harian, dan laporan akhir PDF.
  - Integrasi dengan `TopsisService` untuk memunculkan ranking rekomendasi mitra instansi.
- [x] **14 View Templates Pelaksanaan Mahasiswa**:
  - `mahasiswa/index.php` (stepper alur tahapan, action advice dinamis, summary pembimbing)
  - `profile.php` (form edit biodata dan akademik terintegrasi CSRF dan validasi feedback)
  - `registrasi.php` (form pendaftaran kelayakan SKS/IPK, prasyarat kuliah, dan file upload)
  - `status_registrasi.php` (rincian berkas, timeline log verifikasi koordinator)
  - `penempatan.php` (pembagian jalur Mitra Kampus vs Tempat Mandiri)
  - `rekomendasi_mitra.php` (daftar peringkat instansi TOPSIS, kuota penempatan, form prioritas 1, 2, 3)
  - `tempat_mandiri.php` (usulan instansi mandiri, kontak narahubung, justifikasi alasan, berkas penjajakan)
  - `dokumen.php` (halaman download surat tugas/permohonan hasil generate sistem)
  - `upload_balasan.php` (unggah scan PDF penerimaan instansi bersurat stempel)
  - `pembimbing.php` (kartu rincian kontak dosen pembimbing akademik yang ditugaskan)
  - `logbook.php` (tampilan logbook mingguan, accordion, form input target/luaran mingguan, dan modal Alpine.js untuk catatan kegiatan harian)
  - `catatan_dosen.php` (umpan balik, status, komentar bimbingan dosen pembimbing)
  - `laporan.php` (form judul laporan akhir, unggah berkas laporan akhir PDF, dan status kunci)
  - `penilaian.php` (kartu transkrip nilai angka, grade huruf kelulusan, dan tabel rincian komponen)
  - `riwayat.php` (daftar riwayat pendaftaran lintas periode akademik)
  - `notifikasi.php` (list pemberitahuan sistem terintegrasi filter is_read)
- [x] **Verifikasi Sintaks**: Syntax lint check (`php -l`) pada semua berkas lolos 100%.
- [x] **Git Repository**: Mengunggah seluruh revisi dan view template baru ke repositori Git jarak jauh (`origin main`).

### Sedang Dikerjakan

- [ ] Implementasi peran Dosen Pembimbing (monitoring bimbingan, review logbook, koreksi laporan, penilaian akademik).

---

## 16. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Menghapus catatan progress lama.
2. Mengubah keputusan lama tanpa alasan.
3. Menghapus bug dari daftar tanpa menyelesaikannya.
4. Mengubah status selesai jika belum benar-benar selesai.
5. Mengabaikan file ini saat melanjutkan project.
6. Melanjutkan coding tanpa memahami keputusan project.
7. Membuat struktur baru tanpa update progress.
8. Menambahkan library baru tanpa mencatat alasan.
9. Mengubah workflow tanpa update `docs/04-workflow.md`.
10. Mengubah database tanpa update `docs/06-database.md`.

---

## 17. Ringkasan Final

Project saat ini sudah memiliki arah yang jelas:

```text
CI4 + MySQL
Workflow-based
Multi-role
TOPSIS
Dokumen resmi kampus
Dashboard SaaS compact
Sidebar locked menu
Mobile-friendly
Writable storage
Clean URL
Audit log
Arsip periode
```

Langkah berikutnya adalah mulai setup project CodeIgniter 4 dan mengimplementasikan fondasi awal sesuai urutan yang sudah ditentukan.
