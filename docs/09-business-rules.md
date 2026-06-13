# docs/09-business-rules.md

# Business Rules Sistem KP/KPL

## 1. Tujuan Dokumen

Dokumen ini menjelaskan aturan bisnis utama Sistem Manajemen KP/KPL.

Business rules digunakan untuk mengatur:

- Akses role.
- Status workflow.
- Periode KP/KPL.
- Registrasi mahasiswa.
- Validasi dokumen.
- Penempatan mitra dan mandiri.
- TOPSIS.
- Dosen pembimbing.
- Logbook.
- Laporan akhir.
- Penilaian.
- Arsip.
- Edge case sistem.

Dokumen ini wajib dijadikan acuan sebelum membuat controller, service, validation, filter, dan database logic.

---

## 2. Prinsip Umum Business Rules

Prinsip utama:

1. Sistem mengikuti alur akademik KP/KPL, bukan CRUD biasa.
2. Semua proses utama harus terikat pada periode.
3. Status mahasiswa menjadi dasar pembukaan menu.
4. Koordinator adalah pengambil keputusan akademik final.
5. Admin mengelola data dan administrasi sistem.
6. Mahasiswa tidak boleh melewati tahap.
7. TOPSIS hanya rekomendasi, bukan keputusan otomatis.
8. Instansi mandiri tidak wajib memiliki akun.
9. Dokumen yang butuh tanda tangan/stempel tetap didukung melalui download-upload.
10. Semua keputusan penting harus masuk audit log.
11. Semua pesan validasi dan error harus Bahasa Indonesia.
12. Data penting tidak boleh dihapus permanen.
13. Data arsip bersifat read-only.
14. Validasi backend wajib, frontend hanya tambahan.

---

## 3. Aturan Akun User

### Kondisi

User melakukan login atau membuat akun.

### Aturan Sistem

1. Email user harus unik.
2. Satu email hanya boleh memiliki satu akun.
3. Setiap akun memiliki satu role utama.
4. Role valid:
   - `admin`
   - `koordinator`
   - `mahasiswa`
   - `dosen`
   - `instansi`

5. Akun dengan status `inactive` atau `suspended` tidak boleh login.
6. Password wajib disimpan menggunakan `password_hash()`.
7. Password wajib diverifikasi menggunakan `password_verify()`.
8. Login berhasil diarahkan ke dashboard sesuai role.
9. Login gagal harus menampilkan pesan Bahasa Indonesia.
10. Riwayat login dapat disimpan di `login_histories`.

### Respon Sistem

Jika login berhasil:

```text
Login berhasil. Anda akan diarahkan ke dashboard.
```

Jika akun tidak aktif:

```text
Akun Anda tidak aktif. Silakan hubungi Admin.
```

Jika password salah:

```text
Email atau password tidak sesuai.
```

---

## 4. Aturan Login Google Mahasiswa

### Kondisi

Mahasiswa login menggunakan akun Google.

### Aturan Sistem

1. Google Login digunakan terutama untuk mahasiswa.
2. Jika email Google sudah ada di tabel `users`, sistem memakai akun tersebut.
3. Jika email Google belum ada, sistem membuat akun baru role `mahasiswa`.
4. Login Google tidak otomatis membuat registrasi KP/KPL.
5. Setelah login, mahasiswa tetap wajib melengkapi profil.
6. Jika mahasiswa mengisi NPM yang sudah digunakan akun lain, sistem menahan proses dan meminta verifikasi Admin.
7. Google Client ID dan Secret harus disimpan di `.env`.
8. Jika akun Google dibuat tetapi belum lengkap profilnya, status dashboard menampilkan `Profil Belum Lengkap`.

### Respon Sistem

Jika profil belum lengkap:

```text
Silakan lengkapi profil terlebih dahulu sebelum melakukan registrasi KP/KPL.
```

Jika NPM sudah digunakan:

```text
NPM ini sudah terdaftar pada akun lain. Silakan hubungi Admin untuk verifikasi.
```

---

## 5. Aturan NPM dan Email

### Kondisi

Mahasiswa mengisi profil atau registrasi.

### Aturan Sistem

1. Email harus unik di tabel `users`.
2. NPM harus unik di tabel `student_profiles`.
3. NPM hanya boleh digunakan oleh satu mahasiswa.
4. Jika email sama dan NPM sama, gunakan akun yang sama.
5. Jika email beda tetapi NPM sama, sistem harus menolak dan meminta verifikasi Admin.
6. Jika NPM belum ada, mahasiswa boleh menyimpan profil.
7. Perubahan NPM setelah ada registrasi harus dibatasi.

