# docs/05-sidebar-dashboard.md

# Sidebar, Dashboard, Topbar, Footer, dan Locked Menu

## 1. Tujuan Dokumen

Dokumen ini menjelaskan aturan sidebar, dashboard, topbar, footer, layout global, responsive behavior, dan locked menu untuk seluruh role.

Dokumen ini menjadi acuan saat membangun:

- Layout dashboard.
- Sidebar role-based.
- Sidebar collapsible desktop.
- Sidebar drawer mobile.
- Topbar global.
- Footer global.
- Dashboard setiap role.
- Locked menu mahasiswa.
- Partial views.
- Komponen UI dashboard.

---

## 2. Prinsip Utama Layout

Sistem menggunakan layout dashboard modern berbasis SaaS.

Prinsip utama:

1. Layout harus clean, compact, dan modern.
2. Tampilan tidak boleh terasa seperti CRUD admin panel biasa.
3. Semua role memakai layout global yang sama.
4. Sidebar, topbar, footer, dan assets harus menggunakan partials.
5. Sidebar berubah sesuai role.
6. Sidebar mahasiswa harus mengikuti status workflow.
7. Di desktop, sidebar bisa di-collapse.
8. Di mobile, sidebar menjadi hamburger drawer.
9. Topbar wajib ada.
10. Footer wajib ada, tetapi tidak boleh terlalu besar.
11. Card dashboard harus mini/compact, bukan jumbo.
12. Halaman harus nyaman dibuka di HP, terutama untuk mengisi logbook.

---

## 3. Struktur Layout Global

Gunakan satu layout utama untuk seluruh role dashboard:

```text
app/Views/layouts/app.php
```

Struktur konsep:

```php
<?= $this->include('partials/head') ?>

<body>
    <div x-data="dashboardLayout()" class="min-h-screen bg-slate-50 text-slate-900">

        <?= $this->include('partials/sidebar') ?>

        <div class="min-h-screen transition-all duration-300">
            <?= $this->include('partials/topbar') ?>

            <main class="p-4 md:p-5 lg:p-6">
                <?= $this->include('partials/flash-message') ?>
                <?= $this->renderSection('content') ?>
            </main>

            <?= $this->include('partials/footer') ?>
        </div>

        <?= $this->include('partials/scripts') ?>
    </div>
</body>
```

Catatan:

1. Jangan membuat layout dashboard berbeda untuk setiap role.
2. Perbedaan role cukup di sidebar, dashboard content, dan data yang ditampilkan.
3. Gunakan partials dan components agar konsisten.

---

## 4. Struktur Partials

Gunakan struktur berikut:

```text
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

| Partial             | Fungsi                                                                             |
| ------------------- | ---------------------------------------------------------------------------------- |
| `head.php`          | Meta, title, font, Tailwind CDN, Tabler Icons, CSS global.                         |
| `sidebar.php`       | Sidebar dinamis berdasarkan role dan status workflow.                              |
| `topbar.php`        | Header atas, collapse button, hamburger, title, breadcrumb, notification, profile. |
| `footer.php`        | Footer sederhana sistem.                                                           |
| `scripts.php`       | CDN JS global, SweetAlert, Alpine, script layout.                                  |
| `flash-message.php` | Menampilkan pesan sukses/error/warning dari session.                               |
| `breadcrumb.php`    | Menampilkan lokasi halaman jika diperlukan.                                        |

---

## 5. Struktur Components

Gunakan struktur berikut:

```text
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

Fungsi:

| Component              | Fungsi                         |
| ---------------------- | ------------------------------ |
| `stat-card.php`        | Card statistik compact.        |
| `status-badge.php`     | Badge status workflow.         |
| `progress-stepper.php` | Stepper tahapan mahasiswa.     |
| `locked-menu.php`      | Tampilan menu terkunci/gembok. |
| `empty-state.php`      | Tampilan saat data kosong.     |
| `data-table.php`       | Wrapper tabel data.            |
| `form-field.php`       | Input form konsisten.          |
| `page-header.php`      | Judul halaman dan deskripsi.   |
| `section-card.php`     | Card section konten.           |
| `action-card.php`      | Card aksi berikutnya.          |
| `timeline.php`         | Riwayat status/aktivitas.      |
| `mobile-card-list.php` | Pengganti tabel di mobile.     |

