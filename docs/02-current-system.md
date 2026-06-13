# docs/02-current-system.md

# Kondisi Sistem Berjalan Saat Ini

## 1. Tujuan Dokumen

Dokumen ini menjelaskan kondisi proses KP/KPL yang sedang berjalan di kampus saat ini.

Dokumen ini wajib dibaca sebelum membangun sistem agar agent tidak membuat alur baru yang tidak sesuai dengan kebiasaan dan dokumen resmi kampus.

Sistem baru harus mendigitalisasi proses manual yang sudah ada, bukan menghapus seluruh format resmi yang telah digunakan.

---

## 2. Ringkasan Sistem Berjalan

Saat ini proses KP/KPL masih dilakukan secara manual dan terpisah.

Alur umum yang berjalan:

```text
Mahasiswa mengisi Google Form
↓
Mahasiswa mengisi data diri dan data akademik
↓
Mahasiswa upload bukti pembayaran
↓
Mahasiswa mengunduh dokumen dari link
↓
Mahasiswa mencetak dokumen
↓
Mahasiswa meminta tanda tangan/stempel pihak terkait
↓
Mahasiswa menyerahkan dokumen fisik kepada Koordinator KP/KPL
↓
Koordinator memeriksa kelengkapan berkas
↓
Mahasiswa menunggu keputusan/verifikasi
```

Masalah utama:

1. Data tersebar di Google Form, file upload, dan dokumen fisik.
2. Mahasiswa tidak dapat memantau status proses secara real-time.
3. Koordinator harus memeriksa dokumen secara manual.
4. Dokumen berisiko tercecer atau sulit dicari kembali.
5. Data lama tidak tersusun rapi berdasarkan periode.
6. Tidak ada workflow digital yang mengunci tahap secara otomatis.
7. Belum ada sistem rekomendasi penempatan berdasarkan TOPSIS.
8. Logbook, laporan akhir, nilai, dan arsip belum terintegrasi dalam satu sistem.

---

## 3. Form Google Saat Ini

Mahasiswa saat ini mengisi form online untuk pendaftaran KP/KPL.

Form tersebut berisi dua kelompok besar:

1. Data diri.
2. Data akademik.

---

## 4. Data Diri yang Diisi Mahasiswa

Data diri yang perlu didukung sistem:

```text
Nama Lengkap
Nomor Pokok Mahasiswa (NPM)
Tempat, Tanggal Lahir
Jenis Kelamin (L/P)
Agama
Alamat Lengkap sesuai KTP
Kecamatan
Kabupaten
Provinsi
Nomor Telepon/HP aktif
Alamat Email aktif
Nama Orang Tua/Wali
Nomor Telepon Orang Tua/Wali
Semester saat pendaftaran
Tahun Akademik
Angkatan
Program Studi
```

Aturan sistem baru:

1. Data diri diisi melalui sistem, bukan Google Form.
2. Data yang sudah ada di profil mahasiswa dapat diambil otomatis.
3. Mahasiswa tetap dapat melengkapi data yang belum tersedia.
4. Validasi wajib menggunakan Bahasa Indonesia.
5. NPM harus unik.
6. Email harus valid dan unik pada akun user.

---

## 5. Data Akademik yang Diisi Mahasiswa

Data akademik yang perlu didukung sistem:

```text
Jumlah SKS yang telah ditempuh
IPK terakhir skala 4,00
Apakah IPK ≥ 2,50
Apakah telah lulus Pemrograman Dasar
Apakah telah lulus Struktur Data
Apakah telah lulus Basis Data / Database System
Apakah telah lulus Analisis dan Perancangan Sistem Informasi
Apakah telah lulus Jaringan Komputer / Data Communication
Apakah telah lulus minimal salah satu mata kuliah konsentrasi
Status Biaya Pendidikan
Bukti Pembayaran KP/KPL
```

Mata kuliah konsentrasi dapat mencakup:

```text
Rekayasa Perangkat Lunak
Internet of Things
Desain Media Interaktif
Bidang konsentrasi lain sesuai kebijakan periode
```

