# docs/03-roles.md

# Role dan Hak Akses Sistem KP/KPL

## 1. Tujuan Dokumen

Dokumen ini menjelaskan role, tanggung jawab, batasan akses, dan aturan data scope untuk Sistem Manajemen KP/KPL.

Dokumen ini menjadi acuan saat membuat:

- `RoleFilter`
- `WorkflowAccessFilter`
- sidebar role-based
- dashboard setiap role
- query data per role
- validasi backend
- audit log

Role harus dipahami sebagai batas kewenangan, bukan hanya label menu.

---

## 2. Role Final

Sistem memiliki 5 role utama.

| Role UI | Role Database | Fungsi Utama |
|---|---|---|
| Admin | `admin` | Mengelola sistem, data master, akun, dokumen, konfigurasi, dan administrasi. |
| Koordinator KP/KPL | `koordinator` | Mengambil keputusan akademik final untuk registrasi, penempatan, pembimbing, nilai, dan arsip. |
| Mahasiswa | `mahasiswa` | Mengikuti alur KP/KPL dari profil sampai arsip. |
| Dosen Pembimbing | `dosen` | Membimbing, mereview logbook/laporan, dan memberi nilai akademik. |
| Instansi Mitra | `instansi` | Mengonfirmasi penerimaan dan memberi evaluasi/nilai untuk mahasiswa di instansinya. |

Aturan:

1. Setiap user memiliki satu role utama.
2. Role disimpan di `users.role`.
3. Role database memakai `snake_case` singkat.
4. Label UI boleh memakai Bahasa Indonesia yang lebih jelas.
5. Semua akses role wajib dicek di backend.

---

## 3. Prinsip Hak Akses

Prinsip utama:

1. Admin mengelola data dan administrasi, tetapi bukan pengambil keputusan akademik final.
2. Koordinator KP/KPL adalah pengambil keputusan akademik final.
3. Mahasiswa hanya boleh mengakses data miliknya sendiri.
4. Dosen hanya boleh mengakses mahasiswa yang ditetapkan sebagai bimbingannya.
5. Instansi hanya boleh mengakses mahasiswa yang ditempatkan di instansinya.
6. Instansi mandiri tidak wajib memiliki akun.
7. Sidebar bukan proteksi utama; route, controller, service, dan query tetap wajib memfilter akses.
8. Semua aksi penting masuk audit log.
9. Semua penolakan akses harus memakai pesan Bahasa Indonesia.

---

## 4. Data Scope Per Role

| Role | Scope Data |
|---|---|
| Admin | Data sistem dan administrasi sesuai modul, kecuali keputusan akademik final. |
| Koordinator KP/KPL | Seluruh data akademik KP/KPL pada periode/prodi yang menjadi tanggung jawabnya. |
| Mahasiswa | Profil, registrasi, dokumen, penempatan, logbook, nilai, dan arsip miliknya sendiri. |
| Dosen Pembimbing | Mahasiswa yang aktif atau pernah dibimbing melalui `supervisor_assignments`. |
| Instansi Mitra | Mahasiswa yang ditempatkan pada `institution_id` milik instansi tersebut. |

Catatan:

1. Query detail mahasiswa tidak boleh hanya berdasarkan ID/UUID.
2. Query harus memeriksa relasi role.
3. Download dokumen harus memeriksa kepemilikan atau relasi data.
4. Data arsip tetap mengikuti aturan scope yang sama.

---

## 5. Admin

### Fungsi Utama

Admin bertugas mengelola sistem dan administrasi.

### Hak Akses

Admin dapat:

- Mengelola akun user.
- Mengelola data mahasiswa.
- Mengelola data dosen.
- Mengelola data instansi.
- Mengelola program studi.
- Mengelola periode KP/KPL.
- Mengelola dokumen persyaratan.
- Mengelola template surat/dokumen.
- Mengelola konfigurasi dasar TOPSIS.
- Membantu verifikasi administrasi dokumen.
- Melihat data registrasi dan penempatan.
- Melihat logbook, laporan akhir, dan penilaian untuk kebutuhan monitoring.
- Melihat audit log.
- Melakukan export laporan sesuai hak.
- Mengatur konfigurasi sistem.