### Respon Sistem

```text
NPM sudah digunakan oleh akun lain.
```

```text
Email sudah terdaftar. Silakan login menggunakan email tersebut.
```

---

## 6. Aturan Role Access

### Kondisi

User mencoba membuka halaman role tertentu.

### Aturan Sistem

1. Admin hanya boleh membuka route Admin.
2. Koordinator hanya boleh membuka route Koordinator.
3. Mahasiswa hanya boleh membuka route Mahasiswa.
4. Dosen hanya boleh membuka route Dosen.
5. Instansi hanya boleh membuka route Instansi.
6. Role filter wajib diterapkan di backend.
7. Sidebar bukan proteksi utama.
8. Jika user membuka URL role lain, sistem harus menolak.

### Respon Sistem

```text
Anda tidak memiliki akses ke halaman ini.
```

Redirect ke dashboard sesuai role.

---

## 7. Aturan Workflow Access Mahasiswa

### Kondisi

Mahasiswa membuka menu yang belum sesuai tahap.

### Aturan Sistem

1. Menu boleh ditampilkan dalam kondisi terkunci.
2. Menu terkunci harus menampilkan icon gembok.
3. Klik menu terkunci menampilkan alasan.
4. Jika mahasiswa membuka URL langsung, backend harus memblokir.
5. Workflow access dicek berdasarkan `current_status` di `kp_registrations`.
6. Pesan harus menjelaskan syarat menu tersebut.

### Contoh Aturan

| Menu                   | Syarat                                            |
| ---------------------- | ------------------------------------------------- |
| Penempatan             | Registrasi disetujui                              |
| Surat & Dokumen        | Penempatan disetujui                              |
| Upload Dokumen Balasan | Surat tersedia                                    |
| Pembimbing Saya        | Dosen ditetapkan                                  |
| Logbook                | Dosen ditetapkan / sedang berjalan                |
| Laporan Akhir          | Logbook berjalan                                  |
| Penilaian              | Laporan akhir/penilaian sudah masuk tahap terkait |

### Respon Sistem

```text
Menu Penempatan belum dapat dibuka karena registrasi Anda belum disetujui.
```

```text
Menu Logbook belum dapat dibuka karena Anda belum diterima instansi dan dosen pembimbing belum ditetapkan.
```

---

## 8. Aturan Periode KP/KPL

### Kondisi

Admin/Koordinator membuat atau mengelola periode.

### Aturan Sistem

1. Registrasi mahasiswa hanya bisa dilakukan pada periode `aktif`.
2. Periode memiliki status:
   - `draft`
   - `aktif`
   - `ditutup`
   - `diarsipkan`

3. Periode `draft` belum bisa digunakan mahasiswa.
4. Periode `aktif` dapat menerima registrasi.
5. Periode `ditutup` tidak menerima registrasi baru.
6. Periode `diarsipkan` bersifat read-only.
7. Data periode lama tidak boleh dihapus.
8. Jika ada mahasiswa belum selesai, periode tidak boleh diarsipkan tanpa keputusan Koordinator.
9. Periode dapat dikaitkan dengan prodi dan jenis kegiatan KP/KPL.

### Respon Sistem

Jika tidak ada periode aktif:

```text
Belum ada periode KP/KPL yang aktif saat ini.
```

Jika periode sudah ditutup:

```text
Periode pendaftaran sudah ditutup.
```

---

## 9. Aturan Mahasiswa Sudah Pernah Selesai KP/KPL

### Kondisi

Mahasiswa yang sudah menyelesaikan KP/KPL mencoba daftar lagi.

### Aturan Sistem

1. Sistem memeriksa riwayat registrasi mahasiswa.
2. Jika sudah memiliki status `selesai` atau `diarsipkan`, pendaftaran baru dikunci.
3. Mahasiswa tetap bisa melihat riwayat KP/KPL miliknya.
4. Koordinator dapat membuka akses khusus jika ada alasan akademik.
5. Pembukaan akses khusus wajib masuk audit log.

### Respon Sistem

```text
Anda sudah menyelesaikan KP/KPL. Pendaftaran baru hanya dapat dilakukan jika Koordinator membuka akses khusus.
```

---

## 10. Aturan Registrasi

### Kondisi

Mahasiswa mengisi dan mengirim registrasi.

### Aturan Sistem

