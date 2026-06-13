# AGENTS.md

## 1. Identitas Proyek

Nama proyek:

**Sistem Manajemen KP/KPL Berbasis Multi-Role dengan Rekomendasi Penempatan Menggunakan Metode TOPSIS**

Framework utama:

- CodeIgniter 4
- PHP 8.++
- MySQL / MariaDB
- Tailwind CSS CDN
- Alpine.js
- SweetAlert2
- Tabler Icons
- Dompdf
- PhpSpreadsheet

Sistem ini dibangun untuk mendigitalisasi proses Kerja Praktek (KP) dan Kerja Praktek Lapangan (KPL) pada Fakultas Ilmu Komputer Universitas Almuslim.

Sistem ini bukan sekadar CRUD data, tetapi platform workflow akademik yang mengelola proses dari registrasi, verifikasi, penempatan, pembimbing, dokumen, logbook, penilaian, sampai arsip periode.

---

## 2. Prinsip Utama yang Wajib Dipatuhi

Agent wajib mengikuti prinsip berikut:

1. Jangan membangun aplikasi seperti CRUD biasa.
2. Sistem harus berbasis workflow bertahap.
3. Setiap role memiliki hak akses dan dashboard masing-masing.
4. Menu yang belum sesuai tahap harus terkunci di UI dan tetap diblokir di backend.
5. Semua validasi wajib dilakukan di backend.
6. Pesan validasi wajib menggunakan Bahasa Indonesia yang jelas.
7. Semua aksi penting harus dicatat di audit log.
8. Data penting tidak boleh dihapus permanen; gunakan soft delete.
9. File upload harus disimpan rapi di folder `writable`.
10. Dokumen yang sudah diverifikasi tidak boleh diubah atau dihapus sembarangan.
11. Sistem harus responsive dan nyaman dipakai di desktop maupun mobile.
12. UI harus modern, clean, compact, dan mirip SaaS dashboard.
13. Jangan menggunakan Bootstrap/Vuexy sebagai framework utama.
14. Jangan menggunakan emoji sebagai icon utama.
15. Gunakan icon library resmi seperti Tabler Icons.
16. Jangan memasukkan library CDN yang tidak diperlukan.
17. Jangan hardcode konfigurasi yang seharusnya ada di `.env`.
18. Jangan menaruh logic besar di controller.
19. Gunakan Service Layer untuk workflow, TOPSIS, dokumen, upload, notifikasi, dan arsip.
20. Selalu update `docs/11-progress.md` setelah menyelesaikan bagian besar.

---

## 3. Dokumen yang Wajib Dibaca Agent

Sebelum melakukan coding, agent wajib membaca dokumen berikut secara berurutan:

1. `docs/01-prd.md`
2. `docs/02-current-system.md`
3. `docs/03-roles.md`
4. `docs/04-workflow.md`
5. `docs/05-sidebar-dashboard.md`
6. `docs/06-database.md`
7. `docs/07-technology.md`
8. `docs/08-ui-ux-rules.md`
9. `docs/09-business-rules.md`
10. `docs/10-project-structure.md`
11. `docs/11-progress.md`

Gunakan `templates.md` sebagai standar penulisan dokumentasi.

Jika ada instruksi yang bertentangan, prioritaskan urutan berikut:

1. `AGENTS.md`
2. `docs/09-business-rules.md`
3. `docs/04-workflow.md`
4. `docs/06-database.md`
5. `docs/10-project-structure.md`
6. Dokumen lainnya

---

## 4. Struktur Dokumentasi Proyek

Struktur dokumentasi yang harus dipakai:

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

Fungsi utama dokumen:

- `01-prd.md`: tujuan, scope, fitur utama, batasan MVP.
- `02-current-system.md`: kondisi sistem kampus saat ini.
- `03-roles.md`: penjelasan 5 role dan hak akses.
- `04-workflow.md`: alur step-by-step dari registrasi sampai arsip.
- `05-sidebar-dashboard.md`: sidebar, dashboard, topbar, footer, locked menu.
- `06-database.md`: struktur database, tabel, relasi, status, audit log.
- `07-technology.md`: aturan teknologi dan CDN.
- `08-ui-ux-rules.md`: aturan desain modern, compact, responsive.
- `09-business-rules.md`: aturan bisnis dan kasus khusus.
- `10-project-structure.md`: struktur folder CI4, writable, public, clean URL.
- `11-progress.md`: catatan progres agent.

---

## 5. Kondisi Sistem Kampus Saat Ini

Sistem kampus saat ini masih manual dan belum terintegrasi.

Alur saat ini:

1. Mahasiswa mengisi Google Form.
2. Mahasiswa mengisi data diri, data akademik, status pembayaran, dan upload bukti pembayaran.
3. Mahasiswa mengunduh dokumen dari link yang disediakan.
4. Dokumen dicetak secara fisik.
5. Mahasiswa meminta tanda tangan/stempel pihak terkait.
6. Mahasiswa menyerahkan dokumen fisik kepada Koordinator KP/KPL.
7. Koordinator memeriksa berkas secara manual.
8. Data dan dokumen belum tersimpan dalam satu sistem terpusat.