### Batasan Akses

Admin tidak boleh:

- Menjadi pengambil keputusan akademik final jika tidak diberi mandat khusus.
- Menyetujui penempatan sebagai keputusan final menggantikan Koordinator.
- Menetapkan dosen pembimbing sebagai keputusan akademik final tanpa kewenangan Koordinator.
- Memvalidasi nilai akhir sebagai keputusan final jika aturan menetapkan Koordinator sebagai validator.
- Mengubah data arsip langsung tanpa mekanisme koreksi.
- Menghapus data penting secara permanen.

### Aksi Penting

Aksi Admin yang wajib masuk audit log:

- Membuat/mengubah/nonaktifkan akun.
- Mengubah data master penting.
- Mengubah periode.
- Memverifikasi dokumen administrasi.
- Mengubah template dokumen.
- Mengubah konfigurasi TOPSIS.
- Melakukan export data penting.
- Melihat/mengunduh dokumen sensitif jika dibutuhkan.

---

## 6. Koordinator KP/KPL

### Fungsi Utama

Koordinator adalah pemilik keputusan akademik KP/KPL.

### Hak Akses

Koordinator dapat:

- Melihat registrasi mahasiswa pada periode/prodi terkait.
- Menyetujui, menolak, atau meminta revisi registrasi.
- Memvalidasi kelayakan akademik mahasiswa.
- Memvalidasi pengajuan penempatan mitra.
- Memvalidasi pengajuan tempat mandiri.
- Melihat dan meninjau hasil TOPSIS.
- Menyetujui keputusan penempatan akhir.
- Memvalidasi dokumen penerimaan instansi.
- Menetapkan dosen pembimbing.
- Mengganti dosen pembimbing dengan alasan.
- Memantau logbook, laporan akhir, dan progres mahasiswa.
- Memvalidasi nilai akhir.
- Menutup dan mengarsipkan periode.
- Melakukan koreksi arsip sesuai aturan.
- Melihat audit log akademik.

### Batasan Akses

Koordinator tidak boleh:

- Mengabaikan dokumen wajib saat memutuskan registrasi.
- Membuat keputusan tanpa catatan pada kasus revisi/tolak/override.
- Menjadikan TOPSIS sebagai keputusan otomatis tanpa review.
- Menetapkan dosen yang sudah penuh kuota tanpa alasan override.
- Mengubah nilai akhir yang sudah terkunci tanpa mekanisme koreksi.
- Mengarsipkan periode tanpa pemeriksaan data penting.

### Aksi Penting

Aksi Koordinator yang wajib masuk audit log:

- Setujui/revisi/tolak registrasi.
- Override syarat akademik.
- Setujui/revisi/tolak penempatan.
- Override kuota instansi.
- Tetapkan atau ganti dosen pembimbing.
- Validasi penerimaan instansi.
- Validasi nilai akhir.
- Tutup periode.
- Arsipkan periode.
- Koreksi arsip.

---

## 7. Mahasiswa

### Fungsi Utama

Mahasiswa mengikuti workflow KP/KPL secara bertahap.

### Hak Akses

Mahasiswa dapat:

- Login manual atau Google.
- Melengkapi profil pribadi.
- Mengisi registrasi KP/KPL pada periode aktif.
- Mengupload dokumen persyaratan.
- Melihat status registrasi.
- Memperbaiki data/dokumen jika diminta revisi.
- Memilih penempatan mitra.
- Mengajukan tempat mandiri.
- Melihat rekomendasi TOPSIS untuk instansi mitra.
- Mengunduh dokumen/surat generated.
- Mengupload dokumen balasan bertanda tangan/stempel.
- Melihat dosen pembimbing.
- Mengisi logbook mingguan.
- Mengupload laporan akhir.
- Melihat status penilaian dan nilai akhir.
- Melihat riwayat dan arsip miliknya.
- Melihat notifikasi miliknya.

### Batasan Akses

