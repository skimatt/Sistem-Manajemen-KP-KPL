# docs/06-database.md

# Desain Database Sistem KP/KPL

## 1. Tujuan Dokumen

Dokumen ini menjelaskan rancangan database utama untuk Sistem Manajemen KP/KPL.

Database harus dirancang untuk mendukung:

- Multi-role.
- Workflow bertahap.
- Periode KP/KPL.
- Registrasi digital.
- Dokumen persyaratan.
- Penempatan mitra dan mandiri.
- Rekomendasi TOPSIS.
- Penetapan dosen pembimbing.
- Logbook mingguan.
- Penilaian.
- Arsip periode.
- Audit log.

Database tidak boleh dirancang hanya sebagai CRUD sederhana.

---

## 2. Prinsip Utama Database

Prinsip utama:

1. Database mengikuti workflow, bukan hanya data master.
2. Semua data utama harus terikat ke periode.
3. Status utama mahasiswa berada di tabel registrasi.
4. User login dipisahkan dari profil role.
5. Dokumen upload dan dokumen generated harus dipisahkan.
6. Data lama tidak boleh hilang saat periode baru dibuat.
7. Gunakan soft delete untuk data penting.
8. Gunakan audit log untuk aksi penting.
9. Gunakan `uuid` untuk data yang muncul di URL.
10. Gunakan index pada kolom yang sering dicari.
11. Gunakan constraint agar data tidak dobel.
12. Jangan menyimpan file fisik di database; simpan path dan metadata file.
13. Jangan menghapus data arsip secara permanen.
14. Bobot TOPSIS dan bobot nilai harus tersimpan per periode.
15. Form dan template dokumen harus memiliki versi.

---

## 3. Konvensi Database

Gunakan aturan berikut:

### 3.1 Nama Tabel

Gunakan `snake_case`.

Contoh:

```text
users
student_profiles
kp_periods
kp_registrations
student_documents
logbook_weeks
final_scores
audit_logs
```

### 3.2 Nama Kolom

Gunakan `snake_case`.

Contoh:

```text
user_id
period_id
registration_id
current_status
created_at
updated_at
deleted_at
```

### 3.3 Primary Key

Gunakan:

```text
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
```

### 3.4 UUID

Untuk data penting yang mungkin muncul di URL, gunakan:

```text
uuid CHAR(36) UNIQUE
```

Contoh:

```text
/mahasiswa/registrasi/detail/{uuid}
```

Jangan memakai ID auto increment langsung di URL publik jika tidak perlu.

### 3.5 Timestamp

Tabel utama gunakan:

```text
created_at
updated_at
deleted_at
```

### 3.6 Soft Delete

Gunakan `deleted_at` untuk data penting.

Data penting tidak boleh langsung dihapus permanen.

### 3.7 Status

Status database menggunakan `snake_case`.

Contoh:

```text
menunggu_verifikasi
registrasi_disetujui
penempatan_disetujui
dosen_ditetapkan
selesai
diarsipkan
```

UI boleh menampilkan label Bahasa Indonesia.

---

## 4. Kelompok Tabel Utama

Database dibagi menjadi beberapa kelompok.

```text
A. User dan Role
B. Profil Role
C. Periode dan Registrasi
D. Form Dinamis
E. Dokumen
F. Instansi dan Penempatan
G. TOPSIS
H. Dosen Pembimbing
I. Logbook
J. Laporan Akhir
K. Penilaian
L. Arsip dan Koreksi
M. Notifikasi dan Audit
```

---

## 4.1 Tabel MVP Awal yang Dikunci

Untuk mencegah implementasi terlalu besar di awal, tabel database dibagi menjadi tiga kelompok.

### Tabel Wajib Awal

Tabel berikut wajib dibuat pada fondasi MVP:

```text
users
student_profiles
lecturer_profiles
institution_profiles
study_programs
kp_periods
kp_registrations
registration_status_logs
document_requirements
student_documents
generated_documents
placement_requests
placement_choices
institution_quotas
topsis_criteria
topsis_weights
topsis_scores
topsis_results
supervisor_assignments
logbook_weeks
logbook_daily_entries
logbook_reviews
final_reports
assessment_scores
final_scores
audit_logs
```

### Tabel Bisa Menyusul

Tabel berikut boleh dibuat setelah fondasi workflow berjalan:

```text
password_reset_tokens
login_histories
form_templates
form_fields
form_responses
form_response_values
document_templates
assessment_templates
assessment_components
notifications
supervision_quota_logs
archive_corrections
```

### Tabel Pengembangan Lanjutan

Tabel berikut dibuat jika kebutuhan sudah benar-benar muncul:

```text
uploaded_files
lecturer_period_quotas
score_correction_requests
archive_snapshots
email_notifications
wa_notifications
digital_signatures
ocr_results
```

Aturan MVP:

1. `kp_registrations.current_status` hanya menyimpan status workflow utama.
2. Status dokumen disimpan di `student_documents.status`.
3. Status penempatan disimpan di `placement_requests.status`.
4. Status logbook disimpan di `logbook_weeks.status`.
5. Status laporan akhir disimpan di `final_reports.status`.
6. Status nilai disimpan di `assessment_scores.status` dan `final_scores.status`.
7. Form builder dinamis boleh ditunda; form registrasi MVP boleh memakai field eksplisit di `kp_registrations`.
8. Template PDF MVP boleh memakai view `app/Views/pdf` terlebih dahulu; `document_templates` dapat menyusul jika editor template dibutuhkan.
9. Notifikasi database boleh menyusul; flashdata dan SweetAlert cukup untuk fondasi awal.
10. Jika file umum di luar dokumen mahasiswa mulai banyak, buat `uploaded_files`.

---

# A. User dan Role

## 5. Tabel `users`

### Fungsi

Menyimpan akun login semua role.

Role yang menggunakan tabel ini:

- Admin
- Koordinator KP/KPL
- Mahasiswa
- Dosen Pembimbing
- Instansi Mitra

### Kolom Utama

| Kolom             | Tipe               | Keterangan                        |
| ----------------- | ------------------ | --------------------------------- |
| id                | BIGINT UNSIGNED    | Primary key.                      |
| uuid              | CHAR(36)           | ID publik.                        |
| name              | VARCHAR(150)       | Nama user.                        |
| email             | VARCHAR(150)       | Email login, unique.              |
| password_hash     | VARCHAR(255) NULL  | Password hash untuk login manual. |
| google_id         | VARCHAR(255) NULL  | ID Google jika login Google.      |
| avatar            | VARCHAR(255) NULL  | Foto profil jika ada.             |
| role              | ENUM / VARCHAR(30) | Role user.                        |
| phone             | VARCHAR(30) NULL   | Nomor HP.                         |
| email_verified_at | DATETIME NULL      | Waktu email diverifikasi.         |
| last_login_at     | DATETIME NULL      | Login terakhir.                   |
| status            | VARCHAR(30)        | active, inactive, suspended.      |
| created_at        | DATETIME           | Waktu dibuat.                     |
| updated_at        | DATETIME           | Waktu diubah.                     |
| deleted_at        | DATETIME NULL      | Soft delete.                      |

### Role yang Valid

```text
admin
koordinator
mahasiswa
dosen
instansi
```

### Constraint

```text
UNIQUE(email)
UNIQUE(uuid)
INDEX(role)
INDEX(status)
```

### Aturan

1. Email wajib unique.
2. Password disimpan menggunakan `password_hash()`.
3. Verifikasi password menggunakan `password_verify()`.
4. Mahasiswa boleh login Google.
5. Akun nonaktif tidak boleh login.
6. User tidak boleh dihapus permanen jika sudah memiliki data workflow.

