# docs/01-prd.md

# Product Requirements Document — Sistem Manajemen KP/KPL

## 1. Tujuan Dokumen

Dokumen ini menjelaskan gambaran utama produk, tujuan sistem, ruang lingkup, fitur utama, batasan MVP, dan hasil akhir yang ingin dicapai.

Dokumen ini menjadi acuan awal sebelum agent membaca dokumen role, workflow, database, UI/UX, teknologi, dan business rules.

---

## 2. Nama Sistem

**Sistem Manajemen KP/KPL Berbasis Multi-Role dengan Rekomendasi Penempatan Menggunakan Metode TOPSIS**

Singkatan internal yang boleh digunakan:

```text
SIM KP/KPL
```

---

## 3. Latar Belakang Singkat

Proses Kerja Praktek (KP) dan Kerja Praktek Lapangan (KPL) di kampus masih dilakukan secara manual menggunakan Google Form, dokumen fisik, tanda tangan, stempel, dan verifikasi langsung oleh Koordinator.

Alur manual tersebut menyebabkan beberapa masalah:

1. Data mahasiswa tersebar di Google Form, dokumen fisik, dan file terpisah.
2. Proses verifikasi membutuhkan waktu lebih lama.
3. Mahasiswa sulit memantau status pengajuan.
4. Dokumen mudah tercecer atau tidak terdokumentasi rapi.
5. Pemilihan tempat KP/KPL belum didukung sistem rekomendasi yang objektif.
6. Logbook, laporan akhir, dan nilai belum terintegrasi dalam satu sistem.
7. Arsip periode lama sulit dicari kembali.

Sistem baru dibangun untuk mendigitalisasi proses tersebut dalam satu platform terpusat.

---

## 4. Visi Produk

Membangun platform manajemen KP/KPL yang modern, terstruktur, mudah digunakan, dan dapat dipakai berulang setiap periode/tahun.

Sistem harus mendukung:

```text
Registrasi
Verifikasi
Penempatan
Rekomendasi TOPSIS
Pembimbing
Dokumen
Logbook
Laporan Akhir
Penilaian
Arsip Periode
```

Sistem ini bukan CRUD biasa, tetapi workflow akademik berbasis status dan role.

---

## 5. Tujuan Sistem

Tujuan utama sistem:

1. Mengganti proses Google Form menjadi form registrasi digital.
2. Menyimpan data mahasiswa dan dokumen dalam sistem terpusat.
3. Memudahkan Admin dan Koordinator melakukan verifikasi.
4. Memberikan rekomendasi tempat KP/KPL menggunakan metode TOPSIS.
5. Menyediakan dua jalur penempatan:
   - Instansi mitra kampus.
   - Tempat mandiri yang diajukan mahasiswa.

6. Memudahkan penetapan dosen pembimbing.
7. Mengelola surat dan dokumen resmi secara digital.
8. Memfasilitasi logbook mingguan mahasiswa.
9. Memfasilitasi penilaian dari dosen dan instansi.
10. Menyediakan arsip periode yang aman dan dapat dilihat kembali.
11. Memberikan tampilan modern, responsive, dan mudah dipahami.

---

## 6. Aktor Utama

Sistem memiliki 5 role utama:

| Role               | Fungsi Utama                                                                                          |
| ------------------ | ----------------------------------------------------------------------------------------------------- |
| Admin              | Mengelola data master, akun, periode, form, dokumen, template, dan konfigurasi sistem.                |
| Koordinator KP/KPL | Mengambil keputusan akademik, validasi registrasi, validasi penempatan, pembimbing, nilai, dan arsip. |
| Mahasiswa          | Mengikuti alur KP/KPL dari registrasi sampai selesai.                                                 |
| Dosen Pembimbing   | Membimbing mahasiswa, mereview logbook, laporan akhir, dan memberi nilai akademik.                    |
| Instansi Mitra     | Mengonfirmasi penerimaan mahasiswa dan memberi evaluasi/nilai jika memiliki akun.                     |

---

## 7. Scope Sistem

Sistem mencakup:

1. Login dan manajemen akun multi-role.
2. Login Google untuk mahasiswa.
3. Manajemen profil mahasiswa, dosen, dan instansi.
4. Manajemen periode KP/KPL.
5. Form registrasi digital.
6. Upload dokumen persyaratan.
7. Verifikasi registrasi.
8. Pengajuan dan validasi penempatan.
9. Rekomendasi tempat mitra menggunakan TOPSIS.
10. Pengajuan tempat mandiri.
11. Generate dokumen/surat PDF.
12. Upload dokumen balasan instansi.
13. Penetapan dosen pembimbing.
14. Logbook mingguan.
15. Review logbook oleh dosen.
16. Upload laporan akhir.
17. Penilaian instansi.
18. Penilaian dosen.
19. Rekap nilai akhir.
20. Validasi nilai akhir.
21. Arsip periode.
22. Dashboard role-based.
23. Sidebar responsive dengan locked menu.
24. Audit log dasar.
25. Export data dan laporan.

