# docs/08-ui-ux-rules.md

# Aturan UI/UX Sistem KP/KPL

## 1. Tujuan Dokumen

Dokumen ini menjelaskan aturan desain UI/UX untuk Sistem Manajemen KP/KPL.

Dokumen ini wajib menjadi acuan saat membuat:

- Layout dashboard.
- Sidebar.
- Topbar.
- Footer.
- Card statistik.
- Form.
- Tabel.
- Logbook mobile.
- Status badge.
- Progress stepper.
- Locked menu.
- Empty state.
- Modal konfirmasi.
- Halaman setiap role.

Sistem harus terlihat modern, bersih, compact, responsive, dan mudah digunakan.

---

## 2. Prinsip Utama UI/UX

Prinsip utama:

1. UI harus modern seperti SaaS dashboard.
2. UI harus compact, tidak besar-besar.
3. UI harus clean dan profesional.
4. UI harus workflow-based, bukan CRUD-based.
5. UI harus responsive untuk desktop, tablet, dan mobile.
6. UI harus nyaman digunakan mahasiswa dari HP.
7. UI harus memudahkan user memahami status proses.
8. UI harus menampilkan aksi berikutnya secara jelas.
9. UI tidak boleh membingungkan user awam.
10. UI harus konsisten untuk semua role.

---

## 3. Gaya Visual Utama

Gunakan gaya:

```text
Modern SaaS Dashboard
Compact
Clean
Minimal
Professional
Responsive
Mobile-friendly
```

Ciri visual:

1. Sidebar ramping.
2. Topbar kecil dan bersih.
3. Card statistik mini.
4. Border tipis.
5. Shadow lembut.
6. Warna tidak ramai.
7. Spasi rapi.
8. Font modern.
9. Icon konsisten.
10. Elemen tidak berlebihan.

Hindari:

1. Card jumbo.
2. Padding terlalu besar.
3. Sidebar terlalu lebar.
4. Header terlalu tinggi.
5. Warna terlalu mencolok.
6. Gradient berlebihan.
7. Emoji sebagai icon utama.
8. Tampilan seperti template CRUD lama.
9. Tabel terlalu padat tanpa filter.
10. Form panjang tanpa pembagian section.

---

## 4. Warna

Gunakan warna dasar yang lembut dan profesional.

### Warna Dasar

```text
Background utama: slate-50 / gray-50 / white
Card: white
Text utama: slate-900
Text sekunder: slate-500 / slate-600
Border: slate-200
```

### Warna Aksen

Gunakan satu warna aksen utama:

```text
Biru / Indigo / Sky
```

Contoh penggunaan:

```text
Button utama
Active sidebar
Link penting
Progress aktif
Badge informasi
```

### Warna Status

Gunakan warna status yang konsisten:

| Status       | Warna     |
| ------------ | --------- |
| Draft        | Abu       |
| Menunggu     | Kuning    |
| Perlu Revisi | Oranye    |
| Ditolak      | Merah     |
| Disetujui    | Hijau     |
| Aktif        | Biru      |
| Selesai      | Hijau     |
| Terkunci     | Slate/Abu |
| Arsip        | Slate     |

Aturan:

1. Jangan memakai terlalu banyak warna dalam satu halaman.
2. Warna harus membantu pemahaman status.
3. Jangan memakai warna hanya untuk dekorasi.
4. Pastikan kontras teks tetap terbaca.

---

## 5. Typography

Gunakan font:

```text
Inter
```

Aturan typography:

1. Gunakan ukuran font compact.
2. Judul halaman tidak terlalu besar.
3. Label form harus jelas.
4. Teks bantuan memakai ukuran kecil tetapi tetap terbaca.
5. Hindari font terlalu tipis.
6. Hindari terlalu banyak variasi font weight.

Ukuran yang disarankan:

```text
Judul halaman: 20px - 24px
Subjudul: 14px - 16px
Body: 14px
Label: 13px - 14px
Helper text: 12px - 13px
Badge: 11px - 12px
```

---

## 6. Icon

Gunakan:

```text
Tabler Icons
```

Aturan icon:

1. Gunakan icon dari satu library utama.
2. Jangan gunakan emoji sebagai icon utama.
3. Icon sidebar harus konsisten.
4. Icon card statistik harus kecil.
5. Icon locked menu memakai icon gembok.
6. Icon tidak boleh terlalu besar.
7. Icon harus membantu pemahaman menu.

