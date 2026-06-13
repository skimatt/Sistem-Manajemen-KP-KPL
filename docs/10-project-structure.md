# docs/10-project-structure.md

# Struktur Project CodeIgniter 4

## 1. Tujuan Dokumen

Dokumen ini menjelaskan struktur folder project CodeIgniter 4 yang digunakan untuk Sistem Manajemen KP/KPL.

Dokumen ini wajib dijadikan acuan saat agent membuat file, folder, controller, model, service, view, partials, components, upload storage, generated document, dan konfigurasi project.

Tujuannya agar struktur project rapi, mudah dipelihara, mudah dikembangkan, dan tidak berantakan saat fitur bertambah.

---

## 2. Prinsip Struktur Project

Prinsip utama:

1. Gunakan struktur standar CodeIgniter 4.
2. Pisahkan controller berdasarkan role.
3. Jangan membuat satu controller besar untuk semua fitur.
4. Jangan menaruh logic bisnis berat di controller.
5. Logic utama masuk ke folder `Services`.
6. View harus menggunakan `layouts`, `partials`, dan `components`.
7. File upload disimpan di `writable/uploads`.
8. File hasil generate PDF disimpan di `writable/generated`.
9. File tidak boleh disimpan langsung di `public`.
10. Aset frontend custom disimpan di `public/assets`.
11. Konfigurasi penting disimpan di `.env`.
12. URL harus bersih tanpa `index.php`.
13. Dokumentasi project disimpan di root dan folder `docs`.

---

## 3. Struktur Root Project

Struktur utama project:

```text id="rztcod"
root-project/
├── app/
├── public/
├── writable/
├── tests/
├── vendor/
├── docs/
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
├── spark
├── AGENTS.md
├── README.md
└── templates.md
```

Fungsi utama:

| Folder/File    | Fungsi                                               |
| -------------- | ---------------------------------------------------- |
| `app/`         | Source code utama aplikasi.                          |
| `public/`      | Document root, assets publik, dan `.htaccess`.       |
| `writable/`    | Storage upload, generated PDF, cache, logs, session. |
| `tests/`       | Testing jika digunakan.                              |
| `vendor/`      | Dependency Composer.                                 |
| `docs/`        | Dokumentasi teknis project.                          |
| `.env`         | Konfigurasi lokal project.                           |
| `.env.example` | Contoh konfigurasi tanpa credential asli.            |
| `AGENTS.md`    | Instruksi utama untuk AI agent.                      |
| `README.md`    | Ringkasan project.                                   |
| `templates.md` | Template standar dokumentasi.                        |

---

## 4. Struktur Dokumentasi

Struktur dokumentasi:

```text id="b91qpk"
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

Aturan:

1. Jangan menghapus file dokumentasi.
2. Jangan membuat file dokumentasi baru tanpa alasan jelas.
3. Jika struktur folder berubah, update file ini.
4. Jika progress berubah, update `docs/11-progress.md`.
5. Jika ada keputusan teknis baru, catat di dokumen yang sesuai.

---

## 5. Struktur Folder `app`

Struktur utama folder `app`:

```text id="of38cl"
app/
├── Config/
├── Controllers/
├── Database/
│   ├── Migrations/
│   └── Seeds/
├── Filters/
├── Helpers/
├── Language/
│   └── id/
├── Libraries/
├── Models/
├── Services/
├── Validation/
└── Views/
```

Fungsi:

| Folder                 | Fungsi                                                 |
| ---------------------- | ------------------------------------------------------ |
| `Config/`              | Konfigurasi CI4, routes, filters, validation, sidebar. |
| `Controllers/`         | Controller request/response.                           |
| `Database/Migrations/` | File migration database.                               |
| `Database/Seeds/`      | Seeder data awal.                                      |
| `Filters/`             | Auth, role, workflow filter.                           |
| `Helpers/`             | Helper kecil untuk status, sidebar, dokumen.           |
| `Language/id/`         | Pesan validasi Bahasa Indonesia.                       |
| `Libraries/`           | Library custom jika diperlukan.                        |
| `Models/`              | Model database.                                        |
| `Services/`            | Logic bisnis utama.                                    |
| `Validation/`          | Custom validation rules.                               |
| `Views/`               | Tampilan aplikasi.                                     |

---

## 6. Struktur Folder `app/Config`

Struktur:

```text id="d6iy2c"
app/Config/
├── App.php
├── Routes.php
├── Filters.php
├── Validation.php
├── Database.php
├── Sidebar.php
├── Workflow.php
└── Constants.php
```

Fungsi:

| File             | Fungsi                                     |
| ---------------- | ------------------------------------------ |
| `App.php`        | Konfigurasi base URL dan indexPage.        |
| `Routes.php`     | Routing aplikasi.                          |
| `Filters.php`    | Registrasi filter.                         |
| `Validation.php` | Konfigurasi validasi.                      |
| `Database.php`   | Konfigurasi database dari `.env`.          |
| `Sidebar.php`    | Konfigurasi menu sidebar berdasarkan role. |
| `Workflow.php`   | Konfigurasi status dan akses workflow.     |
| `Constants.php`  | Konstanta umum jika diperlukan.            |

Aturan:

1. Jangan hardcode credential.
2. Gunakan `.env` untuk konfigurasi penting.
3. Sidebar sebaiknya dikelola lewat `Sidebar.php`.
4. Workflow access boleh dikelola lewat `Workflow.php`.
5. Jangan mengubah file config tanpa alasan jelas.

---

## 7. Struktur Folder `app/Controllers`

Controller dipisahkan berdasarkan role dan modul.

Struktur:

```text id="7hif8o"
app/Controllers/
├── BaseController.php
├── Auth/
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── GoogleAuthController.php
│   ├── ForgotPasswordController.php
│   └── LogoutController.php
├── Admin/
│   ├── DashboardController.php
│   ├── UserController.php
│   ├── StudentController.php
│   ├── LecturerController.php
│   ├── InstitutionController.php
│   ├── PeriodController.php
│   ├── FormBuilderController.php
│   ├── DocumentTemplateController.php
│   ├── DocumentVerificationController.php
│   ├── TopsisController.php
│   ├── ReportController.php
│   ├── AuditLogController.php
│   └── SettingController.php
├── Koordinator/
│   ├── DashboardController.php
│   ├── RegistrationValidationController.php
│   ├── PlacementValidationController.php
│   ├── TopsisReviewController.php
│   ├── SupervisorAssignmentController.php
│   ├── MonitoringController.php
│   ├── ScoreValidationController.php
│   ├── ArchiveController.php
│   └── ReportController.php
├── Mahasiswa/
│   ├── DashboardController.php
│   ├── ProfileController.php
│   ├── RegistrationController.php
│   ├── PlacementController.php
│   ├── DocumentController.php
│   ├── SupervisorController.php
│   ├── LogbookController.php
│   ├── FinalReportController.php
│   ├── ScoreController.php
│   └── HistoryController.php
├── Dosen/
│   ├── DashboardController.php
│   ├── StudentGuidanceController.php
│   ├── LogbookReviewController.php
│   ├── FinalReportReviewController.php
│   ├── AssessmentController.php
│   └── HistoryController.php
├── Instansi/
│   ├── DashboardController.php
│   ├── ProfileController.php
│   ├── StudentController.php
│   ├── AcceptanceController.php
│   ├── LogbookController.php
│   ├── AssessmentController.php
│   └── HistoryController.php
└── DownloadController.php
```

Aturan controller:

1. Controller hanya mengatur request, response, redirect, dan pemanggilan service.
2. Jangan menaruh logic TOPSIS langsung di controller.
3. Jangan menaruh logic workflow besar di controller.
4. Jangan menaruh logic generate PDF langsung di controller.
5. Jangan membuat controller terlalu besar.
6. Gunakan nama controller sesuai fungsi.
7. Download file harus lewat `DownloadController` atau controller khusus yang memeriksa hak akses.

---

## 8. Struktur Folder `app/Models`

Model disesuaikan dengan tabel database.

Struktur disarankan:

```text id="lch65v"
app/Models/
├── UserModel.php
├── StudentProfileModel.php
├── LecturerProfileModel.php
├── InstitutionProfileModel.php
├── StudyProgramModel.php
├── KpPeriodModel.php
├── KpRegistrationModel.php
├── RegistrationStatusLogModel.php
├── FormTemplateModel.php
├── FormFieldModel.php
├── FormResponseModel.php
├── DocumentTemplateModel.php
├── DocumentRequirementModel.php
├── StudentDocumentModel.php
├── GeneratedDocumentModel.php
├── PlacementRequestModel.php
├── PlacementChoiceModel.php
├── InstitutionQuotaModel.php
├── TopsisCriteriaModel.php
├── TopsisWeightModel.php
├── TopsisScoreModel.php
├── TopsisResultModel.php
├── SupervisorAssignmentModel.php
├── LogbookWeekModel.php
├── LogbookDailyEntryModel.php
├── LogbookReviewModel.php
├── FinalReportModel.php
├── AssessmentTemplateModel.php
├── AssessmentComponentModel.php
├── AssessmentScoreModel.php
├── FinalScoreModel.php
├── NotificationModel.php
├── AuditLogModel.php
└── ArchiveCorrectionModel.php
```

Aturan model:

1. Model hanya fokus pada akses database.
2. Jangan menaruh business rules besar di model.
3. Gunakan `$allowedFields`.
4. Gunakan timestamps.
5. Gunakan soft delete untuk data penting.
6. Query kompleks boleh dibuat sebagai method model jika masih terkait data.
7. Logic lintas tabel lebih baik masuk service.

---

## 9. Struktur Folder `app/Services`

Services berisi logic utama.

Struktur:

```text id="ay0ewc"
app/Services/
├── AuthService.php
├── WorkflowService.php
├── TopsisService.php
├── DocumentService.php
├── UploadService.php
├── NotificationService.php
├── ArchiveService.php
├── AuditService.php
├── RegistrationService.php
├── PlacementService.php
├── SupervisorService.php
├── LogbookService.php
├── AssessmentService.php
├── FinalScoreService.php
└── ExportService.php
```

Fungsi:

| Service               | Fungsi                                |
| --------------------- | ------------------------------------- |
| `AuthService`         | Login, logout, Google login, session. |
| `WorkflowService`     | Status, akses tahap, aksi berikutnya. |
| `TopsisService`       | Perhitungan rekomendasi TOPSIS.       |
| `DocumentService`     | Generate dokumen PDF.                 |
| `UploadService`       | Upload, validasi file, penamaan file. |
| `NotificationService` | Notifikasi user.                      |
| `ArchiveService`      | Tutup dan arsip periode.              |
| `AuditService`        | Audit log aksi penting.               |
| `RegistrationService` | Registrasi dan verifikasi mahasiswa.  |
| `PlacementService`    | Penempatan mitra/mandiri.             |
| `SupervisorService`   | Penetapan dosen pembimbing.           |
| `LogbookService`      | Logbook mingguan dan review.          |
| `AssessmentService`   | Nilai instansi/dosen.                 |
| `FinalScoreService`   | Hitung dan validasi nilai akhir.      |
| `ExportService`       | Export Excel/CSV/PDF rekap.           |

Aturan service:

1. Logic bisnis utama wajib masuk service.
2. Controller memanggil service.
3. Service boleh memanggil beberapa model.
4. Service yang mengubah status harus memanggil `WorkflowService`.
5. Service aksi penting harus memanggil `AuditService`.
6. Service yang menghasilkan notifikasi harus memanggil `NotificationService`.

---

## 10. Struktur Folder `app/Filters`

Struktur:

```text id="zbc0lb"
app/Filters/
├── AuthFilter.php
├── RoleFilter.php
├── WorkflowAccessFilter.php
└── GuestFilter.php
```

Fungsi:

| Filter                 | Fungsi                                                 |
| ---------------------- | ------------------------------------------------------ |
| `AuthFilter`           | Memastikan user login.                                 |
| `RoleFilter`           | Memastikan user sesuai role.                           |
| `WorkflowAccessFilter` | Memastikan mahasiswa boleh membuka menu sesuai status. |
| `GuestFilter`          | Mencegah user login membuka halaman auth.              |

Aturan:

1. Semua route dashboard wajib memakai `AuthFilter`.
2. Semua route role wajib memakai `RoleFilter`.
3. Route mahasiswa yang step-based wajib memakai `WorkflowAccessFilter`.
4. Jika akses ditolak, tampilkan pesan Bahasa Indonesia.
5. Jangan hanya mengandalkan sidebar lock.

---

## 11. Struktur Folder `app/Helpers`

Struktur:

```text id="ux1r59"
app/Helpers/
├── status_helper.php
├── sidebar_helper.php
├── document_helper.php
├── file_helper.php
├── date_helper.php
└── format_helper.php
```

Fungsi:

| Helper                | Fungsi                                    |
| --------------------- | ----------------------------------------- |
| `status_helper.php`   | Label status, warna badge, urutan status. |
| `sidebar_helper.php`  | Render/olah menu sidebar.                 |
| `document_helper.php` | Format kode dokumen, placeholder dokumen. |
| `file_helper.php`     | Format ukuran file, icon file.            |
| `date_helper.php`     | Format tanggal Indonesia.                 |
| `format_helper.php`   | Format umum seperti angka, nilai, NPM.    |

Aturan:

1. Helper hanya berisi fungsi kecil.
2. Jangan menaruh logic bisnis besar di helper.
3. Logic workflow tetap di `WorkflowService`.

---

## 12. Struktur Folder `app/Validation`

Struktur:

```text id="n6p4rs"
app/Validation/
└── CustomRules.php
```

Fungsi `CustomRules.php`:

1. Validasi NPM.
2. Validasi IPK.
3. Validasi SKS.
4. Validasi file dokumen.
5. Validasi status workflow.
6. Validasi kuota instansi.
7. Validasi kuota dosen.

Aturan:

1. Pesan validasi harus Bahasa Indonesia.
2. Validasi utama tetap di backend.
3. Jangan hanya mengandalkan JavaScript frontend.

---

## 13. Struktur Folder `app/Language`

Struktur:

```text id="kp2spw"
app/Language/
└── id/
    ├── Validation.php
    ├── Auth.php
    ├── Workflow.php
    └── Notification.php
