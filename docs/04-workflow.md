# docs/04-workflow.md

# Workflow Sistem KP/KPL

## 1. Tujuan Dokumen

Dokumen ini menjelaskan alur kerja utama sistem KP/KPL dari awal sampai akhir.

Dokumen ini menjadi acuan saat membuat:

- Status workflow.
- Sidebar locked menu.
- Dashboard mahasiswa.
- Validasi backend.
- Role access.
- Redirect halaman.
- Audit log.
- Arsip periode.

Sistem harus dibangun sebagai workflow akademik bertahap, bukan CRUD biasa.

---

## 2. Prinsip Workflow

Prinsip utama:

1. Mahasiswa tidak boleh langsung mengakses semua menu.
2. Menu terbuka berdasarkan status proses.
3. Setiap tahap memiliki status yang jelas.
4. Setiap perubahan status harus tercatat.
5. Setiap aksi penting harus masuk audit log.
6. UI boleh menampilkan menu terkunci, tetapi backend tetap wajib memblokir akses langsung.
7. Dashboard mahasiswa harus selalu menampilkan tahap saat ini dan aksi berikutnya.
8. Koordinator adalah pengambil keputusan akademik final.
9. TOPSIS hanya rekomendasi, bukan keputusan otomatis.
10. Arsip periode menjadi tahap akhir dan bersifat read-only.

---

## 3. Alur Utama Tingkat Tinggi

Alur utama sistem:

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

## 4. Status Workflow Utama

Status utama mahasiswa disimpan di:

```text
kp_registrations.current_status
```

Daftar status utama yang dikunci untuk MVP:

```text
draft
menunggu_verifikasi
revisi_registrasi
registrasi_ditolak
registrasi_disetujui
penempatan_diajukan
penempatan_revisi
penempatan_ditolak
penempatan_disetujui
surat_tersedia
menunggu_penerimaan_instansi
penerimaan_perlu_revisi
diterima_instansi
dosen_ditetapkan
sedang_berjalan
laporan_akhir_dikirim
laporan_akhir_revisi
menunggu_penilaian
menunggu_validasi_akhir
nilai_divalidasi
selesai
diarsipkan
```

Status yang tidak lagi disimpan sebagai `current_status`:

| Status Lama | Keputusan |
|---|---|
| `profil_belum_lengkap` | Dihitung dari kelengkapan `student_profiles`, bukan status registrasi. |
| `siap_registrasi` | Dihitung dari profil lengkap dan periode aktif. |
| `registrasi_draft` | Digabung menjadi `draft`. |
| `menunggu_penempatan` | Dihitung dari `registrasi_disetujui` tanpa pengajuan penempatan aktif. |
| `menunggu_surat` | Dihitung dari `penempatan_disetujui` dan belum ada generated document aktif. |
| `logbook_berjalan` | Dikelola oleh tabel `logbook_weeks`, bukan status utama. |
| `menunggu_laporan_akhir` | Dihitung dari `sedang_berjalan` dan belum ada laporan akhir aktif. |
| `menunggu_penilaian_instansi` | Dikelola oleh `assessment_scores` atau dokumen penilaian. |
| `nilai_instansi_masuk` | Dikelola oleh `assessment_scores`. |
| `menunggu_penilaian_dosen` | Dikelola oleh `assessment_scores`. |
| `nilai_dosen_masuk` | Dikelola oleh `assessment_scores`. |

Aturan:

1. `current_status` hanya menyimpan tahap besar mahasiswa.
2. Status dokumen tetap berada di `student_documents.status`.
3. Status pengajuan penempatan tetap berada di `placement_requests.status`.
4. Status logbook tetap berada di `logbook_weeks.status`.
5. Status laporan akhir tetap berada di `final_reports.status`.
6. Status nilai tetap berada di `assessment_scores.status` dan `final_scores.status`.
7. Jangan menggunakan status Bahasa Inggris campur Bahasa Indonesia di database.
8. Gunakan `snake_case` untuk status database.
9. Gunakan label Bahasa Indonesia di UI.

Contoh:

```text
Database: menunggu_verifikasi
UI: Menunggu Verifikasi
```

---

## 5. Tahap 1 — Login atau Daftar Akun

### Tujuan

User masuk ke sistem sesuai role.

### Aktor