Contoh icon:

```html
<i class="ti ti-layout-dashboard"></i>
<i class="ti ti-user"></i>
<i class="ti ti-file-text"></i>
<i class="ti ti-lock"></i>
<i class="ti ti-book"></i>
<i class="ti ti-bell"></i>
<i class="ti ti-check"></i>
<i class="ti ti-alert-circle"></i>
```

---

## 7. Layout Global

Semua role menggunakan layout global yang sama:

```text
Sidebar
Topbar
Main Content
Footer
```

Struktur:

```text
app/Views/layouts/app.php
app/Views/partials/sidebar.php
app/Views/partials/topbar.php
app/Views/partials/footer.php
app/Views/partials/head.php
app/Views/partials/scripts.php
```

Aturan:

1. Jangan membuat layout dashboard berbeda untuk setiap role.
2. Jangan menulis ulang sidebar di setiap halaman.
3. Gunakan partials.
4. Gunakan components untuk elemen berulang.
5. Content setiap role boleh berbeda, tapi kerangka layout tetap sama.

---

## 8. Sidebar

Sidebar harus:

1. Role-based.
2. Workflow-aware.
3. Compact.
4. Collapsible di desktop.
5. Drawer/hamburger di mobile.
6. Menampilkan active menu.
7. Menampilkan locked menu jika mahasiswa belum sampai tahap tertentu.

### Desktop Normal

```text
[icon] Dashboard
[icon] Registrasi KP/KPL
[icon] Penempatan
[icon] Logbook
```

### Desktop Collapse

```text
[icon]
[icon]
[icon]
[icon]
```

Aturan desktop:

1. Sidebar normal lebar sekitar 240px - 260px.
2. Sidebar collapse lebar sekitar 72px - 84px.
3. Saat collapse, hanya icon yang tampil.
4. Tooltip muncul saat hover.
5. Content area melebar saat collapse.
6. Active menu tetap jelas.

### Mobile

Di mobile sidebar menjadi drawer.

Aturan mobile:

1. Sidebar dibuka melalui hamburger.
2. Sidebar muncul sebagai drawer/full overlay.
3. Ada overlay gelap transparan.
4. Ada tombol close.
5. Menu mudah disentuh.
6. Drawer tertutup setelah memilih menu.
7. Jangan tampilkan sidebar mini di mobile.

---

## 9. Topbar

Topbar wajib ada.

Isi topbar:

```text
Tombol hamburger mobile
Tombol collapse sidebar desktop
Judul halaman
Breadcrumb kecil
Notifikasi
Toggle tema jika ada
Profile dropdown
```

Aturan:

1. Topbar harus compact.
2. Tinggi topbar sekitar 56px - 64px.
3. Jangan terlalu banyak tombol di mobile.
4. Breadcrumb boleh disembunyikan di mobile.
5. Profile dropdown harus berisi nama, role, profil, dan logout.
6. Notifikasi menggunakan badge kecil.
7. Topbar boleh sticky.

---

## 10. Footer

Footer wajib ada tetapi sederhana.

Isi footer:

```text
© 2026 Sistem Manajemen KP/KPL
Fakultas Ilmu Komputer - Universitas Almuslim
Versi 1.0
```

Aturan:

1. Footer tidak fixed.
2. Footer tidak terlalu tinggi.
3. Footer tidak mengganggu mobile.
4. Footer tampil konsisten semua role.
5. Jangan memasukkan terlalu banyak link.

---

## 11. Dashboard

Dashboard setiap role harus berbeda sesuai kebutuhan.

Aturan umum dashboard:

1. Gunakan card statistik mini.
2. Gunakan grid responsive.
3. Gunakan chart hanya jika berguna.
4. Gunakan tabel/list ringkas.
5. Tampilkan aksi yang perlu dilakukan.
6. Jangan tampilkan dashboard kosong.
7. Jangan hanya menampilkan tabel.
8. Dashboard harus menjawab kebutuhan role.

### Admin

Dashboard Admin fokus pada operasional sistem.

Tampilkan:

```text
Periode aktif
Total mahasiswa
Registrasi menunggu verifikasi
Dokumen menunggu verifikasi
Instansi mitra
Dosen aktif
Aktivitas terbaru
```

### Koordinator

Dashboard Koordinator fokus pada keputusan.

Tampilkan:

```text
Registrasi menunggu keputusan
Pengajuan tempat mandiri
Penempatan menunggu validasi
Dosen penuh kuota
Nilai menunggu validasi
Mahasiswa bermasalah
```

### Mahasiswa

Dashboard Mahasiswa fokus pada status dan langkah berikutnya.

Tampilkan:

```text
Status saat ini
Periode aktif
Progress stepper
Aksi berikutnya
Catatan revisi
Dokumen terbaru
Notifikasi
Riwayat status
```

### Dosen

Dashboard Dosen fokus pada bimbingan.

Tampilkan:

```text
Mahasiswa bimbingan
Logbook menunggu review
Laporan akhir menunggu review
Nilai belum diisi
Mahasiswa perlu perhatian
```

### Instansi

Dashboard Instansi fokus pada mahasiswa di instansi.

Tampilkan:

```text
Mahasiswa aktif
Menunggu konfirmasi
Evaluasi belum diisi
Riwayat mahasiswa
Dokumen terkait
```

---

## 12. Card Statistik

Card statistik harus compact.

Aturan:

1. Jangan terlalu tinggi.
2. Padding sedang.
3. Angka utama jelas.
4. Label kecil dan ringkas.
5. Icon kecil.
6. Border tipis.
7. Shadow lembut.
8. Responsive.

Contoh isi card:

```text
Total Mahasiswa
Menunggu Verifikasi
Tempat Mandiri
Logbook Menunggu Review
Nilai Belum Valid
```

Ukuran visual:

```text
Desktop: grid 4-6 kolom
Tablet: grid 2-3 kolom
Mobile: 1 kolom atau 2 kolom jika cukup
```

---

## 13. Progress Stepper

Progress stepper wajib ada di dashboard mahasiswa.

Tahap:

```text
Profil
Registrasi
Penempatan
Dokumen
Pembimbing
Logbook
Laporan Akhir
Penilaian
Selesai
```

Aturan:

1. Tahap selesai diberi tanda selesai.
2. Tahap aktif diberi highlight.
3. Tahap belum terbuka diberi style redup/gembok.
4. Stepper desktop bisa horizontal.
5. Stepper mobile bisa horizontal scroll atau vertical.
6. Stepper harus menampilkan status proses mahasiswa dengan jelas.

---

## 14. Locked Menu

Locked menu digunakan terutama untuk mahasiswa.

Aturan:

1. Menu tetap terlihat.
2. Menu diberi icon gembok.
3. Warna menu lebih redup.
4. Klik menu menampilkan alasan.
5. Jangan langsung masuk ke halaman.
6. Backend tetap memblokir akses langsung.
7. Pesan harus Bahasa Indonesia.
8. Pesan harus spesifik.

Contoh pesan:

```text
Menu Penempatan belum dapat dibuka karena registrasi Anda belum disetujui.
Menu Logbook belum dapat dibuka karena Anda belum diterima instansi dan dosen pembimbing belum ditetapkan.
Menu Laporan Akhir belum dapat dibuka karena tahap logbook belum berjalan.
```

---

## 15. Status Badge

Status badge harus konsisten.

Contoh:

| Status Database        | Label UI             |
| ---------------------- | -------------------- |
| `draft`                | Draft                |
| `menunggu_verifikasi`  | Menunggu Verifikasi  |
| `revisi_registrasi`    | Perlu Revisi         |
| `registrasi_ditolak`   | Ditolak              |
| `registrasi_disetujui` | Disetujui            |
| `penempatan_disetujui` | Penempatan Disetujui |
| `diterima_instansi`    | Diterima Instansi    |
| `dosen_ditetapkan`     | Dosen Ditetapkan     |
| `sedang_berjalan`      | Sedang Berjalan      |
| `selesai`              | Selesai              |
| `diarsipkan`           | Diarsipkan           |

Aturan:

1. Jangan tampilkan raw status database ke user.
2. Label UI harus Bahasa Indonesia.
3. Warna badge harus konsisten.
4. Badge tidak boleh terlalu besar.
5. Badge harus mudah dibaca.