```

Fungsi:

| File               | Fungsi                           |
| ------------------ | -------------------------------- |
| `Validation.php`   | Pesan validasi Bahasa Indonesia. |
| `Auth.php`         | Pesan login, logout, akun.       |
| `Workflow.php`     | Pesan akses tahap/menu.          |
| `Notification.php` | Pesan notifikasi sistem.         |

Aturan:

1. Jangan tampilkan pesan error Bahasa Inggris.
2. Pesan harus jelas dan mudah dipahami.
3. Hindari pesan teknis untuk user awam.

---

## 14. Struktur Folder `app/Views`

Struktur utama:

```text id="ya1jxd"
app/Views/
├── layouts/
├── partials/
├── components/
├── auth/
├── admin/
├── koordinator/
├── mahasiswa/
├── dosen/
├── instansi/
├── pdf/
└── errors/
```

Fungsi:

| Folder         | Fungsi                                |
| -------------- | ------------------------------------- |
| `layouts/`     | Layout utama dashboard/auth/guest.    |
| `partials/`    | Bagian layout yang dipakai ulang.     |
| `components/`  | Komponen UI kecil.                    |
| `auth/`        | View login, register, reset password. |
| `admin/`       | View role Admin.                      |
| `koordinator/` | View role Koordinator.                |
| `mahasiswa/`   | View role Mahasiswa.                  |
| `dosen/`       | View role Dosen.                      |
| `instansi/`    | View role Instansi.                   |
| `pdf/`         | Template HTML untuk PDF.              |
| `errors/`      | Halaman error custom.                 |

---

## 15. Struktur `app/Views/layouts`

Struktur:

```text id="h1vsq7"
app/Views/layouts/
├── app.php
├── auth.php
└── guest.php
```

Fungsi:

| File        | Fungsi                             |
| ----------- | ---------------------------------- |
| `app.php`   | Layout utama dashboard semua role. |
| `auth.php`  | Layout halaman login/register.     |
| `guest.php` | Layout halaman publik jika ada.    |

Aturan:

1. Semua role dashboard memakai `layouts/app.php`.
2. Jangan membuat layout dashboard berbeda untuk setiap role.
3. Perbedaan role berada di sidebar dan content.

---

## 16. Struktur `app/Views/partials`

Struktur:

```text id="pnlics"
app/Views/partials/
├── head.php
├── sidebar.php
├── topbar.php
├── footer.php
├── scripts.php
├── flash-message.php
└── breadcrumb.php
```

Fungsi:

| File                | Fungsi                                                   |
| ------------------- | -------------------------------------------------------- |
| `head.php`          | Meta, font, Tailwind CDN, icons, CSS.                    |
| `sidebar.php`       | Sidebar role-based dan workflow-aware.                   |
| `topbar.php`        | Header atas dashboard.                                   |
| `footer.php`        | Footer global.                                           |
| `scripts.php`       | Alpine, SweetAlert, script global, stack script halaman. |
| `flash-message.php` | Pesan session.                                           |
| `breadcrumb.php`    | Breadcrumb halaman.                                      |

Aturan:

1. Sidebar tidak boleh diulang di setiap halaman.
2. Topbar tidak boleh diulang di setiap halaman.
3. Footer tidak boleh diulang di setiap halaman.
4. CDN global dikelola di partial.

---

## 17. Struktur `app/Views/components`

Struktur:

```text id="5vf420"
app/Views/components/
├── stat-card.php
├── status-badge.php
├── progress-stepper.php
├── locked-menu.php
├── empty-state.php
├── data-table.php
├── form-field.php
├── page-header.php
├── section-card.php
├── action-card.php
├── timeline.php
└── mobile-card-list.php
```

Aturan:

1. Komponen UI berulang wajib dibuat reusable.
2. Jangan copy-paste card yang sama di banyak halaman.
3. Komponen harus compact dan responsive.
4. Komponen status harus memakai label Bahasa Indonesia.
5. Komponen locked menu wajib mendukung pesan alasan.

---

## 18. Struktur View Role Admin

Struktur:

```text id="qu5nuz"
app/Views/admin/
├── dashboard/
│   └── index.php
├── users/
├── students/
├── lecturers/
├── institutions/
├── periods/
├── forms/
├── documents/
├── topsis/
├── reports/
├── audit-logs/
└── settings/
```

Aturan:

1. View Admin fokus pada data master dan administrasi.
2. Gunakan tabel compact.
3. Gunakan filter periode/status.
4. Gunakan DataTables hanya jika data banyak.

---

## 19. Struktur View Role Koordinator

Struktur:

```text id="bmglzt"
app/Views/koordinator/
├── dashboard/
│   └── index.php
├── registrations/
├── placements/
├── topsis/
├── supervisors/
├── monitoring/
├── scores/
├── archives/
└── reports/
```

Aturan:

1. View Koordinator fokus pada keputusan.
2. Tampilkan data yang perlu tindakan.
3. Setiap keputusan harus punya modal konfirmasi.
4. Catatan wajib untuk revisi/tolak.

---

## 20. Struktur View Role Mahasiswa

Struktur:

```text id="kgg9nd"
app/Views/mahasiswa/
├── dashboard/
│   └── index.php
├── profile/
├── registration/
├── placement/
├── documents/
├── supervisor/
├── logbook/
├── final-report/
├── score/
├── history/
└── notifications/
```

Aturan:

1. View Mahasiswa harus mobile-friendly.
2. Dashboard wajib menampilkan progress stepper.
3. Menu terkunci harus diberi alasan.
4. Logbook harus dibuat nyaman untuk HP.
5. Jangan memakai tabel sempit untuk input logbook.

---

## 21. Struktur View Role Dosen

Struktur:

```text id="qwyl63"
app/Views/dosen/
├── dashboard/
│   └── index.php
├── guidance-students/
├── logbook-reviews/
├── final-reports/
├── assessments/
├── history/
└── notifications/
```

Aturan:

1. Dosen hanya melihat mahasiswa bimbingannya.
2. View fokus pada review logbook, laporan, dan nilai.
3. Tampilkan task yang perlu dilakukan dosen.

---

## 22. Struktur View Role Instansi

Struktur:

```text id="cx46gz"
app/Views/instansi/
├── dashboard/
│   └── index.php
├── profile/
├── students/
├── acceptances/
├── logbooks/
├── assessments/
├── history/
├── documents/
└── notifications/
```

Aturan:

1. Instansi hanya melihat mahasiswa di instansinya.
2. Untuk MVP, akun instansi fokus untuk mitra resmi.
3. Tempat mandiri tidak wajib memiliki akun.

---

## 23. Struktur View PDF

Template PDF dipisahkan dari view dashboard.

Struktur:

```text id="7dargr"
app/Views/pdf/
├── layouts/
│   └── document.php
├── lampiran-a/
│   └── registration-form.php
├── recommendation/
│   └── advisor-recommendation.php
├── letters/
│   ├── request-letter.php
│   ├── introduction-letter.php
│   └── supervisor-assignment-letter.php
├── institution/
│   ├── acceptance-form.php
│   └── assessment-form.php
└── scores/
    └── final-score-report.php