---

## 6. Gaya Visual Dashboard

Gaya visual wajib:

```text
Modern SaaS Dashboard
Compact
Clean
Responsive
Minimal
Professional
```

Aturan visual:

1. Gunakan warna dasar putih, slate, gray, dan aksen biru/indigo/emerald secukupnya.
2. Gunakan border tipis dan shadow lembut.
3. Jangan memakai card terlalu besar.
4. Jangan memakai spacing berlebihan.
5. Jangan memakai warna terlalu ramai.
6. Jangan memakai gradient berlebihan.
7. Jangan memakai icon besar-besar.
8. Jangan memakai emoji sebagai icon.
9. Gunakan font Inter.
10. Gunakan Tabler Icons sebagai icon utama.

---

## 7. Sidebar Desktop

Sidebar desktop harus tampil di sisi kiri.

Mode normal:

```text
[icon] Dashboard
[icon] Registrasi KP/KPL
[icon] Penempatan
[icon] Logbook
[icon] Laporan Akhir
```

Mode collapse:

```text
[icon]
[icon]
[icon]
[icon]
```

Aturan sidebar desktop:

1. Sidebar berada di kiri.
2. Sidebar tinggi penuh.
3. Sidebar ramping dan compact.
4. Sidebar dapat di-collapse.
5. Mode collapse hanya menampilkan icon.
6. Label disembunyikan saat collapse.
7. Tooltip muncul saat hover di mode collapse.
8. Active menu harus terlihat jelas.
9. Locked menu tetap menampilkan icon gembok kecil.
10. Content area harus melebar saat sidebar collapse.

Ukuran disarankan:

```text
Sidebar normal: 240px - 260px
Sidebar collapse: 72px - 84px
Topbar height: 56px - 64px
Content padding desktop: 20px - 24px
Content padding mobile: 14px - 16px
```

---

## 8. Sidebar Mobile

Di mobile, sidebar tidak boleh tetap menempel di kiri.

Gunakan hamburger drawer.

Alur mobile:

```text
User klik hamburger di topbar
↓
Overlay muncul
↓
Sidebar drawer tampil dari kiri
↓
User memilih menu
↓
Drawer otomatis tertutup
```

Aturan sidebar mobile:

1. Sidebar menjadi drawer/full overlay.
2. Tampilkan tombol close.
3. Gunakan overlay gelap transparan.
4. Menu harus mudah disentuh.
5. Jarak antar menu cukup untuk jari.
6. Drawer menutup saat klik overlay.
7. Drawer menutup saat menu diklik.
8. Jangan menampilkan sidebar mini di mobile.
9. Hamburger berada di topbar.
10. Topbar tetap terlihat di mobile.

---

## 9. Topbar

Topbar wajib ada untuk semua role.

Isi topbar:

```text
Tombol hamburger mobile
Tombol collapse sidebar desktop
Judul halaman
Breadcrumb kecil
Status singkat jika perlu
Notifikasi
Toggle tema terang/gelap opsional
Profile dropdown
```

Aturan topbar:

1. Topbar harus compact.
2. Tinggi topbar tidak boleh terlalu besar.
3. Topbar bisa sticky.
4. Di mobile, topbar fokus pada hamburger, judul singkat, dan profil.
5. Breadcrumb dapat disembunyikan di mobile jika terlalu sempit.
6. Profile dropdown berisi nama user, role, profil, dan logout.
7. Notifikasi menampilkan badge jumlah tugas.
8. Jangan menaruh terlalu banyak tombol di topbar mobile.

Contoh topbar desktop:

```text
[collapse] Dashboard Mahasiswa
Beranda / Dashboard                                  [Notifikasi] [Tema] [Rahmat]
```

Contoh topbar mobile:

```text
[hamburger] Dashboard                                [Profil]
```

---

## 10. Footer

Footer wajib ada tetapi sederhana.