Mahasiswa tidak boleh:

- Mengakses data mahasiswa lain.
- Membuka menu yang belum sesuai tahap.
- Mengubah registrasi setelah dikirim kecuali status revisi.
- Menghapus dokumen yang sudah valid.
- Mengubah dokumen generated.
- Memilih lebih dari satu penempatan aktif.
- Mengisi logbook sebelum dosen pembimbing ditetapkan.
- Mengubah logbook yang sudah disetujui/terkunci.
- Mengubah nilai.
- Mendaftar ulang jika sudah selesai, kecuali Koordinator membuka akses khusus.

### Workflow Access

Menu mahasiswa dibuka berdasarkan `kp_registrations.current_status` dan status subfitur.

Contoh:

| Menu | Syarat |
|---|---|
| Profil Saya | Login |
| Registrasi KP/KPL | Profil lengkap dan ada periode aktif |
| Penempatan KP/KPL | `registrasi_disetujui` |
| Surat & Dokumen | `penempatan_disetujui` |
| Upload Dokumen Balasan | `surat_tersedia` |
| Pembimbing Saya | `dosen_ditetapkan` |
| Logbook Mingguan | `dosen_ditetapkan` atau `sedang_berjalan` |
| Laporan Akhir | `sedang_berjalan` |
| Penilaian Saya | `menunggu_penilaian` atau status setelahnya |
| Riwayat KP/KPL | Ada data riwayat |

### Aksi Penting

Aksi Mahasiswa yang wajib masuk audit log:

- Submit registrasi.
- Upload dokumen.
- Submit ulang revisi.
- Ajukan penempatan.
- Download dokumen penting jika perlu dicatat.
- Upload dokumen balasan.
- Submit logbook.
- Upload laporan akhir.

---

## 8. Dosen Pembimbing

### Fungsi Utama

Dosen Pembimbing memantau, membimbing, mereview, dan menilai mahasiswa bimbingannya.

### Hak Akses

Dosen dapat:

- Melihat dashboard bimbingan.
- Melihat daftar mahasiswa bimbingan aktif.
- Melihat detail mahasiswa bimbingan.
- Melihat dokumen terkait mahasiswa bimbingan jika relevan.
- Melihat dan mereview logbook mahasiswa bimbingan.
- Memberi komentar/revisi logbook.
- Menyetujui logbook.
- Melihat dan mereview laporan akhir.
- Memberi nilai akademik.
- Melihat riwayat bimbingan.
- Melihat kuota bimbingannya.

### Batasan Akses

Dosen tidak boleh:

- Mengakses mahasiswa yang bukan bimbingannya.
- Mengubah registrasi mahasiswa.
- Menyetujui penempatan.
- Menetapkan dirinya sebagai pembimbing.
- Mengubah nilai setelah dikunci Koordinator.
- Mengubah arsip langsung.
- Melihat data instansi atau mahasiswa lain di luar relasi bimbingan.

### Relasi Akses

Akses dosen harus dicek melalui:

```text
dosen user
↓
lecturer_profiles.id
↓
supervisor_assignments.lecturer_id
↓
kp_registrations.id
```

Hanya assignment dengan status aktif atau riwayat yang sesuai yang boleh dibuka.

### Aksi Penting

Aksi Dosen yang wajib masuk audit log:

- Review logbook.
- Minta revisi logbook.
- Setujui logbook.
- Review laporan akhir.
- Input nilai dosen.
- Koreksi nilai jika mekanisme koreksi tersedia.

---

## 9. Instansi Mitra

### Fungsi Utama

Instansi Mitra mengelola proses penerimaan dan evaluasi mahasiswa yang ditempatkan di instansinya.

### Hak Akses

Instansi Mitra dapat:

- Login jika memiliki akun resmi.
- Mengelola profil instansi miliknya.
- Melihat mahasiswa yang ditempatkan di instansinya.
- Mengonfirmasi penerimaan mahasiswa jika fitur akun digunakan.
- Melihat dokumen terkait mahasiswa di instansinya.
- Melihat logbook mahasiswa jika fitur diaktifkan.
- Memberi evaluasi/nilai instansi.
- Melihat riwayat mahasiswa yang pernah ditempatkan di instansinya.