Dokumen manual yang sudah ada tetap harus dijadikan acuan sistem, bukan dihapus total.

Dokumen yang harus diperhatikan:

- Lampiran A/Formulir Pendaftaran KP/KPL.
- Surat Rekomendasi Dosen Pembimbing Akademik untuk KP.
- Surat Rekomendasi Dosen Pembimbing Akademik untuk KPL.
- Formulir Permohonan Surat Pengantar KP/KPL.
- Surat Permohonan KP/KPL ke instansi.
- Lembar persetujuan instansi.
- Form penilaian instansi.
- Dokumen yang memerlukan tanda tangan dan stempel.

Sistem baru harus mendigitalisasi proses manual tersebut secara bertahap.

Prinsipnya:

```text
Format resmi kampus tetap dipakai.
Input data dilakukan melalui sistem.
Sistem dapat generate dokumen PDF.
Dokumen yang perlu tanda tangan/stempel tetap dapat diunduh, dicetak, diisi, lalu diupload ulang.
Admin/Koordinator memverifikasi dokumen melalui sistem.
```

---

## 6. Role Sistem

Sistem memiliki 5 role utama:

1. Admin
2. Koordinator KP/KPL
3. Mahasiswa
4. Dosen Pembimbing
5. Instansi Mitra

### 6.1 Admin

Admin berperan sebagai pengelola sistem dan data master.

Tanggung jawab:

- Mengelola akun user.
- Mengelola data mahasiswa.
- Mengelola data dosen.
- Mengelola data instansi.
- Mengelola program studi.
- Mengelola periode KP/KPL.
- Mengelola form dinamis.
- Mengelola dokumen persyaratan.
- Mengelola template surat.
- Membantu verifikasi administrasi.
- Mengelola konfigurasi TOPSIS.
- Mengelola export laporan.
- Melihat audit log.
- Mengatur konfigurasi sistem.

Admin bukan pengambil keputusan akademik final, kecuali diberi izin khusus.

### 6.2 Koordinator KP/KPL

Koordinator berperan sebagai pengambil keputusan akademik.

Tanggung jawab:

- Memvalidasi registrasi mahasiswa.
- Menyetujui, menolak, atau meminta revisi registrasi.
- Memvalidasi pengajuan tempat mitra.
- Memvalidasi pengajuan tempat mandiri.
- Meninjau hasil rekomendasi TOPSIS.
- Menetapkan dosen pembimbing.
- Memvalidasi dokumen penerimaan instansi.
- Memantau progres mahasiswa.
- Memvalidasi nilai akhir.
- Menutup dan mengarsipkan periode.

Keputusan akademik final berada pada Koordinator.

### 6.3 Mahasiswa

Mahasiswa mengikuti alur step-based.

Tanggung jawab:

- Login/daftar akun.
- Melengkapi profil.
- Mengisi registrasi KP/KPL.
- Mengupload dokumen persyaratan.
- Memilih penempatan mitra atau mengajukan tempat mandiri.
- Mengunduh dokumen/surat yang digenerate sistem.
- Mengupload dokumen balasan yang sudah ditandatangani/stempel.
- Mengisi logbook mingguan.
- Mengupload laporan akhir.
- Melihat nilai dan riwayat KP/KPL.

Mahasiswa tidak boleh melewati tahap yang belum selesai.

### 6.4 Dosen Pembimbing

Dosen Pembimbing berperan sebagai pembimbing akademik.

Tanggung jawab:

- Melihat mahasiswa bimbingan.
- Memantau logbook.
- Memberi catatan/revisi logbook.
- Memeriksa laporan akhir.
- Memberi nilai akademik.
- Melihat riwayat bimbingan.

Dosen hanya boleh mengakses mahasiswa yang ditetapkan sebagai bimbingannya.

### 6.5 Instansi Mitra

Instansi Mitra berperan sebagai tempat pelaksanaan KP/KPL.

Tanggung jawab:

- Melihat mahasiswa yang ditempatkan.
- Mengonfirmasi penerimaan mahasiswa.
- Melihat logbook mahasiswa jika fitur diaktifkan.
- Memberi evaluasi/nilai instansi.
- Melihat dokumen terkait mahasiswa.

Catatan:

- Instansi mitra resmi dapat memiliki akun.
- Instansi mandiri tidak wajib memiliki akun.
- Untuk instansi mandiri, gunakan jalur dokumen manual: generate dokumen, unduh, tanda tangan/stempel, upload ulang, verifikasi.

---

## 7. Alur Utama Sistem

Alur utama mahasiswa:

```text
Daftar/Login
↓
Lengkapi Profil
↓
Registrasi KP/KPL
↓
Upload Dokumen Persyaratan
↓
Menunggu Verifikasi
↓
Registrasi Disetujui
↓
Penempatan KP/KPL
↓
Pilih Mitra dengan Rekomendasi TOPSIS
atau Ajukan Tempat Mandiri
↓
Validasi Penempatan oleh Koordinator
↓
Generate Surat/Dokumen
↓
Konfirmasi Penerimaan Instansi
↓
Penetapan Dosen Pembimbing
↓
KP/KPL Berjalan
↓
Logbook Mingguan
↓
Upload Laporan Akhir
↓
Penilaian Instansi
↓
Penilaian Dosen
↓
Rekap Nilai Akhir
↓
Validasi Akhir
↓
Selesai
↓
Arsip Periode
```

Sistem harus selalu menampilkan status mahasiswa saat ini dan aksi berikutnya.

---

## 8. Status Workflow Utama

Status utama mahasiswa disimpan pada tabel `kp_registrations`.

Contoh status:

```text
draft
menunggu_verifikasi
revisi_registrasi
registrasi_ditolak
registrasi_disetujui
menunggu_penempatan
penempatan_diajukan
penempatan_revisi
penempatan_ditolak
penempatan_disetujui
menunggu_surat
menunggu_penerimaan_instansi
penerimaan_perlu_revisi
diterima_instansi
dosen_ditetapkan
sedang_berjalan
logbook_berjalan
menunggu_laporan_akhir
laporan_akhir_dikirim
menunggu_penilaian_instansi
nilai_instansi_masuk
menunggu_penilaian_dosen
nilai_dosen_masuk
menunggu_validasi_akhir
selesai
diarsipkan
```

Status ini dipakai untuk:

- Mengunci/membuka menu sidebar.
- Menentukan dashboard mahasiswa.
- Menentukan validasi backend.
- Menentukan aksi berikutnya.
- Menentukan arsip periode.

---

## 9. Aturan Akun dan Login

Sistem mendukung:

- Login manual email/password.
- Login Google untuk mahasiswa.

Aturan akun:

1. Email harus unik.
2. NPM harus unik.
3. Satu user dapat memiliki satu profil mahasiswa.
4. Akun user tidak dibuat ulang setiap periode.
5. Satu user dapat memiliki riwayat KP/KPL berdasarkan periode.
6. Jika email sudah pernah dipakai, user masuk ke akun yang sama.
7. Jika NPM sudah terdaftar tetapi email berbeda, sistem harus meminta verifikasi Admin.
8. Mahasiswa yang sudah selesai KP/KPL tidak bisa daftar ulang kecuali Koordinator membuka akses khusus.
9. Akun nonaktif/suspended tidak boleh login.
10. Password wajib disimpan dengan `password_hash()`.
11. Verifikasi password wajib menggunakan `password_verify()`.

Tabel utama akun:

- `users`
- `student_profiles`
- `lecturer_profiles`
- `institution_profiles`
- `password_reset_tokens`
- `audit_logs`

---

## 10. Aturan Periode

Periode adalah wadah utama seluruh proses KP/KPL.

Contoh periode:

- KP Informatika 2026
- KPL Informatika Medis 2026
- KP/KPL 2027

Status periode:

```text
draft
aktif
ditutup
diarsipkan
```

Aturan:

1. Mahasiswa hanya bisa daftar pada periode aktif sesuai prodi/jenis kegiatan.
2. Periode lama tidak dihapus.
3. Setelah semua proses selesai, periode ditutup.
4. Setelah ditutup dan divalidasi, periode diarsipkan.
5. Data arsip bersifat read-only.
6. Koreksi data arsip hanya bisa dilakukan dengan alasan dan dicatat di audit log.

---

## 11. Aturan Form Dinamis

Sistem mendukung form dinamis seperti Google Form.

Digunakan untuk:

- Form registrasi.
- Form dokumen persyaratan.
- Form logbook.
- Form penilaian.
- Form lain yang dibutuhkan periode.

Aturan:

1. Form yang sudah dipakai tidak boleh diedit langsung.
2. Jika ada perubahan, buat versi baru.
3. Data lama tetap mengikuti versi form lama.
4. Form harus bisa dikaitkan dengan periode.
5. Field wajib harus divalidasi di backend.
6. Pesan error field wajib menggunakan Bahasa Indonesia.
7. Jangan menghapus field yang sudah memiliki data; gunakan status aktif/nonaktif.

---

## 12. Aturan Registrasi KP/KPL

Registrasi menggantikan Google Form manual.

Data yang perlu didukung:

### Data Diri

- Nama lengkap.
- NPM.
- Tempat, tanggal lahir.
- Jenis kelamin.
- Agama.
- Alamat lengkap sesuai KTP.
- Kecamatan.
- Kabupaten.
- Provinsi.
- Nomor HP aktif.
- Email aktif.
- Nama orang tua/wali.
- Nomor HP orang tua/wali.
- Semester.
- Tahun akademik.
- Angkatan.
- Program studi.

### Data Akademik