1. Mahasiswa harus login.
2. Profil mahasiswa harus lengkap.
3. Periode harus aktif.
4. Mahasiswa hanya boleh memiliki satu registrasi aktif dalam satu periode.
5. Registrasi dapat disimpan sebagai draft.
6. Registrasi yang sudah dikirim tidak boleh diedit kecuali dikembalikan untuk revisi.
7. Data akademik wajib divalidasi.
8. Dokumen wajib harus lengkap sesuai periode.
9. Mahasiswa wajib menyetujui pernyataan.
10. Submit registrasi mengubah status menjadi `menunggu_verifikasi`.
11. Submit registrasi masuk audit log.

### Respon Sistem

```text
Registrasi berhasil dikirim dan sedang menunggu verifikasi.
```

Jika profil belum lengkap:

```text
Profil Anda belum lengkap. Silakan lengkapi profil terlebih dahulu.
```

---

## 11. Aturan Syarat Akademik

### Kondisi

Mahasiswa mengisi data akademik.

### Aturan Sistem

1. IPK minimal default adalah 2,50.
2. Jumlah SKS minimal dapat dikonfigurasi per periode.
3. Mahasiswa harus lulus mata kuliah inti sesuai aturan periode.
4. Mata kuliah inti meliputi:
   - Pemrograman Dasar.
   - Struktur Data.
   - Basis Data.
   - Analisis dan Perancangan Sistem Informasi.
   - Jaringan Komputer/Data Communication.

5. Mahasiswa harus lulus minimal satu mata kuliah konsentrasi jika diwajibkan.
6. Jika syarat akademik tidak terpenuhi, sistem memberi peringatan.
7. Koordinator dapat override jika ada kebijakan khusus.
8. Override wajib memiliki alasan dan audit log.

### Respon Sistem

```text
IPK minimal untuk mengikuti KP/KPL adalah 2,50.
```

```text
Jumlah SKS Anda belum memenuhi syarat pendaftaran KP/KPL.
```

---

## 12. Aturan Dokumen Persyaratan

### Kondisi

Mahasiswa mengupload dokumen persyaratan.

### Aturan Sistem

1. Dokumen wajib ditentukan per periode.
2. Setiap dokumen memiliki status.
3. Format file yang diizinkan default:
   - PDF
   - JPG
   - JPEG
   - PNG

4. Ukuran file maksimal default 10 MB, dapat dikonfigurasi.
5. File disimpan di `writable/uploads`.
6. Metadata file disimpan di `student_documents`.
7. File tidak boleh disimpan langsung di `public`.
8. Dokumen yang sudah valid tidak boleh dihapus sembarangan.
9. Jika revisi, upload baru menjadi versi baru.
10. Dokumen lama tetap disimpan untuk riwayat.

### Status Dokumen

```text
belum_upload
menunggu_verifikasi
valid
perlu_revisi
ditolak
```

### Respon Sistem

```text
Dokumen berhasil diupload dan sedang menunggu verifikasi.
```

```text
Format file harus PDF, JPG, JPEG, atau PNG.
```

```text
Ukuran file maksimal 10 MB.
```

---

## 13. Aturan Verifikasi Dokumen

### Kondisi

Admin/Koordinator memverifikasi dokumen mahasiswa.

### Aturan Sistem

1. Admin dapat membantu pemeriksaan administrasi.
2. Koordinator memberi keputusan akhir jika terkait akademik.
3. Dokumen dapat diberi status:
   - `valid`
   - `perlu_revisi`
   - `ditolak`

4. Jika `perlu_revisi`, catatan wajib diisi.
5. Jika `ditolak`, alasan wajib diisi.
6. Verifikasi dokumen wajib masuk audit log.
7. Dokumen bertanda tangan/stempel harus diperiksa secara manual.
8. Sistem tidak boleh otomatis menganggap dokumen valid hanya karena sudah upload.

### Respon Sistem

```text
Dokumen berhasil diverifikasi.
```

```text
Dokumen perlu revisi. Silakan periksa catatan dari verifikator.
```

---

## 14. Aturan Verifikasi Registrasi

### Kondisi

Registrasi mahasiswa berada pada status `menunggu_verifikasi`.

### Aturan Sistem

1. Admin memeriksa kelengkapan administrasi.
2. Koordinator memutuskan hasil akhir.
3. Keputusan yang tersedia:
   - Setujui.
   - Minta Revisi.
   - Tolak.