Isi footer:

```text
© 2026 Sistem Manajemen KP/KPL
Fakultas Ilmu Komputer - Universitas Almuslim
Versi 1.0
```

Aturan footer:

1. Footer tidak fixed.
2. Footer berada di bawah content.
3. Footer compact.
4. Footer tidak mengganggu mobile.
5. Footer tampil konsisten semua role.
6. Jangan membuat footer terlalu ramai.

---

## 11. Sidebar Role-Based

Sidebar harus dibangun berdasarkan role user.

Role yang didukung:

```text
admin
koordinator
mahasiswa
dosen
instansi
```

Aturan:

1. Sidebar tidak boleh hardcode di setiap view.
2. Sidebar harus berasal dari konfigurasi/helper.
3. Menu ditampilkan sesuai role.
4. Menu aktif sesuai URL.
5. Menu terkunci sesuai status workflow mahasiswa.
6. Backend tetap menggunakan RoleFilter dan WorkflowAccessFilter.

File konfigurasi yang disarankan:

```text
app/Config/Sidebar.php
```

Alternatif sederhana:

```text
app/Helpers/sidebar_helper.php
```

Contoh struktur menu:

```php
[
    'role' => 'mahasiswa',
    'menus' => [
        [
            'label' => 'Dashboard',
            'icon' => 'ti ti-layout-dashboard',
            'url' => '/mahasiswa/dashboard',
            'required_status' => null,
        ],
        [
            'label' => 'Logbook Mingguan',
            'icon' => 'ti ti-book',
            'url' => '/mahasiswa/logbook',
            'required_status' => 'dosen_ditetapkan',
        ],
    ],
]
```

---

## 12. Locked Menu Mahasiswa

Mahasiswa harus melihat menu tahap berikutnya walaupun belum bisa dibuka.

Tujuannya:

1. Mahasiswa tahu alur berikutnya.
2. Mahasiswa paham kenapa menu belum terbuka.
3. Dashboard terasa step-based.
4. Sistem tidak membingungkan.

Aturan locked menu:

1. Menu tetap tampil.
2. Menu diberi icon gembok.
3. Warna menu lebih redup.
4. Klik menu menampilkan alasan.
5. Tidak langsung diarahkan ke halaman.
6. Backend tetap menolak URL langsung.
7. Pesan menggunakan Bahasa Indonesia.
8. Locked menu harus tetap responsive.

Contoh pesan:

```text
Menu Penempatan belum dapat dibuka karena registrasi Anda belum disetujui.
Menu Logbook belum dapat dibuka karena Anda belum diterima instansi dan dosen pembimbing belum ditetapkan.
Menu Laporan Akhir belum dapat dibuka karena tahap logbook belum berjalan.
```

---

## 13. Sidebar Admin

Menu Admin:

| Menu                    | Fungsi                              |
| ----------------------- | ----------------------------------- |
| Dashboard               | Ringkasan sistem dan periode aktif. |
| Data Master             | Kelola data dasar sistem.           |
| Data Mahasiswa          | Kelola data mahasiswa.              |
| Data Dosen              | Kelola data dosen.                  |
| Data Instansi           | Kelola data instansi.               |
| Program Studi           | Kelola data prodi.                  |
| Manajemen Akun          | Kelola akun user.                   |
| Periode KP/KPL          | Kelola periode.                     |
| Form Builder            | Kelola form dinamis.                |
| Dokumen Persyaratan     | Kelola dokumen wajib.               |
| Template Surat          | Kelola template dokumen/surat.      |
| Verifikasi Administrasi | Cek kelengkapan dokumen.            |
| Data Registrasi         | Lihat seluruh registrasi.           |
| Data Penempatan         | Lihat penempatan mahasiswa.         |
| Kriteria TOPSIS         | Kelola kriteria dan bobot TOPSIS.   |
| Logbook Mahasiswa       | Monitoring logbook.                 |
| Laporan Akhir           | Monitoring laporan akhir.           |
| Penilaian               | Monitoring penilaian.               |
| Arsip Periode           | Melihat data arsip.                 |
| Laporan & Export        | Export data.                        |
| Audit Log               | Melihat riwayat aksi penting.       |
| Pengaturan Sistem       | Konfigurasi sistem.                 |

