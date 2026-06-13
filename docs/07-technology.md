# docs/07-technology.md

# Teknologi dan Stack Project

## 1. Tujuan Dokumen

Dokumen ini menjelaskan teknologi yang digunakan dalam Sistem Manajemen KP/KPL.

Dokumen ini menjadi acuan agar agent tidak sembarangan memilih framework, library, CDN, struktur backend, atau cara implementasi yang tidak sesuai.

Project ini menggunakan:

```text
Backend: CodeIgniter 4
Database: MySQL / MariaDB
Frontend: Tailwind CSS CDN
Interaksi UI: Alpine.js
Alert/Toast: SweetAlert2
Icon: Tabler Icons
PDF: Dompdf
Excel: PhpSpreadsheet
```

---

## 2. Prinsip Teknologi

Prinsip utama:

1. Gunakan teknologi yang sederhana, stabil, dan mudah dijelaskan untuk skripsi.
2. Jangan membuat stack terlalu kompleks.
3. Jangan mencampur banyak framework frontend.
4. Jangan menggunakan Bootstrap/Vuexy sebagai framework utama.
5. Jangan membuat aplikasi terasa seperti template admin CRUD biasa.
6. Gunakan Tailwind CSS untuk UI custom modern.
7. Gunakan CDN secara terkontrol.
8. Gunakan PHP service layer untuk logic penting.
9. Gunakan validasi backend CI4.
10. Gunakan `.env` untuk konfigurasi.
11. Gunakan migration dan seeder untuk database.
12. Gunakan folder `writable` untuk upload dan generated document.
13. Gunakan clean URL tanpa `index.php`.

---

## 3. Backend

Backend utama menggunakan:

```text
CodeIgniter 4
PHP 8.2+
Composer
```

### 3.1 Fungsi Backend

Backend bertanggung jawab untuk:

1. Auth dan session.
2. Role access.
3. Workflow access.
4. Validasi form.
5. Proses database.
6. Upload file.
7. Generate dokumen.
8. Perhitungan TOPSIS.
9. Perhitungan nilai akhir.
10. Audit log.
11. Export laporan.
12. Arsip periode.

### 3.2 Aturan Backend

1. Controller tidak boleh terlalu gemuk.
2. Logic utama harus masuk ke Services.
3. Model hanya fokus pada query dan akses database.
4. View tidak boleh berisi logic bisnis berat.
5. Semua input wajib divalidasi.
6. Semua aksi penting wajib masuk audit log.
7. Semua akses role wajib melewati filter.
8. Semua akses workflow mahasiswa wajib dicek backend.

---

## 4. Database

Database menggunakan:

```text
MySQL / MariaDB
```

Nama database default:

```text
db_kp_pkl
```

Alternatif jika ingin lebih formal:

```text
db_siman_kp_pkl
```

### 4.1 Aturan Database

1. Gunakan migration CI4.
2. Gunakan seeder untuk data awal.
3. Gunakan index pada kolom penting.
4. Gunakan soft delete untuk data penting.
5. Gunakan `uuid` untuk data yang tampil di URL.
6. Gunakan foreign key jika memungkinkan.
7. Jangan menyimpan file fisik di database.
8. Simpan metadata file dan path saja.
9. Bobot TOPSIS disimpan per periode.
10. Bobot nilai disimpan per periode.
11. Arsip tidak boleh diedit langsung.

---

## 5. Frontend

Frontend menggunakan:

```text
Tailwind CSS CDN
Alpine.js
SweetAlert2
Tabler Icons
Inter Font
```

Gaya tampilan:

```text
Modern SaaS Dashboard
Compact
Clean
Responsive
Mobile-friendly
Workflow-based
```

### 5.1 Aturan Frontend

1. Gunakan Tailwind CSS sebagai styling utama.
2. Jangan gunakan Bootstrap sebagai framework utama.
3. Jangan gunakan Vuexy sebagai dasar utama.
4. Vuexy hanya boleh menjadi inspirasi visual, bukan pondasi.
5. Jangan menggunakan emoji sebagai icon utama.
6. Gunakan Tabler Icons.
7. Gunakan Alpine.js untuk interaksi ringan.
8. Gunakan SweetAlert2 untuk alert dan toast.
9. Gunakan CDN secara terkontrol.
10. Jangan memasukkan semua CDN ke semua halaman.
11. Layout harus responsive.
12. Halaman logbook harus nyaman di mobile.