- Admin
- Koordinator KP/KPL
- Mahasiswa
- Dosen Pembimbing
- Instansi Mitra

### Alur

```text
User membuka halaman login
↓
User memilih login manual atau Google
↓
Sistem memeriksa akun
↓
Sistem memeriksa status akun
↓
Sistem mengarahkan user ke dashboard sesuai role
```

### Aturan

1. Mahasiswa boleh login menggunakan Google jika fitur diaktifkan.
2. Admin, Koordinator, Dosen, dan Instansi dapat menggunakan login manual.
3. Email harus unik.
4. NPM mahasiswa harus unik.
5. Password disimpan menggunakan `password_hash()`.
6. Password diverifikasi menggunakan `password_verify()`.
7. Akun nonaktif tidak boleh masuk.
8. Role menentukan dashboard tujuan.

### Redirect Setelah Login

| Role             | Redirect                 |
| ---------------- | ------------------------ |
| Admin            | `/admin/dashboard`       |
| Koordinator      | `/koordinator/dashboard` |
| Mahasiswa        | `/mahasiswa/dashboard`   |
| Dosen Pembimbing | `/dosen/dashboard`       |
| Instansi Mitra   | `/instansi/dashboard`    |

---

## 6. Tahap 2 — Lengkapi Profil Mahasiswa

### Tujuan

Mahasiswa melengkapi data dasar sebelum registrasi KP/KPL.

### Aktor

- Mahasiswa

### Alur

```text
Mahasiswa login
↓
Sistem memeriksa profil
↓
Jika profil belum lengkap, dashboard menampilkan peringatan
↓
Mahasiswa melengkapi profil
↓
Sistem validasi data
↓
Profil tersimpan
↓
Mahasiswa dapat lanjut registrasi
```

### Data Profil

Data yang perlu disimpan:

```text
Nama lengkap
NPM
Tempat lahir
Tanggal lahir
Jenis kelamin
Agama
Alamat
Kecamatan
Kabupaten
Provinsi
Nomor HP
Email
Nama orang tua/wali
Nomor HP orang tua/wali
Program studi
Angkatan
Semester
```

### Status

Kelengkapan profil tidak disimpan di `kp_registrations.current_status`.

Gunakan:

```text
student_profiles.profile_status
```

Nilai yang disarankan:

```text
incomplete
complete
```

### Aturan

1. Profil wajib lengkap sebelum registrasi dikirim.
2. NPM harus unik.
3. Email harus valid.
4. Data profil dapat digunakan otomatis untuk Lampiran A.
5. Mahasiswa tidak perlu mengisi ulang data yang sudah ada di profil.
6. Jika profil berubah, perubahan penting harus masuk audit log.

---

## 7. Tahap 3 — Registrasi KP/KPL

### Tujuan

Mahasiswa mengisi form registrasi digital sebagai pengganti Google Form manual.

### Aktor

- Mahasiswa

### Alur

```text
Mahasiswa membuka menu Registrasi
↓
Mahasiswa mengisi data akademik
↓
Mahasiswa mengupload dokumen persyaratan
↓
Mahasiswa menyetujui pernyataan
↓
Mahasiswa menyimpan draft atau mengirim registrasi
↓
Sistem melakukan validasi
↓
Status menjadi menunggu_verifikasi
```

### Data Registrasi

Data utama:

```text
Periode KP/KPL
Program studi
Semester
Tahun akademik
Jumlah SKS
IPK
Status IPK minimal
Status kelulusan mata kuliah inti
Status kelulusan mata kuliah konsentrasi
Status biaya pendidikan
Bukti pembayaran
KHS terbaru
Surat rekomendasi Dosen PA
Pernyataan mahasiswa
Persetujuan orang tua/wali jika diwajibkan
```

### Status

```text
draft
menunggu_verifikasi
```

### Aturan

1. Registrasi hanya bisa dilakukan pada periode aktif.
2. Mahasiswa hanya boleh memiliki satu registrasi aktif dalam satu periode.
3. Mahasiswa dapat menyimpan draft.
4. Setelah submit, data tidak bisa diubah kecuali dikembalikan untuk revisi.
5. IPK dan SKS menjadi syarat kelayakan awal.
6. Kelengkapan dokumen wajib diperiksa.
7. Semua file wajib divalidasi tipe dan ukuran.
8. Submit registrasi harus masuk audit log.

