# README.md

# Sistem Manajemen KP/KPL Berbasis Multi-Role dengan Rekomendasi Penempatan Menggunakan Metode TOPSIS

## 1. Deskripsi Singkat

Project ini adalah sistem manajemen Kerja Praktek (KP) dan Kerja Praktek Lapangan (KPL) berbasis web yang dibangun menggunakan CodeIgniter 4 dan MySQL.

Sistem ini dirancang untuk mendigitalisasi proses KP/KPL yang sebelumnya masih dilakukan secara manual melalui Google Form, dokumen fisik, tanda tangan, stempel, dan verifikasi langsung oleh Koordinator.

Aplikasi ini bukan sekadar CRUD, tetapi sistem workflow akademik yang mengelola proses dari awal sampai akhir:

```text
Registrasi
↓
Verifikasi
↓
Penempatan
↓
Rekomendasi TOPSIS
↓
Penetapan Pembimbing
↓
Dokumen dan Surat
↓
Logbook
↓
Laporan Akhir
↓
Penilaian
↓
Arsip Periode
```

---

## 2. Tujuan Sistem

Tujuan utama sistem ini adalah:

1. Mengubah proses KP/KPL manual menjadi sistem digital terpusat.
2. Memudahkan mahasiswa melakukan registrasi dan memantau status KP/KPL.
3. Membantu Admin dan Koordinator mengelola data, dokumen, verifikasi, dan periode.
4. Membantu Koordinator menentukan penempatan mahasiswa secara lebih objektif menggunakan metode TOPSIS.
5. Membantu dosen pembimbing memantau logbook, laporan, dan penilaian mahasiswa.
6. Menyediakan arsip periode agar data KP/KPL setiap tahun tidak hilang.
7. Menyediakan tampilan dashboard modern, clean, compact, dan responsive.

---

## 3. Role Sistem

Sistem memiliki 5 role utama:

1. **Admin**
   - Mengelola data master, akun, periode, form, dokumen, template surat, konfigurasi sistem, dan audit log.

2. **Koordinator KP/KPL**
   - Memvalidasi registrasi, menyetujui penempatan, menetapkan dosen pembimbing, memvalidasi nilai akhir, dan mengarsipkan periode.

3. **Mahasiswa**
   - Mengikuti alur step-based mulai dari registrasi, penempatan, dokumen, logbook, laporan akhir, sampai melihat nilai dan arsip.

4. **Dosen Pembimbing**
   - Memantau mahasiswa bimbingan, mereview logbook, memberi catatan, memeriksa laporan akhir, dan mengisi nilai akademik.

5. **Instansi Mitra**
   - Mengonfirmasi penerimaan mahasiswa, melihat data mahasiswa yang ditempatkan, dan memberi evaluasi/nilai jika memiliki akun.

---

## 4. Fitur Utama

Fitur utama sistem:

1. Auth multi-role.
2. Login Google untuk mahasiswa.
3. Manajemen akun user.
4. Manajemen data mahasiswa, dosen, dan instansi.
5. Manajemen periode KP/KPL.
6. Form registrasi digital.
7. Upload dokumen persyaratan.
8. Verifikasi registrasi.
9. Penempatan mitra kampus.
10. Penempatan tempat mandiri.
11. Rekomendasi penempatan menggunakan TOPSIS.
12. Penetapan dosen pembimbing.
13. Generate surat dan dokumen PDF.
14. Upload dokumen balasan instansi.
15. Logbook mingguan.
16. Review logbook oleh dosen.
17. Upload laporan akhir.
18. Penilaian instansi.
19. Penilaian dosen.
20. Rekap nilai akhir.
21. Arsip periode.
22. Audit log.
23. Dashboard role-based.
24. Sidebar step-based dengan menu terkunci.
25. Tampilan responsive untuk desktop dan mobile.

---

## 5. Teknologi yang Digunakan

Backend:

```text
CodeIgniter 4
PHP 8.2+
MySQL / MariaDB
Composer
```

Frontend:

```text
Tailwind CSS CDN
Alpine.js
SweetAlert2
Tabler Icons
Inter Font
DataTables
Tom Select
Flatpickr
Chart.js
```

Dokumen dan Export:

```text
Dompdf
PhpSpreadsheet
CSV Export
```

Keamanan:

```text
CSRF Protection
password_hash()
password_verify()
Role Filter
Workflow Access Filter
Soft Delete
Audit Log
Upload Validation
```

---

## 6. Prinsip UI/UX

Aplikasi harus memiliki tampilan:

```text
Modern
Clean
Compact
Responsive
SaaS Dashboard Style
Workflow-based
Bukan CRUD biasa
```

Aturan UI utama:

1. Gunakan layout global dengan partials.
2. Semua role memakai topbar, sidebar, content area, dan footer.
3. Sidebar bisa collapse di desktop.
4. Sidebar berubah menjadi hamburger drawer di mobile.
5. Menu mahasiswa yang belum sesuai tahap harus tampil terkunci dengan icon gembok.
6. Dashboard mahasiswa harus menampilkan progress stepper.
7. Card statistik harus compact, bukan besar-besar.
8. Halaman logbook harus nyaman dipakai dari HP.
9. Tabel besar harus responsive.
10. Gunakan Tabler Icons, bukan emoji.

---

## 7. Struktur Dokumentasi

Dokumentasi utama project:

```text
root-project/
├── AGENTS.md
├── README.md
├── templates.md
└── docs/
    ├── 01-prd.md
    ├── 02-current-system.md
    ├── 03-roles.md
    ├── 04-workflow.md
    ├── 05-sidebar-dashboard.md
    ├── 06-database.md
    ├── 07-technology.md
    ├── 08-ui-ux-rules.md
    ├── 09-business-rules.md
    ├── 10-project-structure.md
    └── 11-progress.md
```

Sebelum coding, baca `AGENTS.md` dan seluruh file dalam folder `docs/` sesuai urutan.

---

## 8. Struktur Project CI4

Struktur utama project:

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
├── Views/
│   ├── layouts/
│   ├── partials/
│   ├── components/
│   ├── auth/
│   ├── admin/
│   ├── koordinator/
│   ├── mahasiswa/
│   ├── dosen/
│   └── instansi/
├── Filters/
├── Validation/
├── Helpers/
└── Language/
    └── id/
```

Logic penting tidak boleh ditaruh langsung di controller.

Gunakan service:

```text
WorkflowService
TopsisService
DocumentService
NotificationService
ArchiveService
UploadService
```

---

## 9. Struktur Storage

File upload disimpan di folder `writable`, bukan langsung di `public`.

```text
writable/
├── uploads/
│   └── kp-pkl/
│       └── {tahun_periode}/
│           └── {prodi}/
│               └── {npm}/
│                   ├── registrasi/
│                   ├── rekomendasi-pa/
│                   ├── penempatan/
│                   ├── surat/
│                   ├── penerimaan-instansi/
│                   ├── logbook/
│                   ├── laporan-akhir/
│                   ├── penilaian/
│                   └── arsip/
│
├── generated/
│   └── documents/
│       └── {tahun_periode}/
│           └── {npm}/
│               ├── lampiran-a/
│               ├── surat-pengantar/
│               ├── surat-permohonan/
│               ├── surat-tugas-pembimbing/
│               ├── form-penilaian/
│               └── rekap-nilai/
```

Akses file harus melalui controller agar hak akses dapat diperiksa.

---

## 10. Clean URL

URL harus bersih tanpa `index.php`.

Contoh URL yang benar:

```text
/login
/dashboard
/mahasiswa/registrasi
/mahasiswa/penempatan
/admin/data-mahasiswa
/koordinator/validasi-registrasi
```

Tidak boleh:

```text
/index.php/login
/index.php/dashboard
```

Pastikan:

```text
.htaccess aktif
mod_rewrite aktif
baseURL diatur di .env
indexPage dikosongkan
```

---

## 11. Environment

Gunakan `.env` untuk konfigurasi utama.

Contoh:

```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
app.indexPage = ''

database.default.hostname = localhost
database.default.database = db_kp_pkl
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306

security.csrfProtection = 'session'
security.tokenRandomize = true

app.uploadMaxSize = 10240
app.allowedFileTypes = pdf,jpg,jpeg,png

google.clientID =
google.clientSecret =
google.redirectURI =
```

---

## 12. Database Default

Nama database default:

```text
db_kp_pkl
```

Database harus dirancang mengikuti workflow, bukan hanya tabel CRUD.

Tabel inti:

```text
users
student_profiles
lecturer_profiles
institution_profiles
kp_periods
kp_registrations
placement_requests
supervisor_assignments
student_documents
generated_documents
logbook_weeks
logbook_daily_entries
assessment_scores
final_scores
audit_logs
```

Detail database dijelaskan di `docs/06-database.md`.

---

## 13. Batasan MVP

Fokus tahap awal:

1. Auth multi-role.
2. Login Google mahasiswa.
3. Dashboard role-based.
4. Sidebar responsive dan locked menu.
5. Periode KP/KPL.
6. Registrasi digital.
7. Upload dan verifikasi dokumen.
8. Penempatan mitra dan mandiri.
9. TOPSIS dasar.
10. Generate dokumen PDF.
11. Penetapan dosen pembimbing.
12. Logbook mingguan.
13. Laporan akhir.
14. Penilaian.
15. Arsip periode.
16. Audit log dasar.

Fitur lanjutan seperti WhatsApp gateway, OCR, tanda tangan digital, dan notifikasi email kompleks tidak wajib untuk MVP.

---

## 14. Catatan Penting untuk Developer

1. Jangan membuat tampilan seperti CRUD biasa.
2. Jangan membuka semua menu mahasiswa dari awal.
3. Jangan menaruh file upload di public.
4. Jangan hardcode konfigurasi.
5. Jangan menulis validasi Bahasa Inggris.
6. Jangan membuat controller terlalu gemuk.
7. Jangan membuat tabel tanpa mempertimbangkan periode.
8. Jangan menghapus data penting secara permanen.
9. Jangan mengabaikan mobile responsive.
10. Jangan lupa update `docs/11-progress.md`.

---

## 15. Status Project

Status awal:

```text
Tahap perencanaan dan dokumentasi awal.
```

Langkah awal yang harus dilakukan:

```text
1. Finalisasi AGENTS.md.
2. Finalisasi README.md.
3. Finalisasi templates.md.
4. Finalisasi semua docs.
5. Setup CodeIgniter 4.
6. Setup .env dan database.
7. Setup layout utama.
8. Setup auth dan role.
9. Mulai implementasi workflow dasar.
```