4. Jika disetujui, status menjadi `registrasi_disetujui`.
5. Jika revisi, status menjadi `revisi_registrasi`.
6. Jika ditolak, status menjadi `registrasi_ditolak`.
7. Revisi dan penolakan wajib memiliki catatan.
8. Keputusan masuk status log dan audit log.

### Respon Sistem

```text
Registrasi berhasil disetujui.
```

```text
Registrasi dikembalikan untuk revisi.
```

```text
Registrasi ditolak.
```

---

## 15. Aturan Revisi Registrasi

### Kondisi

Registrasi dikembalikan untuk revisi.

### Aturan Sistem

1. Mahasiswa dapat memperbaiki data/dokumen yang diminta.
2. Catatan revisi harus terlihat jelas di dashboard.
3. Dokumen versi lama tetap tersimpan.
4. Setelah submit ulang, status kembali ke `menunggu_verifikasi`.
5. Submit ulang masuk audit log.
6. Mahasiswa tidak boleh mengubah data yang tidak terkait revisi jika tidak diizinkan.

### Respon Sistem

```text
Perbaikan registrasi berhasil dikirim ulang.
```

---

## 16. Aturan Penempatan

### Kondisi

Registrasi mahasiswa telah disetujui.

### Aturan Sistem

1. Menu Penempatan terbuka setelah status `registrasi_disetujui`.
2. Mahasiswa dapat memilih:
   - Penempatan mitra.
   - Tempat mandiri.

3. Mahasiswa hanya boleh memiliki satu pengajuan penempatan aktif.
4. Jika pengajuan ditolak, mahasiswa dapat mengajukan ulang.
5. Jika pengajuan disetujui, mahasiswa lanjut ke tahap surat/dokumen.
6. Keputusan penempatan dilakukan oleh Koordinator.
7. Keputusan masuk audit log.

### Respon Sistem

```text
Pengajuan penempatan berhasil dikirim.
```

---

## 17. Aturan Penempatan Mitra

### Kondisi

Mahasiswa memilih instansi mitra kampus.

### Aturan Sistem

1. Sistem menampilkan instansi mitra aktif.
2. Sistem memeriksa kuota instansi.
3. Sistem menghitung rekomendasi TOPSIS jika data tersedia.
4. Mahasiswa dapat memilih instansi berdasarkan rekomendasi.
5. Pengajuan dikirim ke Koordinator.
6. Koordinator dapat menyetujui, menolak, atau meminta revisi.
7. Kuota instansi bertambah setelah penempatan disetujui.
8. Jika kuota penuh, mahasiswa tidak bisa memilih instansi tersebut kecuali ada override Koordinator.
9. Override wajib memiliki alasan.

### Respon Sistem

```text
Kuota instansi sudah penuh. Silakan pilih instansi lain.
```

```text
Pengajuan penempatan mitra berhasil dikirim.
```

---

## 18. Aturan TOPSIS

### Kondisi

Sistem menghitung rekomendasi instansi mitra.

### Aturan Sistem

1. TOPSIS hanya digunakan untuk instansi mitra.
2. TOPSIS tidak digunakan untuk tempat mandiri kecuali pengembangan lanjutan.
3. Kriteria TOPSIS disimpan di database.
4. Bobot TOPSIS disimpan per periode.
5. Hasil TOPSIS disimpan di database.
6. Snapshot perhitungan disimpan jika memungkinkan.
7. TOPSIS hanya rekomendasi, bukan keputusan final.
8. Koordinator dapat memilih hasil rekomendasi atau membuat keputusan lain dengan alasan.
9. Perubahan bobot periode baru tidak boleh mengubah hasil periode lama.

### Kriteria Awal

```text
kesesuaian_bidang
kesesuaian_kemampuan
kuota
jarak
status_kemitraan
pembimbing_lapangan
```

### Respon Sistem

```text
Rekomendasi penempatan berhasil dihitung.
```

Jika data tidak lengkap:

```text
Rekomendasi belum dapat dihitung karena data kriteria belum lengkap.
```

---

## 19. Aturan Tempat Mandiri

### Kondisi

Mahasiswa mengajukan instansi sendiri.

### Aturan Sistem

1. Tempat mandiri tidak wajib memiliki akun instansi.
2. Mahasiswa wajib mengisi data instansi lengkap.
3. Koordinator memvalidasi kelayakan tempat.
4. Jika disetujui, sistem generate surat pengantar dan dokumen terkait.
5. Jika ditolak, mahasiswa harus memilih tempat lain.
6. Jika revisi, mahasiswa memperbaiki data instansi.
7. Tempat mandiri yang disetujui dapat disimpan sebagai instansi tipe `mandiri`.
8. Jika sering digunakan, Admin/Koordinator dapat mengubahnya menjadi mitra.
9. Semua keputusan wajib memiliki catatan.