---

## 6. Tailwind CSS

Tailwind CSS digunakan sebagai framework styling utama.

Digunakan untuk:

1. Layout.
2. Sidebar.
3. Topbar.
4. Footer.
5. Dashboard card.
6. Form.
7. Button.
8. Badge.
9. Stepper.
10. Modal.
11. Table wrapper.
12. Responsive design.
13. Dark mode jika diperlukan.

### CDN Tailwind

Gunakan:

```html
<script src="https://cdn.tailwindcss.com"></script>
```

Konfigurasi dasar:

```html
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        fontFamily: {
          sans: ["Inter", "ui-sans-serif", "system-ui"],
        },
        colors: {
          primary: {
            50: "#eff6ff",
            100: "#dbeafe",
            500: "#3b82f6",
            600: "#2563eb",
            700: "#1d4ed8",
          },
        },
      },
    },
  };
</script>
```

### Catatan Production

Pada tahap pengembangan, demo, dan skripsi, Tailwind CDN boleh digunakan agar cepat.

Untuk production jangka panjang, Tailwind sebaiknya di-build menjadi file CSS statis agar lebih optimal.

---

## 7. Inter Font

Gunakan Inter sebagai font utama.

CDN:

```html
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
  rel="stylesheet"
/>
```

Aturan:

1. Gunakan Inter untuk seluruh dashboard.
2. Jangan menggunakan banyak font.
3. Gunakan ukuran font yang compact.
4. Hindari heading terlalu besar.
5. Pastikan teks tetap terbaca di mobile.

---

## 8. Tabler Icons

Icon utama menggunakan Tabler Icons Webfont.

CDN:

```html
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"
/>
```

Contoh penggunaan:

```html
<i class="ti ti-layout-dashboard"></i>
<i class="ti ti-user"></i>
<i class="ti ti-file-text"></i>
<i class="ti ti-lock"></i>
<i class="ti ti-book"></i>
<i class="ti ti-bell"></i>
```

Aturan:

1. Gunakan Tabler Icons untuk sidebar.
2. Gunakan Tabler Icons untuk card dashboard.
3. Gunakan icon lock/gembok untuk locked menu.
4. Jangan menggunakan emoji sebagai icon utama.
5. Jangan mencampur terlalu banyak icon library.

---

## 9. Alpine.js

Alpine.js digunakan untuk interaksi ringan di frontend.

CDN:

```html
<script
  defer
  src="https://cdn.jsdelivr.net/npm/alpinejs@latest/dist/cdn.min.js"
></script>
```

Digunakan untuk:

1. Sidebar collapse.
2. Mobile drawer.
3. Dropdown profil.
4. Dropdown notifikasi.
5. Toggle dark mode.
6. Tab sederhana.
7. Show/hide filter.
8. Modal sederhana.
9. Preview UI ringan.

Aturan:

1. Gunakan Alpine.js untuk interaksi sederhana.
2. Jangan menggunakan React/Vue hanya untuk dashboard ini.
3. Jangan menulis JavaScript terlalu banyak di view.
4. Script reusable letakkan di `public/assets/js`.
5. Jangan membuat logic bisnis di JavaScript frontend.

---

## 10. SweetAlert2

SweetAlert2 digunakan untuk alert, toast, dan konfirmasi aksi penting.

CDN:

```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest"></script>
```

Digunakan untuk:

1. Toast sukses.
2. Toast error.
3. Alert warning.
4. Konfirmasi hapus.
5. Konfirmasi setujui.
6. Konfirmasi tolak.
7. Konfirmasi tutup periode.
8. Pesan locked menu.

Contoh pesan:

```text
Registrasi berhasil dikirim.
Dokumen berhasil diverifikasi.
Yakin ingin menyetujui registrasi ini?
Menu ini belum dapat dibuka karena registrasi Anda belum disetujui.
```

