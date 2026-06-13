# templates.md

## 1. Tujuan File

File ini menjadi standar penulisan dokumentasi proyek.

Semua file `.md` dalam folder `docs/` harus mengikuti gaya penulisan yang ringkas, jelas, terstruktur, dan tidak berlebihan.

Dokumentasi dibuat untuk membantu AI coding agent dan developer manusia memahami project tanpa membuang banyak token.

---

## 2. Prinsip Penulisan Dokumentasi

Gunakan prinsip berikut:

1. Tulis langsung ke inti pembahasan.
2. Jangan membuat penjelasan terlalu panjang jika tidak diperlukan.
3. Gunakan heading yang jelas.
4. Gunakan bullet list untuk aturan.
5. Gunakan tabel jika lebih mudah dibaca.
6. Gunakan blok kode untuk contoh struktur, status, alur, atau path.
7. Jangan mengulang isi file lain secara berlebihan.
8. Jika ada detail besar, cukup beri ringkasan dan arahkan ke file terkait.
9. Hindari istilah yang ambigu.
10. Gunakan Bahasa Indonesia yang jelas.

---

## 3. Format Dasar Setiap File Docs

Setiap file di folder `docs/` sebaiknya mengikuti format berikut:

```md
# Judul Dokumen

## 1. Tujuan Dokumen

Jelaskan fungsi dokumen ini secara singkat.

## 2. Ringkasan

Jelaskan inti pembahasan dalam beberapa poin.

## 3. Detail Utama

Isi sesuai topik file.

## 4. Aturan Penting

Daftar aturan yang wajib dipatuhi agent.

## 5. Catatan Implementasi

Catatan teknis atau arahan coding jika diperlukan.

## 6. Hal yang Tidak Boleh Dilakukan

Daftar larangan agar agent tidak salah arah.
```

Tidak semua file harus memiliki semua bagian, tetapi format ini menjadi acuan utama.

---

## 4. Template Penulisan Alur Workflow

Gunakan format berikut untuk menjelaskan alur sistem:

````md
## Nama Alur

### Tujuan

Jelaskan tujuan alur.

### Aktor Terlibat

- Role 1
- Role 2

### Alur Utama

```text
Tahap 1
↓
Tahap 2
↓
Tahap 3
```
````

### Status yang Digunakan

```text
status_awal
status_proses
status_selesai
```

### Aturan

1. Aturan pertama.
2. Aturan kedua.

### Validasi

- Validasi input.
- Validasi role.
- Validasi status workflow.

````

---

## 5. Template Penulisan Role

Gunakan format berikut untuk setiap role:

```md
## Nama Role

### Fungsi Utama

Jelaskan fungsi role secara singkat.

### Hak Akses

- Akses 1
- Akses 2
- Akses 3

### Batasan Akses

- Tidak boleh mengakses data tertentu.
- Tidak boleh melakukan aksi tertentu.

### Aksi Penting

- Aksi 1
- Aksi 2
- Aksi 3

### Catatan Implementasi

Tuliskan catatan teknis jika ada.
````

---

## 6. Template Penulisan Sidebar

Gunakan format berikut untuk menu sidebar:

```md
## Sidebar Nama Role

| Menu       | Status/Akses     | Fungsi Singkat                     |
| ---------- | ---------------- | ---------------------------------- |
| Dashboard  | Terbuka          | Melihat ringkasan data dan status. |
| Registrasi | Terbuka/Terkunci | Mengisi data registrasi.           |
```

Untuk menu mahasiswa yang terkunci, gunakan format:

```md
| Logbook Mingguan | Terkunci sampai status `diterima_instansi` | Mengisi logbook mingguan. |
```

---

## 7. Template Penulisan Database

Gunakan format berikut untuk tabel database:

```md
## Nama Tabel

### Fungsi

Jelaskan fungsi tabel.

### Kolom Utama

| Kolom      | Tipe     | Keterangan               |
| ---------- | -------- | ------------------------ |
| id         | BIGINT   | Primary key internal.    |
| uuid       | CHAR(36) | ID publik untuk URL/API. |
| created_at | DATETIME | Waktu data dibuat.       |