### Respon Sistem

```text
Pengajuan tempat mandiri berhasil dikirim.
```

```text
Tempat mandiri perlu revisi. Silakan periksa catatan Koordinator.
```

---

## 20. Aturan Surat dan Dokumen Generated

### Kondisi

Penempatan mahasiswa disetujui.

### Aturan Sistem

1. Sistem dapat generate dokumen PDF.
2. Dokumen dibuat dari template resmi kampus.
3. Dokumen hasil generate disimpan di `writable/generated`.
4. Metadata dokumen disimpan di `generated_documents`.
5. Jika dokumen digenerate ulang, buat versi baru.
6. Dokumen tidak boleh dihapus sembarangan.
7. Mahasiswa dapat download dokumen sesuai hak akses.
8. Nomor surat dapat dikelola Admin/Koordinator.
9. Generate dokumen penting masuk audit log.

### Dokumen Utama

```text
Lampiran A
Surat Rekomendasi Dosen PA
Surat Permohonan/Pengantar Instansi
Lembar Persetujuan Instansi
Surat Tugas Dosen Pembimbing
Form Penilaian Instansi
Rekap Nilai Akhir
```

### Respon Sistem

```text
Dokumen berhasil digenerate.
```

---

## 21. Aturan Dokumen Tanda Tangan dan Stempel

### Kondisi

Dokumen membutuhkan tanda tangan atau stempel fisik.

### Aturan Sistem

1. Sistem menyediakan dokumen PDF untuk diunduh.
2. Mahasiswa mencetak dokumen.
3. Pihak terkait menandatangani dan memberi stempel jika diperlukan.
4. Mahasiswa mengupload ulang dokumen.
5. Admin/Koordinator memverifikasi dokumen.
6. Jika tanda tangan/stempel tidak ada, dokumen dapat dikembalikan untuk revisi.
7. Dokumen valid disimpan sebagai bagian arsip.

### Respon Sistem

```text
Dokumen belum dapat diverifikasi karena belum terdapat tanda tangan atau stempel.
```

---

## 22. Aturan Penerimaan Instansi

### Kondisi

Mahasiswa membawa surat ke instansi atau instansi mitra menerima pengajuan.

### Aturan Sistem

1. Jika instansi mitra memiliki akun, konfirmasi dapat dilakukan melalui sistem.
2. Jika instansi tidak memiliki akun, konfirmasi dilakukan dengan dokumen fisik.
3. Mahasiswa upload dokumen penerimaan yang sudah ditandatangani/stempel.
4. Admin/Koordinator memverifikasi dokumen.
5. Jika diterima, status menjadi `diterima_instansi`.
6. Jika ditolak, mahasiswa kembali ke tahap penempatan.
7. Jika dokumen tidak valid, status menjadi `penerimaan_perlu_revisi`.

### Respon Sistem

```text
Dokumen penerimaan instansi berhasil diverifikasi.
```

```text
Instansi menolak pengajuan. Silakan ajukan penempatan lain.
```

---

## 23. Aturan Dosen Pembimbing

### Kondisi

Mahasiswa telah diterima instansi.

### Aturan Sistem

1. Dosen pembimbing ditetapkan oleh Koordinator.
2. Admin mengelola data dosen dan kuota.
3. Sistem memeriksa kuota sebelum penetapan.
4. Satu mahasiswa hanya memiliki satu dosen pembimbing aktif.
5. Dosen lama disimpan sebagai riwayat jika diganti.
6. Dosen tidak boleh melebihi kuota kecuali Koordinator melakukan override dengan alasan.
7. Setelah dosen ditetapkan, mahasiswa dapat membuka menu pembimbing dan logbook.
8. Penetapan pembimbing masuk audit log.

### Respon Sistem

```text
Dosen pembimbing berhasil ditetapkan.
```

```text
Dosen pembimbing sudah mencapai kuota maksimal.
```

---

## 24. Aturan Logbook

### Kondisi

Mahasiswa sudah diterima instansi dan dosen pembimbing ditetapkan.

### Aturan Sistem