---

## 16. Form

Form harus nyaman digunakan.

Aturan form:

1. Input full width di mobile.
2. Label jelas.
3. Helper text jika perlu.
4. Error text berada dekat field.
5. Required field ditandai.
6. Form panjang dibagi menjadi beberapa section.
7. Gunakan step/tab jika form terlalu panjang.
8. Tombol utama jelas.
9. Tombol batal/kembali tidak boleh lebih dominan dari tombol utama.
10. Validasi backend wajib.
11. Validasi frontend hanya tambahan.

Contoh section form registrasi:

```text
Data Diri
Data Akademik
Dokumen Persyaratan
Preferensi Lokasi
Pernyataan
Review & Submit
```

---

## 17. Upload File

UI upload file harus jelas.

Aturan:

1. Tampilkan format file yang diperbolehkan.
2. Tampilkan ukuran maksimal.
3. Tampilkan status upload.
4. Tampilkan nama file yang sudah diupload.
5. Tampilkan status verifikasi dokumen.
6. Jika dokumen perlu revisi, tampilkan catatan.
7. Jika dokumen sudah valid, jangan tampilkan tombol hapus sembarangan.
8. Upload harus mudah dilakukan dari HP.

Contoh teks:

```text
Format file: PDF, JPG, JPEG, PNG
Ukuran maksimal: 10 MB
Pastikan dokumen terlihat jelas dan memiliki tanda tangan/stempel jika diwajibkan.
```

---

## 18. Tabel

Tabel digunakan untuk Admin, Koordinator, Dosen, dan Instansi.

Aturan:

1. Gunakan tabel untuk data banyak.
2. Tabel harus compact.
3. Kolom aksi harus ringkas.
4. Status menggunakan badge.
5. Gunakan filter dan search.
6. Gunakan pagination.
7. Jangan menampilkan terlalu banyak kolom.
8. Di mobile, tabel boleh berubah menjadi card list.
9. Untuk data besar, gunakan server-side processing.

---

## 19. Mobile Card List

Di mobile, data yang kompleks lebih baik ditampilkan sebagai card list.

Contoh:

```text
Rahmat Mulia
NPM: 235520110141
Status: Menunggu Verifikasi
Periode: KP 2026

[Lihat Detail]
```

Aturan:

1. Card list harus ringkas.
2. Tampilkan informasi paling penting saja.
3. Tombol aksi harus mudah disentuh.
4. Jangan paksa user membaca tabel sempit.

---

## 20. Logbook Mobile

Halaman logbook harus mobile-first.

Aturan:

1. Jangan memakai tabel untuk input logbook.
2. Gunakan card/form section.
3. Textarea harus cukup besar.
4. Tombol Simpan Draft dan Kirim harus jelas.
5. Upload dokumentasi harus mudah.
6. Catatan dosen harus mudah dibaca.
7. Status logbook harus terlihat.
8. Riwayat logbook tampil sebagai list/card.
9. Mahasiswa bisa mengisi cepat dari HP.
10. Jangan membuat halaman terlalu berat.

Contoh struktur:

```text
Logbook Minggu 1
Status: Draft

Tanggal
[ input tanggal ]

Jam Mulai
[ input ]

Jam Selesai
[ input ]

Uraian Kegiatan
[ textarea ]

Hasil Kegiatan
[ textarea ]

Kendala
[ textarea ]

Dokumentasi
[ upload ]

[Simpan Draft]
[Kirim Logbook]
```

---

## 21. Empty State

Setiap halaman kosong harus memiliki empty state.

Contoh:

```text
Belum ada logbook.
Logbook akan tersedia setelah dosen pembimbing ditetapkan.
```

Aturan:

1. Jangan biarkan halaman kosong.
2. Jelaskan kondisi.
3. Tampilkan aksi jika ada.
4. Gunakan icon Tabler.
5. Jangan gunakan emoji besar.

---

## 22. Modal dan Konfirmasi

Gunakan SweetAlert2.

Aksi yang butuh konfirmasi:

```text
Setujui registrasi
Tolak registrasi
Minta revisi
Setujui penempatan
Tolak penempatan
Tetapkan dosen pembimbing
Validasi nilai akhir
Tutup periode
Arsipkan periode
Hapus/nonaktifkan data
```