---

## 6. Tabel `password_reset_tokens`

### Fungsi

Menyimpan token reset password.

### Kolom Utama

| Kolom      | Tipe            | Keterangan         |
| ---------- | --------------- | ------------------ |
| id         | BIGINT UNSIGNED | Primary key.       |
| user_id    | BIGINT UNSIGNED | Relasi ke users.   |
| token      | VARCHAR(255)    | Token reset.       |
| expires_at | DATETIME        | Waktu kedaluwarsa. |
| used_at    | DATETIME NULL   | Waktu digunakan.   |
| created_at | DATETIME        | Waktu dibuat.      |

### Aturan

1. Token memiliki masa berlaku.
2. Token hanya dapat digunakan sekali.
3. Token yang sudah digunakan tidak boleh dipakai lagi.

---

## 7. Tabel `login_histories`

### Fungsi

Menyimpan riwayat login user.

### Kolom Utama

| Kolom        | Tipe            | Keterangan          |
| ------------ | --------------- | ------------------- |
| id           | BIGINT UNSIGNED | Primary key.        |
| user_id      | BIGINT UNSIGNED | User yang login.    |
| ip_address   | VARCHAR(100)    | IP address.         |
| user_agent   | TEXT            | Browser/perangkat.  |
| login_method | VARCHAR(30)     | manual/google.      |
| success      | TINYINT         | 1 sukses, 0 gagal.  |
| note         | TEXT NULL       | Catatan jika gagal. |
| created_at   | DATETIME        | Waktu login.        |

---

# B. Profil Role

## 8. Tabel `student_profiles`

### Fungsi

Menyimpan profil mahasiswa.

### Kolom Utama

| Kolom            | Tipe                 | Keterangan                     |
| ---------------- | -------------------- | ------------------------------ |
| id               | BIGINT UNSIGNED      | Primary key.                   |
| user_id          | BIGINT UNSIGNED      | Relasi ke users.               |
| npm              | VARCHAR(30)          | Nomor Pokok Mahasiswa, unique. |
| full_name        | VARCHAR(150)         | Nama lengkap.                  |
| birth_place      | VARCHAR(100) NULL    | Tempat lahir.                  |
| birth_date       | DATE NULL            | Tanggal lahir.                 |
| gender           | VARCHAR(20) NULL     | L/P.                           |
| religion         | VARCHAR(50) NULL     | Agama.                         |
| address          | TEXT NULL            | Alamat lengkap.                |
| district         | VARCHAR(100) NULL    | Kecamatan.                     |
| city             | VARCHAR(100) NULL    | Kabupaten/Kota.                |
| province         | VARCHAR(100) NULL    | Provinsi.                      |
| phone            | VARCHAR(30) NULL     | Nomor HP.                      |
| parent_name      | VARCHAR(150) NULL    | Nama orang tua/wali.           |
| parent_phone     | VARCHAR(30) NULL     | Nomor HP orang tua/wali.       |
| study_program_id | BIGINT UNSIGNED NULL | Program studi.                 |
| generation_year  | YEAR NULL            | Angkatan.                      |
| current_semester | INT NULL             | Semester saat ini.             |
| profile_status   | VARCHAR(30)          | complete/incomplete.           |
| created_at       | DATETIME             | Waktu dibuat.                  |
| updated_at       | DATETIME             | Waktu diubah.                  |
| deleted_at       | DATETIME NULL        | Soft delete.                   |

### Constraint

```text
UNIQUE(user_id)
UNIQUE(npm)
INDEX(study_program_id)
INDEX(profile_status)
```

### Aturan

1. Satu user mahasiswa hanya memiliki satu student profile.
2. NPM wajib unique.
3. Profil harus lengkap sebelum registrasi dikirim.
4. Data profil digunakan untuk generate Lampiran A.

---

## 9. Tabel `lecturer_profiles`

### Fungsi

Menyimpan data dosen.

### Kolom Utama

| Kolom                 | Tipe                 | Keterangan                |
| --------------------- | -------------------- | ------------------------- |
| id                    | BIGINT UNSIGNED      | Primary key.              |
| user_id               | BIGINT UNSIGNED      | Relasi ke users.          |
| nidn                  | VARCHAR(50) NULL     | NIDN/NIP.                 |
| full_name             | VARCHAR(150)         | Nama dosen.               |
| study_program_id      | BIGINT UNSIGNED NULL | Program studi.            |
| expertise             | VARCHAR(150) NULL    | Bidang keahlian.          |
| max_supervision_quota | INT                  | Kuota maksimal bimbingan. |
| is_available          | TINYINT              | Status ketersediaan.      |
| created_at            | DATETIME             | Waktu dibuat.             |
| updated_at            | DATETIME             | Waktu diubah.             |
| deleted_at            | DATETIME NULL        | Soft delete.              |

### Constraint

```text
UNIQUE(user_id)
UNIQUE(nidn)
INDEX(study_program_id)
INDEX(is_available)
```

### Aturan

1. Dosen dapat memiliki kuota bimbingan.
2. Dosen tidak boleh menerima mahasiswa melebihi kuota kecuali ada override.
3. Dosen hanya melihat mahasiswa bimbingannya.

---

## 10. Tabel `institution_profiles`

### Fungsi

Menyimpan data instansi mitra atau instansi mandiri.

### Kolom Utama

| Kolom              | Tipe                 | Keterangan                           |
| ------------------ | -------------------- | ------------------------------------ |
| id                 | BIGINT UNSIGNED      | Primary key.                         |
| user_id            | BIGINT UNSIGNED NULL | Akun instansi jika mitra punya akun. |
| uuid               | CHAR(36)             | ID publik.                           |
| name               | VARCHAR(200)         | Nama instansi.                       |
| type               | VARCHAR(30)          | mitra/mandiri.                       |
| field_category     | VARCHAR(150) NULL    | Bidang instansi.                     |
| address            | TEXT                 | Alamat.                              |
| district           | VARCHAR(100) NULL    | Kecamatan.                           |
| city               | VARCHAR(100) NULL    | Kabupaten/Kota.                      |
| province           | VARCHAR(100) NULL    | Provinsi.                            |
| contact_person     | VARCHAR(150) NULL    | Nama kontak.                         |
| contact_position   | VARCHAR(100) NULL    | Jabatan kontak.                      |
| contact_phone      | VARCHAR(30) NULL     | Nomor HP/telepon.                    |
| contact_email      | VARCHAR(150) NULL    | Email kontak.                        |
| partnership_status | VARCHAR(30)          | active/inactive/pending.             |
| has_account        | TINYINT              | 1 jika punya akun.                   |
| created_at         | DATETIME             | Waktu dibuat.                        |
| updated_at         | DATETIME             | Waktu diubah.                        |
| deleted_at         | DATETIME NULL        | Soft delete.                         |

### Constraint

```text
UNIQUE(uuid)
INDEX(type)
INDEX(partnership_status)
INDEX(city)
```

### Aturan

1. Instansi mitra dapat memiliki akun.
2. Instansi mandiri tidak wajib memiliki akun.
3. Instansi mandiri tetap disimpan agar bisa menjadi arsip.
4. Instansi mandiri dapat dinaikkan menjadi mitra jika disetujui.

---

## 11. Tabel `study_programs`

### Fungsi

Menyimpan program studi.

### Kolom Utama