---

## 8. Tahap 4 — Verifikasi Registrasi

### Tujuan

Admin/Koordinator memeriksa kelengkapan dan kelayakan registrasi mahasiswa.

### Aktor

- Admin
- Koordinator KP/KPL

### Alur

```text
Mahasiswa submit registrasi
↓
Admin memeriksa kelengkapan administrasi
↓
Koordinator memeriksa kelayakan akademik
↓
Koordinator memilih keputusan
↓
Disetujui / Revisi / Ditolak
```

### Keputusan

| Keputusan | Status Baru            | Keterangan                                         |
| --------- | ---------------------- | -------------------------------------------------- |
| Setujui   | `registrasi_disetujui` | Mahasiswa lanjut ke penempatan.                    |
| Revisi    | `revisi_registrasi`    | Mahasiswa memperbaiki data/dokumen.                |
| Tolak     | `registrasi_ditolak`   | Mahasiswa tidak dapat lanjut kecuali dibuka ulang. |

### Aturan

1. Admin dapat membantu verifikasi administrasi.
2. Koordinator memberi keputusan akhir.
3. Jika revisi, catatan wajib diisi.
4. Jika ditolak, alasan wajib diisi.
5. Dokumen yang belum valid harus diberi status.
6. Mahasiswa mendapat notifikasi status.
7. Keputusan wajib masuk audit log.

---

## 9. Tahap 5 — Revisi Registrasi

### Tujuan

Mahasiswa memperbaiki data atau dokumen yang belum valid.

### Aktor

- Mahasiswa
- Admin
- Koordinator KP/KPL

### Alur

```text
Registrasi dikembalikan untuk revisi
↓
Mahasiswa membaca catatan revisi
↓
Mahasiswa memperbaiki data/dokumen
↓
Mahasiswa submit ulang
↓
Status kembali menunggu_verifikasi
```

### Status

```text
revisi_registrasi
menunggu_verifikasi
```

### Aturan

1. Hanya field atau dokumen yang direvisi yang boleh dibuka jika memungkinkan.
2. Dokumen lama tidak langsung dihapus.
3. Upload baru menjadi versi terbaru.
4. Riwayat revisi harus disimpan.
5. Submit ulang masuk audit log.

---

## 10. Tahap 6 — Penempatan KP/KPL

### Tujuan

Mahasiswa memilih jalur penempatan setelah registrasi disetujui.

### Aktor

- Mahasiswa
- Koordinator KP/KPL
- Admin

### Alur

```text
Registrasi disetujui
↓
Menu Penempatan terbuka
↓
Mahasiswa memilih jalur penempatan
↓
Jalur Mitra atau Jalur Mandiri
```

### Status

```text
registrasi_disetujui
```

Catatan:

Status "menunggu penempatan" tidak perlu disimpan sebagai `current_status`.
Kondisi tersebut dihitung dari `registrasi_disetujui` dan belum ada pengajuan penempatan aktif.

### Aturan

1. Menu Penempatan terkunci sebelum registrasi disetujui.
2. Mahasiswa dapat melihat instruksi penempatan.
3. Mahasiswa dapat memilih mitra kampus atau mengajukan tempat mandiri.
4. Mahasiswa tidak boleh memilih lebih dari satu jalur aktif sekaligus.
5. Jika jalur ditolak, mahasiswa dapat mengajukan ulang sesuai aturan.

---

## 11. Tahap 7A — Penempatan Mitra dengan TOPSIS

### Tujuan

Sistem memberikan rekomendasi instansi mitra menggunakan metode TOPSIS.

### Aktor

- Mahasiswa
- Koordinator KP/KPL
- Admin

### Alur

```text
Mahasiswa memilih jalur mitra
↓
Sistem mengambil daftar instansi mitra tersedia
↓
Sistem menghitung TOPSIS
↓
Sistem menampilkan ranking rekomendasi
↓
Mahasiswa memilih instansi
↓
Pengajuan dikirim ke Koordinator
↓
Koordinator validasi
```

### Kriteria TOPSIS

Kriteria awal:

```text
Kesesuaian bidang instansi
Kesesuaian kemampuan mahasiswa
Ketersediaan kuota
Jarak lokasi
Status kemitraan/riwayat kerja sama
Ketersediaan pembimbing lapangan
```