Aturan sistem baru:

1. IPK, SKS, dan kelulusan mata kuliah inti digunakan sebagai syarat awal.
2. IPK, SKS, dan kelengkapan berkas bukan kriteria utama TOPSIS.
3. Jika syarat akademik tidak terpenuhi, registrasi tidak dapat disetujui kecuali ada override Koordinator.
4. Bukti pembayaran wajib divalidasi format, ukuran, dan kejelasannya.
5. Status biaya pendidikan harus dapat dikonfigurasi jika berubah pada periode berikutnya.

---

## 6. Dokumen Download dan Print Fisik

Dalam sistem lama, setelah mengisi Google Form, mahasiswa diminta mengunduh dokumen melalui link.

Catatan manual saat ini:

```text
Dokumen wajib dicetak secara fisik.
Dokumen dibawa langsung untuk diserahkan kepada Koordinator KP/KPL.
```

Sistem baru harus tetap mendukung pola ini untuk dokumen yang membutuhkan tanda tangan atau stempel.

Prinsip sistem baru:

```text
Dokumen resmi kampus dijadikan template digital.
Sistem mengisi dokumen otomatis dari data mahasiswa.
Mahasiswa dapat mengunduh PDF.
Mahasiswa dapat mencetak dokumen jika perlu.
Mahasiswa mengupload kembali dokumen yang sudah ditandatangani/stempel.
Admin/Koordinator memverifikasi dokumen di sistem.
```

---

## 7. Lampiran A/Formulir Pendaftaran KP/KPL

Lampiran A adalah formulir pendaftaran utama.

Isi Lampiran A mencakup:

1. Identitas Mahasiswa.
2. Data Akademik.
3. Preferensi Lokasi Kerja Praktek Lapangan.
4. Pernyataan Kesediaan Mematuhi Aturan.
5. Persetujuan Orang Tua/Wali.
6. Verifikasi Administrasi oleh Koordinator KPL.

Sistem baru harus menjadikan Lampiran A sebagai acuan template digital.

---

## 8. Bagian A Lampiran A — Identitas Mahasiswa

Bagian identitas mahasiswa pada Lampiran A mencakup:

```text
Nama Lengkap
Nomor Pokok Mahasiswa (NPM)
Tempat, Tanggal Lahir
Jenis Kelamin
Agama
Alamat Lengkap sesuai KTP
Nomor Telepon/HP aktif
Alamat Email aktif
Nama Orang Tua/Wali
Nomor Telepon Orang Tua/Wali
Semester saat pendaftaran
Tahun Akademik
```

Aturan implementasi:

1. Field ini masuk ke form registrasi digital.
2. Sebagian data dapat otomatis diambil dari profil mahasiswa.
3. Data harus tetap dapat digunakan untuk generate PDF Lampiran A.
4. Jika field wajib belum lengkap, registrasi tidak bisa dikirim.

---

## 9. Bagian B Lampiran A — Data Akademik

Bagian data akademik mencakup:

```text
Jumlah SKS yang telah ditempuh
IPK terakhir
Status IPK minimal 2,50
Kelulusan Pemrograman Dasar
Kelulusan Struktur Data
Kelulusan Basis Data
Kelulusan Analisis dan Perancangan Sistem Informasi
Kelulusan Jaringan Komputer
Kelulusan minimal salah satu mata kuliah konsentrasi
Dosen Pembimbing Akademik (PA)
Rekomendasi dari Dosen PA
```

Aturan implementasi:

1. Data akademik harus masuk ke registrasi digital.
2. Nilai mata kuliah dapat dibuat sebagai field opsional/wajib sesuai konfigurasi periode.
3. Surat rekomendasi Dosen PA tetap menjadi dokumen persyaratan.
4. Rekomendasi Dosen PA dapat berupa:
   - Layak mengikuti KP/KPL.
   - Belum layak mengikuti KP/KPL.

5. Jika rekomendasi menyatakan belum layak, Koordinator harus melihat alasan sebelum memutuskan.