### Batasan Akses

Instansi Mitra tidak boleh:

- Mengakses mahasiswa di instansi lain.
- Mengakses data akademik internal yang tidak relevan.
- Mengubah data registrasi mahasiswa.
- Mengubah keputusan Koordinator.
- Mengisi nilai untuk mahasiswa yang tidak ditempatkan di instansinya.
- Mengakses dokumen yang bukan haknya.

### Instansi Mandiri

Aturan khusus:

1. Instansi mandiri tidak wajib memiliki akun.
2. Untuk instansi mandiri, penerimaan dan penilaian dapat memakai jalur dokumen manual.
3. Mahasiswa download dokumen, instansi tanda tangan/stempel, lalu mahasiswa upload ulang.
4. Admin/Koordinator memverifikasi dokumen.
5. Jika instansi mandiri sering digunakan, Admin/Koordinator dapat mengubahnya menjadi mitra.

### Relasi Akses

Akses instansi harus dicek melalui:

```text
instansi user
↓
institution_profiles.id
↓
placement_requests.institution_id
↓
kp_registrations.id
```

Untuk arsip, relasi tetap berdasarkan penempatan yang pernah disetujui.

---

## 10. Permission Matrix Ringkas

| Fitur | Admin | Koordinator | Mahasiswa | Dosen | Instansi |
|---|---|---|---|---|---|
| Kelola akun | Ya | Tidak | Tidak | Tidak | Tidak |
| Kelola data master | Ya | Terbatas | Tidak | Tidak | Tidak |
| Kelola periode | Ya | Ya sesuai hak | Tidak | Tidak | Tidak |
| Isi profil mahasiswa | Tidak | Tidak | Milik sendiri | Tidak | Tidak |
| Submit registrasi | Tidak | Tidak | Milik sendiri | Tidak | Tidak |
| Verifikasi administrasi | Ya | Ya | Tidak | Tidak | Tidak |
| Keputusan registrasi final | Tidak | Ya | Tidak | Tidak | Tidak |
| Ajukan penempatan | Tidak | Tidak | Milik sendiri | Tidak | Tidak |
| Validasi penempatan | Tidak | Ya | Tidak | Tidak | Tidak |
| Review TOPSIS | Ya | Ya | Lihat rekomendasi | Tidak | Tidak |
| Generate dokumen | Ya | Ya | Download milik sendiri | Tidak | Tidak |
| Upload dokumen balasan | Tidak | Tidak | Milik sendiri | Tidak | Tidak |
| Tetapkan dosen | Tidak | Ya | Tidak | Tidak | Tidak |
| Isi logbook | Tidak | Tidak | Milik sendiri | Tidak | Tidak |
| Review logbook | Monitoring | Monitoring | Tidak | Mahasiswa bimbingan | Opsional |
| Upload laporan akhir | Tidak | Tidak | Milik sendiri | Tidak | Tidak |
| Nilai instansi | Monitoring/input manual | Validasi | Lihat status | Tidak | Mahasiswa di instansi |
| Nilai dosen | Monitoring | Validasi | Lihat status | Mahasiswa bimbingan | Tidak |
| Validasi nilai akhir | Tidak | Ya | Tidak | Tidak | Tidak |
| Arsip | Ya | Ya | Milik sendiri | Bimbingan sendiri | Mahasiswa di instansi |
| Audit log | Ya | Terbatas | Tidak | Tidak | Tidak |

Keterangan:

1. "Monitoring" berarti boleh melihat sesuai kebutuhan, bukan mengambil keputusan final.
2. "Terbatas" berarti mengikuti konfigurasi hak akses sistem.
3. Semua aksi tetap wajib dicek di service/query, bukan hanya di sidebar.

---

## 11. Route Group

Gunakan route group berdasarkan role.

```text
/admin/*
/koordinator/*
/mahasiswa/*
/dosen/*
/instansi/*
```