```

Aturan:

1. Template PDF tidak boleh dicampur dengan view dashboard.
2. Template PDF harus mengikuti format dokumen kampus.
3. Gunakan `DocumentService` untuk generate.
4. Hasil generate disimpan di `writable/generated`.

---

## 24. Struktur Folder `public`

Struktur:

```text id="obfwug"
public/
├── assets/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   ├── sidebar.js
│   │   ├── alerts.js
│   │   ├── form.js
│   │   └── charts.js
│   └── images/
│       ├── logo.png
│       ├── logo-sm.png
│       └── placeholder.png
├── favicon.ico
├── index.php
└── .htaccess
```

Aturan:

1. `public` adalah document root.
2. File upload mahasiswa tidak boleh disimpan di `public`.
3. Hanya assets publik yang boleh masuk `public`.
4. File CSS/JS custom diletakkan di `public/assets`.
5. `.htaccess` wajib ada untuk clean URL.

---

## 25. Struktur Folder `public/assets/css`

Struktur:

```text id="zvbcxn"
public/assets/css/
└── app.css
```

Fungsi `app.css`:

1. Custom CSS kecil.
2. Override kecil untuk DataTables.
3. Override kecil untuk Tom Select.
4. Styling scrollbar jika dibutuhkan.
5. Styling print khusus jika tidak masuk PDF.

Aturan:

1. Jangan menulis CSS terlalu banyak.
2. Gunakan Tailwind utility terlebih dahulu.
3. CSS custom hanya untuk kebutuhan yang sulit dengan utility.

---

## 26. Struktur Folder `public/assets/js`

Struktur:

```text id="er2z55"
public/assets/js/
├── app.js
├── sidebar.js
├── alerts.js
├── form.js
└── charts.js
```

Fungsi:

| File         | Fungsi                                              |
| ------------ | --------------------------------------------------- |
| `app.js`     | Helper global.                                      |
| `sidebar.js` | Sidebar collapse/drawer jika diperlukan.            |
| `alerts.js`  | SweetAlert flashdata dan toast.                     |
| `form.js`    | Preview upload, disable double submit, helper form. |
| `charts.js`  | Helper Chart.js.                                    |

Aturan:

1. Jangan menulis JavaScript besar di view.
2. Jangan menaruh logic bisnis di JS frontend.
3. Gunakan JS hanya untuk UX.
4. Backend tetap wajib validasi.

---

## 27. Struktur Folder `writable`

Struktur utama:

```text id="lci7pf"
writable/
├── uploads/
├── generated/
├── cache/
├── logs/
├── session/
└── temp/
```

Fungsi:

| Folder       | Fungsi                             |
| ------------ | ---------------------------------- |
| `uploads/`   | File upload dari user.             |
| `generated/` | File hasil generate sistem.        |
| `cache/`     | Cache CI4.                         |
| `logs/`      | Log aplikasi.                      |
| `session/`   | Session jika memakai file session. |
| `temp/`      | File sementara.                    |

Aturan:

1. File sensitif disimpan di `writable`.
2. Jangan simpan upload di `public`.
3. Download file harus lewat controller.
4. File harus diperiksa hak akses sebelum dikirim.

---

## 28. Struktur Upload KP/KPL

Struktur upload:

```text id="4yh3n6"
writable/
└── uploads/
    └── kp-pkl/
        └── {tahun_periode}/
            └── {prodi}/
                └── {npm}/
                    ├── registrasi/
                    ├── rekomendasi-pa/
                    ├── penempatan/
                    ├── surat/
                    ├── penerimaan-instansi/
                    ├── logbook/
                    ├── laporan-akhir/
                    ├── penilaian/
                    └── arsip/