---

## 10. Bagian C Lampiran A — Preferensi Lokasi KP/KPL

Lampiran A meminta mahasiswa mengisi minimal 3 pilihan instansi.

Data yang perlu didukung:

```text
Prioritas 1:
- Nama Instansi
- Alamat Instansi
- Alasan Pemilihan

Prioritas 2:
- Nama Instansi
- Alamat Instansi
- Alasan Pemilihan

Prioritas 3:
- Nama Instansi
- Alamat Instansi
- Alasan Pemilihan
```

Terdapat juga pertanyaan:

```text
Apakah mahasiswa bersedia ditempatkan di instansi lain jika pilihan tidak tersedia?
Apakah mahasiswa memiliki usulan instansi di luar daftar mitra tetap Program Studi?
```

Aturan implementasi:

1. Preferensi lokasi masuk ke tahap Penempatan.
2. Jika memilih mitra, sistem dapat menampilkan hasil rekomendasi TOPSIS.
3. Jika mengajukan tempat mandiri, mahasiswa wajib mengisi data instansi secara lengkap.
4. Jika instansi mandiri belum bermitra, sistem menggunakan jalur dokumen manual.
5. Koordinator tetap menjadi pengambil keputusan akhir.

---

## 11. Bagian D Lampiran A — Pernyataan Kesediaan Mahasiswa

Bagian ini berisi pernyataan mahasiswa bahwa:

1. Mahasiswa telah membaca dan memahami panduan KP/KPL.
2. Mahasiswa bersedia mematuhi aturan instansi.
3. Mahasiswa bertanggung jawab atas keselamatan diri.
4. Mahasiswa bersedia menerima sanksi jika melanggar.
5. Data yang diisi benar dan dapat dipertanggungjawabkan.

Aturan implementasi:

1. Sistem harus menyediakan checkbox persetujuan digital.
2. Mahasiswa wajib menyetujui pernyataan sebelum submit registrasi.
3. Untuk kebutuhan fisik, sistem tetap dapat generate halaman pernyataan PDF.
4. Jika dibutuhkan materai/tanda tangan, mahasiswa dapat download, cetak, tanda tangan, lalu upload ulang.

---

## 12. Bagian E Lampiran A — Persetujuan Orang Tua/Wali

Bagian ini berisi persetujuan orang tua/wali terhadap mahasiswa yang mengikuti KP/KPL.

Aturan implementasi:

1. Sistem menyimpan data orang tua/wali.
2. Sistem dapat generate form persetujuan orang tua/wali.
3. Jika tanda tangan fisik diperlukan, mahasiswa download dan upload ulang dokumen yang sudah ditandatangani.
4. Dokumen persetujuan orang tua/wali dapat menjadi dokumen wajib per periode.

---

## 13. Bagian F Lampiran A — Verifikasi Administrasi Koordinator

Bagian verifikasi administrasi diisi oleh Koordinator.

Berkas yang diperiksa:

```text
Formulir Pendaftaran Lampiran A
Fotokopi KHS terbaru yang dilegalisasi
Fotokopi bukti pembayaran biaya KP/KPL
Surat rekomendasi dari Dosen PA
Pas foto terbaru 3x4
Surat pernyataan bermaterai
Persetujuan orang tua/wali
Surat Usulan Instansi Mandiri jika ada
```

Kesimpulan verifikasi:

```text
Diterima
Ditolak
```

Aturan implementasi:

1. Verifikasi administrasi dipindahkan ke sistem.
2. Admin dapat membantu memeriksa kelengkapan dokumen.
3. Koordinator memberi keputusan akhir.
4. Setiap dokumen memiliki status:
   - Belum Upload
   - Menunggu Verifikasi
   - Valid
   - Perlu Revisi
   - Ditolak

5. Koordinator/Admin wajib memberi catatan jika dokumen perlu revisi atau ditolak.
6. Semua aksi verifikasi masuk audit log.

---

## 14. Surat Rekomendasi Dosen PA