1. Logbook menggunakan model mingguan.
2. Satu minggu hanya memiliki satu logbook aktif per mahasiswa.
3. Logbook mingguan berisi detail kegiatan harian.
4. Mahasiswa dapat menyimpan draft.
5. Mahasiswa dapat submit logbook.
6. Setelah submit, logbook menunggu review dosen.
7. Dosen dapat menyetujui atau meminta revisi.
8. Logbook yang disetujui dikunci.
9. Mahasiswa dapat revisi logbook jika status `perlu_revisi`.
10. Logbook harus mobile-friendly.
11. Submit dan review logbook masuk audit log.

### Status Logbook

```text
draft
dikirim
perlu_revisi
disetujui
terkunci
```

### Respon Sistem

```text
Logbook berhasil disimpan sebagai draft.
```

```text
Logbook berhasil dikirim dan menunggu review dosen.
```

---

## 25. Aturan Review Logbook Dosen

### Kondisi

Dosen membuka logbook mahasiswa bimbingan.

### Aturan Sistem

1. Dosen hanya boleh melihat mahasiswa bimbingannya.
2. Dosen dapat memberi komentar.
3. Dosen dapat menyetujui logbook.
4. Dosen dapat meminta revisi logbook.
5. Jika revisi, komentar wajib diisi.
6. Logbook yang disetujui menjadi terkunci.
7. Review masuk audit log.

### Respon Sistem

```text
Logbook berhasil disetujui.
```

```text
Logbook dikembalikan untuk revisi.
```

---

## 26. Aturan Laporan Akhir

### Kondisi

Mahasiswa mengupload laporan akhir.

### Aturan Sistem

1. Laporan akhir hanya bisa diupload setelah tahap berjalan.
2. Format file harus divalidasi.
3. Mahasiswa dapat upload versi revisi jika diminta.
4. Dosen dapat memberi catatan revisi.
5. Laporan yang disetujui menjadi dasar penilaian jika aturan periode mengharuskan.
6. Upload dan review laporan akhir masuk audit log.

### Respon Sistem

```text
Laporan akhir berhasil diupload.
```

```text
Laporan akhir perlu revisi. Silakan periksa catatan dosen pembimbing.
```

---

## 27. Aturan Penilaian Instansi

### Kondisi

Instansi memberi nilai mahasiswa.

### Aturan Sistem

1. Instansi mitra dengan akun dapat mengisi nilai melalui sistem.
2. Instansi mandiri menggunakan form penilaian manual.
3. Form penilaian manual digenerate sistem.
4. Mahasiswa download form.
5. Instansi mengisi, tanda tangan, dan stempel.
6. Mahasiswa upload ulang form.
7. Admin/Koordinator memverifikasi.
8. Nilai instansi dapat diinput setelah dokumen valid.
9. Nilai yang sudah valid tidak boleh diubah sembarangan.
10. Nilai masuk audit log.

### Respon Sistem

```text
Nilai instansi berhasil disimpan.
```

```text
Form penilaian instansi perlu revisi karena belum terdapat tanda tangan atau stempel.
```

---

## 28. Aturan Penilaian Dosen

### Kondisi

Dosen memberi nilai akademik.

### Aturan Sistem

1. Dosen hanya bisa menilai mahasiswa bimbingannya.
2. Nilai dapat disimpan draft jika diperlukan.
3. Setelah submit, nilai menunggu validasi Koordinator.
4. Dosen tidak dapat mengubah nilai setelah dikunci.
5. Input nilai masuk audit log.
6. Jika perlu koreksi nilai, harus melalui mekanisme koreksi.

### Respon Sistem

```text
Nilai dosen berhasil dikirim.
```

---

## 29. Aturan Nilai Akhir

### Kondisi

Nilai instansi dan nilai dosen sudah masuk.

### Aturan Sistem

1. Nilai akhir dihitung berdasarkan bobot periode.
2. Bobot nilai disimpan per periode.
3. Snapshot bobot nilai disimpan di `final_scores`.
4. Contoh bobot awal:
   - Nilai Instansi: 40%.
   - Nilai Dosen: 50%.
   - Administrasi/Logbook: 10%.

5. Koordinator memvalidasi nilai akhir.
6. Setelah divalidasi, nilai dikunci.
7. Nilai yang sudah dikunci tidak boleh diubah langsung.
8. Koreksi nilai harus memiliki alasan dan audit log.

### Respon Sistem

```text
Nilai akhir berhasil divalidasi.
```

---

## 30. Aturan Arsip Periode

### Kondisi

Periode KP/KPL selesai.

### Aturan Sistem