### Relasi

- Relasi ke tabel lain.

### Index/Constraint

- UNIQUE pada kolom tertentu.
- INDEX pada kolom pencarian.

### Catatan

Catatan penting terkait tabel.
```

Jangan menulis terlalu banyak kolom detail jika belum diperlukan. Fokus pada struktur inti dulu.

---

## 8. Template Penulisan Business Rules

Gunakan format berikut:

```md
## Nama Aturan

### Kondisi

Jelaskan kondisi kasus.

### Aturan Sistem

1. Aturan pertama.
2. Aturan kedua.

### Respon Sistem

Jelaskan apa yang ditampilkan atau dilakukan sistem.

### Catatan Implementasi

Tuliskan validasi atau logic yang perlu diterapkan.
```

Contoh:

```md
## Mahasiswa Sudah Pernah Selesai KP/KPL

### Kondisi

Mahasiswa login dengan email yang sama dan sudah memiliki riwayat KP/KPL selesai pada periode sebelumnya.

### Aturan Sistem

1. Akun tidak dibuat ulang.
2. Dashboard menampilkan riwayat KP/KPL.
3. Pendaftaran baru dikunci, kecuali Koordinator membuka akses ulang.

### Respon Sistem

Tampilkan pesan bahwa mahasiswa telah menyelesaikan KP/KPL dan dapat melihat riwayatnya.
```

---

## 9. Template Penulisan UI/UX Rules

Gunakan format berikut:

```md
## Nama Komponen/Halaman

### Tujuan UI

Jelaskan fungsi tampilan.

### Struktur Tampilan

- Bagian atas
- Bagian tengah
- Bagian bawah

### Perilaku Desktop

Jelaskan tampilan desktop.

### Perilaku Mobile

Jelaskan tampilan mobile.

### Aturan Desain

1. Aturan desain pertama.
2. Aturan desain kedua.

### Hal yang Dilarang

- Jangan membuat tampilan terlalu besar.
- Jangan membuat tabel sulit dibaca di mobile.
```

---

## 10. Template Penulisan Technology Rules

Gunakan format berikut:

```md
## Nama Teknologi

### Fungsi

Jelaskan fungsi teknologi.

### Digunakan Untuk

- Kebutuhan 1
- Kebutuhan 2

### Aturan Penggunaan

1. Aturan pertama.
2. Aturan kedua.

### Larangan

- Jangan gunakan untuk hal tertentu.
```

Contoh:

```md
## SweetAlert2

### Fungsi

Digunakan untuk alert, konfirmasi, dan toast.

### Digunakan Untuk

- Notifikasi sukses.
- Notifikasi error.
- Konfirmasi aksi penting.

### Aturan Penggunaan

1. Gunakan pesan Bahasa Indonesia.
2. Jangan gunakan alert browser bawaan.

### Larangan

- Jangan menggunakan alert() bawaan JavaScript.
```

---

## 11. Template Penulisan Project Structure

Gunakan format berikut:

````md
## Nama Folder

### Fungsi

Jelaskan fungsi folder.

### Struktur

```text
folder/
├── subfolder/
└── file.php
```
````

### Aturan

1. Aturan pertama.
2. Aturan kedua.

### Catatan

Catatan tambahan jika diperlukan.

````

---

## 12. Template Penulisan Progress

Gunakan format berikut di `docs/11-progress.md`:

```md
# Progress Project

## Ringkasan Status

Status terakhir project.

## Selesai

- [x] Item yang sudah selesai.

## Sedang Dikerjakan

- [ ] Item yang sedang dikerjakan.

## Belum Dikerjakan

- [ ] Item yang belum dikerjakan.

## Bug/Masalah

| Tanggal | Masalah | Status | Catatan |
|---|---|---|---|

## Keputusan Penting

| Tanggal | Keputusan | Alasan |
|---|---|---|

## Catatan Agent

Catatan tambahan dari agent.
````

---

## 13. Standar Penamaan File

Gunakan penamaan file yang jelas:

```text
01-prd.md
02-current-system.md
03-roles.md
04-workflow.md
05-sidebar-dashboard.md
06-database.md
07-technology.md
08-ui-ux-rules.md
09-business-rules.md
10-project-structure.md
11-progress.md
```