Sistem lama menggunakan surat rekomendasi dari Dosen Pembimbing Akademik.

Ada dua jenis:

1. Surat Rekomendasi Dosen PA untuk KP Program Studi Informatika.
2. Surat Rekomendasi Dosen PA untuk KPL Program Studi Informatika Medis.

Isi utama surat:

```text
Data Dosen PA
Data Mahasiswa
Semester
Jumlah SKS
IPK
Periode KP/KPL
Penilaian akademik mahasiswa
Kesimpulan layak atau belum layak
Catatan tambahan
Pengesahan untuk arsip prodi
```

Aspek penilaian akademik:

```text
Kedisiplinan akademik
Kemampuan akademik
Inisiatif dan kemandirian
Etika dan sikap
Kesiapan psikologis untuk terjun ke lapangan
```

Aturan implementasi MVP:

1. Sistem menyediakan template surat rekomendasi PA.
2. Mahasiswa dapat mengunduh surat.
3. Mahasiswa meminta tanda tangan Dosen PA secara manual.
4. Mahasiswa upload kembali surat yang sudah ditandatangani.
5. Admin/Koordinator memverifikasi dokumen.
6. Surat rekomendasi PA dapat menjadi dokumen wajib.

Catatan pengembangan lanjutan:

1. Dosen PA dapat diberi akun untuk mengisi rekomendasi langsung melalui sistem.
2. Namun fitur ini tidak wajib untuk MVP.

---

## 15. Formulir Permohonan Surat Pengantar

Sistem lama juga menggunakan form untuk permohonan surat pengantar KP/KPL.

Data yang diminta:

```text
Email
NPM
Nama
Prodi
Konsentrasi pembelajaran
Bidang peminatan/konsentrasi akademik
Nama instansi pilihan 1
Alamat instansi pilihan 1
Nama instansi pilihan 2
Alamat instansi pilihan 2
Nama instansi pilihan 3
Alamat instansi pilihan 3
Alamat domisili mahasiswa
Kecamatan domisili
Kabupaten/Kota domisili
```

Aturan implementasi:

1. Data ini tidak perlu dibuat sebagai form terpisah jika sudah masuk ke tahap Penempatan.
2. Sistem dapat mengambil data dari profil, registrasi, dan penempatan.
3. Sistem dapat generate surat pengantar berdasarkan data tersebut.
4. Jika ada tiga pilihan instansi, sistem harus menyimpan urutan prioritas.

---

## 16. Surat Permohonan/Pengantar ke Instansi

Surat permohonan/pengantar digunakan untuk mengajukan mahasiswa ke instansi.

Isi utama surat:

```text
Nomor surat
Lampiran
Perihal
Nama instansi tujuan
Data mahasiswa
Rencana pelaksanaan KP/KPL
Bidang/kegiatan yang diminati
Tugas mahasiswa selama KP/KPL
Jadwal dan durasi kegiatan
Usulan pembimbing lapangan
Ketentuan penutup
Tanda tangan Ketua Program Studi
```

Surat juga memiliki lembar persetujuan dari instansi.

Lembar persetujuan berisi:

```text
Nama instansi
Alamat instansi
Nama pimpinan
Jabatan
Setuju/Tidak setuju menerima mahasiswa
Alasan jika tidak setuju
Calon pembimbing lapangan
Periode KP/KPL yang disepakati
Tanggal persetujuan
Tanda tangan dan stempel
```

Aturan implementasi:

1. Sistem dapat generate surat permohonan/pengantar PDF.
2. Surat menggunakan data dari registrasi dan penempatan.
3. Mahasiswa dapat download surat.
4. Mahasiswa menyerahkan surat ke instansi.
5. Instansi mengisi lembar persetujuan, tanda tangan, dan stempel.
6. Mahasiswa upload ulang dokumen.
7. Admin/Koordinator memverifikasi dokumen.
8. Jika dokumen valid, mahasiswa lanjut ke tahap berikutnya.
9. Jika instansi menolak, mahasiswa kembali ke tahap penempatan.