```

Contoh:

```text id="2u7p7r"
writable/uploads/kp-pkl/2026/informatika/235520110141/registrasi/bukti_pembayaran.pdf
```

Aturan:

1. Folder dibuat otomatis oleh `UploadService`.
2. Nama folder memakai tahun periode, prodi, dan NPM.
3. Nama file diganti otomatis.
4. File lama tidak ditimpa.
5. Versi dokumen disimpan di database.

---

## 29. Struktur Generated Documents

Struktur generated document:

```text id="h15h3l"
writable/
└── generated/
    └── documents/
        └── {tahun_periode}/
            └── {npm}/
                ├── lampiran-a/
                ├── surat-pengantar/
                ├── surat-permohonan/
                ├── surat-tugas-pembimbing/
                ├── form-penilaian/
                └── rekap-nilai/
```

Contoh:

```text id="nf3scx"
writable/generated/documents/2026/235520110141/surat-pengantar/surat_pengantar_20260612.pdf
```

Aturan:

1. Folder dibuat otomatis oleh `DocumentService`.
2. Dokumen generated disimpan sebagai PDF.
3. Metadata dokumen disimpan di `generated_documents`.
4. Jika dokumen digenerate ulang, buat versi baru.
5. Jangan hapus dokumen generated yang sudah digunakan.

---

## 30. Penamaan File Upload

Format nama file:

```text id="v0h2sn"
{npm}_{jenis_dokumen}_{periode}_{timestamp}.{ext}
```

Contoh:

```text id="y4f9e2"
235520110141_bukti_pembayaran_2026_20260612_101530.pdf
235520110141_surat_rekomendasi_pa_2026_20260612_101800.pdf
235520110141_surat_penerimaan_instansi_2026_20260615_090200.pdf
```

Aturan:

1. Jangan gunakan nama asli sebagai nama simpan.
2. Nama asli boleh disimpan di database sebagai metadata.
3. Nama file harus aman.
4. Hindari spasi.
5. Hindari karakter aneh.
6. Jangan overwrite file lama.

---

## 31. `.env`

Gunakan `.env` untuk konfigurasi lokal.

Contoh:

```env id="1yltey"
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