| Kolom      | Tipe            | Keterangan       |
| ---------- | --------------- | ---------------- |
| id         | BIGINT UNSIGNED | Primary key.     |
| code       | VARCHAR(30)     | Kode prodi.      |
| name       | VARCHAR(150)    | Nama prodi.      |
| faculty    | VARCHAR(150)    | Fakultas.        |
| kp_label   | VARCHAR(20)     | KP/KPL.          |
| status     | VARCHAR(30)     | active/inactive. |
| created_at | DATETIME        | Waktu dibuat.    |
| updated_at | DATETIME        | Waktu diubah.    |

### Contoh Data

```text
Informatika → KP
Informatika Medis → KPL
```

---

# C. Periode dan Registrasi

## 12. Tabel `kp_periods`

### Fungsi

Menyimpan periode KP/KPL.

### Kolom Utama

| Kolom              | Tipe                 | Keterangan                      |
| ------------------ | -------------------- | ------------------------------- |
| id                 | BIGINT UNSIGNED      | Primary key.                    |
| uuid               | CHAR(36)             | ID publik.                      |
| study_program_id   | BIGINT UNSIGNED NULL | Prodi terkait.                  |
| name               | VARCHAR(150)         | Nama periode.                   |
| academic_year      | VARCHAR(20)          | Tahun akademik.                 |
| semester           | VARCHAR(20)          | Ganjil/Genap.                   |
| activity_type      | VARCHAR(20)          | KP/KPL.                         |
| registration_start | DATE NULL            | Tanggal mulai pendaftaran.      |
| registration_end   | DATE NULL            | Tanggal akhir pendaftaran.      |
| activity_start     | DATE NULL            | Tanggal mulai kegiatan.         |
| activity_end       | DATE NULL            | Tanggal akhir kegiatan.         |
| status             | VARCHAR(30)          | draft/aktif/ditutup/diarsipkan. |
| created_by         | BIGINT UNSIGNED NULL | User pembuat.                   |
| created_at         | DATETIME             | Waktu dibuat.                   |
| updated_at         | DATETIME             | Waktu diubah.                   |
| deleted_at         | DATETIME NULL        | Soft delete.                    |

### Status

```text
draft
aktif
ditutup
diarsipkan
```

### Constraint

```text
UNIQUE(uuid)
INDEX(status)
INDEX(academic_year)
INDEX(study_program_id)
```

### Aturan

1. Mahasiswa hanya bisa daftar pada periode aktif.
2. Periode lama tidak boleh dihapus.
3. Data periode menjadi wadah registrasi, dokumen, logbook, nilai, dan arsip.
4. Periode yang diarsipkan bersifat read-only.

---

## 13. Tabel `kp_registrations`

### Fungsi

Menyimpan data registrasi mahasiswa pada periode tertentu.

Tabel ini adalah pusat workflow mahasiswa.

### Kolom Utama

| Kolom                         | Tipe                 | Keterangan                  |
| ----------------------------- | -------------------- | --------------------------- |
| id                            | BIGINT UNSIGNED      | Primary key.                |
| uuid                          | CHAR(36)             | ID publik.                  |
| period_id                     | BIGINT UNSIGNED      | Relasi ke periode.          |
| student_id                    | BIGINT UNSIGNED      | Relasi ke student_profiles. |
| current_status                | VARCHAR(50)          | Status utama workflow.      |
| academic_sks                  | INT                  | Jumlah SKS.                 |
| academic_gpa                  | DECIMAL(3,2)         | IPK.                        |
| is_gpa_eligible               | TINYINT              | IPK memenuhi syarat.        |
| passed_basic_programming      | TINYINT              | Lulus Pemrograman Dasar.    |
| passed_data_structure         | TINYINT              | Lulus Struktur Data.        |
| passed_database               | TINYINT              | Lulus Basis Data.           |
| passed_system_analysis        | TINYINT              | Lulus APSI.                 |
| passed_networking             | TINYINT              | Lulus Jaringan Komputer.    |
| passed_concentration_course   | TINYINT              | Lulus konsentrasi.          |
| education_payment_status      | VARCHAR(50) NULL     | Status biaya pendidikan.    |
| academic_advisor_name         | VARCHAR(150) NULL    | Nama Dosen PA.              |
| advisor_recommendation_status | VARCHAR(30) NULL     | layak/belum_layak.          |
| submitted_at                  | DATETIME NULL        | Waktu submit.               |
| verified_at                   | DATETIME NULL        | Waktu verifikasi.           |
| verified_by                   | BIGINT UNSIGNED NULL | User verifikator.           |
| final_note                    | TEXT NULL            | Catatan umum.               |
| created_at                    | DATETIME             | Waktu dibuat.               |
| updated_at                    | DATETIME             | Waktu diubah.               |
| deleted_at                    | DATETIME NULL        | Soft delete.                |

### Constraint

```text
UNIQUE(uuid)
UNIQUE(period_id, student_id)
INDEX(current_status)
INDEX(period_id)
INDEX(student_id)
```

### Aturan

1. Satu mahasiswa hanya boleh memiliki satu registrasi pada satu periode.
2. Status utama mahasiswa berada di tabel ini.
3. Menu mahasiswa dibuka berdasarkan `current_status`.
4. Registrasi tidak boleh diubah setelah disetujui kecuali melalui mekanisme revisi/koreksi.
5. Submit, revisi, persetujuan, dan penolakan wajib masuk status log dan audit log.

---

## 14. Tabel `registration_status_logs`

### Fungsi

Menyimpan riwayat perubahan status registrasi.

### Kolom Utama

| Kolom           | Tipe                 | Keterangan                  |
| --------------- | -------------------- | --------------------------- |
| id              | BIGINT UNSIGNED      | Primary key.                |
| registration_id | BIGINT UNSIGNED      | Relasi ke kp_registrations. |
| old_status      | VARCHAR(50) NULL     | Status lama.                |
| new_status      | VARCHAR(50)          | Status baru.                |
| changed_by      | BIGINT UNSIGNED NULL | User yang mengubah.         |
| changed_by_role | VARCHAR(30)          | Role pengubah.              |
| note            | TEXT NULL            | Catatan perubahan.          |
| created_at      | DATETIME             | Waktu perubahan.            |

### Aturan

1. Setiap perubahan `current_status` wajib dicatat.
2. Catatan wajib untuk revisi, penolakan, dan koreksi.
3. Data ini digunakan untuk timeline mahasiswa.

---

# D. Form Dinamis

## 15. Tabel `form_templates`

### Fungsi

Menyimpan template form dinamis.

Digunakan untuk:

- Registrasi.
- Dokumen persyaratan.
- Penilaian.
- Form lain per periode.

### Kolom Utama

| Kolom      | Tipe                 | Keterangan                            |
| ---------- | -------------------- | ------------------------------------- |
| id         | BIGINT UNSIGNED      | Primary key.                          |
| uuid       | CHAR(36)             | ID publik.                            |
| name       | VARCHAR(150)         | Nama form.                            |
| form_type  | VARCHAR(50)          | registration/assessment/document/etc. |
| version    | INT                  | Versi form.                           |
| period_id  | BIGINT UNSIGNED NULL | Periode jika khusus periode.          |
| status     | VARCHAR(30)          | draft/active/inactive/archived.       |
| created_by | BIGINT UNSIGNED NULL | Pembuat.                              |
| created_at | DATETIME             | Waktu dibuat.                         |
| updated_at | DATETIME             | Waktu diubah.                         |
| deleted_at | DATETIME NULL        | Soft delete.                          |

### Aturan