### Keputusan Koordinator

| Keputusan | Status Baru            |
| --------- | ---------------------- |
| Setujui   | `penempatan_disetujui` |
| Revisi    | `penempatan_revisi`    |
| Tolak     | `penempatan_ditolak`   |

### Aturan

1. TOPSIS hanya rekomendasi.
2. Koordinator tetap memberi keputusan akhir.
3. Bobot TOPSIS harus berdasarkan periode.
4. Kuota instansi wajib diperiksa.
5. Jika kuota penuh, instansi tidak boleh dipilih kecuali ada override Koordinator.
6. Hasil TOPSIS harus disimpan.
7. Keputusan penempatan masuk audit log.

---

## 12. Tahap 7B — Penempatan Tempat Mandiri

### Tujuan

Mahasiswa mengajukan instansi sendiri di luar daftar mitra kampus.

### Aktor

- Mahasiswa
- Koordinator KP/KPL
- Admin

### Alur

```text
Mahasiswa memilih jalur tempat mandiri
↓
Mahasiswa mengisi data instansi
↓
Mahasiswa mengisi alasan pemilihan
↓
Mahasiswa submit pengajuan
↓
Koordinator memvalidasi kelayakan
↓
Disetujui / Revisi / Ditolak
```

### Data Instansi Mandiri

Data minimal:

```text
Nama instansi
Alamat instansi
Bidang instansi
Nama kontak/pimpinan
Jabatan kontak
Nomor HP/telepon
Email jika ada
Alasan memilih instansi
Kesesuaian dengan bidang studi
Rencana kegiatan
```

### Keputusan Koordinator

| Keputusan | Status Baru            |
| --------- | ---------------------- |
| Setujui   | `penempatan_disetujui` |
| Revisi    | `penempatan_revisi`    |
| Tolak     | `penempatan_ditolak`   |

### Aturan

1. Tempat mandiri tidak wajib memiliki akun instansi.
2. Koordinator mengecek kelayakan tempat.
3. Jika disetujui, sistem generate dokumen/surat.
4. Jika ditolak, mahasiswa harus memilih tempat lain.
5. Semua keputusan harus memiliki catatan.
6. Semua keputusan masuk audit log.

---

## 13. Tahap 8 — Generate Surat dan Dokumen

### Tujuan

Sistem menghasilkan dokumen resmi berdasarkan data registrasi dan penempatan.

### Aktor

- Admin
- Koordinator KP/KPL
- Mahasiswa

### Alur

```text
Penempatan disetujui
↓
Sistem menyiapkan template dokumen
↓
Sistem generate PDF
↓
Mahasiswa download dokumen
↓
Dokumen dicetak jika perlu
↓
Dokumen dibawa ke instansi
```

### Dokumen yang Dapat Digenerate

```text
Lampiran A/Formulir Pendaftaran KP/KPL
Surat Rekomendasi Dosen PA
Surat Permohonan/Pengantar ke Instansi
Lembar Persetujuan Instansi
Surat Tugas Dosen Pembimbing
Form Penilaian Instansi
Rekap Nilai Akhir
```

### Status

```text
surat_tersedia
```

Catatan:

Kondisi "menunggu surat" dihitung dari `penempatan_disetujui` dan belum ada dokumen generated aktif.

### Aturan

1. Dokumen harus mengikuti template resmi kampus.
2. Nomor surat dapat dikelola Admin/Koordinator.
3. Dokumen yang digenerate disimpan di `writable/generated`.
4. Mahasiswa mengunduh dokumen melalui controller.
5. File tidak boleh diakses langsung dari public.
6. Generate dokumen penting masuk audit log.

---

## 14. Tahap 9 — Konfirmasi Penerimaan Instansi

### Tujuan

Memastikan instansi menerima mahasiswa.

### Aktor

- Mahasiswa
- Instansi Mitra
- Admin
- Koordinator KP/KPL

### Jalur Mitra dengan Akun

```text
Instansi login
↓
Instansi melihat pengajuan
↓
Instansi menerima atau menolak
↓
Sistem menyimpan keputusan
```

### Jalur Mandiri atau Tanpa Akun

```text
Mahasiswa download surat/form
↓
Mahasiswa menyerahkan ke instansi
↓
Instansi mengisi persetujuan
↓
Instansi tanda tangan dan stempel
↓
Mahasiswa upload ulang dokumen
↓
Admin/Koordinator verifikasi
```