---

## 17. Masalah Sistem Manual yang Harus Diselesaikan

Sistem baru harus menyelesaikan masalah berikut:

| Masalah Manual                                 | Solusi Sistem Baru                             |
| ---------------------------------------------- | ---------------------------------------------- |
| Data tersebar di Google Form dan dokumen fisik | Data disimpan di database terpusat             |
| Mahasiswa tidak tahu status pengajuan          | Dashboard menampilkan status dan progress      |
| Dokumen mudah tercecer                         | Upload dan arsip dokumen per periode/NPM       |
| Verifikasi manual lambat                       | Verifikasi digital oleh Admin/Koordinator      |
| Pemilihan tempat belum objektif                | Rekomendasi TOPSIS untuk instansi mitra        |
| Surat harus dibuat manual                      | Sistem generate PDF dari template              |
| Tempat mandiri sulit dilacak                   | Jalur pengajuan mandiri dengan dokumen balasan |
| Logbook belum terintegrasi                     | Logbook mingguan di sistem                     |
| Nilai belum terpusat                           | Penilaian instansi dan dosen dalam sistem      |
| Data lama sulit dicari                         | Arsip periode read-only                        |

---

## 18. Prinsip Transisi dari Manual ke Digital

Sistem baru harus realistis dengan kondisi kampus.

Prinsip:

1. Jangan menghapus format dokumen lama.
2. Jangan memaksa semua tanda tangan menjadi digital.
3. Dokumen resmi tetap bisa diunduh dan dicetak.
4. Proses tanda tangan/stempel tetap didukung.
5. Setelah dokumen fisik lengkap, mahasiswa upload ulang ke sistem.
6. Admin/Koordinator memverifikasi dokumen secara digital.
7. Semua dokumen tersimpan dalam arsip mahasiswa dan periode.

---

## 19. Keputusan Implementasi untuk MVP

Untuk MVP, gunakan keputusan berikut:

| Komponen                   | Keputusan MVP                                       |
| -------------------------- | --------------------------------------------------- |
| Google Form                | Diganti form registrasi digital                     |
| Lampiran A                 | Dijadikan template digital dan PDF                  |
| Surat rekomendasi PA       | Download-upload manual                              |
| Surat pengantar            | Generate PDF dari sistem                            |
| Persetujuan instansi       | Upload ulang dokumen bertanda tangan/stempel        |
| Tempat mandiri             | Tidak wajib akun instansi                           |
| Penilaian instansi mandiri | Form manual diunduh, diisi, distempel, upload ulang |
| Verifikasi                 | Admin membantu, Koordinator final                   |
| Arsip                      | Semua dokumen disimpan per periode dan NPM          |

---

## 20. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Mengabaikan alur manual kampus saat ini.
2. Menghapus kebutuhan dokumen fisik tanpa instruksi.
3. Membuat sistem yang tidak mendukung tanda tangan/stempel.
4. Membuat instansi mandiri wajib punya akun.
5. Menghilangkan Lampiran A dari alur registrasi.
6. Menggabungkan semua dokumen menjadi satu upload tanpa status.
7. Membuat verifikasi dokumen tanpa catatan revisi.
8. Menganggap Google Form lama sebagai fitur yang tetap dipakai.
9. Membuat proses registrasi tanpa arsip dokumen.
10. Membuat surat tanpa template yang bisa diatur.

---

## 21. Ringkasan Final

Sistem berjalan saat ini masih manual melalui Google Form dan dokumen fisik.

Sistem baru harus mengubah proses tersebut menjadi:

```text
Form digital
↓
Upload dokumen
↓
Generate PDF
↓
Download dan tanda tangan/stempel jika perlu
↓
Upload ulang dokumen
↓
Verifikasi digital
↓
Arsip periode
```

Sistem harus tetap menghormati dokumen resmi kampus, tetapi membuat prosesnya lebih rapi, terpusat, mudah dipantau, dan siap digunakan berulang setiap periode.