1. Form yang sudah digunakan tidak boleh diedit langsung.
2. Jika ada perubahan, buat versi baru.
3. Data lama tetap mengikuti versi form lama.

---

## 16. Tabel `form_fields`

### Fungsi

Menyimpan field dari form dinamis.

### Kolom Utama

| Kolom            | Tipe            | Keterangan                                 |
| ---------------- | --------------- | ------------------------------------------ |
| id               | BIGINT UNSIGNED | Primary key.                               |
| form_template_id | BIGINT UNSIGNED | Relasi ke form_templates.                  |
| field_name       | VARCHAR(100)    | Nama field internal.                       |
| label            | VARCHAR(150)    | Label UI.                                  |
| field_type       | VARCHAR(50)     | text/select/file/date/number/textarea/etc. |
| options_json     | JSON NULL       | Pilihan jika select/radio.                 |
| validation_rules | TEXT NULL       | Rule validasi.                             |
| is_required      | TINYINT         | Wajib/tidak.                               |
| sort_order       | INT             | Urutan field.                              |
| status           | VARCHAR(30)     | active/inactive.                           |
| created_at       | DATETIME        | Waktu dibuat.                              |
| updated_at       | DATETIME        | Waktu diubah.                              |

---

## 17. Tabel `form_responses`

### Fungsi

Menyimpan response form per user/registrasi.

### Kolom Utama

| Kolom            | Tipe                 | Keterangan                |
| ---------------- | -------------------- | ------------------------- |
| id               | BIGINT UNSIGNED      | Primary key.              |
| form_template_id | BIGINT UNSIGNED      | Relasi ke form.           |
| registration_id  | BIGINT UNSIGNED NULL | Relasi ke registrasi.     |
| user_id          | BIGINT UNSIGNED NULL | User pengisi.             |
| status           | VARCHAR(30)          | draft/submitted/verified. |
| submitted_at     | DATETIME NULL        | Waktu submit.             |
| created_at       | DATETIME             | Waktu dibuat.             |
| updated_at       | DATETIME             | Waktu diubah.             |

---

## 18. Tabel `form_response_values`

### Fungsi

Menyimpan nilai dari setiap field form.

### Kolom Utama

| Kolom            | Tipe                 | Keterangan            |
| ---------------- | -------------------- | --------------------- |
| id               | BIGINT UNSIGNED      | Primary key.          |
| form_response_id | BIGINT UNSIGNED      | Relasi ke response.   |
| form_field_id    | BIGINT UNSIGNED      | Relasi ke field.      |
| value_text       | TEXT NULL            | Nilai teks.           |
| value_file_id    | BIGINT UNSIGNED NULL | File jika field file. |
| created_at       | DATETIME             | Waktu dibuat.         |
| updated_at       | DATETIME             | Waktu diubah.         |

---

# E. Dokumen

## 19. Tabel `document_templates`

### Fungsi

Menyimpan template dokumen/surat.

Contoh:

- Lampiran A.
- Surat Rekomendasi PA.
- Surat Pengantar.
- Surat Permohonan.
- Lembar Persetujuan Instansi.
- Form Penilaian Instansi.
- Rekap Nilai Akhir.

### Kolom Utama

| Kolom         | Tipe                 | Keterangan                      |
| ------------- | -------------------- | ------------------------------- |
| id            | BIGINT UNSIGNED      | Primary key.                    |
| uuid          | CHAR(36)             | ID publik.                      |
| name          | VARCHAR(150)         | Nama template.                  |
| code          | VARCHAR(50)          | Kode template.                  |
| document_type | VARCHAR(50)          | Jenis dokumen.                  |
| content_html  | LONGTEXT             | Template HTML.                  |
| version       | INT                  | Versi template.                 |
| status        | VARCHAR(30)          | draft/active/inactive/archived. |
| created_by    | BIGINT UNSIGNED NULL | Pembuat.                        |
| created_at    | DATETIME             | Waktu dibuat.                   |
| updated_at    | DATETIME             | Waktu diubah.                   |
| deleted_at    | DATETIME NULL        | Soft delete.                    |

### Aturan

1. Template yang sudah digunakan tidak boleh diedit langsung.
2. Jika berubah, buat versi baru.
3. Template digunakan oleh DocumentService untuk generate PDF.

---

## 20. Tabel `document_requirements`

### Fungsi

Menyimpan daftar dokumen wajib per periode.

### Kolom Utama

| Kolom              | Tipe            | Keterangan                       |
| ------------------ | --------------- | -------------------------------- |
| id                 | BIGINT UNSIGNED | Primary key.                     |
| period_id          | BIGINT UNSIGNED | Periode.                         |
| document_name      | VARCHAR(150)    | Nama dokumen.                    |
| document_code      | VARCHAR(80)     | Kode dokumen.                    |
| allowed_extensions | VARCHAR(150)    | pdf,jpg,jpeg,png.                |
| max_size_kb        | INT             | Maksimal ukuran file.            |
| is_required        | TINYINT         | Wajib/tidak.                     |
| stage              | VARCHAR(50)     | registrasi/penempatan/penilaian. |
| sort_order         | INT             | Urutan.                          |
| status             | VARCHAR(30)     | active/inactive.                 |
| created_at         | DATETIME        | Waktu dibuat.                    |
| updated_at         | DATETIME        | Waktu diubah.                    |

---

## 21. Tabel `student_documents`

### Fungsi

Menyimpan dokumen upload mahasiswa.

### Kolom Utama

| Kolom             | Tipe                 | Keterangan             |
| ----------------- | -------------------- | ---------------------- |
| id                | BIGINT UNSIGNED      | Primary key.           |
| uuid              | CHAR(36)             | ID publik.             |
| registration_id   | BIGINT UNSIGNED      | Relasi registrasi.     |
| requirement_id    | BIGINT UNSIGNED NULL | Relasi dokumen wajib.  |
| uploaded_by       | BIGINT UNSIGNED      | User pengupload.       |
| document_name     | VARCHAR(150)         | Nama dokumen.          |
| document_code     | VARCHAR(80)          | Kode dokumen.          |
| original_name     | VARCHAR(255)         | Nama file asli.        |
| stored_name       | VARCHAR(255)         | Nama file tersimpan.   |
| file_path         | VARCHAR(500)         | Path file di writable. |
| file_ext          | VARCHAR(20)          | Ekstensi.              |
| file_size_kb      | INT                  | Ukuran file.           |
| mime_type         | VARCHAR(100)         | MIME type.             |
| version           | INT                  | Versi upload.          |
| status            | VARCHAR(30)          | Status dokumen.        |
| verified_by       | BIGINT UNSIGNED NULL | Verifikator.           |
| verified_at       | DATETIME NULL        | Waktu verifikasi.      |
| verification_note | TEXT NULL            | Catatan verifikasi.    |
| created_at        | DATETIME             | Waktu upload.          |
| updated_at        | DATETIME             | Waktu ubah.            |
| deleted_at        | DATETIME NULL        | Soft delete.           |

### Status Dokumen

```text
belum_upload
menunggu_verifikasi
valid
perlu_revisi
ditolak
```

### Aturan

1. File fisik disimpan di `writable/uploads`.
2. Database hanya menyimpan metadata dan path.
3. Dokumen lama tidak langsung dihapus jika ada revisi.
4. Verifikasi dokumen wajib memiliki catatan jika revisi/ditolak.
5. Download file harus melalui controller.

---

## 22. Tabel `generated_documents`

### Fungsi

Menyimpan metadata dokumen hasil generate sistem.

### Kolom Utama