### Status

```text
menunggu_penerimaan_instansi
penerimaan_perlu_revisi
diterima_instansi
```

### Aturan

1. Dokumen penerimaan wajib diverifikasi.
2. Tanda tangan dan stempel dapat menjadi syarat valid.
3. Jika dokumen tidak jelas, status menjadi perlu revisi.
4. Jika instansi menolak, mahasiswa kembali ke tahap penempatan.
5. File balasan disimpan di `writable/uploads`.
6. Verifikasi masuk audit log.

---

## 15. Tahap 10 — Penetapan Dosen Pembimbing

### Tujuan

Koordinator menetapkan dosen pembimbing untuk mahasiswa.

### Aktor

- Koordinator KP/KPL
- Admin
- Dosen Pembimbing
- Mahasiswa

### Alur

```text
Mahasiswa diterima instansi
↓
Koordinator membuka halaman penetapan pembimbing
↓
Sistem menampilkan dosen dan kuota
↓
Koordinator memilih dosen pembimbing
↓
Sistem menyimpan penetapan
↓
Mahasiswa dan dosen mendapat notifikasi
```

### Status

```text
diterima_instansi
dosen_ditetapkan
```

### Aturan

1. Dosen pembimbing ditetapkan setelah penerimaan instansi valid.
2. Dosen tidak boleh melebihi kuota.
3. Koordinator dapat mengganti dosen dengan alasan.
4. Riwayat dosen lama tetap disimpan.
5. Dosen hanya bisa melihat mahasiswa bimbingannya.
6. Penetapan pembimbing masuk audit log.

---

## 16. Tahap 11 — KP/KPL Berjalan

### Tujuan

Menandai bahwa mahasiswa sudah dapat menjalankan kegiatan KP/KPL.

### Aktor

- Mahasiswa
- Dosen Pembimbing
- Koordinator KP/KPL
- Instansi Mitra

### Alur

```text
Dosen pembimbing ditetapkan
↓
Status mahasiswa menjadi sedang_berjalan
↓
Menu Logbook terbuka
↓
Mahasiswa mulai mengisi logbook mingguan
```

### Status

```text
sedang_berjalan
```

Catatan:

Status logbook tidak disimpan sebagai `current_status`.
Progres logbook dikelola di tabel `logbook_weeks`.

### Aturan

1. Logbook tidak boleh dibuka sebelum dosen ditetapkan.
2. Dashboard mahasiswa menampilkan periode kegiatan.
3. Dosen dapat melihat mahasiswa di daftar bimbingan.
4. Koordinator dapat memantau progres mahasiswa.
5. Jika ada masalah, Koordinator dapat memberi status khusus jika dibutuhkan.

---

## 17. Tahap 12 — Logbook Mingguan

### Tujuan

Mahasiswa melaporkan kegiatan KP/KPL secara berkala.

### Aktor

- Mahasiswa
- Dosen Pembimbing
- Instansi Mitra jika fitur diaktifkan

### Alur

```text
Mahasiswa membuat logbook minggu ke-n
↓
Mahasiswa mengisi detail kegiatan harian
↓
Mahasiswa menyimpan draft
↓
Mahasiswa submit logbook
↓
Dosen mereview
↓
Disetujui atau perlu revisi
```

### Status Logbook

```text
draft
dikirim
perlu_revisi
disetujui
terkunci
```

### Aturan

1. Logbook menggunakan model mingguan.
2. Detail kegiatan harian berada di dalam logbook mingguan.
3. Mahasiswa dapat menyimpan draft.
4. Setelah submit, logbook menunggu review dosen.
5. Dosen dapat menyetujui atau meminta revisi.
6. Logbook yang sudah disetujui dikunci.
7. Logbook harus mobile-friendly.
8. Submit dan review logbook masuk audit log.

---

## 18. Tahap 13 — Laporan Akhir

### Tujuan

Mahasiswa mengupload laporan akhir setelah kegiatan KP/KPL berjalan.

### Aktor

- Mahasiswa
- Dosen Pembimbing
- Koordinator KP/KPL

### Alur