Aturan:

1. Jangan gunakan `alert()` bawaan JavaScript.
2. Semua pesan harus Bahasa Indonesia.
3. Gunakan toast untuk aksi ringan.
4. Gunakan modal konfirmasi untuk aksi penting.
5. Aksi penting tetap divalidasi backend.

---

## 11. DataTables

DataTables digunakan untuk halaman data besar.

CDN:

```html
<link
  rel="stylesheet"
  href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css"
/>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
```

Digunakan untuk:

1. Data mahasiswa.
2. Data dosen.
3. Data instansi.
4. Data registrasi.
5. Data penempatan.
6. Data logbook.
7. Data nilai.
8. Data arsip.
9. Audit log.

Aturan:

1. Gunakan DataTables hanya pada halaman yang membutuhkan tabel besar.
2. Jangan gunakan DataTables pada halaman mahasiswa yang sederhana.
3. Untuk data besar, gunakan server-side processing.
4. Di mobile, tabel harus responsive atau berubah menjadi card list.
5. Jangan menampilkan terlalu banyak kolom sekaligus.
6. Status harus memakai badge, bukan raw text panjang.

Catatan:

Jika DataTables membutuhkan jQuery untuk fitur tertentu, jQuery boleh dimuat hanya di halaman yang menggunakan DataTables.

---

## 12. Tom Select

Tom Select digunakan untuk dropdown pencarian modern.

CDN:

```html
<link
  href="https://cdn.jsdelivr.net/npm/tom-select@latest/dist/css/tom-select.css"
  rel="stylesheet"
/>
<script src="https://cdn.jsdelivr.net/npm/tom-select@latest/dist/js/tom-select.complete.min.js"></script>
```

Digunakan untuk:

1. Pilih dosen pembimbing.
2. Pilih instansi mitra.
3. Pilih mahasiswa.
4. Pilih program studi.
5. Pilih periode.
6. Pilih kriteria.

Aturan:

1. Gunakan Tom Select hanya untuk dropdown yang butuh pencarian.
2. Jangan gunakan untuk select kecil yang opsinya sedikit.
3. Jangan campur dengan Select2 kecuali ada alasan kuat.
4. Styling harus disesuaikan agar cocok dengan Tailwind.

---

## 13. Flatpickr

Flatpickr digunakan untuk input tanggal.

CDN:

```html
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/flatpickr.min.css"
/>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/l10n/id.js"></script>
```

Digunakan untuk:

1. Tanggal lahir.
2. Tanggal periode pendaftaran.
3. Tanggal mulai KP/KPL.
4. Tanggal selesai KP/KPL.
5. Tanggal logbook.
6. Tanggal surat.
7. Tanggal verifikasi.

Aturan:

1. Gunakan lokal Indonesia.
2. Format tampilan tanggal harus mudah dibaca.
3. Simpan tanggal di database dalam format `YYYY-MM-DD`.
4. Jangan hanya mengandalkan validasi tanggal di frontend.

---

## 14. Chart.js

Chart.js digunakan untuk grafik dashboard.