Aturan:

1. Gunakan angka urutan.
2. Gunakan huruf kecil.
3. Gunakan tanda hubung `-`.
4. Jangan gunakan spasi.
5. Jangan gunakan nama file yang terlalu panjang.

---

## 14. Standar Penulisan Status

Gunakan format `snake_case` untuk status workflow.

Contoh:

```text
draft
menunggu_verifikasi
revisi_registrasi
registrasi_disetujui
penempatan_disetujui
diterima_instansi
dosen_ditetapkan
sedang_berjalan
menunggu_penilaian
selesai
diarsipkan
```

Jangan gunakan format tidak konsisten seperti:

```text
Menunggu Verifikasi
pendingVerifikasi
status-registrasi
approved
```

Status untuk database memakai `snake_case`, sedangkan tampilan UI memakai Bahasa Indonesia yang mudah dipahami.

Contoh:

```text
Database: menunggu_verifikasi
UI: Menunggu Verifikasi
```

---

## 15. Standar Penulisan Validasi

Pesan validasi harus:

1. Berbahasa Indonesia.
2. Spesifik.
3. Tidak teknis.
4. Menjelaskan masalah.
5. Jika perlu, memberi solusi.

Contoh baik:

```text
Bukti pembayaran wajib diunggah.
Format file harus PDF, JPG, JPEG, atau PNG.
Ukuran file maksimal 10 MB.
Dokumen belum dapat diverifikasi karena belum terdapat tanda tangan.
```

Contoh buruk:

```text
Validation failed.
The field is required.
Invalid file.
Upload error.
```

---

## 16. Standar Penulisan UI

Gunakan istilah UI yang konsisten:

```text
Dashboard
Sidebar
Topbar
Footer
Progress Stepper
Status Badge
Locked Menu
Empty State
Toast
Modal Konfirmasi
Card Statistik
Tabel Data
```

Jangan mencampur istilah tanpa kebutuhan.

---

## 17. Standar Penulisan Role

Gunakan istilah role yang konsisten:

```text
Admin
Koordinator KP/KPL
Mahasiswa
Dosen Pembimbing
Instansi Mitra
```

Jangan mengganti-ganti istilah seperti:

```text
Operator
Atasan
Pembimbing KP
Tempat Magang
Pihak Luar
```

Kecuali istilah tersebut memang dijelaskan sebagai alias.

---

## 18. Standar Penulisan KP/KPL

Gunakan istilah:

```text
KP/KPL
```

Penjelasan:

- KP digunakan untuk Program Studi Informatika.
- KPL digunakan untuk Program Studi Informatika Medis jika mengikuti kebijakan kampus.

Jika konteks hanya satu program studi, boleh ditulis KP atau KPL sesuai kebutuhan.

---

## 19. Standar Penulisan Dokumen

Gunakan nama dokumen yang konsisten:

```text
Lampiran A/Formulir Pendaftaran KP/KPL
Surat Rekomendasi Dosen PA
Surat Permohonan/Pengantar ke Instansi
Surat Tugas Dosen Pembimbing
Form Penerimaan Instansi
Form Penilaian Instansi
Laporan Akhir
Rekap Nilai Akhir
```

---

## 20. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Membuat dokumentasi terlalu panjang tanpa kebutuhan.
2. Mengulang isi `AGENTS.md` di semua file.
3. Membuat file docs baru tanpa alasan jelas.
4. Mengubah istilah role sembarangan.
5. Mengubah status workflow tanpa memperbarui dokumen terkait.
6. Menulis validasi Bahasa Inggris.
7. Membuat aturan yang bertentangan dengan workflow utama.
8. Menghapus keputusan penting tanpa mencatat alasan.
9. Membuat struktur folder baru tanpa memperbarui `10-project-structure.md`.
10. Mengabaikan `11-progress.md`.

---

## 21. Catatan Akhir

Dokumentasi ini harus membantu agent bekerja lebih cepat, bukan memperlambat.

Tulis yang penting, hindari pengulangan, dan pastikan setiap dokumen punya fungsi yang jelas.