---

## 14. Sidebar Koordinator

Menu Koordinator:

| Menu                     | Fungsi                                  |
| ------------------------ | --------------------------------------- |
| Dashboard                | Ringkasan keputusan dan tugas akademik. |
| Validasi Registrasi      | Setujui, tolak, revisi registrasi.      |
| Pengajuan Penempatan     | Validasi pengajuan mitra/mandiri.       |
| Rekomendasi TOPSIS       | Lihat hasil ranking TOPSIS.             |
| Validasi Tempat Mandiri  | Validasi instansi mandiri.              |
| Penetapan Pembimbing     | Tetapkan dosen pembimbing.              |
| Monitoring Mahasiswa     | Pantau status mahasiswa.                |
| Monitoring Logbook       | Pantau progres logbook.                 |
| Monitoring Laporan Akhir | Pantau laporan akhir.                   |
| Validasi Penilaian       | Validasi nilai instansi/dosen.          |
| Rekap Nilai Akhir        | Lihat dan validasi nilai akhir.         |
| Manajemen Periode        | Aktifkan/tutup periode sesuai hak.      |
| Arsip KP/KPL             | Lihat arsip akademik.                   |
| Laporan Rekapitulasi     | Export rekap akademik.                  |
| Catatan Keputusan        | Riwayat keputusan koordinator.          |

---

## 15. Sidebar Mahasiswa

Menu Mahasiswa:

| Menu                   | Syarat Akses                             | Fungsi                              |
| ---------------------- | ---------------------------------------- | ----------------------------------- |
| Dashboard              | Login                                    | Melihat status dan aksi berikutnya. |
| Profil Saya            | Login                                    | Melengkapi profil.                  |
| Registrasi KP/KPL      | Profil lengkap/periode aktif             | Mengisi registrasi.                 |
| Status Registrasi      | Setelah draft/submit                     | Melihat status registrasi.          |
| Penempatan KP/KPL      | `registrasi_disetujui`                   | Memilih mitra atau tempat mandiri.  |
| Rekomendasi Mitra      | `registrasi_disetujui`                   | Melihat ranking TOPSIS.             |
| Tempat Mandiri         | `registrasi_disetujui`                   | Mengajukan instansi sendiri.        |
| Surat & Dokumen        | `penempatan_disetujui`                   | Download dokumen/surat.             |
| Upload Dokumen Balasan | `surat_tersedia`                         | Upload surat penerimaan/berstempel. |
| Pembimbing Saya        | `dosen_ditetapkan`                       | Melihat dosen pembimbing.           |
| Logbook Mingguan       | `dosen_ditetapkan` / `sedang_berjalan`   | Mengisi logbook.                    |
| Catatan Dosen          | `logbook_berjalan`                       | Melihat komentar/revisi.            |
| Laporan Akhir          | `logbook_berjalan`                       | Upload laporan akhir.               |
| Penilaian Saya         | `menunggu_penilaian_instansi` atau lebih | Melihat status nilai.               |
| Riwayat KP/KPL         | Ada riwayat                              | Melihat arsip milik sendiri.        |
| Notifikasi             | Login                                    | Melihat pemberitahuan.              |

Catatan:

1. Menu terkunci tetap tampil.
2. Menu terkunci memakai gembok.
3. Klik menu terkunci menampilkan alasan.
4. Backend tetap memblokir akses langsung.

---

## 16. Sidebar Dosen Pembimbing

Menu Dosen:

| Menu                | Fungsi                                 |
| ------------------- | -------------------------------------- |
| Dashboard           | Ringkasan mahasiswa bimbingan.         |
| Mahasiswa Bimbingan | Daftar mahasiswa yang dibimbing.       |
| Detail Mahasiswa    | Data dan status mahasiswa.             |
| Logbook Mahasiswa   | Melihat logbook mahasiswa.             |
| Review Logbook      | Setujui/revisi logbook.                |
| Catatan Bimbingan   | Catatan untuk mahasiswa.               |
| Laporan Akhir       | Review laporan akhir.                  |
| Penilaian Dosen     | Input nilai akademik.                  |
| Riwayat Bimbingan   | Arsip mahasiswa yang pernah dibimbing. |
| Kuota Bimbingan     | Melihat kuota bimbingan.               |
| Notifikasi          | Pemberitahuan tugas dosen.             |