| Kolom           | Tipe                 | Keterangan                           |
| --------------- | -------------------- | ------------------------------------ |
| id              | BIGINT UNSIGNED      | Primary key.                         |
| uuid            | CHAR(36)             | ID publik.                           |
| registration_id | BIGINT UNSIGNED      | Relasi registrasi.                   |
| template_id     | BIGINT UNSIGNED      | Template dokumen.                    |
| generated_by    | BIGINT UNSIGNED NULL | User/sistem.                         |
| document_name   | VARCHAR(150)         | Nama dokumen.                        |
| document_code   | VARCHAR(80)          | Kode dokumen.                        |
| file_path       | VARCHAR(500)         | Path PDF di writable/generated.      |
| version         | INT                  | Versi generate.                      |
| status          | VARCHAR(30)          | generated/active/replaced/cancelled. |
| generated_at    | DATETIME             | Waktu generate.                      |
| created_at      | DATETIME             | Waktu dibuat.                        |
| updated_at      | DATETIME             | Waktu diubah.                        |

### Aturan

1. Hasil generate disimpan di `writable/generated`.
2. Jika dokumen digenerate ulang, simpan versi baru.
3. Dokumen yang sudah digunakan tidak boleh dihapus sembarangan.

---

# F. Instansi dan Penempatan

## 23. Tabel `placement_requests`

### Fungsi

Menyimpan pengajuan penempatan mahasiswa.

### Kolom Utama

| Kolom                     | Tipe                 | Keterangan               |
| ------------------------- | -------------------- | ------------------------ |
| id                        | BIGINT UNSIGNED      | Primary key.             |
| uuid                      | CHAR(36)             | ID publik.               |
| registration_id           | BIGINT UNSIGNED      | Relasi registrasi.       |
| placement_type            | VARCHAR(30)          | mitra/mandiri.           |
| institution_id            | BIGINT UNSIGNED NULL | Instansi jika sudah ada. |
| proposed_institution_name | VARCHAR(200) NULL    | Nama instansi mandiri.   |
| proposed_address          | TEXT NULL            | Alamat instansi mandiri. |
| proposed_field            | VARCHAR(150) NULL    | Bidang instansi.         |
| contact_person            | VARCHAR(150) NULL    | Kontak instansi.         |
| contact_position          | VARCHAR(100) NULL    | Jabatan.                 |
| contact_phone             | VARCHAR(30) NULL     | Nomor kontak.            |
| contact_email             | VARCHAR(150) NULL    | Email kontak.            |
| reason                    | TEXT NULL            | Alasan memilih.          |
| status                    | VARCHAR(50)          | Status pengajuan.        |
| submitted_at              | DATETIME NULL        | Waktu submit.            |
| reviewed_by               | BIGINT UNSIGNED NULL | Koordinator.             |
| reviewed_at               | DATETIME NULL        | Waktu review.            |
| review_note               | TEXT NULL            | Catatan review.          |
| created_at                | DATETIME             | Waktu dibuat.            |
| updated_at                | DATETIME             | Waktu ubah.              |
| deleted_at                | DATETIME NULL        | Soft delete.             |

### Status

```text
draft
diajukan
disetujui
perlu_revisi
ditolak
dibatalkan
```

### Aturan

1. Satu registrasi hanya boleh memiliki satu pengajuan penempatan aktif.
2. Tempat mandiri tidak wajib punya akun instansi.
3. Jika tempat mandiri disetujui, data instansi dapat disimpan ke `institution_profiles`.
4. Keputusan Koordinator wajib masuk audit log.

---

## 24. Tabel `placement_choices`

### Fungsi

Menyimpan pilihan prioritas instansi mahasiswa.

Digunakan untuk Lampiran A dan preferensi lokasi.

### Kolom Utama

| Kolom               | Tipe                 | Keterangan                        |
| ------------------- | -------------------- | --------------------------------- |
| id                  | BIGINT UNSIGNED      | Primary key.                      |
| registration_id     | BIGINT UNSIGNED      | Relasi registrasi.                |
| institution_id      | BIGINT UNSIGNED NULL | Jika instansi mitra.              |
| institution_name    | VARCHAR(200)         | Nama instansi.                    |
| institution_address | TEXT                 | Alamat.                           |
| reason              | TEXT NULL            | Alasan pemilihan.                 |
| priority_order      | INT                  | Prioritas 1/2/3.                  |
| is_selected         | TINYINT              | Dipilih sebagai penempatan akhir. |
| created_at          | DATETIME             | Waktu dibuat.                     |
| updated_at          | DATETIME             | Waktu ubah.                       |

### Aturan

1. Minimal mendukung 3 pilihan.
2. Urutan prioritas harus disimpan.
3. Pilihan akhir diberi `is_selected = 1`.

---

## 25. Tabel `institution_quotas`

### Fungsi

Menyimpan kuota instansi per periode.

### Kolom Utama

| Kolom          | Tipe            | Keterangan       |
| -------------- | --------------- | ---------------- |
| id             | BIGINT UNSIGNED | Primary key.     |
| period_id      | BIGINT UNSIGNED | Periode.         |
| institution_id | BIGINT UNSIGNED | Instansi.        |
| quota_total    | INT             | Kuota total.     |
| quota_used     | INT             | Kuota terpakai.  |
| status         | VARCHAR(30)     | active/inactive. |
| created_at     | DATETIME        | Waktu dibuat.    |
| updated_at     | DATETIME        | Waktu ubah.      |

### Constraint

```text
UNIQUE(period_id, institution_id)
```

### Aturan

1. Kuota diperiksa saat mahasiswa memilih instansi.
2. Kuota bertambah saat penempatan disetujui.
3. Kuota berkurang jika penempatan dibatalkan secara sah.
4. Hindari race condition saat kuota tinggal sedikit.

---

# G. TOPSIS

## 26. Tabel `topsis_criteria`

### Fungsi

Menyimpan kriteria TOPSIS.

### Kolom Utama

| Kolom       | Tipe            | Keterangan       |
| ----------- | --------------- | ---------------- |
| id          | BIGINT UNSIGNED | Primary key.     |
| code        | VARCHAR(50)     | Kode kriteria.   |
| name        | VARCHAR(150)    | Nama kriteria.   |
| type        | VARCHAR(20)     | benefit/cost.    |
| description | TEXT NULL       | Keterangan.      |
| status      | VARCHAR(30)     | active/inactive. |
| created_at  | DATETIME        | Waktu dibuat.    |
| updated_at  | DATETIME        | Waktu ubah.      |

### Kriteria Awal

```text
kesesuaian_bidang
kesesuaian_kemampuan
kuota
jarak
status_kemitraan
pembimbing_lapangan
```

---

## 27. Tabel `topsis_weights`

### Fungsi

Menyimpan bobot kriteria TOPSIS per periode.

### Kolom Utama

| Kolom       | Tipe            | Keterangan    |
| ----------- | --------------- | ------------- |
| id          | BIGINT UNSIGNED | Primary key.  |
| period_id   | BIGINT UNSIGNED | Periode.      |
| criteria_id | BIGINT UNSIGNED | Kriteria.     |
| weight      | DECIMAL(5,2)    | Bobot.        |
| created_at  | DATETIME        | Waktu dibuat. |
| updated_at  | DATETIME        | Waktu ubah.   |

### Constraint

```text
UNIQUE(period_id, criteria_id)
```

### Aturan

1. Bobot disimpan per periode.
2. Perubahan bobot periode baru tidak mengubah hasil periode lama.
3. Total bobot harus divalidasi sesuai aturan sistem.