- Jumlah SKS.
- IPK terakhir.
- Status IPK minimal 2,50.
- Kelulusan Pemrograman Dasar.
- Kelulusan Struktur Data.
- Kelulusan Basis Data.
- Kelulusan Analisis dan Perancangan Sistem Informasi.
- Kelulusan Jaringan Komputer/Data Communication.
- Kelulusan minimal salah satu mata kuliah konsentrasi.
- Status biaya pendidikan.
- Bukti pembayaran KP/KPL.
- KHS terbaru.
- Surat rekomendasi Dosen PA.
- Dokumen pendukung lain sesuai periode.

Registrasi harus memiliki status:

```text
draft
dikirim
menunggu_verifikasi
perlu_revisi
ditolak
disetujui
```

---

## 13. Aturan Penempatan

Setelah registrasi disetujui, menu Penempatan terbuka.

Ada dua jalur:

### 13.1 Penempatan Mitra Kampus

Alur:

```text
Mahasiswa memilih jalur mitra
↓
Sistem menampilkan daftar instansi mitra
↓
Sistem menghitung rekomendasi TOPSIS
↓
Mahasiswa memilih instansi
↓
Pengajuan dikirim ke Koordinator
↓
Koordinator menyetujui/menolak/meminta revisi
```

TOPSIS hanya rekomendasi. Keputusan final tetap di Koordinator.

### 13.2 Tempat Mandiri

Alur:

```text
Mahasiswa mengisi data tempat mandiri
↓
Koordinator mengecek kelayakan
↓
Jika disetujui, sistem generate surat/form
↓
Mahasiswa download dokumen
↓
Mahasiswa membawa ke instansi
↓
Instansi mengisi, tanda tangan, dan stempel
↓
Mahasiswa upload ulang dokumen
↓
Admin/Koordinator verifikasi
↓
Jika valid, lanjut ke tahap berikutnya
```

Tempat mandiri tidak wajib memiliki akun instansi.

---

## 14. Aturan TOPSIS

TOPSIS digunakan untuk rekomendasi tempat dari daftar instansi mitra.

Kriteria awal:

- Kesesuaian bidang instansi.
- Kesesuaian kemampuan mahasiswa dengan kebutuhan instansi.
- Ketersediaan kuota.
- Jarak lokasi.
- Status kemitraan/riwayat kerja sama.
- Ketersediaan pembimbing lapangan.

Jenis kriteria:

- Benefit: kesesuaian bidang, kesesuaian kemampuan, kuota, status kemitraan, pembimbing lapangan.
- Cost: jarak lokasi.

Aturan:

1. Bobot TOPSIS disimpan per periode.
2. Hasil TOPSIS lama tidak boleh berubah jika bobot periode baru berubah.
3. TOPSIS dibuat di `TopsisService`.
4. Controller tidak boleh berisi rumus TOPSIS langsung.
5. Hasil rekomendasi harus disimpan agar bisa diaudit.

---

## 15. Aturan Dosen Pembimbing

Dosen pembimbing ditetapkan setelah penempatan disetujui.

Aturan:

1. Koordinator menetapkan dosen pembimbing.
2. Admin hanya mengelola data dosen dan kuota.
3. Dosen memiliki kuota maksimal bimbingan.
4. Sistem tidak boleh memilih dosen yang kuotanya penuh.
5. Koordinator dapat mengubah kuota jika memiliki hak.
6. Jika dosen diganti, riwayat dosen lama tetap disimpan.
7. Dosen hanya bisa mengakses mahasiswa yang menjadi bimbingannya.

---

## 16. Aturan Surat dan Dokumen

Sistem harus dapat generate dokumen PDF dari template.

Dokumen yang perlu didukung:

- Lampiran A/Formulir Pendaftaran KP/KPL.
- Surat rekomendasi Dosen PA.
- Surat permohonan/pengantar ke instansi.
- Surat tugas dosen pembimbing.
- Form penerimaan instansi.
- Form penilaian instansi.
- Form penilaian dosen.
- Rekap nilai akhir.
- Surat selesai KP/KPL jika dibutuhkan.

Aturan dokumen:

1. Template resmi kampus tetap dijadikan acuan.
2. Dokumen dapat digenerate dari data sistem.
3. Dokumen yang perlu tanda tangan/stempel dapat diunduh.
4. Mahasiswa dapat upload ulang dokumen yang sudah ditandatangani/stempel.
5. Admin/Koordinator memverifikasi dokumen.
6. Setiap dokumen memiliki status.
7. Dokumen lama tidak dihapus saat ada revisi; simpan versi baru.

Status dokumen:

```text
belum_upload
menunggu_verifikasi
valid
perlu_revisi
ditolak
```

---

## 17. Aturan Logbook

Gunakan model logbook mingguan.

Logbook mingguan berisi detail kegiatan harian.

Isi utama:

- Minggu ke.
- Tanggal mulai minggu.
- Tanggal selesai minggu.
- Kegiatan harian.
- Jam mulai dan selesai.
- Uraian kegiatan.
- Hasil kegiatan.
- Kendala.
- Dokumentasi.
- Rencana minggu berikutnya.