---

## 17. Sidebar Instansi Mitra

Menu Instansi:

| Menu                  | Fungsi                             |
| --------------------- | ---------------------------------- |
| Dashboard             | Ringkasan mahasiswa di instansi.   |
| Profil Instansi       | Mengelola profil instansi.         |
| Mahasiswa KP/KPL      | Daftar mahasiswa ditempatkan.      |
| Konfirmasi Penerimaan | Terima/tolak mahasiswa.            |
| Pembimbing Lapangan   | Data pembimbing lapangan.          |
| Logbook Mahasiswa     | Melihat logbook jika fitur aktif.  |
| Validasi Logbook      | Validasi logbook jika fitur aktif. |
| Evaluasi Mahasiswa    | Memberi evaluasi.                  |
| Penilaian Instansi    | Input nilai instansi.              |
| Riwayat Mahasiswa     | Arsip mahasiswa di instansi.       |
| Dokumen               | Melihat dokumen terkait.           |
| Notifikasi            | Pemberitahuan instansi.            |

Catatan:

1. Untuk tempat mandiri, akun instansi tidak wajib pada MVP.
2. Penilaian tempat mandiri dapat memakai dokumen manual upload ulang.

---

## 18. Dashboard Admin

Dashboard Admin harus compact dan menampilkan ringkasan operasional.

Bagian atas:

```text
Card mini:
- Periode Aktif
- Total Mahasiswa
- Registrasi Menunggu Verifikasi
- Dokumen Menunggu Verifikasi
- Instansi Mitra
- Dosen Aktif
```

Bagian tengah:

```text
Chart:
- Status mahasiswa per periode
- Distribusi penempatan per instansi/prodi
```

Bagian bawah:

```text
Tabel/List ringkas:
- Registrasi terbaru
- Dokumen terbaru
- Aktivitas sistem terbaru
```

Aturan:

1. Card tidak boleh terlalu besar.
2. Tampilkan data prioritas.
3. Gunakan tabel ringkas.
4. Mobile berubah menjadi card list.

---

## 19. Dashboard Koordinator

Dashboard Koordinator fokus pada keputusan yang perlu ditindaklanjuti.

Bagian atas:

```text
Card mini:
- Registrasi Menunggu Keputusan
- Pengajuan Tempat Mandiri
- Penempatan Menunggu Validasi
- Dosen Hampir Penuh Kuota
- Nilai Menunggu Validasi
```

Bagian tengah:

```text
Chart/List:
- Status mahasiswa per tahap
- Distribusi mahasiswa per dosen pembimbing
```

Bagian bawah:

```text
Task list:
- Registrasi perlu diputuskan
- Pengajuan mandiri terbaru
- Nilai akhir menunggu validasi
- Mahasiswa bermasalah/terlambat logbook
```

Aturan:

1. Dashboard harus membantu Koordinator mengambil keputusan cepat.
2. Data harus berdasarkan periode aktif.
3. Setiap task harus memiliki tombol aksi.

---

## 20. Dashboard Mahasiswa

Dashboard Mahasiswa adalah dashboard paling penting untuk UX.

Dashboard harus menjawab:

```text
Saya sedang di tahap apa?
Apa yang harus saya lakukan sekarang?
Menu apa yang belum bisa saya buka dan kenapa?
```

Bagian atas:

```text
Card mini:
- Status Saat Ini
- Periode Aktif
- Tahap Aktif
- Logbook Terkirim
```

Bagian utama:

```text
Progress Stepper:
1. Profil
2. Registrasi
3. Penempatan
4. Dokumen
5. Pembimbing
6. Logbook
7. Laporan Akhir
8. Penilaian
9. Selesai
```