CDN:

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@latest"></script>
```

Digunakan untuk:

1. Grafik status mahasiswa per periode.
2. Grafik distribusi penempatan.
3. Grafik jumlah mahasiswa per prodi.
4. Grafik logbook terlambat.
5. Grafik nilai akhir jika dibutuhkan.

Aturan:

1. Jangan terlalu banyak grafik.
2. Cukup 1–2 grafik utama per dashboard.
3. Grafik harus ringkas dan mudah dibaca.
4. Jangan memuat Chart.js di halaman yang tidak memiliki grafik.
5. Data grafik harus berdasarkan query backend.

---

## 15. Dompdf

Dompdf digunakan untuk generate dokumen PDF.

Digunakan untuk:

1. Lampiran A/Formulir Pendaftaran KP/KPL.
2. Surat Rekomendasi Dosen PA.
3. Surat Permohonan/Pengantar Instansi.
4. Lembar Persetujuan Instansi.
5. Surat Tugas Dosen Pembimbing.
6. Form Penilaian Instansi.
7. Rekap Nilai Akhir.
8. Laporan rekap jika diperlukan.

Instalasi:

```bash
composer require dompdf/dompdf
```

Aturan:

1. Template PDF dibuat dari view HTML khusus.
2. Jangan campur template PDF dengan view dashboard.
3. Simpan hasil generate di `writable/generated`.
4. Metadata dokumen disimpan di `generated_documents`.
5. Gunakan ukuran kertas sesuai kebutuhan kampus.
6. Dokumen yang sudah digunakan tidak boleh dihapus sembarangan.
7. Jika generate ulang, simpan sebagai versi baru.

---

## 16. PhpSpreadsheet

PhpSpreadsheet digunakan untuk export Excel.

Instalasi:

```bash
composer require phpoffice/phpspreadsheet
```

Digunakan untuk:

1. Export data mahasiswa.
2. Export data registrasi.
3. Export data penempatan.
4. Export rekap dosen pembimbing.
5. Export logbook.
6. Export nilai akhir.
7. Export arsip periode.

Aturan:

1. Export harus berdasarkan filter periode.
2. Jangan export semua data tanpa batas jika data besar.
3. Nama file export harus jelas.
4. Export penting masuk audit log jika diperlukan.

Contoh nama file:

```text
rekap_registrasi_kp_2026.xlsx
rekap_nilai_akhir_kpl_2026.xlsx
```

---

## 17. Authentication

Gunakan custom auth CI4 agar mudah dikontrol dan dijelaskan.

Auth mendukung:

1. Login manual email/password.
2. Login Google untuk mahasiswa.
3. Session login.
4. Role redirect.
5. Logout.
6. Reset password jika diperlukan.

Aturan:

1. Password wajib menggunakan `password_hash()`.
2. Password diverifikasi menggunakan `password_verify()`.
3. Email harus unique.
4. Akun nonaktif tidak boleh login.
5. Login Google harus menghubungkan email ke akun user.
6. Jika NPM sudah dipakai email lain, perlu verifikasi Admin.
7. Setelah login, redirect sesuai role.
8. Semua login penting dicatat di `login_histories`.

---

## 18. Google Login

Google Login digunakan terutama untuk mahasiswa.

Aturan:

1. Google Login hanya membuat/menghubungkan akun berdasarkan email.
2. Mahasiswa tetap wajib melengkapi profil.
3. Google Login tidak otomatis membuat registrasi KP/KPL.
4. Jika email sudah ada, gunakan akun tersebut.
5. Jika email baru, buat user role mahasiswa.
6. Jika NPM yang diisi kemudian sudah digunakan, sistem harus meminta verifikasi Admin.
7. Client ID dan Secret disimpan di `.env`.

Konfigurasi `.env`:

```env
google.clientID =
google.clientSecret =
google.redirectURI =
```

---

## 19. Filters

Gunakan filter CI4 untuk proteksi akses.

Filter yang dibutuhkan:

```text
AuthFilter
RoleFilter
WorkflowAccessFilter
```

### 19.1 AuthFilter

Memastikan user sudah login.

### 19.2 RoleFilter

Memastikan user memiliki role sesuai route.

Contoh:

```text
/admin/* hanya untuk admin
/koordinator/* hanya untuk koordinator
/mahasiswa/* hanya untuk mahasiswa
/dosen/* hanya untuk dosen
/instansi/* hanya untuk instansi
```

### 19.3 WorkflowAccessFilter

Memastikan mahasiswa hanya bisa mengakses menu sesuai status workflow.

Contoh:

```text
/mahasiswa/logbook hanya bisa dibuka jika status minimal dosen_ditetapkan
```

Aturan:

1. Sidebar lock bukan proteksi utama.
2. Backend tetap wajib memblokir URL langsung.
3. Jika akses ditolak, redirect ke dashboard dengan pesan jelas.

---

## 20. Validation

Gunakan validasi CI4.

Aturan:

1. Semua form wajib divalidasi di backend.
2. Pesan validasi harus Bahasa Indonesia.
3. Jangan menggunakan pesan bawaan Bahasa Inggris.
4. Buat file bahasa validasi di `app/Language/id/Validation.php`.
5. Custom validation rules ditaruh di `app/Validation/CustomRules.php`.
6. Validasi frontend hanya tambahan.

Contoh pesan:

```text
Nama lengkap wajib diisi.
NPM wajib diisi.
Email tidak valid.
IPK minimal 2,50.
Bukti pembayaran wajib diunggah.
Format file harus PDF, JPG, JPEG, atau PNG.
Ukuran file maksimal 10 MB.
```

---

## 21. Upload File

Upload file menggunakan service khusus.

File service:

```text
app/Services/UploadService.php
```

Folder upload:

```text
writable/uploads/kp-pkl/
```

Aturan upload:

1. File tidak boleh disimpan langsung di public.
2. File harus divalidasi ekstensi.
3. File harus divalidasi MIME type.
4. File harus divalidasi ukuran maksimal.
5. Nama file harus diganti otomatis.
6. Simpan metadata file di database.
7. Download file harus lewat controller.
8. User hanya bisa download file sesuai hak akses.
9. Dokumen yang sudah valid tidak boleh dihapus sembarangan.

Format nama file:

```text
{npm}_{jenis_dokumen}_{periode}_{timestamp}.{ext}
```

---

## 22. Document Service

Gunakan service khusus dokumen.

File service:

```text
app/Services/DocumentService.php
```

Fungsi:

1. Generate PDF.
2. Mengambil template dokumen.
3. Mengisi placeholder data.
4. Menyimpan hasil PDF.
5. Membuat metadata di `generated_documents`.
6. Mengatur versi dokumen.
7. Menyediakan download dokumen.

Aturan:

1. Controller hanya memanggil service.
2. Template dokumen tidak boleh hardcode di controller.
3. Dokumen hasil generate disimpan di `writable/generated`.
4. Versi dokumen harus disimpan.

---

## 23. Workflow Service

Gunakan service khusus workflow.

File service:

```text
app/Services/WorkflowService.php
```

Fungsi:

1. Menentukan status saat ini.
2. Mengubah status registrasi.
3. Mengecek apakah menu boleh dibuka.
4. Menentukan aksi berikutnya.
5. Membuat status log.
6. Membuat audit log jika perlu.

Aturan:

1. Jangan menyebarkan logic status di banyak controller.
2. Semua perubahan status melewati WorkflowService.
3. Semua perubahan status dicatat di `registration_status_logs`.

---

## 24. Topsis Service

Gunakan service khusus TOPSIS.

File service:

```text
app/Services/TopsisService.php
```

Fungsi:

1. Mengambil kriteria.
2. Mengambil bobot periode.
3. Mengambil alternatif instansi.
4. Membuat matriks keputusan.
5. Normalisasi matriks.
6. Menghitung matriks terbobot.
7. Menghitung solusi ideal positif dan negatif.
8. Menghitung jarak solusi.
9. Menghitung nilai preferensi.
10. Menyimpan hasil ranking.

Aturan:

1. Rumus TOPSIS tidak boleh diletakkan langsung di controller.
2. Hasil TOPSIS harus disimpan di `topsis_results`.
3. Snapshot perhitungan disimpan jika memungkinkan.
4. TOPSIS hanya rekomendasi.
5. Koordinator tetap keputusan final.

---

## 25. Notification Service

Gunakan service notifikasi.

File service:

```text
app/Services/NotificationService.php
```

Fungsi:

1. Membuat notifikasi user.
2. Menampilkan badge notifikasi.
3. Menandai notifikasi dibaca.
4. Mengirim toast melalui flashdata jika perlu.

Aturan:

1. Notifikasi sistem disimpan di database jika penting.
2. Toast ringan boleh menggunakan session flashdata.
3. Semua pesan Bahasa Indonesia.

---

## 26. Archive Service

Gunakan service arsip.

File service:

```text
app/Services/ArchiveService.php
```

Fungsi:

1. Memeriksa kelengkapan data periode.
2. Menutup periode.
3. Mengarsipkan periode.
4. Mengunci data arsip.
5. Membuka koreksi arsip jika disetujui.

Aturan:

1. Periode tidak boleh diarsipkan jika data penting belum lengkap.
2. Arsip bersifat read-only.
3. Koreksi arsip wajib memiliki alasan.
4. Koreksi arsip wajib audit log.

---

## 27. Audit Service

Boleh dibuat service khusus audit.

File service:

```text
app/Services/AuditService.php
```

Fungsi:

1. Mencatat aksi penting.
2. Menyimpan user, role, IP, user agent.
3. Menyimpan old values dan new values.
4. Membantu trace perubahan data.

Aturan:

1. Aksi penting wajib tercatat.
2. Jangan mencatat data sensitif seperti password.
3. Audit log tidak boleh diedit oleh user biasa.

---

## 28. CDN Global

CDN global dimuat di:

```text
app/Views/partials/head.php
app/Views/partials/scripts.php
```

### Head Global

```html
<!-- Inter Font -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
  rel="stylesheet"
/>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Tabler Icons -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"
/>
```

### Scripts Global

```html
<!-- Alpine.js -->
<script
  defer
  src="https://cdn.jsdelivr.net/npm/alpinejs@latest/dist/cdn.min.js"
></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest"></script>
```

---

## 29. CDN Per Halaman

CDN khusus halaman dimuat hanya jika halaman membutuhkan.

Contoh:

```php
<?= $this->section('page_styles') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<?= $this->endSection() ?>
```

Aturan:

1. DataTables hanya di halaman tabel.
2. Chart.js hanya di halaman grafik.
3. Flatpickr hanya di halaman input tanggal.
4. Tom Select hanya di halaman dropdown pencarian.
5. Jangan memuat semua CDN di semua halaman.

---

## 30. Public Assets

Walaupun memakai CDN, project tetap membutuhkan custom assets.

Struktur:

```text
public/
├── assets/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   ├── sidebar.js
│   │   ├── alerts.js
│   │   └── form.js
│   └── images/
│       ├── logo.png
│       └── placeholder.png
├── favicon.ico
└── .htaccess
```

Fungsi:

| File         | Fungsi                                                     |
| ------------ | ---------------------------------------------------------- |
| `app.css`    | Custom CSS kecil yang tidak nyaman ditulis dengan utility. |
| `app.js`     | Helper global dashboard.                                   |
| `sidebar.js` | Sidebar collapse/drawer jika tidak cukup dengan Alpine.    |
| `alerts.js`  | SweetAlert flashdata/toast.                                |
| `form.js`    | Preview upload, helper form, validasi ringan frontend.     |

Aturan:

1. Jangan menaruh banyak inline JS di view.
2. Jangan menulis CSS terlalu banyak jika bisa memakai Tailwind.
3. Jangan membuat assets besar tanpa kebutuhan.

---

## 31. Clean URL

Sistem harus menggunakan URL tanpa `index.php`.

Target URL:

```text
/login
/dashboard
/mahasiswa/registrasi
/admin/data-mahasiswa
/koordinator/validasi-registrasi
```

Bukan:

```text
/index.php/login
/index.php/dashboard
```

Aturan:

1. Gunakan `.htaccess` di folder public.
2. Pastikan Apache `mod_rewrite` aktif.
3. Arahkan web server ke folder `public`.
4. Kosongkan `indexPage`.
5. Atur `baseURL` di `.env`.

Config:

```php
public string $indexPage = '';
```

`.env`:

```env
app.baseURL = 'http://localhost:8080/'
app.indexPage = ''
```

---

## 32. Environment

Gunakan `.env`.

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

Aturan:

1. Jangan hardcode database credential.
2. Jangan hardcode Google Client Secret.
3. Jangan hardcode base URL.
4. Jangan commit `.env` berisi credential asli ke repository publik.
5. Sediakan `.env.example`.

---

## 33. Security

Keamanan wajib:

1. CSRF aktif.
2. Password hashing.
3. Role filter.
4. Workflow filter.
5. Escape output view.
6. Validasi semua input.
7. Validasi upload file.
8. Batasi ukuran file.
9. Batasi tipe file.
10. Simpan file di writable.
11. Download file melalui controller.
12. Soft delete data penting.
13. Audit log aksi penting.
14. Jangan tampilkan error detail di production.
15. Jangan simpan password atau secret di audit log.

---

## 34. Session

Session digunakan untuk:

1. Login user.
2. Flash message.
3. CSRF session jika digunakan.
4. Temporary state ringan.

Aturan:

1. Jangan menyimpan data besar di session.
2. Jangan menyimpan file di session.
3. Jangan menyimpan data sensitif berlebihan.
4. Session timeout harus wajar.
5. Logout harus menghancurkan session login.

---

## 35. Routing

Gunakan route group berdasarkan role.

Contoh:

```php
$routes->group('admin', ['filter' => 'auth:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
});

$routes->group('koordinator', ['filter' => 'auth:koordinator'], static function ($routes) {
    $routes->get('dashboard', 'Koordinator\DashboardController::index');
});

$routes->group('mahasiswa', ['filter' => 'auth:mahasiswa'], static function ($routes) {
    $routes->get('dashboard', 'Mahasiswa\DashboardController::index');
});
```

Aturan:

1. Route harus jelas.
2. Jangan membuat route acak.
3. Route mahasiswa yang terkait workflow harus memakai WorkflowAccessFilter.
4. Gunakan nama controller yang sesuai fungsi.
5. Jangan menaruh semua route dalam satu controller besar.

---

## 36. API Internal

Jika diperlukan, boleh membuat endpoint internal untuk:

1. DataTables server-side.
2. Chart dashboard.
3. Dependent dropdown.
4. Cek kuota instansi.
5. Preview rekomendasi TOPSIS.

Aturan:

1. Endpoint internal tetap wajib auth.
2. Endpoint internal tetap wajib role check.
3. Jangan membuat API publik tanpa kebutuhan.
4. Response JSON harus konsisten.
5. Jangan bocorkan data role lain.

---

## 37. Testing

Testing minimal:

1. Auth login/logout.
2. Role access.
3. Workflow access.
4. Validasi registrasi.
5. Upload dokumen.
6. Verifikasi dokumen.
7. TOPSIS.
8. Penempatan.
9. Logbook.
10. Penilaian.
11. Arsip.
12. Clean URL.
13. Responsive mobile.

Jenis pengujian utama:

```text
Black Box Testing
```

Hasil pengujian harus bisa dimasukkan ke laporan skripsi.

---

## 38. Library yang Tidak Direkomendasikan untuk MVP

Hindari dulu:

```text
Bootstrap sebagai framework utama
Vuexy sebagai template utama
React
Vue
Inertia
Livewire
FullCalendar
Moment.js
ApexCharts
jQuery UI
Select2 jika tidak butuh
Lodash
Animate.css berlebihan
WhatsApp Gateway
OCR
WebSocket
```

Alasan:

1. Membuat project lebih berat.
2. Menambah kompleksitas.
3. Tidak wajib untuk MVP.
4. Bisa membingungkan agent.
5. Bisa membuat UI tidak konsisten.

---

## 39. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Menggunakan Bootstrap/Vuexy sebagai pondasi utama.
2. Mencampur banyak framework frontend.
3. Memuat semua CDN di semua halaman.
4. Menaruh logic TOPSIS di controller.
5. Menaruh logic workflow di view.
6. Menyimpan file upload di public.
7. Membuat pesan validasi Bahasa Inggris.
8. Mengabaikan CSRF.
9. Mengabaikan role filter.
10. Mengabaikan workflow filter.
11. Mengabaikan `.env`.
12. Menghapus data penting secara permanen.
13. Mengabaikan migration dan seeder.
14. Mengabaikan mobile responsive.
15. Membuat dashboard seperti CRUD template biasa.

---

## 40. Ringkasan Final

Stack final:

```text
Backend:
- CodeIgniter 4
- PHP 8.2+
- Composer

Database:
- MySQL/MariaDB
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

Project harus tetap sederhana untuk skripsi, tetapi cukup rapi, aman, dan scalable untuk dikembangkan.