Aturan:

1. Judul jelas.
2. Pesan menjelaskan dampak aksi.
3. Tombol utama sesuai aksi.
4. Aksi bahaya memakai warna danger.
5. Semua aksi tetap divalidasi backend.
6. Jangan gunakan alert browser bawaan.

---

## 23. Notification dan Toast

Gunakan toast untuk pesan ringan.

Contoh:

```text
Registrasi berhasil dikirim.
Dokumen berhasil diupload.
Data berhasil disimpan.
Logbook berhasil dikirim.
```

Gunakan modal/alert untuk pesan penting:

```text
Registrasi Anda perlu revisi.
Dokumen ditolak karena tidak ada stempel.
Menu belum bisa dibuka.
```

Aturan:

1. Semua pesan Bahasa Indonesia.
2. Jangan terlalu sering menampilkan toast.
3. Toast tidak boleh menutupi tombol penting.
4. Notifikasi penting juga disimpan di database.

---

## 24. Breadcrumb

Breadcrumb digunakan untuk halaman dalam.

Contoh:

```text
Dashboard / Registrasi / Detail
```

Aturan:

1. Breadcrumb compact.
2. Boleh disembunyikan di mobile.
3. Jangan terlalu panjang.
4. Gunakan label Bahasa Indonesia.

---

## 25. Page Header

Setiap halaman utama harus punya page header.

Isi:

```text
Judul halaman
Deskripsi singkat
Aksi utama jika ada
```

Contoh:

```text
Registrasi KP/KPL
Lengkapi data akademik dan dokumen persyaratan untuk mengikuti KP/KPL.
```

Aturan:

1. Judul jelas.
2. Deskripsi singkat.
3. Aksi utama di kanan desktop.
4. Aksi utama bisa di bawah judul pada mobile.

---

## 26. Timeline Status

Timeline digunakan untuk riwayat status mahasiswa.

Contoh:

```text
Registrasi dikirim
Dokumen diverifikasi
Registrasi disetujui
Penempatan diajukan
Penempatan disetujui
Dosen ditetapkan
```

Aturan:

1. Timeline tampil di detail mahasiswa.
2. Timeline tampil di dashboard mahasiswa jika perlu.
3. Timeline menggunakan data `registration_status_logs`.
4. Jangan menampilkan data teknis mentah.
5. Catatan revisi harus terlihat.

---

## 27. Responsive Breakpoint

Gunakan pendekatan mobile-first.

Aturan:

```text
Mobile: 1 kolom
Tablet: 2-3 kolom
Desktop: 4-6 kolom untuk card
```

Perilaku:

| Perangkat | Aturan                                                               |
| --------- | -------------------------------------------------------------------- |
| Mobile    | Sidebar drawer, card 1 kolom, form full width, tabel jadi card list. |
| Tablet    | Sidebar collapse/drawer, card 2-3 kolom.                             |
| Desktop   | Sidebar tampil, card 4-6 kolom, tabel normal.                        |

---

## 28. Accessibility Dasar

Aturan aksesibilitas dasar:

1. Button harus jelas.
2. Link harus bisa dikenali.
3. Text harus kontras.
4. Form label wajib ada.
5. Jangan mengandalkan warna saja untuk status.
6. Icon penting harus didampingi label/tooltip.
7. Tombol mobile cukup besar untuk disentuh.
8. Fokus keyboard tetap terlihat jika memungkinkan.

---

## 29. Dark Mode

Dark mode opsional.

Jika dibuat:

1. Gunakan Tailwind `darkMode: 'class'`.
2. Toggle dark mode di topbar.
3. Simpan preferensi di localStorage.
4. Pastikan semua card terbaca.
5. Pastikan status badge tetap jelas.
6. Jangan menghabiskan waktu terlalu banyak untuk dark mode pada MVP.

---

## 30. Loading State

Setiap aksi yang memproses data harus memiliki loading state.

Contoh:

```text
Menyimpan...
Mengupload...
Mengirim registrasi...
Memproses rekomendasi...
Menggenerate dokumen...
```

Aturan:

1. Button disable saat proses.
2. Jangan izinkan double submit.
3. Tampilkan spinner kecil jika perlu.
4. Setelah sukses/gagal, tampilkan pesan.

---

## 31. Error State