Status logbook:

```text
draft
dikirim
perlu_revisi
disetujui
terkunci
```

Aturan:

1. Mahasiswa bisa menyimpan draft.
2. Mahasiswa submit logbook mingguan.
3. Dosen pembimbing dapat memberi komentar.
4. Dosen dapat menyetujui atau meminta revisi.
5. Logbook yang sudah disetujui dikunci.
6. Logbook harus nyaman diisi melalui mobile.
7. Untuk instansi mitra, validasi logbook oleh instansi dapat dibuat opsional.
8. Untuk instansi mandiri, validasi utama tetap melalui dokumen manual dan penilaian akhir.

---

## 18. Aturan Penilaian

Penilaian berasal dari:

1. Instansi/pembimbing lapangan.
2. Dosen pembimbing.
3. Administrasi/logbook jika dibutuhkan.

Contoh bobot awal:

```text
Nilai Instansi: 40%
Nilai Dosen: 50%
Administrasi/Logbook: 10%
```

Aturan:

1. Bobot nilai harus dapat dikonfigurasi per periode.
2. Nilai lama tidak boleh berubah jika bobot periode baru berubah.
3. Instansi mitra dapat memberi nilai melalui akun.
4. Instansi mandiri menggunakan form penilaian manual.
5. Mahasiswa download form penilaian.
6. Instansi mengisi, tanda tangan, dan stempel.
7. Mahasiswa upload ulang form nilai.
8. Admin/Koordinator memverifikasi.
9. Dosen mengisi nilai akademik.
10. Koordinator memvalidasi nilai akhir.
11. Nilai akhir dikunci setelah divalidasi.

---

## 19. Aturan Arsip Periode

Setelah periode selesai, data harus masuk arsip.

Arsip menyimpan:

- Data mahasiswa.
- Data registrasi.
- Dokumen upload.
- Dokumen hasil generate.
- Penempatan.
- Dosen pembimbing.
- Instansi.
- Logbook.
- Laporan akhir.
- Nilai instansi.
- Nilai dosen.
- Nilai akhir.
- Riwayat status.
- Audit log penting.

Aturan arsip:

1. Data arsip bersifat read-only.
2. Mahasiswa bisa melihat riwayat miliknya.
3. Dosen bisa melihat riwayat mahasiswa bimbingannya.
4. Admin dan Koordinator bisa melihat seluruh arsip sesuai hak akses.
5. Perubahan data arsip hanya bisa melalui koreksi arsip.
6. Koreksi arsip wajib mencatat alasan dan audit log.

---

## 20. Aturan UI/UX

Sistem harus menggunakan gaya:

```text
Modern SaaS Dashboard
Compact
Clean
Responsive
Workflow-based
Bukan CRUD biasa
```

Aturan tampilan:

1. Gunakan full Tailwind CSS.
2. Gunakan layout compact seperti SaaS dashboard modern.
3. Card statistik tidak boleh terlalu besar.
4. Sidebar harus ramping.
5. Sidebar bisa collapse di desktop.
6. Saat collapse, sidebar hanya menampilkan icon.
7. Di mobile, sidebar berubah menjadi hamburger drawer/full overlay.
8. Topbar wajib ada.
9. Footer wajib ada.
10. Semua role memakai layout global yang sama.
11. Gunakan partials untuk sidebar, topbar, footer, assets, flash message.
12. Mahasiswa memiliki locked menu dengan icon gembok.
13. Dashboard mahasiswa wajib menampilkan progress stepper.
14. Halaman logbook wajib mobile-friendly.
15. Tabel besar harus responsive; di mobile ubah menjadi card/list jika perlu.
16. Jangan memakai tampilan admin panel jadul.
17. Jangan memakai card jumbo yang boros ruang.
18. Jangan menggunakan emoji sebagai icon utama.

---

## 21. Aturan Sidebar dan Dashboard

Sidebar harus role-based dan workflow-aware.

Role-based berarti menu berubah sesuai role.

Workflow-aware berarti menu mahasiswa berubah berdasarkan status proses.

Contoh menu mahasiswa awal:

```text
Dashboard
Profil Saya
Registrasi KP/KPL
Status Registrasi
Penempatan KP/KPL [terkunci]
Pembimbing Saya [terkunci]
Surat & Dokumen [terkunci]
Upload Dokumen Balasan [terkunci]
Logbook Mingguan [terkunci]
Laporan Akhir [terkunci]
Penilaian Saya [terkunci]
Riwayat KP/KPL
Notifikasi
```

Locked menu harus memiliki:

1. Tampilan icon gembok.
2. Alasan kenapa belum bisa dibuka.
3. Proteksi backend.

Dashboard setiap role harus menjawab kebutuhan utama role tersebut.

Dashboard mahasiswa harus menampilkan:

- Status tahap saat ini.
- Periode aktif.
- Progress stepper.
- Aksi berikutnya.
- Catatan revisi.
- Dokumen penting.
- Riwayat KP/KPL.

Dashboard Admin harus menampilkan:

- Periode aktif.
- Total mahasiswa.
- Dokumen menunggu verifikasi.
- Akun baru.
- Surat/dokumen yang digenerate.
- Aktivitas terbaru.

Dashboard Koordinator harus menampilkan:

- Registrasi menunggu keputusan.
- Pengajuan tempat menunggu validasi.
- Dosen pembimbing penuh kuota.
- Mahasiswa bermasalah.
- Nilai akhir menunggu validasi.

Dashboard Dosen harus menampilkan:

- Mahasiswa bimbingan.
- Logbook menunggu review.
- Laporan akhir menunggu pemeriksaan.
- Nilai belum diisi.

Dashboard Instansi harus menampilkan:

- Mahasiswa yang ditempatkan.
- Konfirmasi penerimaan.
- Evaluasi/nilai yang perlu diisi.
- Riwayat mahasiswa.

---

## 22. Aturan CDN Frontend

Gunakan CDN secara terkontrol.

CDN global:

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

<!-- Alpine.js -->
<script
  defer
  src="https://cdn.jsdelivr.net/npm/alpinejs@latest/dist/cdn.min.js"
></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest"></script>
```

CDN khusus halaman tertentu:

```html
<!-- DataTables -->
<link
  rel="stylesheet"
  href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css"
/>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

<!-- Tom Select -->
<link
  href="https://cdn.jsdelivr.net/npm/tom-select@latest/dist/css/tom-select.css"
  rel="stylesheet"
/>
<script src="https://cdn.jsdelivr.net/npm/tom-select@latest/dist/js/tom-select.complete.min.js"></script>

<!-- Flatpickr -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/flatpickr.min.css"
/>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/l10n/id.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@latest"></script>
```

Aturan:

1. Jangan memasukkan semua CDN ke semua halaman jika tidak dibutuhkan.
2. Global CDN hanya untuk library yang dipakai seluruh layout.
3. Library tabel, chart, datepicker, dan select hanya dipanggil di halaman terkait.
4. Jangan mencampur Bootstrap/Vuexy sebagai framework utama.
5. Jangan memakai terlalu banyak library tanpa alasan.
6. Semua CDN dikelola melalui partial assets.

---

## 23. Aturan Validasi Bahasa Indonesia

Semua validasi harus berbahasa Indonesia.

Contoh pesan:

```text
Nama lengkap wajib diisi.
NPM wajib diisi.
NPM hanya boleh berisi angka.
Email tidak valid.
Nomor HP wajib diisi.
IPK wajib diisi.
IPK minimal 2,50.
Jumlah SKS belum memenuhi syarat.
Bukti pembayaran wajib diunggah.
Format file harus PDF, JPG, JPEG, atau PNG.
Ukuran file maksimal 10 MB.
Dokumen belum dapat diverifikasi karena tidak terdapat tanda tangan.
Dokumen belum dapat diverifikasi karena tidak terdapat stempel instansi.
Menu ini belum dapat dibuka karena registrasi Anda belum disetujui.
Logbook belum dapat dibuka karena Anda belum diterima oleh instansi.
Dosen pembimbing sudah mencapai kuota maksimal.
Kuota instansi sudah penuh.
```

Aturan:

1. Jangan gunakan pesan error bawaan Bahasa Inggris.
2. Buat file bahasa Indonesia di `app/Language/id/Validation.php`.
3. Buat custom validation rules jika diperlukan.
4. Validasi frontend hanya bantuan; validasi utama tetap backend.

---

## 24. Aturan Struktur Folder CI4

Gunakan struktur berikut:

```text
app/
├── Config/
│   ├── Routes.php
│   ├── Filters.php
│   ├── Validation.php
│   ├── Sidebar.php
│   └── App.php
│
├── Controllers/
│   ├── Auth/
│   ├── Admin/
│   ├── Koordinator/
│   ├── Mahasiswa/
│   ├── Dosen/
│   └── Instansi/
│
├── Models/
│
├── Services/
│   ├── WorkflowService.php
│   ├── TopsisService.php
│   ├── DocumentService.php
│   ├── NotificationService.php
│   ├── ArchiveService.php
│   └── UploadService.php
│
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
│
├── Filters/
│   ├── AuthFilter.php
│   ├── RoleFilter.php
│   └── WorkflowAccessFilter.php
│
├── Validation/
│   └── CustomRules.php
│
├── Helpers/
│   ├── status_helper.php
│   ├── sidebar_helper.php
│   └── document_helper.php
│
└── Language/
    └── id/
        └── Validation.php