---

## 28. Tabel `topsis_scores`

### Fungsi

Menyimpan nilai alternatif instansi terhadap kriteria untuk mahasiswa tertentu atau periode tertentu.

### Kolom Utama

| Kolom           | Tipe            | Keterangan            |
| --------------- | --------------- | --------------------- |
| id              | BIGINT UNSIGNED | Primary key.          |
| registration_id | BIGINT UNSIGNED | Registrasi mahasiswa. |
| institution_id  | BIGINT UNSIGNED | Instansi alternatif.  |
| criteria_id     | BIGINT UNSIGNED | Kriteria.             |
| score           | DECIMAL(10,4)   | Nilai kriteria.       |
| created_at      | DATETIME        | Waktu dibuat.         |
| updated_at      | DATETIME        | Waktu ubah.           |

### Aturan

1. Nilai kriteria harus tersimpan agar hasil dapat diaudit.
2. Score dapat berasal dari data sistem atau input Admin/Koordinator.
3. Score tidak boleh kosong saat perhitungan.

---

## 29. Tabel `topsis_results`

### Fungsi

Menyimpan hasil ranking TOPSIS.

### Kolom Utama

| Kolom                | Tipe            | Keterangan            |
| -------------------- | --------------- | --------------------- |
| id                   | BIGINT UNSIGNED | Primary key.          |
| registration_id      | BIGINT UNSIGNED | Registrasi mahasiswa. |
| institution_id       | BIGINT UNSIGNED | Instansi alternatif.  |
| preference_value     | DECIMAL(10,6)   | Nilai preferensi.     |
| rank_order           | INT             | Ranking.              |
| calculation_snapshot | JSON NULL       | Snapshot perhitungan. |
| calculated_at        | DATETIME        | Waktu hitung.         |
| created_at           | DATETIME        | Waktu dibuat.         |

### Aturan

1. Hasil TOPSIS harus disimpan.
2. Hasil ditampilkan sebagai rekomendasi.
3. Koordinator tetap memberi keputusan final.
4. Snapshot membantu audit dan pembuktian skripsi.

---

# H. Dosen Pembimbing

## 30. Tabel `supervisor_assignments`

### Fungsi

Menyimpan penetapan dosen pembimbing.

### Kolom Utama

| Kolom           | Tipe            | Keterangan                 |
| --------------- | --------------- | -------------------------- |
| id              | BIGINT UNSIGNED | Primary key.               |
| uuid            | CHAR(36)        | ID publik.                 |
| registration_id | BIGINT UNSIGNED | Relasi registrasi.         |
| lecturer_id     | BIGINT UNSIGNED | Dosen pembimbing.          |
| assigned_by     | BIGINT UNSIGNED | Koordinator.               |
| assigned_at     | DATETIME        | Waktu penetapan.           |
| status          | VARCHAR(30)     | active/replaced/cancelled. |
| note            | TEXT NULL       | Catatan.                   |
| created_at      | DATETIME        | Waktu dibuat.              |
| updated_at      | DATETIME        | Waktu ubah.                |

### Aturan

1. Satu mahasiswa hanya memiliki satu pembimbing aktif.
2. Jika pembimbing diganti, data lama menjadi `replaced`.
3. Dosen tidak boleh melebihi kuota.
4. Penetapan masuk audit log.

---

## 31. Tabel `supervision_quota_logs`

### Fungsi

Menyimpan riwayat perubahan kuota bimbingan.

### Kolom Utama

| Kolom       | Tipe            | Keterangan       |
| ----------- | --------------- | ---------------- |
| id          | BIGINT UNSIGNED | Primary key.     |
| lecturer_id | BIGINT UNSIGNED | Dosen.           |
| period_id   | BIGINT UNSIGNED | Periode.         |
| old_quota   | INT             | Kuota lama.      |
| new_quota   | INT             | Kuota baru.      |
| changed_by  | BIGINT UNSIGNED | User pengubah.   |
| reason      | TEXT NULL       | Alasan.          |
| created_at  | DATETIME        | Waktu perubahan. |

---

# I. Logbook

## 32. Tabel `logbook_weeks`

### Fungsi

Menyimpan logbook mingguan mahasiswa.

### Kolom Utama

| Kolom           | Tipe                 | Keterangan              |
| --------------- | -------------------- | ----------------------- |
| id              | BIGINT UNSIGNED      | Primary key.            |
| uuid            | CHAR(36)             | ID publik.              |
| registration_id | BIGINT UNSIGNED      | Registrasi mahasiswa.   |
| week_number     | INT                  | Minggu ke.              |
| start_date      | DATE                 | Tanggal mulai minggu.   |
| end_date        | DATE                 | Tanggal selesai minggu. |
| weekly_target   | TEXT NULL            | Target minggu ini.      |
| weekly_result   | TEXT NULL            | Hasil minggu ini.       |
| obstacle        | TEXT NULL            | Kendala.                |
| next_plan       | TEXT NULL            | Rencana berikutnya.     |
| status          | VARCHAR(30)          | Status logbook.         |
| submitted_at    | DATETIME NULL        | Waktu submit.           |
| approved_at     | DATETIME NULL        | Waktu disetujui.        |
| approved_by     | BIGINT UNSIGNED NULL | Dosen.                  |
| created_at      | DATETIME             | Waktu dibuat.           |
| updated_at      | DATETIME             | Waktu ubah.             |
| deleted_at      | DATETIME NULL        | Soft delete.            |

### Status

```text
draft
dikirim
perlu_revisi
disetujui
terkunci
```

### Constraint

```text
UNIQUE(registration_id, week_number)
```

---

## 33. Tabel `logbook_daily_entries`

### Fungsi

Menyimpan detail kegiatan harian di dalam logbook mingguan.

### Kolom Utama

| Kolom                 | Tipe                 | Keterangan                 |
| --------------------- | -------------------- | -------------------------- |
| id                    | BIGINT UNSIGNED      | Primary key.               |
| logbook_week_id       | BIGINT UNSIGNED      | Relasi ke logbook_weeks.   |
| activity_date         | DATE                 | Tanggal kegiatan.          |
| start_time            | TIME NULL            | Jam mulai.                 |
| end_time              | TIME NULL            | Jam selesai.               |
| activity_description  | TEXT                 | Uraian kegiatan.           |
| result_description    | TEXT NULL            | Hasil kegiatan.            |
| documentation_file_id | BIGINT UNSIGNED NULL | File dokumentasi jika ada. |
| created_at            | DATETIME             | Waktu dibuat.              |
| updated_at            | DATETIME             | Waktu ubah.                |

---

## 34. Tabel `logbook_reviews`

### Fungsi

Menyimpan review dosen terhadap logbook.

### Kolom Utama

| Kolom           | Tipe            | Keterangan                      |
| --------------- | --------------- | ------------------------------- |
| id              | BIGINT UNSIGNED | Primary key.                    |
| logbook_week_id | BIGINT UNSIGNED | Relasi logbook.                 |
| reviewed_by     | BIGINT UNSIGNED | Dosen.                          |
| status          | VARCHAR(30)     | disetujui/perlu_revisi/ditolak. |
| comment         | TEXT NULL       | Komentar dosen.                 |
| reviewed_at     | DATETIME        | Waktu review.                   |
| created_at      | DATETIME        | Waktu dibuat.                   |

---

# J. Laporan Akhir

## 35. Tabel `final_reports`

### Fungsi

Menyimpan laporan akhir mahasiswa.

### Kolom Utama