```text
Mahasiswa membuka menu Laporan Akhir
↓
Mahasiswa upload file laporan akhir
↓
Dosen memeriksa laporan
↓
Dosen menyetujui atau meminta revisi
↓
Jika valid, mahasiswa lanjut ke penilaian
```

### Status

```text
laporan_akhir_dikirim
laporan_akhir_revisi
menunggu_penilaian
```

Catatan:

Kondisi "menunggu laporan akhir" dihitung dari `sedang_berjalan` dan belum ada laporan akhir aktif.
Jika laporan akhir disetujui, status utama dapat naik ke `menunggu_penilaian`.

### Aturan

1. Laporan akhir tidak boleh diupload sebelum tahap berjalan.
2. Format file harus divalidasi.
3. Dosen dapat memberi catatan revisi.
4. File lama tetap disimpan jika ada revisi.
5. Laporan akhir valid menjadi syarat penilaian akhir jika ditentukan periode.

---

## 19. Tahap 14 — Penilaian Instansi

### Tujuan

Instansi memberi evaluasi terhadap mahasiswa.

### Aktor

- Instansi Mitra
- Mahasiswa
- Admin
- Koordinator KP/KPL

### Jalur Mitra dengan Akun

```text
Instansi login
↓
Instansi membuka daftar mahasiswa
↓
Instansi mengisi evaluasi dan nilai
↓
Nilai tersimpan
```

### Jalur Mandiri atau Tanpa Akun

```text
Sistem generate form penilaian instansi
↓
Mahasiswa download form
↓
Instansi mengisi, tanda tangan, dan stempel
↓
Mahasiswa upload ulang form
↓
Admin/Koordinator memverifikasi
↓
Nilai diinput/ditandai valid
```

### Status

Status detail penilaian instansi disimpan di `assessment_scores.status` atau status dokumen nilai manual.

`kp_registrations.current_status` tetap memakai:

```text
menunggu_penilaian
menunggu_validasi_akhir
```

### Aturan

1. Instansi mandiri tidak wajib memiliki akun.
2. Form nilai mandiri harus diverifikasi.
3. Nilai yang sudah diverifikasi tidak boleh diubah sembarangan.
4. Jika dokumen nilai tidak valid, mahasiswa diminta revisi.
5. Semua input atau verifikasi nilai masuk audit log.

---

## 20. Tahap 15 — Penilaian Dosen

### Tujuan

Dosen Pembimbing memberi nilai akademik.

### Aktor

- Dosen Pembimbing
- Koordinator KP/KPL

### Alur

```text
Dosen membuka mahasiswa bimbingan
↓
Dosen melihat logbook dan laporan akhir
↓
Dosen mengisi nilai akademik
↓
Nilai tersimpan
↓
Status nilai dosen diperbarui di assessment_scores
```

### Status

Status detail penilaian dosen disimpan di `assessment_scores.status`.

`kp_registrations.current_status` tetap memakai:

```text
menunggu_penilaian
menunggu_validasi_akhir
```

### Aturan

1. Dosen hanya menilai mahasiswa bimbingannya.
2. Nilai dapat disimpan sebagai draft jika dibutuhkan.
3. Setelah submit, nilai menunggu validasi Koordinator.
4. Dosen tidak dapat mengubah nilai setelah dikunci Koordinator.
5. Input nilai dosen masuk audit log.

---

## 21. Tahap 16 — Rekap dan Validasi Nilai Akhir

### Tujuan

Sistem menghitung nilai akhir dan Koordinator memvalidasi.

### Aktor

- Koordinator KP/KPL
- Admin
- Dosen Pembimbing

### Alur

```text
Nilai instansi masuk
↓
Nilai dosen masuk
↓
Sistem menghitung nilai akhir
↓
Koordinator memeriksa rekap
↓
Koordinator validasi nilai akhir
↓
Nilai dikunci
```

### Komponen Nilai

Contoh bobot awal:

```text
Nilai Instansi: 40%
Nilai Dosen: 50%
Administrasi/Logbook: 10%
```

### Status

```text
menunggu_validasi_akhir
nilai_divalidasi
selesai
```

### Aturan

1. Bobot nilai harus dikonfigurasi per periode.
2. Nilai akhir tidak boleh berubah jika bobot periode lain berubah.
3. Koordinator memberi validasi final.
4. Setelah divalidasi, nilai dikunci.
5. Koreksi nilai harus melalui mekanisme khusus dan audit log.