Aturan:

1. Semua route dashboard wajib memakai `AuthFilter`.
2. Semua route role wajib memakai `RoleFilter`.
3. Route mahasiswa yang step-based wajib memakai `WorkflowAccessFilter`.
4. Route download wajib memeriksa data scope.
5. Jika akses ditolak, redirect ke dashboard role terkait dengan pesan jelas.

---

## 12. Filter yang Dibutuhkan

### AuthFilter

Memastikan user sudah login.

### RoleFilter

Memastikan user mengakses route sesuai role.

Contoh:

```text
/admin/* hanya untuk `admin`
/koordinator/* hanya untuk `koordinator`
/mahasiswa/* hanya untuk `mahasiswa`
/dosen/* hanya untuk `dosen`
/instansi/* hanya untuk `instansi`
```

### WorkflowAccessFilter

Memastikan mahasiswa hanya membuka menu sesuai tahap workflow.

### Data Ownership Check

Selain filter, controller/service harus memeriksa kepemilikan data.

Contoh:

```text
Mahasiswa membuka /download/document/{uuid}
↓
Sistem cek document.registration.student_id == student login
↓
Jika tidak cocok, akses ditolak
```

---

## 13. Pesan Akses Ditolak

Gunakan pesan Bahasa Indonesia.

Contoh:

```text
Anda tidak memiliki akses ke halaman ini.
Menu Penempatan belum dapat dibuka karena registrasi Anda belum disetujui.
Menu Logbook belum dapat dibuka karena dosen pembimbing belum ditetapkan.
Anda tidak memiliki akses untuk mengunduh dokumen ini.
Data mahasiswa tidak ditemukan atau bukan bagian dari hak akses Anda.
```

Jangan gunakan:

```text
Access denied.
Forbidden.
Unauthorized.
Invalid access.
```

---

## 14. Edge Case Hak Akses

Kasus yang wajib ditangani:

1. Mahasiswa mencoba membuka data mahasiswa lain lewat UUID.
2. Dosen membuka mahasiswa yang bukan bimbingannya.
3. Instansi membuka mahasiswa di instansi lain.
4. User login membuka route role lain.
5. Mahasiswa membuka menu workflow lewat URL langsung.
6. Akun instansi mandiri tidak ada, tetapi dokumen manual tetap harus bisa diproses.
7. Dosen pembimbing diganti, dosen lama hanya boleh melihat riwayat jika diizinkan.
8. Periode sudah diarsipkan, semua role hanya boleh akses read-only sesuai scope.
9. Admin mencoba melakukan keputusan akademik final tanpa kewenangan.
10. Koordinator melakukan override tanpa alasan.

---

## 15. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Menjadikan sidebar sebagai satu-satunya proteksi.
2. Membuka semua menu mahasiswa dari awal.
3. Mengizinkan mahasiswa melihat data mahasiswa lain.
4. Mengizinkan dosen melihat mahasiswa di luar bimbingannya.
5. Mengizinkan instansi melihat mahasiswa di instansi lain.
6. Membuat Admin mengambil keputusan akademik final tanpa aturan.
7. Membuat TOPSIS menjadi keputusan otomatis.
8. Membuat instansi mandiri wajib memiliki akun.
9. Mengubah arsip langsung tanpa mekanisme koreksi.
10. Menampilkan pesan akses dalam Bahasa Inggris.

---

## 16. Ringkasan Final

Keputusan role final:

```text
Admin = pengelola sistem dan administrasi.
Koordinator KP/KPL = pengambil keputusan akademik final.
Mahasiswa = pemilik data proses KP/KPL miliknya sendiri.
Dosen Pembimbing = reviewer dan penilai mahasiswa bimbingannya.
Instansi Mitra = penerima dan penilai mahasiswa di instansinya.
```

Implementasi wajib memakai:

```text
AuthFilter
RoleFilter
WorkflowAccessFilter
Data ownership check
Audit log
Pesan Bahasa Indonesia
```

Dokumen ini mengunci hak akses dasar sebelum implementasi fondasi CI4 dimulai.