Bagian aksi:

```text
Aksi Berikutnya:
- Lengkapi profil
- Submit registrasi
- Perbaiki dokumen
- Pilih penempatan
- Upload dokumen balasan
- Isi logbook minggu ini
```

Bagian bawah:

```text
- Catatan revisi terbaru
- Dokumen terbaru
- Notifikasi
- Riwayat status
```

Aturan:

1. Jangan tampilkan dashboard kosong.
2. Jangan hanya tampilkan tabel.
3. Gunakan progress stepper.
4. Gunakan card aksi berikutnya.
5. Tampilkan locked menu dengan alasan.
6. Harus sangat nyaman di mobile.

---

## 21. Dashboard Dosen

Dashboard Dosen fokus pada bimbingan dan review.

Bagian atas:

```text
Card mini:
- Total Mahasiswa Bimbingan
- Logbook Menunggu Review
- Laporan Akhir Menunggu Review
- Nilai Belum Diisi
```

Bagian tengah:

```text
List:
- Mahasiswa perlu perhatian
- Logbook terbaru
- Laporan terbaru
```

Bagian bawah:

```text
Riwayat bimbingan ringkas
```

Aturan:

1. Dosen hanya melihat mahasiswa bimbingannya.
2. Task review harus jelas.
3. Tampilkan tombol aksi cepat.

---

## 22. Dashboard Instansi

Dashboard Instansi fokus pada mahasiswa yang ditempatkan.

Bagian atas:

```text
Card mini:
- Mahasiswa Aktif
- Menunggu Konfirmasi
- Evaluasi Belum Diisi
- Riwayat Mahasiswa
```

Bagian tengah:

```text
Daftar mahasiswa aktif
```

Bagian bawah:

```text
Dokumen dan notifikasi
```

Aturan:

1. Instansi hanya melihat mahasiswa di instansinya.
2. Jangan tampilkan data instansi lain.
3. Untuk MVP, fitur ini fokus pada instansi mitra resmi.

---

## 23. Responsive Behavior

Aturan responsive:

### Desktop

```text
Sidebar kiri tampil.
Sidebar bisa collapse.
Topbar full.
Card grid 4-6 kolom.
Tabel tampil normal.
```

### Tablet

```text
Sidebar bisa collapse.
Card grid 2-3 kolom.
Tabel dapat scroll horizontal.
```

### Mobile

```text
Sidebar menjadi hamburger drawer.
Topbar compact.
Card menjadi 1 kolom.
Tabel berubah menjadi card list jika perlu.
Form full width.
Tombol dibuat mudah disentuh.
Logbook dibuat nyaman diisi.
```

---

## 24. Halaman Logbook Mobile

Karena mahasiswa kemungkinan besar mengisi logbook dari HP, halaman logbook harus mobile-first.

Aturan:

1. Jangan pakai tabel sempit untuk input logbook.
2. Gunakan form card.
3. Textarea harus cukup tinggi.
4. Tombol simpan draft dan submit mudah dijangkau.
5. Upload dokumentasi mudah digunakan.
6. Riwayat logbook tampil sebagai list/card.
7. Status logbook tampil jelas.
8. Catatan dosen mudah dibaca.

Contoh layout mobile:

```text
Minggu 1
Status: Draft

Tanggal
[ input tanggal ]

Kegiatan Hari Ini
[ textarea ]

Hasil Kegiatan
[ textarea ]

Kendala
[ textarea ]

Upload Dokumentasi
[ pilih file ]

[Simpan Draft]
[Kirim Logbook]
```

---

## 25. Status Badge

Gunakan badge status yang konsisten.

Contoh:

| Status Database        | Label UI             | Warna  |
| ---------------------- | -------------------- | ------ |
| `draft`                | Draft                | Abu    |
| `menunggu_verifikasi`  | Menunggu Verifikasi  | Kuning |
| `revisi_registrasi`    | Perlu Revisi         | Oranye |
| `registrasi_disetujui` | Registrasi Disetujui | Hijau  |
| `penempatan_disetujui` | Penempatan Disetujui | Hijau  |
| `diterima_instansi`    | Diterima Instansi    | Biru   |
| `dosen_ditetapkan`     | Dosen Ditetapkan     | Biru   |
| `selesai`              | Selesai              | Hijau  |
| `diarsipkan`           | Diarsipkan           | Slate  |