---

## 22. Tahap 17 — Selesai

### Tujuan

Menandai mahasiswa telah menyelesaikan KP/KPL.

### Aktor

- Koordinator KP/KPL
- Admin
- Mahasiswa

### Alur

```text
Nilai akhir divalidasi
↓
Status mahasiswa menjadi selesai
↓
Mahasiswa dapat melihat hasil akhir
↓
Data masuk riwayat mahasiswa
```

### Status

```text
selesai
```

### Aturan

1. Mahasiswa yang sudah selesai tidak dapat mengubah data proses.
2. Mahasiswa dapat melihat riwayat.
3. Mahasiswa tidak dapat daftar ulang pada periode baru kecuali diberi akses khusus oleh Koordinator.
4. Status selesai menjadi dasar arsip periode.

---

## 23. Tahap 18 — Arsip Periode

### Tujuan

Menyimpan seluruh data periode secara rapi dan read-only.

### Aktor

- Admin
- Koordinator KP/KPL
- Mahasiswa
- Dosen Pembimbing
- Instansi Mitra

### Alur

```text
Semua proses periode selesai
↓
Koordinator/Admin menutup periode
↓
Sistem memeriksa data penting
↓
Periode diarsipkan
↓
Data menjadi read-only
```

### Status Periode

```text
draft
aktif
ditutup
diarsipkan
```

### Data Arsip

Arsip menyimpan:

```text
Data mahasiswa
Data registrasi
Dokumen upload
Dokumen generated
Penempatan
Instansi
Dosen pembimbing
Logbook
Laporan akhir
Penilaian instansi
Penilaian dosen
Nilai akhir
Riwayat status
Audit log penting
```

### Aturan

1. Arsip bersifat read-only.
2. Mahasiswa hanya melihat arsip miliknya.
3. Dosen hanya melihat arsip mahasiswa bimbingannya.
4. Instansi hanya melihat arsip mahasiswa di instansinya.
5. Admin/Koordinator melihat arsip sesuai hak akses.
6. Koreksi arsip harus menggunakan fitur koreksi dan alasan.
7. Koreksi arsip wajib masuk audit log.

---

## 24. Locked Menu Berdasarkan Status

Menu mahasiswa harus dibuka berdasarkan status.

Contoh aturan:

| Menu                   | Syarat Status Minimal                     |
| ---------------------- | ----------------------------------------- |
| Dashboard              | Login                                     |
| Profil Saya            | Login                                     |
| Registrasi KP/KPL      | Profil lengkap dan periode aktif          |
| Status Registrasi      | `draft` atau lebih                        |
| Penempatan KP/KPL      | `registrasi_disetujui`                    |
| Surat & Dokumen        | `penempatan_disetujui`                    |
| Upload Dokumen Balasan | `surat_tersedia`                          |
| Pembimbing Saya        | `dosen_ditetapkan`                        |
| Logbook Mingguan       | `dosen_ditetapkan` atau `sedang_berjalan` |
| Laporan Akhir          | `sedang_berjalan`                         |
| Penilaian Saya         | `menunggu_penilaian` atau lebih           |
| Riwayat KP/KPL         | Ada data riwayat                          |

Catatan:

1. Menu tetap boleh tampil.
2. Menu terkunci memakai icon gembok.
3. Klik menu terkunci menampilkan alasan.
4. Backend tetap wajib menolak akses langsung.

---

## 25. Pesan Akses Workflow

Contoh pesan jika user membuka menu yang belum tersedia:

```text
Menu Penempatan belum dapat dibuka karena registrasi Anda belum disetujui.
Menu Surat & Dokumen belum dapat dibuka karena penempatan Anda belum disetujui.
Menu Logbook belum dapat dibuka karena Anda belum diterima instansi dan dosen pembimbing belum ditetapkan.
Menu Laporan Akhir belum dapat dibuka karena tahap logbook belum berjalan.
Menu Penilaian belum dapat dibuka karena laporan akhir belum divalidasi.
```

Aturan:

1. Pesan harus berbahasa Indonesia.
2. Pesan harus menjelaskan alasan.
3. Jangan hanya menampilkan “Access denied”.
4. Redirect user ke dashboard mahasiswa jika akses ditolak.

---

## 26. Workflow Access Filter

Sistem harus memiliki filter akses workflow.