| Kolom           | Tipe                 | Keterangan           |
| --------------- | -------------------- | -------------------- |
| id              | BIGINT UNSIGNED      | Primary key.         |
| uuid            | CHAR(36)             | ID publik.           |
| registration_id | BIGINT UNSIGNED      | Relasi registrasi.   |
| uploaded_by     | BIGINT UNSIGNED      | User mahasiswa.      |
| title           | VARCHAR(255)         | Judul laporan.       |
| file_path       | VARCHAR(500)         | Path file laporan.   |
| original_name   | VARCHAR(255)         | Nama file asli.      |
| stored_name     | VARCHAR(255)         | Nama file tersimpan. |
| file_ext        | VARCHAR(20)          | Ekstensi.            |
| file_size_kb    | INT                  | Ukuran file.         |
| version         | INT                  | Versi upload.        |
| status          | VARCHAR(30)          | Status laporan.      |
| reviewed_by     | BIGINT UNSIGNED NULL | Dosen.               |
| reviewed_at     | DATETIME NULL        | Waktu review.        |
| review_note     | TEXT NULL            | Catatan.             |
| created_at      | DATETIME             | Waktu upload.        |
| updated_at      | DATETIME             | Waktu ubah.          |
| deleted_at      | DATETIME NULL        | Soft delete.         |

### Status

```text
dikirim
perlu_revisi
disetujui
ditolak
```

---

# K. Penilaian

## 36. Tabel `assessment_templates`

### Fungsi

Menyimpan template penilaian.

### Kolom Utama

| Kolom           | Tipe            | Keterangan                |
| --------------- | --------------- | ------------------------- |
| id              | BIGINT UNSIGNED | Primary key.              |
| period_id       | BIGINT UNSIGNED | Periode.                  |
| name            | VARCHAR(150)    | Nama template.            |
| assessment_type | VARCHAR(30)     | instansi/dosen/admin.     |
| version         | INT             | Versi.                    |
| status          | VARCHAR(30)     | active/inactive/archived. |
| created_at      | DATETIME        | Waktu dibuat.             |
| updated_at      | DATETIME        | Waktu ubah.               |

---

## 37. Tabel `assessment_components`

### Fungsi

Menyimpan komponen penilaian.

### Kolom Utama

| Kolom                  | Tipe            | Keterangan       |
| ---------------------- | --------------- | ---------------- |
| id                     | BIGINT UNSIGNED | Primary key.     |
| assessment_template_id | BIGINT UNSIGNED | Template.        |
| component_name         | VARCHAR(150)    | Nama komponen.   |
| max_score              | DECIMAL(5,2)    | Nilai maksimal.  |
| weight                 | DECIMAL(5,2)    | Bobot komponen.  |
| sort_order             | INT             | Urutan.          |
| status                 | VARCHAR(30)     | active/inactive. |
| created_at             | DATETIME        | Waktu dibuat.    |
| updated_at             | DATETIME        | Waktu ubah.      |

---

## 38. Tabel `assessment_scores`

### Fungsi

Menyimpan nilai mahasiswa.

### Kolom Utama

| Kolom                  | Tipe                 | Keterangan                       |
| ---------------------- | -------------------- | -------------------------------- |
| id                     | BIGINT UNSIGNED      | Primary key.                     |
| registration_id        | BIGINT UNSIGNED      | Registrasi.                      |
| assessment_template_id | BIGINT UNSIGNED      | Template nilai.                  |
| component_id           | BIGINT UNSIGNED      | Komponen.                        |
| assessor_user_id       | BIGINT UNSIGNED NULL | User penilai jika punya akun.    |
| assessor_role          | VARCHAR(30)          | instansi/dosen/admin.            |
| score                  | DECIMAL(5,2)         | Nilai.                           |
| note                   | TEXT NULL            | Catatan.                         |
| source_document_id     | BIGINT UNSIGNED NULL | Dokumen nilai manual jika ada.   |
| status                 | VARCHAR(30)          | draft/submitted/verified/locked. |
| created_at             | DATETIME             | Waktu dibuat.                    |
| updated_at             | DATETIME             | Waktu ubah.                      |

### Aturan

1. Nilai dosen hanya untuk mahasiswa bimbingannya.
2. Nilai instansi mandiri dapat bersumber dari dokumen upload.
3. Nilai terkunci setelah divalidasi Koordinator.

---

## 39. Tabel `final_scores`

### Fungsi

Menyimpan nilai akhir mahasiswa.

### Kolom Utama

| Kolom             | Tipe                 | Keterangan                                   |
| ----------------- | -------------------- | -------------------------------------------- |
| id                | BIGINT UNSIGNED      | Primary key.                                 |
| registration_id   | BIGINT UNSIGNED      | Registrasi.                                  |
| institution_score | DECIMAL(5,2) NULL    | Nilai instansi.                              |
| lecturer_score    | DECIMAL(5,2) NULL    | Nilai dosen.                                 |
| admin_score       | DECIMAL(5,2) NULL    | Nilai administrasi/logbook.                  |
| final_score       | DECIMAL(5,2)         | Nilai akhir angka.                           |
| final_grade       | VARCHAR(5) NULL      | Huruf mutu jika dipakai.                     |
| weight_snapshot   | JSON                 | Snapshot bobot nilai.                        |
| status            | VARCHAR(30)          | draft/menunggu_validasi/divalidasi/terkunci. |
| validated_by      | BIGINT UNSIGNED NULL | Koordinator.                                 |
| validated_at      | DATETIME NULL        | Waktu validasi.                              |
| validation_note   | TEXT NULL            | Catatan validasi.                            |
| created_at        | DATETIME             | Waktu dibuat.                                |
| updated_at        | DATETIME             | Waktu ubah.                                  |

### Constraint

```text
UNIQUE(registration_id)
```

### Aturan

1. Nilai akhir dihitung dari bobot periode.
2. Snapshot bobot harus disimpan.
3. Nilai akhir dikunci setelah divalidasi.
4. Koreksi nilai akhir harus lewat mekanisme khusus.

---

# L. Arsip dan Koreksi

## 40. Tabel `archive_corrections`

### Fungsi

Menyimpan permintaan/perubahan koreksi data arsip.

### Kolom Utama

| Kolom           | Tipe                 | Keterangan                          |
| --------------- | -------------------- | ----------------------------------- |
| id              | BIGINT UNSIGNED      | Primary key.                        |
| period_id       | BIGINT UNSIGNED      | Periode arsip.                      |
| registration_id | BIGINT UNSIGNED NULL | Registrasi terkait.                 |
| requested_by    | BIGINT UNSIGNED      | User pengaju.                       |
| approved_by     | BIGINT UNSIGNED NULL | User penyetuju.                     |
| correction_type | VARCHAR(100)         | Jenis koreksi.                      |
| reason          | TEXT                 | Alasan koreksi.                     |
| old_data_json   | JSON NULL            | Data lama.                          |
| new_data_json   | JSON NULL            | Data baru.                          |
| status          | VARCHAR(30)          | diajukan/disetujui/ditolak/selesai. |
| created_at      | DATETIME             | Waktu dibuat.                       |
| updated_at      | DATETIME             | Waktu ubah.                         |

### Aturan

1. Arsip tidak boleh diedit langsung.
2. Koreksi wajib memiliki alasan.
3. Koreksi wajib masuk audit log.

---

# M. Notifikasi dan Audit

## 41. Tabel `notifications`

### Fungsi

Menyimpan notifikasi user.

### Kolom Utama