```

Aturan:

1. Controller dipisah per role.
2. Controller hanya mengatur request/response.
3. Logic utama masuk ke Services.
4. Model hanya untuk akses database.
5. View harus memakai layouts dan partials.
6. Jangan membuat layout berbeda-beda untuk setiap role jika tidak perlu.
7. Gunakan filter untuk auth, role, dan workflow access.

---

## 25. Aturan Struktur View

Gunakan struktur view:

```text
app/Views/
├── layouts/
│   ├── app.php
│   ├── auth.php
│   └── guest.php
│
├── partials/
│   ├── head.php
│   ├── sidebar.php
│   ├── topbar.php
│   ├── footer.php
│   ├── scripts.php
│   ├── flash-message.php
│   └── breadcrumb.php
│
├── components/
│   ├── stat-card.php
│   ├── status-badge.php
│   ├── progress-stepper.php
│   ├── locked-menu.php
│   ├── empty-state.php
│   ├── data-table.php
│   ├── form-field.php
│   └── page-header.php
```

Semua role memakai `layouts/app.php` untuk dashboard.

---

## 26. Aturan Writable dan Storage

Semua upload harus disimpan di `writable`, bukan langsung di `public`.

Struktur:

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
│
├── cache/
├── logs/
└── session/
```

Aturan:

1. File upload tidak boleh diakses langsung dari public.
2. Akses file harus melalui controller download.
3. File harus dicek permission dan role sebelum diunduh.
4. Nama file harus diganti otomatis.
5. File yang sudah diverifikasi tidak boleh dihapus langsung.
6. Simpan versi dokumen jika ada revisi.

Format nama file:

```text
{npm}_{jenis_dokumen}_{periode}_{timestamp}.{ext}
```

Contoh:

```text
235520110141_bukti_pembayaran_2026_20260612_101530.pdf
235520110141_surat_rekomendasi_pa_2026_20260612_101800.pdf
235520110141_surat_penerimaan_instansi_2026_20260615_090200.pdf
```

---

## 27. Aturan Public Assets

Struktur public assets:

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

Aturan:

1. CDN utama tetap dipanggil dari partial.
2. Custom CSS kecil masuk `public/assets/css/app.css`.
3. Custom JS global masuk `public/assets/js/app.js`.
4. SweetAlert flashdata dikelola di `alerts.js`.
5. Sidebar collapse/drawer dapat dikelola Alpine.js atau `sidebar.js`.

---

## 28. Clean URL Tanpa index.php

URL harus bersih.

Target URL:

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

Aturan:

1. Gunakan `.htaccess` di folder `public`.
2. Pastikan Apache `mod_rewrite` aktif.
3. Arahkan web server ke folder `public`.
4. Set `indexPage` menjadi kosong.
5. Atur `baseURL` di `.env`.

Konfigurasi:

```php
public string $indexPage = '';
```

`.env`:

```env
app.baseURL = 'http://localhost:8080/'
app.indexPage = ''
```

---

## 29. Aturan Keamanan

Keamanan wajib:

1. CSRF aktif.
2. Password pakai `password_hash()`.
3. Verifikasi password pakai `password_verify()`.
4. Gunakan Auth Filter.
5. Gunakan Role Filter.
6. Gunakan Workflow Access Filter.
7. Jangan hanya mengandalkan sidebar lock.
8. Backend harus memblokir akses langsung ke URL yang belum boleh dibuka.
9. Escape output di view.
10. Validasi semua input.
11. Validasi semua upload file.
12. Gunakan soft delete.
13. Gunakan audit log.
14. Jangan simpan file sensitif di folder public.
15. Jangan hardcode credential.
16. Gunakan `.env`.

---

## 30. Aturan Database

Database harus mengikuti workflow, bukan CRUD sederhana.

Tabel inti yang perlu dirancang:

```text
users
student_profiles
lecturer_profiles
institution_profiles
password_reset_tokens
kp_periods
kp_registrations
registration_status_logs
form_templates
form_fields
form_responses
document_templates
document_requirements
student_documents
generated_documents
partner_institutions
placement_requests
placement_choices
topsis_criteria
topsis_weights
topsis_results
supervisor_assignments
logbook_weeks
logbook_daily_entries
logbook_reviews
assessment_templates
assessment_components
assessment_scores
final_scores
audit_logs
archive_corrections
```

Aturan database:

1. Gunakan `id` sebagai primary key internal.
2. Gunakan `uuid` untuk data penting yang muncul di URL.
3. Gunakan timestamps.
4. Gunakan soft delete untuk data penting.
5. Gunakan index pada kolom pencarian utama.
6. Email harus unique.
7. NPM harus unique.
8. Kombinasi `user_id` dan `period_id` pada registrasi harus unique.
9. Status utama mahasiswa berada di `kp_registrations`.
10. Bobot TOPSIS disimpan per periode.
11. Bobot nilai disimpan per periode.
12. Form dan template harus memiliki versi.

---

## 31. Aturan Notifikasi

Gunakan:

- SweetAlert2 untuk alert.
- SweetAlert2 Toast untuk notifikasi singkat.
- Session flashdata CI4 untuk pesan setelah redirect.
- Badge notification untuk jumlah tugas tertunda.

Contoh notifikasi:

```text
Registrasi berhasil dikirim.
Registrasi perlu revisi.
Dokumen berhasil diupload.
Dokumen belum valid karena belum ada stempel.
Tempat mandiri berhasil diajukan.
Surat pengantar sudah tersedia.
Dosen pembimbing telah ditetapkan.
Logbook minggu ke-1 perlu revisi.
Nilai akhir sudah divalidasi.
```

---

## 32. Aturan Export dan PDF

Gunakan:

- Dompdf untuk PDF.
- PhpSpreadsheet untuk Excel.
- CSV untuk export ringan jika diperlukan.

Export yang dibutuhkan:

- Rekap mahasiswa per periode.
- Rekap registrasi.
- Rekap penempatan.
- Rekap dosen pembimbing.
- Rekap instansi.
- Rekap logbook.
- Rekap nilai akhir.
- Arsip periode.

---

## 33. Aturan Environment

Gunakan `.env`.

Contoh konfigurasi minimal:

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

Nama database default:

```text
db_kp_pkl
```

Jika user meminta nama database lain, sesuaikan.

---

## 34. Batasan MVP

Fokus MVP:

1. Auth multi-role.
2. Login Google untuk mahasiswa.
3. Data master.
4. Periode KP/KPL.
5. Form registrasi digital.
6. Upload dokumen persyaratan.
7. Verifikasi registrasi.
8. Penempatan mitra.
9. Penempatan mandiri.
10. TOPSIS dasar.
11. Generate dokumen PDF.
12. Upload dokumen balasan.
13. Penetapan dosen pembimbing.
14. Logbook mingguan.
15. Upload laporan akhir.
16. Penilaian instansi.
17. Penilaian dosen.
18. Rekap nilai akhir.
19. Arsip periode.
20. Audit log dasar.
21. Dashboard role-based.
22. Sidebar lock dan responsive layout.

Fitur lanjutan yang tidak wajib MVP:

- WhatsApp gateway.
- Tanda tangan digital.
- OCR dokumen.
- Chat internal.
- Notifikasi email kompleks.
- Full akun untuk instansi mandiri.
- Validasi logbook real-time oleh instansi mandiri.
- Dashboard analitik lanjutan.

---

## 35. Cara Kerja Agent

Saat mengerjakan project:

1. Baca `AGENTS.md`.
2. Baca semua file docs sesuai urutan.
3. Cek `docs/11-progress.md`.
4. Jangan langsung coding fitur besar tanpa memahami workflow.
5. Buat perubahan kecil dan terstruktur.
6. Jangan mengubah keputusan penting tanpa alasan.
7. Jika ada konflik, tuliskan catatan di progress.
8. Setelah selesai bagian besar, update progress.
9. Jangan membuat file/folder baru tanpa alasan yang jelas.
10. Jangan menghapus file penting tanpa instruksi.

---

## 36. Larangan Khusus

Agent dilarang:

1. Membuat sistem sebagai CRUD biasa.
2. Membuat semua menu terbuka tanpa workflow.
3. Mengunci menu hanya di frontend tanpa backend.
4. Menaruh file upload di public secara langsung.
5. Menggunakan Bootstrap/Vuexy sebagai framework utama.
6. Menggunakan emoji sebagai icon utama.
7. Menulis pesan validasi Bahasa Inggris.
8. Menghapus data penting secara permanen.
9. Menaruh logic TOPSIS di controller.
10. Menaruh logic workflow di view.
11. Menggunakan banyak CDN tanpa kebutuhan.
12. Mengubah struktur folder tanpa memperbarui docs.
13. Mengabaikan audit log untuk aksi penting.
14. Mengabaikan responsive mobile.
15. Mengabaikan kondisi sistem kampus saat ini.

---

## 37. Ringkasan Keputusan Final

Keputusan utama proyek:

```text
Framework: CodeIgniter 4
Database: MySQL/MariaDB
Frontend: Full Tailwind CSS CDN
UI Style: Modern SaaS Dashboard, compact, clean
Icon: Tabler Icons
Interaksi: Alpine.js
Alert/Toast: SweetAlert2
PDF: Dompdf
Excel: PhpSpreadsheet
Auth: Custom Auth + Google Login
Validasi: Bahasa Indonesia
Storage: writable/uploads dan writable/generated
URL: tanpa index.php
Layout: partials global
Sidebar: role-based, collapsible, mobile drawer
Mahasiswa: step-based flow dengan menu terkunci
TOPSIS: rekomendasi mitra, bukan keputusan final
Tempat mandiri: generate dokumen, print/tanda tangan/stempel, upload ulang
Logbook: mingguan, mobile-friendly
Penilaian: instansi + dosen + validasi akhir
Arsip: periode read-only
Audit: wajib untuk aksi penting
```

Sistem ini harus menjadi platform manajemen KP/KPL yang dapat digunakan berulang setiap tahun/periode, dengan data lama tetap tersimpan, terdokumentasi, dan dapat diakses kembali melalui arsip.