Pesan error harus jelas.

Contoh baik:

```text
Dokumen gagal diupload karena ukuran file melebihi 10 MB.
Menu Logbook belum dapat dibuka karena dosen pembimbing belum ditetapkan.
Periode pendaftaran belum aktif.
```

Contoh buruk:

```text
Error.
Access denied.
Invalid.
Failed.
```

Aturan:

1. Pesan Bahasa Indonesia.
2. Jelaskan penyebab.
3. Beri solusi jika mungkin.
4. Jangan tampilkan error teknis ke user biasa.

---

## 32. UX untuk Mahasiswa

Mahasiswa adalah user utama yang harus dibuat paling mudah.

Dashboard mahasiswa harus selalu menampilkan:

1. Status saat ini.
2. Progress stepper.
3. Aksi berikutnya.
4. Catatan revisi jika ada.
5. Dokumen yang perlu diperbaiki.
6. Menu terkunci dan alasannya.
7. Riwayat status.

Prinsip:

```text
Mahasiswa tidak perlu menebak harus melakukan apa.
```

---

## 33. UX untuk Admin

Admin butuh efisiensi.

Admin harus mudah:

1. Mencari data mahasiswa.
2. Memfilter berdasarkan periode/status.
3. Mengecek dokumen.
4. Melihat dokumen perlu verifikasi.
5. Mengelola data master.
6. Export laporan.
7. Melihat audit log.

Prinsip:

```text
Admin harus cepat menemukan data.
```

---

## 34. UX untuk Koordinator

Koordinator butuh keputusan cepat.

Koordinator harus mudah:

1. Melihat registrasi yang perlu keputusan.
2. Melihat pengajuan tempat mandiri.
3. Melihat hasil TOPSIS.
4. Menetapkan dosen berdasarkan kuota.
5. Melihat nilai menunggu validasi.
6. Melihat mahasiswa bermasalah.

Prinsip:

```text
Koordinator harus cepat mengambil keputusan dengan data yang jelas.
```

---

## 35. UX untuk Dosen

Dosen butuh review cepat.

Dosen harus mudah:

1. Melihat mahasiswa bimbingan.
2. Melihat logbook menunggu review.
3. Memberi catatan.
4. Melihat laporan akhir.
5. Mengisi nilai.

Prinsip:

```text
Dosen tidak boleh kesulitan mencari mahasiswa yang perlu direview.
```

---

## 36. UX untuk Instansi

Instansi butuh tampilan sederhana.

Instansi harus mudah:

1. Melihat mahasiswa aktif.
2. Konfirmasi penerimaan.
3. Memberi evaluasi.
4. Mengisi nilai.
5. Melihat dokumen terkait.

Prinsip:

```text
Instansi tidak boleh diberi menu terlalu banyak.
```

---

## 37. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Membuat dashboard seperti CRUD biasa.
2. Membuat card terlalu besar.
3. Membuat sidebar terlalu lebar.
4. Membuka semua menu mahasiswa dari awal.
5. Menghilangkan locked menu mahasiswa.
6. Membuat logbook sulit diisi dari HP.
7. Menggunakan emoji sebagai icon utama.
8. Mencampur Bootstrap/Vuexy sebagai framework utama.
9. Membuat halaman kosong tanpa empty state.
10. Menampilkan raw status database ke user.
11. Menggunakan pesan error Bahasa Inggris.
12. Menggunakan tabel sempit untuk mobile.
13. Membuat topbar terlalu ramai.
14. Membuat footer terlalu besar.
15. Mengabaikan loading state pada submit form.

---

## 38. Ringkasan Final

Keputusan UI/UX final:

```text
Style: Modern SaaS Dashboard
Layout: Compact dan clean
Frontend: Tailwind CSS CDN
Font: Inter
Icon: Tabler Icons
Interaksi: Alpine.js
Alert: SweetAlert2
Sidebar: role-based dan collapsible
Mobile: hamburger drawer
Mahasiswa: locked menu + progress stepper
Dashboard: card mini + aksi berikutnya
Form: jelas dan responsive
Logbook: mobile-first
Tabel: responsive atau card list di mobile
Pesan: Bahasa Indonesia
```

UI/UX sistem harus membantu user memahami proses, bukan hanya menampilkan data.