| Kolom      | Tipe              | Keterangan                  |
| ---------- | ----------------- | --------------------------- |
| id         | BIGINT UNSIGNED   | Primary key.                |
| user_id    | BIGINT UNSIGNED   | Penerima.                   |
| title      | VARCHAR(150)      | Judul notifikasi.           |
| message    | TEXT              | Isi notifikasi.             |
| type       | VARCHAR(30)       | info/success/warning/error. |
| url        | VARCHAR(255) NULL | Link tujuan.                |
| is_read    | TINYINT           | Sudah dibaca/belum.         |
| read_at    | DATETIME NULL     | Waktu dibaca.               |
| created_at | DATETIME          | Waktu dibuat.               |

---

## 42. Tabel `audit_logs`

### Fungsi

Menyimpan riwayat aksi penting.

### Kolom Utama

| Kolom      | Tipe                 | Keterangan         |
| ---------- | -------------------- | ------------------ |
| id         | BIGINT UNSIGNED      | Primary key.       |
| user_id    | BIGINT UNSIGNED NULL | User pelaku.       |
| role       | VARCHAR(30) NULL     | Role pelaku.       |
| action     | VARCHAR(150)         | Nama aksi.         |
| table_name | VARCHAR(100) NULL    | Tabel terkait.     |
| record_id  | BIGINT UNSIGNED NULL | ID record terkait. |
| old_values | JSON NULL            | Data lama.         |
| new_values | JSON NULL            | Data baru.         |
| ip_address | VARCHAR(100) NULL    | IP address.        |
| user_agent | TEXT NULL            | Browser/perangkat. |
| note       | TEXT NULL            | Catatan.           |
| created_at | DATETIME             | Waktu aksi.        |

### Aksi yang Wajib Dicatat

```text
submit_registrasi
verifikasi_registrasi
tolak_registrasi
revisi_registrasi
upload_dokumen
verifikasi_dokumen
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

---

## 43. Relasi Utama Database

Relasi inti:

```text
users
├── student_profiles
├── lecturer_profiles
└── institution_profiles

study_programs
├── student_profiles
├── lecturer_profiles
└── kp_periods

kp_periods
└── kp_registrations
    ├── registration_status_logs
    ├── student_documents
    ├── generated_documents
    ├── placement_requests
    ├── placement_choices
    ├── topsis_scores
    ├── topsis_results
    ├── supervisor_assignments
    ├── logbook_weeks
    │   ├── logbook_daily_entries
    │   └── logbook_reviews
    ├── final_reports
    ├── assessment_scores
    └── final_scores
```

---

## 44. Index yang Disarankan

Index penting:

```text
users.email
users.role
users.status

student_profiles.npm
student_profiles.study_program_id

lecturer_profiles.nidn
lecturer_profiles.study_program_id

institution_profiles.type
institution_profiles.partnership_status

kp_periods.status
kp_periods.academic_year
kp_periods.study_program_id

kp_registrations.period_id
kp_registrations.student_id
kp_registrations.current_status

student_documents.registration_id
student_documents.status
student_documents.document_code

placement_requests.registration_id
placement_requests.status
placement_requests.placement_type

supervisor_assignments.lecturer_id
supervisor_assignments.registration_id
supervisor_assignments.status

logbook_weeks.registration_id
logbook_weeks.status

assessment_scores.registration_id
assessment_scores.assessor_role

audit_logs.user_id
audit_logs.action
audit_logs.created_at
```

---

## 45. Constraint Penting

Constraint yang harus diterapkan:

```text
UNIQUE users.email
UNIQUE student_profiles.npm
UNIQUE lecturer_profiles.nidn
UNIQUE kp_periods.uuid
UNIQUE kp_registrations.uuid
UNIQUE kp_registrations(period_id, student_id)
UNIQUE institution_quotas(period_id, institution_id)
UNIQUE topsis_weights(period_id, criteria_id)
UNIQUE supervisor_assignments.uuid
UNIQUE logbook_weeks(registration_id, week_number)
UNIQUE final_scores(registration_id)
```

---

## 46. Catatan Migration CI4

Gunakan migration CI4 untuk membuat tabel.

Aturan:

1. Jangan membuat tabel manual langsung di phpMyAdmin jika sedang pengembangan serius.
2. Gunakan migration agar struktur dapat dilacak.
3. Nama migration harus jelas.
4. Satu migration boleh berisi satu kelompok tabel yang berhubungan.
5. Jangan mengubah migration lama yang sudah berjalan di production; buat migration baru.

Contoh nama migration:

```text
CreateUsersTable
CreateProfilesTables
CreatePeriodsAndRegistrationsTables
CreateDocumentsTables
CreatePlacementTables
CreateTopsisTables
CreateLogbookTables
CreateAssessmentTables
CreateAuditLogsTable
```

---

## 47. Data Seeder Awal

Seeder awal yang disarankan:

```text
AdminSeeder
StudyProgramSeeder
RoleUserSeeder
TopsisCriteriaSeeder
DocumentTemplateSeeder
AssessmentTemplateSeeder
```

Data awal minimal:

1. Akun admin.
2. Program studi.
3. Kriteria TOPSIS awal.
4. Template dokumen dasar.
5. Template nilai dasar.
6. Periode contoh jika diperlukan.

---

## 48. Nama Database

Nama database default:

```text
db_kp_pkl
```

Jika ingin nama lebih formal:

```text
db_siman_kp_pkl
```

Gunakan satu nama secara konsisten di `.env`.

---

## 49. Hal yang Tidak Boleh Dilakukan

Agent tidak boleh:

1. Membuat database hanya berisi tabel CRUD sederhana.
2. Menggabungkan semua data user ke satu tabel besar tanpa profil role.
3. Menyimpan file fisik langsung di database.
4. Menyimpan file upload di public.
5. Menghapus data penting secara permanen.
6. Mengubah data arsip langsung tanpa koreksi.
7. Menghapus riwayat status.
8. Mengubah bobot TOPSIS lama dan membuat hasil periode lama berubah.
9. Mengubah form/template lama yang sudah digunakan tanpa versi baru.
10. Membuat status database tidak konsisten.
11. Menampilkan ID auto increment sebagai identifier publik tanpa kebutuhan.
12. Mengabaikan index untuk kolom penting.
13. Mengabaikan constraint unique pada email, NPM, dan registrasi periode.
14. Membuat nilai akhir tanpa snapshot bobot.
15. Membuat logbook tanpa relasi ke periode/registrasi.

---

## 50. Ringkasan Final

Database harus mengikuti pola berikut:

```text
User
↓
Profil Role
↓
Periode
↓
Registrasi Mahasiswa
↓
Status Workflow
↓
Dokumen
↓
Penempatan
↓
TOPSIS
↓
Pembimbing
↓
Logbook
↓
Laporan Akhir
↓
Penilaian
↓
Nilai Akhir
↓
Arsip
```

Tabel paling penting adalah:

```text
users
student_profiles
lecturer_profiles
institution_profiles
study_programs
kp_periods
kp_registrations
registration_status_logs
document_requirements
student_documents
generated_documents
placement_requests
placement_choices
institution_quotas
topsis_criteria
topsis_weights
topsis_scores
topsis_results
supervisor_assignments
logbook_weeks
logbook_daily_entries
logbook_reviews
final_reports
assessment_scores
final_scores
audit_logs
```

Database ini harus kuat untuk MVP, tetapi tetap bisa dikembangkan untuk fitur lanjutan seperti tanda tangan digital, OCR, WhatsApp Gateway, atau integrasi SIAKAD.