---

## 8. Di Luar Scope MVP

Fitur berikut tidak wajib dibuat pada tahap MVP:

1. WhatsApp Gateway.
2. OCR dokumen.
3. Tanda tangan digital resmi.
4. Full chat internal.
5. Notifikasi email kompleks.
6. Validasi dokumen otomatis menggunakan AI.
7. Integrasi langsung dengan SIAKAD kampus.
8. Pembayaran online.
9. Akun penuh untuk semua instansi mandiri.
10. Mobile app native.
11. Realtime notification dengan WebSocket.

Fitur tersebut dapat menjadi pengembangan lanjutan.

---

## 9. Prinsip Produk

Produk wajib mengikuti prinsip berikut:

1. Workflow-based, bukan CRUD-based.
2. Role-based access.
3. Step-based flow untuk mahasiswa.
4. Data terpusat per periode.
5. Dokumen terdokumentasi rapi.
6. Validasi backend yang kuat.
7. Pesan error Bahasa Indonesia.
8. UI modern, compact, dan responsive.
9. Mobile-friendly untuk pengisian logbook.
10. Arsip periode tidak boleh hilang.
11. TOPSIS sebagai rekomendasi, bukan keputusan final.
12. Koordinator tetap menjadi pengambil keputusan akademik.

---

## 10. Alur Produk Tingkat Tinggi

Alur utama sistem:

```text
Mahasiswa daftar/login
↓
Mahasiswa melengkapi profil
↓
Mahasiswa mengisi registrasi KP/KPL
↓
Mahasiswa upload dokumen persyaratan
↓
Admin/Koordinator memverifikasi
↓
Jika disetujui, mahasiswa masuk tahap penempatan
↓
Mahasiswa memilih mitra berdasarkan TOPSIS
atau mengajukan tempat mandiri
↓
Koordinator memvalidasi penempatan
↓
Sistem generate surat/dokumen
↓
Mahasiswa/instansi mengembalikan dokumen balasan
↓
Koordinator menetapkan dosen pembimbing
↓
Mahasiswa menjalankan KP/KPL
↓
Mahasiswa mengisi logbook mingguan
↓
Dosen mereview logbook
↓
Mahasiswa upload laporan akhir
↓
Instansi memberi nilai
↓
Dosen memberi nilai
↓
Sistem menghitung nilai akhir
↓
Koordinator memvalidasi nilai akhir
↓
Status mahasiswa selesai
↓
Periode dapat diarsipkan
```

---

## 11. Jalur Penempatan

Sistem menyediakan dua jalur penempatan.

### 11.1 Penempatan Mitra

Mahasiswa memilih dari daftar instansi mitra kampus.

Sistem menjalankan TOPSIS untuk membantu memberi ranking rekomendasi berdasarkan:

1. Kesesuaian bidang instansi.
2. Kesesuaian kemampuan mahasiswa.
3. Kuota instansi.
4. Jarak lokasi.
5. Status kemitraan.
6. Ketersediaan pembimbing lapangan.

Koordinator tetap memiliki keputusan final.

### 11.2 Penempatan Mandiri

Mahasiswa mengajukan instansi sendiri.

Alur tempat mandiri:

```text
Mahasiswa isi data instansi mandiri
↓
Koordinator validasi kelayakan
↓
Sistem generate surat/form
↓
Mahasiswa download dokumen
↓
Instansi tanda tangan dan stempel
↓
Mahasiswa upload ulang dokumen
↓
Admin/Koordinator verifikasi
↓
Jika valid, mahasiswa lanjut tahap berikutnya
```

Instansi mandiri tidak wajib memiliki akun.

---

## 12. Konsep Dokumen Resmi

Sistem tidak menghapus format dokumen lama kampus.

Sistem harus menggunakan dokumen lama sebagai acuan template digital.

Dokumen yang perlu didukung:

1. Lampiran A/Formulir Pendaftaran KP/KPL.
2. Surat Rekomendasi Dosen PA.
3. Formulir Permohonan Surat Pengantar.
4. Surat Permohonan/Pengantar ke Instansi.
5. Lembar Persetujuan Instansi.
6. Surat Tugas Dosen Pembimbing.
7. Form Penilaian Instansi.
8. Rekap Nilai Akhir.
9. Surat selesai KP/KPL jika dibutuhkan.

Dokumen yang perlu tanda tangan/stempel tetap dapat diunduh, dicetak, diisi, lalu diupload kembali.

---

## 13. Konsep Logbook

Logbook menggunakan model mingguan.

Satu logbook minggu berisi detail kegiatan harian.

Isi utama logbook:

1. Minggu ke.
2. Tanggal mulai.
3. Tanggal selesai.
4. Kegiatan harian.
5. Jam mulai dan selesai.
6. Uraian kegiatan.
7. Hasil kegiatan.
8. Kendala.
9. Dokumentasi.
10. Rencana minggu berikutnya.