Aturan:

1. Jangan commit `.env` berisi credential asli.
2. Buat `.env.example`.
3. Jangan hardcode database.
4. Jangan hardcode Google secret.
5. Jangan hardcode baseURL.

---

## 32. `.env.example`

Struktur `.env.example`:

```env id="j0jhz4"
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

google.clientID = your-google-client-id
google.clientSecret = your-google-client-secret
google.redirectURI = http://localhost:8080/auth/google/callback
```

Aturan:

1. `.env.example` boleh dicommit.
2. `.env.example` tidak boleh berisi credential asli.
3. Developer menyalin `.env.example` menjadi `.env`.

---

## 33. `.gitignore`

Pastikan `.gitignore` memuat:

```text id="o5s71w"
.env
vendor/
writable/cache/*
writable/logs/*
writable/session/*
writable/uploads/*
writable/generated/*
!writable/cache/index.html
!writable/logs/index.html
!writable/session/index.html
!writable/uploads/.gitkeep
!writable/generated/.gitkeep
```

Aturan:

1. Jangan commit file upload user.
2. Jangan commit generated PDF.
3. Jangan commit log.
4. Jangan commit session.
5. Jangan commit credential `.env`.
6. Boleh simpan `.gitkeep` agar folder tetap ada.

---

## 34. Clean URL Tanpa `index.php`