1. Periode dapat ditutup setelah proses utama selesai.
2. Periode dapat diarsipkan setelah data diperiksa.
3. Arsip menyimpan seluruh data penting.
4. Data arsip bersifat read-only.
5. Mahasiswa hanya melihat arsip miliknya.
6. Dosen hanya melihat arsip mahasiswa bimbingannya.
7. Instansi hanya melihat arsip mahasiswa di instansinya.
8. Admin/Koordinator melihat arsip sesuai hak akses.
9. Koreksi arsip harus melalui fitur koreksi arsip.
10. Koreksi arsip wajib memiliki alasan dan audit log.

### Respon Sistem

```text
Periode berhasil diarsipkan.
```

Jika masih ada data belum selesai:

```text
Periode belum dapat diarsipkan karena masih terdapat mahasiswa yang belum menyelesaikan proses.
```

---

## 31. Aturan Koreksi Arsip

### Kondisi

Data arsip perlu diperbaiki.

### Aturan Sistem

1. Data arsip tidak boleh diedit langsung.
2. User berwenang membuat permintaan koreksi.
3. Koreksi harus mencatat data lama dan data baru.
4. Koreksi harus memiliki alasan.
5. Koreksi harus disetujui user berwenang.
6. Koreksi masuk audit log.
7. Riwayat koreksi tetap disimpan.

### Respon Sistem

```text
Permintaan koreksi arsip berhasil diajukan.
```

---

## 32. Aturan Audit Log

### Kondisi

User melakukan aksi penting.

### Aturan Sistem

Aksi berikut wajib masuk audit log:

```text
login_penting
submit_registrasi
upload_dokumen
verifikasi_dokumen
setujui_registrasi
tolak_registrasi
revisi_registrasi
ajukan_penempatan
validasi_penempatan
hitung_topsis
generate_dokumen
upload_dokumen_balasan
tetapkan_pembimbing
submit_logbook
review_logbook
upload_laporan_akhir
input_nilai_instansi
input_nilai_dosen
validasi_nilai_akhir
tutup_periode
arsipkan_periode
koreksi_arsip
```

Audit log menyimpan:

1. User pelaku.
2. Role pelaku.
3. Aksi.
4. Data terkait.
5. Data lama jika ada.
6. Data baru jika ada.
7. IP address.
8. User agent.
9. Waktu aksi.
10. Catatan jika ada.

### Aturan Tambahan

1. Jangan menyimpan password di audit log.
2. Jangan mengizinkan user biasa mengubah audit log.
3. Audit log tidak boleh dihapus sembarangan.

---

## 33. Aturan Notifikasi

### Kondisi

Ada perubahan status atau aksi penting.

### Aturan Sistem

Notifikasi diberikan untuk:

1. Registrasi berhasil dikirim.
2. Registrasi disetujui.
3. Registrasi perlu revisi.
4. Registrasi ditolak.
5. Penempatan disetujui.
6. Tempat mandiri perlu revisi.
7. Surat tersedia.
8. Dokumen penerimaan perlu revisi.
9. Dosen pembimbing ditetapkan.
10. Logbook perlu revisi.
11. Laporan akhir perlu revisi.
12. Nilai akhir divalidasi.

### Aturan

1. Notifikasi penting disimpan di database.
2. Toast ringan boleh menggunakan session flashdata.
3. Pesan harus Bahasa Indonesia.
4. Notifikasi harus mengarah ke halaman terkait jika ada.

---

## 34. Aturan File Download

### Kondisi

User mengunduh file upload atau dokumen generated.

### Aturan Sistem

1. File tidak boleh diakses langsung dari public.
2. File diunduh melalui controller.
3. Sistem memeriksa user login.
4. Sistem memeriksa role.
5. Sistem memeriksa kepemilikan/relasi data.
6. Mahasiswa hanya boleh download file miliknya.
7. Dosen hanya boleh download file mahasiswa bimbingannya.
8. Instansi hanya boleh download file mahasiswa di instansinya.
9. Admin/Koordinator boleh download sesuai hak.
10. Jika file tidak ditemukan, tampilkan pesan jelas.

### Respon Sistem

```text
Anda tidak memiliki akses untuk mengunduh dokumen ini.
```

```text
File tidak ditemukan atau sudah tidak tersedia.
```

---

## 35. Aturan Upload Keamanan File

### Kondisi

User mengupload file.

### Aturan Sistem