Logbook harus nyaman diisi melalui HP.

---

## 14. Konsep Penilaian

Penilaian berasal dari:

1. Instansi/pembimbing lapangan.
2. Dosen pembimbing.
3. Komponen administrasi/logbook jika digunakan.

Contoh bobot awal:

```text
Nilai Instansi: 40%
Nilai Dosen: 50%
Administrasi/Logbook: 10%
```

Bobot nilai harus bisa dikonfigurasi per periode.

Untuk instansi mandiri, penilaian dilakukan melalui dokumen manual yang digenerate sistem, diisi instansi, ditandatangani/stempel, lalu diupload kembali oleh mahasiswa.

---

## 15. Konsep Arsip Periode

Setiap periode menjadi wadah data KP/KPL.

Setelah periode selesai:

```text
Periode aktif
↓
Ditutup
↓
Divalidasi
↓
Diarsipkan
```

Arsip menyimpan:

1. Data mahasiswa.
2. Data registrasi.
3. Dokumen upload.
4. Dokumen hasil generate.
5. Penempatan.
6. Dosen pembimbing.
7. Instansi.
8. Logbook.
9. Laporan akhir.
10. Nilai instansi.
11. Nilai dosen.
12. Nilai akhir.
13. Riwayat status.
14. Audit log penting.

Data arsip bersifat read-only.

---

## 16. Kebutuhan Non-Fungsional

### 16.1 Keamanan

Sistem harus memiliki:

1. Login aman.
2. Password hashing.
3. CSRF protection.
4. Role filter.
5. Workflow access filter.
6. Validasi file upload.
7. Soft delete.
8. Audit log.
9. File disimpan di `writable`, bukan public.

### 16.2 Performance

Sistem harus:

1. Memakai query yang efisien.
2. Menggunakan index database pada kolom penting.
3. Menggunakan DataTables server-side untuk data besar.
4. Tidak memuat semua CDN di semua halaman jika tidak diperlukan.

### 16.3 Usability

Sistem harus:

1. Mudah dipahami mahasiswa.
2. Menampilkan status tahap saat ini.
3. Menampilkan aksi berikutnya.
4. Memberi pesan validasi yang jelas.
5. Nyaman dipakai di HP.

### 16.4 Maintainability

Sistem harus:

1. Menggunakan struktur folder rapi.
2. Memakai layout dan partials.
3. Memisahkan logic ke Services.
4. Menggunakan dokumentasi `docs/`.
5. Mencatat progres di `docs/11-progress.md`.

---

## 17. Kriteria Keberhasilan MVP

MVP dianggap berhasil jika:

1. User multi-role dapat login sesuai akses.
2. Mahasiswa dapat melakukan registrasi digital.
3. Admin/Koordinator dapat memverifikasi registrasi.
4. Menu mahasiswa terkunci dan terbuka sesuai status.
5. Mahasiswa dapat memilih mitra atau mengajukan tempat mandiri.
6. TOPSIS dapat menghasilkan ranking rekomendasi tempat.
7. Sistem dapat generate surat/dokumen PDF.
8. Mahasiswa dapat upload dokumen balasan.
9. Koordinator dapat menetapkan dosen pembimbing.
10. Mahasiswa dapat mengisi logbook mingguan.
11. Dosen dapat mereview logbook.
12. Mahasiswa dapat upload laporan akhir.
13. Nilai instansi dan nilai dosen dapat diinput.
14. Nilai akhir dapat direkap.
15. Periode selesai dapat diarsipkan.
16. Dashboard dan sidebar responsive berjalan baik.
17. Validasi Bahasa Indonesia berjalan di semua form utama.

---

## 18. Larangan Produk

Agent tidak boleh:

1. Membuat sistem hanya berupa CRUD tabel.
2. Membuka semua menu mahasiswa dari awal.
3. Mengabaikan alur manual kampus saat ini.
4. Mengabaikan Lampiran A dan dokumen resmi kampus.
5. Menggunakan TOPSIS sebagai keputusan final otomatis.
6. Membuat instansi mandiri wajib memiliki akun.
7. Menyimpan file upload langsung di public.
8. Membuat tampilan besar-besar dan boros ruang.
9. Mengabaikan mobile responsive.
10. Menulis pesan validasi Bahasa Inggris.
11. Mengabaikan arsip periode.
12. Mengabaikan audit log untuk aksi penting.

---

## 19. Ringkasan Final

Sistem ini adalah platform manajemen KP/KPL yang mengubah alur manual kampus menjadi sistem digital berbasis workflow.

Fokus utama:

```text
Multi-role
Step-based workflow
Form digital
Dokumen resmi
TOPSIS
Penempatan mitra/mandiri
Dosen pembimbing
Logbook mingguan
Penilaian
Arsip periode
Modern SaaS dashboard
```

Produk harus terlihat modern, mudah digunakan, rapi secara database, aman secara akses, dan realistis dengan kondisi kampus saat ini.