URL harus bersih.

Target:

```text id="h0xikb"
/login
/dashboard
/mahasiswa/registrasi
/mahasiswa/logbook
/admin/data-mahasiswa
/koordinator/validasi-registrasi
```

Bukan:

```text id="myesqe"
/index.php/login
/index.php/dashboard
```

Aturan:

1. Document root server harus diarahkan ke folder `public`.
2. `.htaccess` di folder `public` harus aktif.
3. Apache `mod_rewrite` harus aktif.
4. `AllowOverride All` harus aktif jika memakai Apache.
5. `indexPage` harus kosong.
6. `baseURL` harus benar.

Config:

```php id="zgn04k"
public string $indexPage = '';
```

`.env`:

```env id="g4wxa8"
app.indexPage = ''
```

---

## 35. Routing

Gunakan route group per role.

Contoh struktur:

```php id="m5g8cr"
$routes->get('/', 'Auth\LoginController::index');

$routes->group('auth', static function ($routes) {
    $routes->get('login', 'Auth\LoginController::index');
    $routes->post('login', 'Auth\LoginController::attempt');
    $routes->get('logout', 'Auth\LogoutController::index');
    $routes->get('google', 'Auth\GoogleAuthController::redirect');
    $routes->get('google/callback', 'Auth\GoogleAuthController::callback');
});

$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
});

$routes->group('koordinator', ['filter' => 'role:koordinator'], static function ($routes) {
    $routes->get('dashboard', 'Koordinator\DashboardController::index');
});

$routes->group('mahasiswa', ['filter' => 'role:mahasiswa'], static function ($routes) {
    $routes->get('dashboard', 'Mahasiswa\DashboardController::index');
});

$routes->group('dosen', ['filter' => 'role:dosen'], static function ($routes) {
    $routes->get('dashboard', 'Dosen\DashboardController::index');
});

$routes->group('instansi', ['filter' => 'role:instansi'], static function ($routes) {
    $routes->get('dashboard', 'Instansi\DashboardController::index');
});
```

Aturan:

1. Jangan membuat route acak.
2. Gunakan prefix role.
3. Gunakan nama controller sesuai modul.
4. Route mahasiswa yang step-based harus memakai workflow filter.
5. Download route harus memeriksa hak akses.

---

## 36. Migration

Folder migration:

```text id="66yxyi"
app/Database/Migrations/
```

Contoh migration:

```text id="5g51nl"
2026-01-01-000001_CreateUsersTable.php
2026-01-01-000002_CreateProfilesTables.php
2026-01-01-000003_CreatePeriodsAndRegistrationsTables.php
2026-01-01-000004_CreateDocumentsTables.php
2026-01-01-000005_CreatePlacementTables.php
2026-01-01-000006_CreateTopsisTables.php
2026-01-01-000007_CreateSupervisorTables.php
2026-01-01-000008_CreateLogbookTables.php
2026-01-01-000009_CreateAssessmentTables.php
2026-01-01-000010_CreateAuditAndNotificationTables.php
```