Aturan:

1. Status database tetap `snake_case`.
2. Label UI Bahasa Indonesia.
3. Jangan menampilkan raw status database kepada user awam.
4. Warna harus konsisten seluruh halaman.

---

## 26. Empty State

Setiap halaman yang belum memiliki data harus memiliki empty state.

Contoh:

```text
Belum ada logbook.
Silakan buat logbook minggu pertama setelah dosen pembimbing ditetapkan.
```

Aturan:

1. Jangan biarkan halaman kosong.
2. Empty state harus menjelaskan kondisi.
3. Jika ada aksi berikutnya, tampilkan tombol.
4. Gunakan icon Tabler, bukan emoji.

---

## 27. Data Table

Tabel digunakan untuk data besar pada Admin dan Koordinator.

Aturan:

1. Gunakan DataTables hanya jika data banyak.
2. Untuk data besar, gunakan server-side processing.
3. Di mobile, tabel boleh scroll horizontal atau berubah menjadi card list.
4. Kolom aksi harus compact.
5. Filter harus jelas.
6. Jangan menampilkan terlalu banyak kolom sekaligus.
7. Gunakan badge untuk status.

---

## 28. SweetAlert dan Toast

Gunakan SweetAlert2 untuk:

1. Konfirmasi setujui.
2. Konfirmasi tolak.
3. Konfirmasi hapus.
4. Konfirmasi tutup periode.
5. Notifikasi sukses.
6. Notifikasi error.
7. Pesan locked menu.

Contoh pesan:

```text
Registrasi berhasil disetujui.
Dokumen berhasil diverifikasi.
Menu ini belum dapat dibuka karena registrasi Anda belum disetujui.
Yakin ingin menolak pengajuan ini?
```

Aturan:

1. Jangan gunakan `alert()` bawaan JavaScript.
2. Semua pesan Bahasa Indonesia.
3. Untuk aksi penting, gunakan modal konfirmasi.
4. Untuk sukses ringan, gunakan toast.

---

## 29. Dark Mode

Dark mode bersifat opsional.

Jika dibuat:

1. Gunakan `darkMode: 'class'` pada Tailwind.
2. Toggle tersedia di topbar.
3. Simpan preferensi di localStorage.
4. Pastikan semua komponen tetap terbaca.
5. Jangan mengutamakan dark mode dibanding fitur utama.

---

## 30. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Membuat sidebar hardcode per halaman.
2. Membuat layout berbeda-beda untuk setiap role tanpa alasan.
3. Membuka semua menu mahasiswa dari awal.
4. Mengunci menu hanya di frontend.
5. Mengabaikan hamburger menu mobile.
6. Membuat sidebar desktop terlalu besar.
7. Membuat card dashboard terlalu jumbo.
8. Membuat topbar terlalu tinggi.
9. Menggunakan emoji sebagai icon utama.
10. Mencampur Bootstrap/Vuexy sebagai framework utama.
11. Membuat halaman logbook sulit digunakan di HP.
12. Menampilkan halaman kosong tanpa empty state.
13. Menampilkan status database mentah kepada user.

---

## 31. Ringkasan Final

Keputusan layout final:

```text
Layout: SaaS dashboard compact
Frontend: Tailwind CSS CDN
Icon: Tabler Icons
Interaksi: Alpine.js
Alert: SweetAlert2
Sidebar: role-based
Sidebar desktop: collapsible
Sidebar mobile: hamburger drawer
Topbar: wajib
Footer: wajib
Mahasiswa: locked menu dengan icon gembok
Dashboard mahasiswa: progress stepper + aksi berikutnya
Mobile: wajib nyaman untuk logbook
Partials: wajib
Components: wajib
```

Sistem harus terlihat modern, ringkas, profesional, dan mudah digunakan oleh semua role.