1. Validasi ekstensi.
2. Validasi MIME type.
3. Validasi ukuran.
4. Rename file otomatis.
5. Simpan file di folder sesuai periode/prodi/NPM.
6. Jangan gunakan nama file asli sebagai nama simpan.
7. Jangan izinkan file executable.
8. Jangan izinkan path traversal.
9. Jangan izinkan overwrite file lama.
10. Simpan metadata file.

### Format Nama File

```text
{npm}_{jenis_dokumen}_{periode}_{timestamp}.{ext}
```

---

## 36. Aturan Penghapusan Data

### Kondisi

User menghapus data.

### Aturan Sistem

1. Data penting menggunakan soft delete.
2. Data yang sudah digunakan workflow tidak boleh dihapus langsung.
3. Data master dapat dinonaktifkan.
4. Dokumen valid tidak boleh dihapus sembarangan.
5. Arsip tidak boleh dihapus.
6. Penghapusan/penonaktifan masuk audit log.

### Respon Sistem

```text
Data berhasil dinonaktifkan.
```

Bukan:

```text
Data berhasil dihapus permanen.
```

---

## 37. Aturan DataTables dan Data Besar

### Kondisi

Admin/Koordinator membuka data besar.

### Aturan Sistem

1. Gunakan pagination.
2. Gunakan search.
3. Gunakan filter periode.
4. Gunakan filter status.
5. Untuk data besar, gunakan server-side processing.
6. Jangan memuat semua data tanpa batas.
7. Export harus berdasarkan filter.

---

## 38. Aturan Edge Case Penting

### 38.1 Tidak Ada Periode Aktif

Respon:

```text
Belum ada periode KP/KPL yang aktif saat ini.
```

Mahasiswa tidak bisa registrasi.

### 38.2 Mahasiswa Belum Lengkap Profil

Respon:

```text
Profil Anda belum lengkap. Lengkapi profil terlebih dahulu.
```

Registrasi dikunci.

### 38.3 Dokumen Tidak Jelas

Respon:

```text
Dokumen belum dapat diverifikasi karena file tidak terbaca dengan jelas.
```

Status dokumen menjadi `perlu_revisi`.

### 38.4 Instansi Penuh Kuota

Respon:

```text
Kuota instansi sudah penuh. Silakan pilih instansi lain.
```

### 38.5 Dosen Penuh Kuota

Respon:

```text
Dosen pembimbing sudah mencapai kuota maksimal.
```

### 38.6 User Akses URL Langsung

Respon:

```text
Anda belum dapat mengakses halaman ini karena tahap sebelumnya belum selesai.
```

### 38.7 Nilai Belum Lengkap

Respon:

```text
Nilai akhir belum dapat divalidasi karena nilai instansi atau nilai dosen belum lengkap.
```

### 38.8 Periode Belum Bisa Diarsipkan

Respon:

```text
Periode belum dapat diarsipkan karena masih terdapat proses mahasiswa yang belum selesai.
```

---

## 39. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Membuka semua menu mahasiswa dari awal.
2. Mengandalkan sidebar sebagai satu-satunya proteksi.
3. Mengabaikan validasi backend.
4. Menggunakan pesan error Bahasa Inggris.
5. Membuat TOPSIS sebagai keputusan final otomatis.
6. Membuat instansi mandiri wajib punya akun.
7. Menghapus data penting permanen.
8. Mengubah data arsip langsung.
9. Menghapus riwayat status.
10. Mengubah dokumen valid tanpa versi.
11. Mengubah bobot TOPSIS lama sehingga hasil lama berubah.
12. Mengubah nilai akhir terkunci tanpa koreksi.
13. Menyimpan file upload di public.
14. Mengabaikan audit log.
15. Mengabaikan kuota dosen atau instansi.

---

## 40. Ringkasan Final

Business rules utama:

```text
Akun unik berdasarkan email.
Mahasiswa unik berdasarkan NPM.
Registrasi hanya pada periode aktif.
Satu mahasiswa hanya satu registrasi per periode.
Menu mahasiswa terbuka berdasarkan status.
Admin mengelola sistem dan administrasi.
Koordinator mengambil keputusan akademik final.
TOPSIS hanya rekomendasi.
Tempat mandiri tidak wajib akun.
Dokumen resmi tetap bisa download-print-upload.
Logbook mingguan dan mobile-friendly.
Nilai akhir dikunci setelah validasi.
Arsip bersifat read-only.
Semua aksi penting masuk audit log.
```

Business rules ini wajib diterapkan di UI, controller, service, filter, validasi, dan database.