File yang disarankan:

```text
app/Filters/WorkflowAccessFilter.php
```

Fungsi:

1. Memeriksa user login.
2. Memeriksa role.
3. Memeriksa status registrasi.
4. Memeriksa apakah menu/URL boleh dibuka.
5. Menolak akses jika status belum memenuhi.
6. Menampilkan pesan flash/SweetAlert.
7. Redirect ke dashboard.

Contoh konsep:

```text
URL /mahasiswa/logbook
Syarat status: dosen_ditetapkan
Jika current_status belum mencapai dosen_ditetapkan:
  redirect ke dashboard
  tampilkan pesan alasan
```

---

## 27. Status Log

Setiap perubahan status harus disimpan.

Tabel yang disarankan:

```text
registration_status_logs
```

Data yang disimpan:

```text
registration_id
old_status
new_status
changed_by
changed_by_role
note
created_at
```

Contoh perubahan status:

```text
menunggu_verifikasi → registrasi_disetujui
penempatan_diajukan → penempatan_disetujui
diterima_instansi → dosen_ditetapkan
menunggu_validasi_akhir → selesai
```

---

## 28. Audit Log

Aksi penting yang wajib masuk audit log:

```text
Login penting
Submit registrasi
Upload dokumen
Verifikasi dokumen
Setujui/tolak registrasi
Ajukan tempat
Setujui/tolak penempatan
Generate dokumen
Upload dokumen balasan
Tetapkan dosen pembimbing
Submit logbook
Review logbook
Upload laporan akhir
Input nilai instansi
Input nilai dosen
Validasi nilai akhir
Tutup periode
Arsipkan periode
Koreksi arsip
```

---

## 29. Edge Case Workflow

Agent harus mempertimbangkan kasus berikut:

1. Mahasiswa belum melengkapi profil.
2. Tidak ada periode aktif.
3. Mahasiswa sudah pernah selesai KP/KPL.
4. Mahasiswa submit registrasi lalu ingin edit.
5. Dokumen upload salah.
6. Registrasi ditolak.
7. Tempat mitra penuh kuota.
8. TOPSIS tidak menemukan rekomendasi cocok.
9. Tempat mandiri ditolak.
10. Instansi menolak mahasiswa.
11. Dokumen balasan tanpa stempel.
12. Dosen pembimbing penuh kuota.
13. Dosen pembimbing diganti.
14. Mahasiswa terlambat isi logbook.
15. Logbook ditolak/revisi.
16. Nilai instansi belum masuk.
17. Dosen belum input nilai.
18. Periode hendak ditutup tetapi masih ada mahasiswa belum selesai.
19. Data arsip perlu koreksi.
20. User mencoba akses URL langsung tanpa izin.

Detail aturan kasus ditulis di `docs/09-business-rules.md`.

---

## 30. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Membuka seluruh menu mahasiswa sejak awal.
2. Membuat workflow hanya berdasarkan tampilan sidebar.
3. Mengabaikan validasi backend.
4. Membuat status tidak konsisten.
5. Mencampur label UI dengan status database.
6. Menghapus riwayat status.
7. Menjadikan TOPSIS sebagai keputusan otomatis.
8. Mengharuskan instansi mandiri punya akun.
9. Mengizinkan mahasiswa mengubah data setelah disetujui tanpa mekanisme revisi.
10. Mengarsipkan periode tanpa pemeriksaan data penting.
11. Mengubah data arsip tanpa audit log.
12. Menampilkan pesan error Bahasa Inggris.

---

## 31. Ringkasan Final

Workflow utama sistem:

```text
Akun
↓
Profil
↓
Registrasi
↓
Verifikasi
↓
Penempatan
↓
Dokumen
↓
Penerimaan Instansi
↓
Dosen Pembimbing
↓
KP/KPL Berjalan
↓
Logbook
↓
Laporan Akhir
↓
Penilaian
↓
Nilai Akhir
↓
Selesai
↓
Arsip
```

Sistem harus selalu menjawab tiga pertanyaan untuk mahasiswa:

```text
Saya sedang di tahap apa?
Apa yang harus saya lakukan sekarang?
Menu apa yang belum bisa saya buka dan kenapa?
```

Workflow ini menjadi dasar utama sidebar, dashboard, database status, validasi backend, dan arsip periode.