Aturan:

1. Gunakan migration untuk struktur database.
2. Jangan mengubah migration lama setelah dipakai production.
3. Buat migration baru untuk perubahan struktur.
4. Nama migration harus jelas.
5. Jalankan migration lewat `php spark migrate`.

---

## 37. Seeder

Folder seeder:

```text id="ypshlf"
app/Database/Seeds/
```

Seeder awal:

```text id="699lf1"
AdminSeeder.php
StudyProgramSeeder.php
TopsisCriteriaSeeder.php
DocumentTemplateSeeder.php
AssessmentTemplateSeeder.php
PeriodSeeder.php
```

Aturan:

1. Seeder digunakan untuk data awal.
2. Jangan isi data dummy terlalu banyak di production.
3. Password seeder admin harus bisa diganti.
4. Data awal harus realistis untuk demo.

---

## 38. Testing

Folder testing:

```text id="9kxd9n"
tests/
```

Testing minimal:

1. Auth login.
2. Role access.
3. Workflow access.
4. Registrasi.
5. Upload dokumen.
6. Penempatan.
7. TOPSIS.
8. Logbook.
9. Penilaian.
10. Arsip.

Jenis testing yang cocok untuk skripsi:

```text id="4v0xwd"
Black Box Testing
```

---

## 39. Composer Dependency

Dependency yang disarankan:

```bash id="wcb8u9"
composer require dompdf/dompdf
composer require phpoffice/phpspreadsheet
```

Jika memakai Google Client PHP:

```bash id="z9o2bf"
composer require google/apiclient
```

Aturan:

1. Jangan install package tanpa kebutuhan.
2. Catat package yang dipakai di README.
3. Jangan commit folder `vendor`.
4. Jalankan `composer install` setelah clone.

---

## 40. Hak Akses File Download

Download file harus melalui route/controller.

Contoh route:

```text id="wrhgsz"
/download/document/{uuid}
/download/generated/{uuid}
```

Aturan:

1. User harus login.
2. Sistem cek role.
3. Sistem cek kepemilikan/relasi data.
4. Mahasiswa hanya boleh file miliknya.
5. Dosen hanya boleh file mahasiswa bimbingannya.
6. Instansi hanya boleh file mahasiswa di instansinya.
7. Admin/Koordinator sesuai hak akses.
8. Jika tidak berhak, tolak dengan pesan Bahasa Indonesia.

---

## 41. Struktur untuk Maintenance

Agar mudah maintenance:

1. Semua keputusan teknis dicatat di docs.
2. Semua progress dicatat di `docs/11-progress.md`.
3. Logic tidak tersebar di controller.
4. View reusable memakai components.
5. Sidebar dikelola dari config/helper.
6. Status workflow dikelola dari config/service.
7. Upload dikelola oleh UploadService.
8. Dokumen dikelola oleh DocumentService.
9. TOPSIS dikelola oleh TopsisService.
10. Audit dikelola oleh AuditService.

---

## 42. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Membuat controller besar berisi semua logic.
2. Membuat semua view tanpa layout.
3. Mengulang sidebar di setiap file view.
4. Menaruh upload di public.
5. Menaruh generated PDF di public.
6. Mengakses file tanpa controller.
7. Menyimpan credential di source code.
8. Mengabaikan `.env`.
9. Mengabaikan `.gitignore`.
10. Membuat route acak tanpa group role.
11. Mengubah struktur folder tanpa update dokumen.
12. Mengubah migration lama setelah digunakan.
13. Menaruh template PDF di folder view dashboard biasa.
14. Menaruh logic TOPSIS di controller.
15. Menaruh logic workflow di helper kecil.
16. Menghapus data writable penting tanpa instruksi.

---

## 43. Ringkasan Final

Struktur project final:

```text id="1lpag6"
app/
├── Config
├── Controllers per role
├── Models
├── Services
├── Filters
├── Helpers
├── Validation
├── Language/id
└── Views layouts/partials/components/role/pdf

public/
├── assets/css
├── assets/js
├── assets/images
├── index.php
└── .htaccess

writable/
├── uploads/kp-pkl
├── generated/documents
├── logs
├── cache
└── session

docs/
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

Project harus rapi dari awal agar mudah dikembangkan, mudah dijelaskan dalam skripsi, dan mudah dimaintenance setelah sistem selesai.
