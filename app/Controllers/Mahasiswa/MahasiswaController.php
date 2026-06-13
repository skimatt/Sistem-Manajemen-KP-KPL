<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Services\AuditService;
use App\Services\TopsisService;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MahasiswaController extends BaseController
{
    /**
     * Helper to get student profile and latest registration.
     */
    protected function getStudentData()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $profile = $db->table('student_profiles')
            ->where('user_id', $userId)
            ->get()
            ->getRow();
            
        $registration = null;
        if ($profile) {
            $registration = $db->table('kp_registrations')
                ->where('student_id', $profile->id)
                ->orderBy('id', 'DESC')
                ->get()
                ->getRow();
        }
        
        return [$profile, $registration];
    }

    /**
     * Helper to check access stage.
     */
    protected function checkAccess($stage)
    {
        list($profile, $registration) = $this->getStudentData();
        
        if (!$profile) {
            return $stage === 'profil';
        }
        
        if ($stage === 'profil') {
            return true;
        }
        
        if ($stage === 'registrasi') {
            return $profile->profile_status === 'complete';
        }
        
        if (!$registration) {
            return false;
        }
        
        $status = $registration->current_status;
        
        $stages = [
            'draft'                => 1,
            'menunggu_verifikasi'  => 2,
            'revisi_registrasi'    => 2,
            'registrasi_ditolak'   => 2,
            'registrasi_disetujui' => 3,
            'penempatan_diajukan'  => 4,
            'penempatan_disetujui' => 5,
            'diterima_instansi'    => 6,
            'dosen_ditetapkan'     => 7,
            'sedang_berjalan'      => 8,
            'selesai'              => 9,
            'diarsipkan'           => 10
        ];
        
        $currentRank = $stages[$status] ?? 0;
        
        switch ($stage) {
            case 'penempatan':
                return $currentRank >= 3;
            case 'dokumen':
                return $currentRank >= 5;
            case 'pembimbing':
            case 'logbook':
                return $currentRank >= 7;
            case 'laporan':
                return $currentRank >= 8;
            case 'penilaian':
                return $currentRank >= 9;
            default:
                return false;
        }
    }

    /**
     * Student Dashboard.
     */
    public function index()
    {
        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();
        
        $studentStatus = 'draft';
        $profileStatus = 'incomplete';
        $activeStage = 1;
        $activePeriod = null;
        $nextAction = '';
        $actionUrl = '';
        $logbookCount = 0;
        
        if ($profile) {
            $profileStatus = $profile->profile_status;
            if ($registration) {
                $studentStatus = $registration->current_status;
                $activePeriod = $db->table('kp_periods')->where('id', $registration->period_id)->get()->getRow();
                $logbookCount = $db->table('logbook_weeks')->where('registration_id', $registration->id)->countAllResults();
            }
        }
        
        // Calculate dynamic active stage
        if ($profileStatus !== 'complete') {
            $activeStage = 1;
        } elseif (!$registration) {
            $activeStage = 2;
        } else {
            $stages = [
                'draft'                => 2,
                'menunggu_verifikasi'  => 2,
                'revisi_registrasi'    => 2,
                'registrasi_ditolak'   => 2,
                'registrasi_disetujui' => 3,
                'penempatan_diajukan'  => 3,
                'penempatan_disetujui' => 4,
                'diterima_instansi'    => 5,
                'dosen_ditetapkan'     => 6,
                'sedang_berjalan'      => 6,
                'selesai'              => 9,
                'diarsipkan'           => 9
            ];
            $activeStage = $stages[$studentStatus] ?? 2;
        }
        
        // Next Action Info Text
        if ($activeStage === 1) {
            $nextAction = 'Lengkapi data profil diri dan akademik Anda untuk memulai pengajuan KP/KPL.';
            $actionUrl = 'mahasiswa/profile';
        } elseif ($activeStage === 2) {
            if (!$registration) {
                $nextAction = 'Silakan lakukan pendaftaran/registrasi KP/KPL baru pada periode aktif.';
                $actionUrl = 'mahasiswa/registrasi';
            } elseif ($studentStatus === 'draft') {
                $nextAction = 'Kirim draft registrasi KP/KPL Anda agar dapat diverifikasi oleh Koordinator.';
                $actionUrl = 'mahasiswa/registrasi';
            } elseif ($studentStatus === 'revisi_registrasi') {
                $nextAction = 'Ada catatan revisi dari Koordinator pada berkas registrasi Anda. Harap segera diperbaiki.';
                $actionUrl = 'mahasiswa/registrasi';
            } elseif ($studentStatus === 'registrasi_ditolak') {
                $nextAction = 'Pendaftaran Anda ditolak. Silakan hubungi Koordinator atau periksa kembali syarat pendaftaran.';
                $actionUrl = 'mahasiswa/registrasi';
            } else {
                $nextAction = 'Registrasi Anda berhasil dikirim. Menunggu verifikasi dokumen oleh Koordinator.';
                $actionUrl = '';
            }
        } elseif ($activeStage === 3) {
            if ($studentStatus === 'registrasi_disetujui') {
                $nextAction = 'Registrasi disetujui! Silakan ajukan penempatan instansi mitra atau tempat mandiri.';
                $actionUrl = 'mahasiswa/penempatan';
            } else {
                $nextAction = 'Pengajuan penempatan instansi telah dikirim. Menunggu persetujuan Koordinator.';
                $actionUrl = '';
            }
        } elseif ($activeStage === 4) {
            $nextAction = 'Penempatan disetujui! Silakan unduh surat pengantar instansi pada menu Dokumen dan upload surat balasannya.';
            $actionUrl = 'mahasiswa/dokumen';
        } elseif ($activeStage === 5) {
            $nextAction = 'Instansi telah menerima Anda. Menunggu penetapan dosen pembimbing oleh Koordinator.';
            $actionUrl = '';
        } elseif ($activeStage === 6) {
            $nextAction = 'KP/KPL sedang berjalan. Jangan lupa isi logbook kegiatan harian secara mingguan.';
            $actionUrl = 'mahasiswa/logbook';
        } elseif ($activeStage === 9) {
            $nextAction = 'Selamat! Anda telah menyelesaikan seluruh rangkaian kegiatan KP/KPL.';
            $actionUrl = '';
        }

        // Get supervisor details if assigned
        $supervisor = null;
        if ($registration) {
            $assignment = $db->table('supervisor_assignments')
                ->select('supervisor_assignments.*, lecturer_profiles.full_name as lecturer_name, lecturer_profiles.nidn')
                ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id')
                ->where('supervisor_assignments.registration_id', $registration->id)
                ->where('supervisor_assignments.status', 'active')
                ->get()
                ->getRow();
            if ($assignment) {
                $supervisor = $assignment;
            }
        }

        // Get placement details
        $placement = null;
        if ($registration) {
            $placement = $db->table('placement_requests')
                ->select('placement_requests.*, institution_profiles.name as instansi_name, institution_profiles.address as instansi_address')
                ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
                ->where('placement_requests.registration_id', $registration->id)
                ->orderBy('placement_requests.id', 'DESC')
                ->get()
                ->getRow();
        }
        
        $data = [
            'title'          => 'Dashboard Mahasiswa',
            'profile'        => $profile,
            'registration'   => $registration,
            'period'         => $activePeriod,
            'status'         => $studentStatus,
            'activeStage'    => $activeStage,
            'nextAction'     => $nextAction,
            'actionUrl'      => $actionUrl,
            'logbook_count'  => $logbookCount,
            'supervisor'     => $supervisor,
            'placement'      => $placement
        ];
        
        return view('mahasiswa/index', $data);
    }

    /**
     * Edit Profile View.
     */
    public function profile()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $profile = $db->table('student_profiles')->where('user_id', $userId)->get()->getRow();
        $prodis = $db->table('study_programs')->where('status', 'active')->get()->getResult();
        
        $data = [
            'title'    => 'Profil Saya',
            'profile'  => $profile,
            'prodis'   => $prodis,
        ];
        
        return view('mahasiswa/profile', $data);
    }

    /**
     * Submit Profile Updates.
     */
    public function updateProfile()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $profile = $db->table('student_profiles')->where('user_id', $userId)->get()->getRow();
        
        $rules = [
            'birth_place'      => 'required',
            'birth_date'       => 'required|valid_date',
            'gender'           => 'required|in_list[L,P]',
            'religion'         => 'required',
            'address'          => 'required',
            'district'         => 'required',
            'city'             => 'required',
            'province'         => 'required',
            'phone'            => 'required|numeric|min_length[10]|max_length[15]',
            'parent_name'      => 'required',
            'parent_phone'     => 'required|numeric|min_length[10]|max_length[15]',
            'study_program_id' => 'required|integer',
            'current_semester' => 'required|integer|greater_than_equal_to[1]',
        ];

        $messages = [
            'birth_place'      => ['required' => 'Tempat lahir wajib diisi.'],
            'birth_date'       => ['required' => 'Tanggal lahir wajib diisi.', 'valid_date' => 'Tanggal lahir tidak valid.'],
            'gender'           => ['required' => 'Jenis kelamin wajib diisi.', 'in_list' => 'Jenis kelamin tidak valid.'],
            'religion'         => ['required' => 'Agama wajib diisi.'],
            'address'          => ['required' => 'Alamat lengkap wajib diisi.'],
            'district'         => ['required' => 'Kecamatan wajib diisi.'],
            'city'             => ['required' => 'Kabupaten/Kota wajib diisi.'],
            'province'         => ['required' => 'Provinsi wajib diisi.'],
            'phone'            => ['required' => 'Nomor HP wajib diisi.', 'numeric' => 'Nomor HP hanya boleh berisi angka.', 'min_length' => 'Nomor HP minimal 10 digit.', 'max_length' => 'Nomor HP maksimal 15 digit.'],
            'parent_name'      => ['required' => 'Nama orang tua/wali wajib diisi.'],
            'parent_phone'     => ['required' => 'Nomor HP orang tua/wali wajib diisi.', 'numeric' => 'Nomor HP orang tua/wali hanya boleh berisi angka.', 'min_length' => 'Nomor HP orang tua/wali minimal 10 digit.', 'max_length' => 'Nomor HP orang tua/wali maksimal 15 digit.'],
            'study_program_id' => ['required' => 'Program studi wajib dipilih.'],
            'current_semester' => ['required' => 'Semester saat ini wajib diisi.', 'greater_than_equal_to' => 'Semester minimal bernilai 1.'],
        ];
        
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $postData = $this->request->getPost();
        
        // Determine completeness
        $isComplete = 'complete';
        foreach (['birth_place', 'birth_date', 'gender', 'religion', 'address', 'district', 'city', 'province', 'phone', 'parent_name', 'parent_phone', 'study_program_id', 'current_semester'] as $field) {
            if (empty($postData[$field])) {
                $isComplete = 'incomplete';
                break;
            }
        }
        
        $updateData = [
            'birth_place'      => $postData['birth_place'],
            'birth_date'       => $postData['birth_date'],
            'gender'           => $postData['gender'],
            'religion'         => $postData['religion'],
            'address'          => $postData['address'],
            'district'         => $postData['district'],
            'city'             => $postData['city'],
            'province'         => $postData['province'],
            'phone'            => $postData['phone'],
            'parent_name'      => $postData['parent_name'],
            'parent_phone'     => $postData['parent_phone'],
            'study_program_id' => $postData['study_program_id'],
            'current_semester' => $postData['current_semester'],
            'profile_status'   => $isComplete,
            'updated_at'       => date('Y-m-d H:i:s')
        ];
        
        $db->transStart();
        
        $db->table('student_profiles')
            ->where('user_id', $userId)
            ->update($updateData);
            
        // Log in Audit Logs
        AuditService::log(
            'UPDATE_PROFILE',
            'student_profiles',
            $profile->id,
            (array)$profile,
            $updateData,
            'Mahasiswa memperbarui profil diri'
        );
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
        
        return redirect()->to(base_url('mahasiswa/profile'))->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Registration Stage Page.
     */
    public function registrasi()
    {
        if (!$this->checkAccess('registrasi')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }
        
        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();
        
        // Find active period for this student study program
        $activePeriod = $db->table('kp_periods')
            ->where('study_program_id', $profile->study_program_id)
            ->where('status', 'aktif')
            ->get()
            ->getRow();
            
        $data = [
            'title'        => 'Registrasi KP/KPL Baru',
            'profile'      => $profile,
            'registration' => $registration,
            'period'       => $activePeriod,
        ];
        
        return view('mahasiswa/registrasi', $data);
    }

    /**
     * Submit Registration Form.
     */
    public function submitRegistrasi()
    {
        if (!$this->checkAccess('registrasi')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        // Get active period
        $activePeriod = $db->table('kp_periods')
            ->where('study_program_id', $profile->study_program_id)
            ->where('status', 'aktif')
            ->get()
            ->getRow();

        if (!$activePeriod) {
            return redirect()->back()->with('error', 'Tidak ada periode pendaftaran aktif untuk Program Studi Anda.');
        }

        // Validation Rules
        $rules = [
            'academic_sks'                => 'required|integer|greater_than_equal_to[80]',
            'academic_gpa'                => 'required|decimal|greater_than_equal_to[2.50]',
            'passed_basic_programming'    => 'required|in_list[0,1]',
            'passed_data_structure'       => 'required|in_list[0,1]',
            'passed_database'             => 'required|in_list[0,1]',
            'passed_system_analysis'      => 'required|in_list[0,1]',
            'passed_networking'           => 'required|in_list[0,1]',
            'passed_concentration_course' => 'required|in_list[0,1]',
            'academic_advisor_name'       => 'required',
            'payment_proof'               => 'uploaded[payment_proof]|max_size[payment_proof,10240]|ext_in[payment_proof,pdf,jpg,jpeg,png]',
            'khs_file'                    => 'uploaded[khs_file]|max_size[khs_file,10240]|ext_in[khs_file,pdf]',
            'recommendation_file'         => 'uploaded[recommendation_file]|max_size[recommendation_file,10240]|ext_in[recommendation_file,pdf]',
        ];

        $messages = [
            'academic_sks'                => ['required' => 'Jumlah SKS wajib diisi.', 'greater_than_equal_to' => 'Jumlah SKS minimal 80 untuk mendaftar KP/KPL.'],
            'academic_gpa'                => ['required' => 'IPK wajib diisi.', 'greater_than_equal_to' => 'IPK minimal 2,50 untuk mendaftar KP/KPL.'],
            'passed_basic_programming'    => ['required' => 'Kelulusan Pemrograman Dasar wajib dipilih.'],
            'passed_data_structure'       => ['required' => 'Kelulusan Struktur Data wajib dipilih.'],
            'passed_database'             => ['required' => 'Kelulusan Basis Data wajib dipilih.'],
            'passed_system_analysis'      => ['required' => 'Kelulusan Analisis Sistem (APSI) wajib dipilih.'],
            'passed_networking'           => ['required' => 'Kelulusan Jaringan Komputer wajib dipilih.'],
            'passed_concentration_course' => ['required' => 'Kelulusan Mata Kuliah Konsentrasi wajib dipilih.'],
            'academic_advisor_name'       => ['required' => 'Nama Dosen Pembimbing Akademik wajib diisi.'],
            'payment_proof'               => ['uploaded' => 'Bukti pembayaran wajib diunggah.', 'max_size' => 'Ukuran file bukti pembayaran maksimal 10 MB.', 'ext_in' => 'Format bukti pembayaran harus PDF, JPG, JPEG, atau PNG.'],
            'khs_file'                    => ['uploaded' => 'File KHS terbaru wajib diunggah.', 'max_size' => 'Ukuran KHS terbaru maksimal 10 MB.', 'ext_in' => 'Format file KHS harus PDF.'],
            'recommendation_file'         => ['uploaded' => 'Surat rekomendasi Dosen PA wajib diunggah.', 'max_size' => 'Ukuran rekomendasi Dosen PA maksimal 10 MB.', 'ext_in' => 'Format file surat rekomendasi harus PDF.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload directory
        $uploadDir = WRITEPATH . 'uploads/kp-pkl/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Process File Uploads
        $paymentFile = $this->request->getFile('payment_proof');
        $khsFile = $this->request->getFile('khs_file');
        $recomFile = $this->request->getFile('recommendation_file');

        $paymentStored = $paymentFile->getRandomName();
        $khsStored = $khsFile->getRandomName();
        $recomStored = $recomFile->getRandomName();

        $paymentFile->move($uploadDir, $paymentStored);
        $khsFile->move($uploadDir, $khsStored);
        $recomFile->move($uploadDir, $recomStored);

        $db->transStart();

        $uuid = \CodeIgniter\Encryption\Encryption::createKey(); // Simple fallback UUID or generate unique string
        $uuid = bin2hex(random_bytes(16)); // Standard random hex ID for routing
        $uuid = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);

        $registrationData = [
            'uuid'                          => $uuid,
            'period_id'                     => $activePeriod->id,
            'student_id'                    => $profile->id,
            'current_status'                => 'menunggu_verifikasi',
            'academic_sks'                  => $this->request->getPost('academic_sks'),
            'academic_gpa'                  => $this->request->getPost('academic_gpa'),
            'is_gpa_eligible'               => 1,
            'passed_basic_programming'      => $this->request->getPost('passed_basic_programming'),
            'passed_data_structure'         => $this->request->getPost('passed_data_structure'),
            'passed_database'               => $this->request->getPost('passed_database'),
            'passed_system_analysis'        => $this->request->getPost('passed_system_analysis'),
            'passed_networking'             => $this->request->getPost('passed_networking'),
            'passed_concentration_course'   => $this->request->getPost('passed_concentration_course'),
            'education_payment_status'      => 'terbayar',
            'academic_advisor_name'         => $this->request->getPost('academic_advisor_name'),
            'advisor_recommendation_status' => 'layak',
            'submitted_at'                  => date('Y-m-d H:i:s'),
            'created_at'                    => date('Y-m-d H:i:s'),
            'updated_at'                    => date('Y-m-d H:i:s')
        ];

        // Insert Registration
        $db->table('kp_registrations')->insert($registrationData);
        $registrationId = $db->insertID();

        // Write to status logs
        $db->table('registration_status_logs')->insert([
            'registration_id' => $registrationId,
            'old_status'      => 'draft',
            'new_status'      => 'menunggu_verifikasi',
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'mahasiswa',
            'note'            => 'Mahasiswa mengajukan registrasi dan mengupload berkas persyaratan.',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Save student_documents references
        $docs = [
            ['code' => 'bukti_pembayaran', 'name' => 'Bukti Pembayaran KP/KPL', 'file' => $paymentFile, 'stored' => $paymentStored],
            ['code' => 'khs_terbaru', 'name' => 'KHS Terbaru', 'file' => $khsFile, 'stored' => $khsStored],
            ['code' => 'rekomendasi_dosen_pa', 'name' => 'Surat Rekomendasi Dosen PA', 'file' => $recomFile, 'stored' => $recomStored]
        ];

        foreach ($docs as $d) {
            $db->table('student_documents')->insert([
                'uuid'            => bin2hex(random_bytes(16)),
                'registration_id' => $registrationId,
                'uploaded_by'     => session()->get('user_id'),
                'document_name'   => $d['name'],
                'document_code'   => $d['code'],
                'original_name'   => $d['file']->getClientName(),
                'stored_name'     => $d['stored'],
                'file_path'       => 'uploads/kp-pkl/' . $d['stored'],
                'file_ext'        => $d['file']->getClientExtension(),
                'file_size_kb'    => round($d['file']->getSize() / 1024),
                'mime_type'       => $d['file']->getMimeType(),
                'version'         => 1,
                'status'          => 'menunggu_verifikasi',
                'created_at'      => date('Y-m-d H:i:s')
            ]);
        }

        // Log Audit
        AuditService::log(
            'SUBMIT_REGISTRATION',
            'kp_registrations',
            $registrationId,
            null,
            $registrationData,
            'Mahasiswa melakukan pendaftaran registrasi KP/KPL baru'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pendaftaran. Silakan coba beberapa saat lagi.');
        }

        return redirect()->to(base_url('mahasiswa/status-registrasi'))->with('success', 'Registrasi pendaftaran KP/KPL Anda berhasil dikirim.');
    }

    /**
     * Status Registrasi Page.
     */
    public function statusRegistrasi()
    {
        if (!$this->checkAccess('registrasi')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $logs = [];
        $docs = [];
        if ($registration) {
            $logs = $db->table('registration_status_logs')
                ->where('registration_id', $registration->id)
                ->orderBy('id', 'DESC')
                ->get()
                ->getResult();

            $docs = $db->table('student_documents')
                ->where('registration_id', $registration->id)
                ->get()
                ->getResult();
        }

        $data = [
            'title'        => 'Status Registrasi Anda',
            'registration' => $registration,
            'logs'         => $logs,
            'docs'         => $docs
        ];

        return view('mahasiswa/status_registrasi', $data);
    }

    /**
     * Penempatan gateway screen.
     */
    public function penempatan()
    {
        if (!$this->checkAccess('penempatan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak. Menu penempatan terkunci.');
        }

        list($profile, $registration) = $this->getStudentData();

        $data = [
            'title'        => 'Penempatan KP/KPL',
            'registration' => $registration
        ];

        return view('mahasiswa/penempatan', $data);
    }

    /**
     * Handle pathway choice: Mitra vs Mandiri.
     */
    public function choosePenempatanType()
    {
        if (!$this->checkAccess('penempatan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $type = $this->request->getPost('placement_type');
        if (!in_list($type, ['mitra', 'mandiri'])) {
            return redirect()->back()->with('error', 'Pilihan jalur tidak valid.');
        }

        if ($type === 'mitra') {
            return redirect()->to(base_url('mahasiswa/rekomendasi-mitra'));
        } else {
            return redirect()->to(base_url('mahasiswa/tempat-mandiri'));
        }
    }

    /**
     * Mitra selection view with TOPSIS recommendation ranks.
     */
    public function rekomendasiMitra()
    {
        if (!$this->checkAccess('penempatan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        // Run TOPSIS first to populate/update recommendations
        try {
            TopsisService::calculate($registration->id);
        } catch (\Exception $e) {
            // Uniform fallback if quotas are missing or something
        }

        // Get ranked instansi from topsis_results
        $rekomendasi = $db->table('topsis_results')
            ->select('topsis_results.*, institution_profiles.name as instansi_name, institution_profiles.field_category, institution_profiles.address, institution_quotas.quota_total, institution_quotas.quota_used')
            ->join('institution_profiles', 'institution_profiles.id = topsis_results.institution_id')
            ->join('institution_quotas', 'institution_quotas.institution_id = topsis_results.institution_id AND institution_quotas.period_id = ' . $registration->period_id)
            ->where('topsis_results.registration_id', $registration->id)
            ->orderBy('topsis_results.rank_order', 'ASC')
            ->get()
            ->getResult();

        $data = [
            'title'        => 'Rekomendasi Penempatan Mitra',
            'registration' => $registration,
            'rekomendasi'  => $rekomendasi
        ];

        return view('mahasiswa/rekomendasi_mitra', $data);
    }

    /**
     * Submit Mitra priority choices (1, 2, 3).
     */
    public function submitMitraChoices()
    {
        if (!$this->checkAccess('penempatan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $choice1 = $this->request->getPost('institution_id_1');
        $choice2 = $this->request->getPost('institution_id_2');
        $choice3 = $this->request->getPost('institution_id_3');
        $reason = $this->request->getPost('reason');

        if (empty($choice1) || empty($choice2) || empty($choice3)) {
            return redirect()->back()->withInput()->with('error', 'Anda harus memilih tepat 3 prioritas instansi mitra.');
        }

        if ($choice1 == $choice2 || $choice1 == $choice3 || $choice2 == $choice3) {
            return redirect()->back()->withInput()->with('error', 'Pilihan prioritas instansi tidak boleh sama.');
        }

        $db->transStart();

        // Clear previous choices if any
        $db->table('placement_choices')->where('registration_id', $registration->id)->delete();

        // Insert choices
        $choices = [$choice1, $choice2, $choice3];
        foreach ($choices as $order => $instId) {
            $inst = $db->table('institution_profiles')->where('id', $instId)->get()->getRow();
            $db->table('placement_choices')->insert([
                'registration_id'     => $registration->id,
                'institution_id'      => $instId,
                'institution_name'    => $inst->name,
                'institution_address' => $inst->address,
                'reason'              => ($order === 0) ? $reason : null,
                'priority_order'      => $order + 1,
                'is_selected'         => 0,
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s')
            ]);
        }

        // Insert or Update placement requests
        $db->table('placement_requests')->where('registration_id', $registration->id)->delete();
        
        $reqData = [
            'uuid'            => bin2hex(random_bytes(16)),
            'registration_id' => $registration->id,
            'placement_type'  => 'mitra',
            'institution_id'  => $choice1, // Initial target choice
            'reason'          => $reason,
            'status'          => 'diajukan',
            'submitted_at'    => date('Y-m-d H:i:s'),
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ];
        $db->table('placement_requests')->insert($reqData);

        // Update registration status
        $db->table('kp_registrations')
            ->where('id', $registration->id)
            ->update([
                'current_status' => 'penempatan_diajukan',
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

        // Write status logs
        $db->table('registration_status_logs')->insert([
            'registration_id' => $registration->id,
            'old_status'      => $registration->current_status,
            'new_status'      => 'penempatan_diajukan',
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'mahasiswa',
            'note'            => 'Mahasiswa mengajukan penempatan mitra dengan pilihan prioritas.',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Audit log
        AuditService::log(
            'SUBMIT_MITRA_CHOICES',
            'placement_requests',
            $registration->id,
            null,
            $reqData,
            'Mahasiswa mensubmit pilihan prioritas instansi mitra'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses penempatan. Silakan coba kembali.');
        }

        return redirect()->to(base_url('mahasiswa/dashboard'))->with('success', 'Pengajuan penempatan instansi mitra berhasil dikirim.');
    }

    /**
     * Self-placed Tempat Mandiri view form.
     */
    public function tempatMandiri()
    {
        if (!$this->checkAccess('penempatan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        list($profile, $registration) = $this->getStudentData();

        $data = [
            'title'        => 'Pengajuan Tempat Mandiri',
            'registration' => $registration
        ];

        return view('mahasiswa/tempat_mandiri', $data);
    }

    /**
     * Submit Self-placed proposal and proof file.
     */
    public function submitMandiriProposal()
    {
        if (!$this->checkAccess('penempatan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $rules = [
            'proposed_institution_name' => 'required',
            'proposed_address'          => 'required',
            'proposed_field'            => 'required',
            'contact_person'            => 'required',
            'contact_position'          => 'required',
            'contact_phone'             => 'required|numeric',
            'contact_email'             => 'required|valid_email',
            'reason'                    => 'required',
            'mandiri_proof'             => 'uploaded[mandiri_proof]|max_size[mandiri_proof,10240]|ext_in[mandiri_proof,pdf]',
        ];

        $messages = [
            'proposed_institution_name' => ['required' => 'Nama instansi mandiri wajib diisi.'],
            'proposed_address'          => ['required' => 'Alamat instansi wajib diisi.'],
            'proposed_field'            => ['required' => 'Bidang instansi wajib diisi.'],
            'contact_person'            => ['required' => 'Nama kontak person wajib diisi.'],
            'contact_position'          => ['required' => 'Jabatan kontak wajib diisi.'],
            'contact_phone'             => ['required' => 'Nomor HP kontak wajib diisi.', 'numeric' => 'Nomor HP kontak hanya boleh berisi angka.'],
            'contact_email'             => ['required' => 'Email kontak wajib diisi.', 'valid_email' => 'Format email kontak tidak valid.'],
            'reason'                    => ['required' => 'Alasan pengajuan wajib diisi.'],
            'mandiri_proof'             => ['uploaded' => 'File bukti penjajakan/komunikasi wajib diunggah.', 'max_size' => 'File bukti maksimal 10 MB.', 'ext_in' => 'Format file bukti harus PDF.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploadDir = WRITEPATH . 'uploads/kp-pkl/';
        $proofFile = $this->request->getFile('mandiri_proof');
        $proofStored = $proofFile->getRandomName();
        $proofFile->move($uploadDir, $proofStored);

        $db->transStart();

        // Clear previous requests
        $db->table('placement_requests')->where('registration_id', $registration->id)->delete();

        $reqData = [
            'uuid'                      => bin2hex(random_bytes(16)),
            'registration_id'           => $registration->id,
            'placement_type'            => 'mandiri',
            'proposed_institution_name' => $this->request->getPost('proposed_institution_name'),
            'proposed_address'          => $this->request->getPost('proposed_address'),
            'proposed_field'            => $this->request->getPost('proposed_field'),
            'contact_person'            => $this->request->getPost('contact_person'),
            'contact_position'          => $this->request->getPost('contact_position'),
            'contact_phone'             => $this->request->getPost('contact_phone'),
            'contact_email'             => $this->request->getPost('contact_email'),
            'reason'                    => $this->request->getPost('reason'),
            'status'                    => 'diajukan',
            'submitted_at'              => date('Y-m-d H:i:s'),
            'created_at'                => date('Y-m-d H:i:s'),
            'updated_at'                => date('Y-m-d H:i:s')
        ];
        $db->table('placement_requests')->insert($reqData);
        $requestId = $db->insertID();

        // Update student_documents reference
        $db->table('student_documents')->insert([
            'uuid'            => bin2hex(random_bytes(16)),
            'registration_id' => $registration->id,
            'uploaded_by'     => session()->get('user_id'),
            'document_name'   => 'Dokumen Bukti Penjajakan Mandiri',
            'document_code'   => 'proposal_mandiri',
            'original_name'   => $proofFile->getClientName(),
            'stored_name'     => $proofStored,
            'file_path'       => 'uploads/kp-pkl/' . $proofStored,
            'file_ext'        => $proofFile->getClientExtension(),
            'file_size_kb'    => round($proofFile->getSize() / 1024),
            'mime_type'       => $proofFile->getMimeType(),
            'version'         => 1,
            'status'          => 'menunggu_verifikasi',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Update registration status
        $db->table('kp_registrations')
            ->where('id', $registration->id)
            ->update([
                'current_status' => 'penempatan_diajukan',
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

        // Write status logs
        $db->table('registration_status_logs')->insert([
            'registration_id' => $registration->id,
            'old_status'      => $registration->current_status,
            'new_status'      => 'penempatan_diajukan',
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'mahasiswa',
            'note'            => 'Mahasiswa mengajukan penempatan mandiri: ' . $reqData['proposed_institution_name'],
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Audit log
        AuditService::log(
            'SUBMIT_MANDIRI_PROPOSAL',
            'placement_requests',
            $requestId,
            null,
            $reqData,
            'Mahasiswa mengajukan tempat mandiri baru'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan tempat mandiri. Silakan coba kembali.');
        }

        return redirect()->to(base_url('mahasiswa/dashboard'))->with('success', 'Pengajuan penempatan mandiri berhasil dikirim.');
    }

    /**
     * Download center for system-generated letters.
     */
    public function dokumen()
    {
        if (!$this->checkAccess('dokumen')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Menu Surat & Dokumen belum dibuka.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        // Get list of generated documents
        $generatedDocs = $db->table('generated_documents')
            ->where('registration_id', $registration->id)
            ->get()
            ->getResult();

        $data = [
            'title'         => 'Surat & Dokumen Resmi',
            'registration'  => $registration,
            'generatedDocs' => $generatedDocs
        ];

        return view('mahasiswa/dokumen', $data);
    }

    /**
     * Download wrapper for student-accessible files.
     */
    public function downloadFile($id, $type = 'generated')
    {
        $db = \Config\Database::connect();
        
        if ($type === 'generated') {
            $doc = $db->table('generated_documents')->where('id', $id)->get()->getRow();
            if ($doc) {
                // Ensure student is authorized to download this document
                list($profile, $registration) = $this->getStudentData();
                if ($registration && $doc->registration_id == $registration->id) {
                    $filePath = WRITEPATH . $doc->file_path;
                    if (file_exists($filePath)) {
                        return $this->response->download($filePath, null);
                    }
                }
            }
        } else {
            $doc = $db->table('student_documents')->where('id', $id)->get()->getRow();
            if ($doc) {
                list($profile, $registration) = $this->getStudentData();
                if ($registration && $doc->registration_id == $registration->id) {
                    $filePath = WRITEPATH . $doc->file_path;
                    if (file_exists($filePath)) {
                        return $this->response->download($filePath, null);
                    }
                }
            }
        }
        
        return redirect()->back()->with('error', 'Berkas tidak ditemukan atau Anda tidak memiliki akses.');
    }

    /**
     * Scanned reply letter view form.
     */
    public function uploadBalasan()
    {
        if (!$this->checkAccess('dokumen')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        list($profile, $registration) = $this->getStudentData();

        $db = \Config\Database::connect();
        $replyDoc = $db->table('student_documents')
            ->where('registration_id', $registration->id)
            ->where('document_code', 'surat_balasan')
            ->get()
            ->getRow();

        $data = [
            'title'        => 'Upload Surat Balasan Instansi',
            'registration' => $registration,
            'replyDoc'     => $replyDoc
        ];

        return view('mahasiswa/upload_balasan', $data);
    }

    /**
     * Process scanned acceptance reply letter upload.
     */
    public function submitBalasanFile()
    {
        if (!$this->checkAccess('dokumen')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $rules = [
            'reply_letter' => 'uploaded[reply_letter]|max_size[reply_letter,10240]|ext_in[reply_letter,pdf]',
        ];

        $messages = [
            'reply_letter' => ['uploaded' => 'Surat balasan wajib diunggah.', 'max_size' => 'Ukuran file surat balasan maksimal 10 MB.', 'ext_in' => 'Format file surat balasan harus PDF.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploadDir = WRITEPATH . 'uploads/kp-pkl/';
        $replyFile = $this->request->getFile('reply_letter');
        $replyStored = $replyFile->getRandomName();
        $replyFile->move($uploadDir, $replyStored);

        $db->transStart();

        // Delete old reply document if any
        $db->table('student_documents')
            ->where('registration_id', $registration->id)
            ->where('document_code', 'surat_balasan')
            ->delete();

        // Insert doc
        $db->table('student_documents')->insert([
            'uuid'            => bin2hex(random_bytes(16)),
            'registration_id' => $registration->id,
            'uploaded_by'     => session()->get('user_id'),
            'document_name'   => 'Surat Balasan / Penerimaan Instansi',
            'document_code'   => 'surat_balasan',
            'original_name'   => $replyFile->getClientName(),
            'stored_name'     => $replyStored,
            'file_path'       => 'uploads/kp-pkl/' . $replyStored,
            'file_ext'        => $replyFile->getClientExtension(),
            'file_size_kb'    => round($replyFile->getSize() / 1024),
            'mime_type'       => $replyFile->getMimeType(),
            'version'         => 1,
            'status'          => 'menunggu_verifikasi',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Update registration status to menunggu penerimaan instansi (which koordinator verifies)
        $db->table('kp_registrations')
            ->where('id', $registration->id)
            ->update([
                'current_status' => 'menunggu_penerimaan_instansi',
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

        // Log status change
        $db->table('registration_status_logs')->insert([
            'registration_id' => $registration->id,
            'old_status'      => $registration->current_status,
            'new_status'      => 'menunggu_penerimaan_instansi',
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'mahasiswa',
            'note'            => 'Mahasiswa mengupload berkas surat balasan penerimaan instansi.',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Audit Log
        AuditService::log(
            'UPLOAD_REPLY_LETTER',
            'student_documents',
            $registration->id,
            null,
            null,
            'Mahasiswa mengupload surat balasan instansi mitra/mandiri'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses unggahan. Silakan coba lagi.');
        }

        return redirect()->to(base_url('mahasiswa/dashboard'))->with('success', 'Surat balasan instansi berhasil diunggah.');
    }

    /**
     * Assigned Advisor Page.
     */
    public function pembimbing()
    {
        if (!$this->checkAccess('pembimbing')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Pembimbing belum ditetapkan.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $assignment = $db->table('supervisor_assignments')
            ->select('supervisor_assignments.*, lecturer_profiles.nidn, lecturer_profiles.full_name as lecturer_name, lecturer_profiles.expertise, users.email, users.phone')
            ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id')
            ->join('users', 'users.id = lecturer_profiles.user_id')
            ->where('supervisor_assignments.registration_id', $registration->id)
            ->where('supervisor_assignments.status', 'active')
            ->get()
            ->getRow();

        $data = [
            'title'      => 'Dosen Pembimbing Saya',
            'supervisor' => $assignment
        ];

        return view('mahasiswa/pembimbing', $data);
    }

    /**
     * Weekly logbook view.
     */
    public function logbook()
    {
        if (!$this->checkAccess('logbook')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Menu Logbook belum dibuka.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $weeks = $db->table('logbook_weeks')
            ->where('registration_id', $registration->id)
            ->orderBy('week_number', 'ASC')
            ->get()
            ->getResult();

        // Get daily entries map by week id
        $entries = [];
        foreach ($weeks as $w) {
            $entries[$w->id] = $db->table('logbook_daily_entries')
                ->where('logbook_week_id', $w->id)
                ->orderBy('activity_date', 'ASC')
                ->get()
                ->getResult();
        }

        $data = [
            'title'        => 'Logbook Kegiatan Mingguan',
            'registration' => $registration,
            'weeks'        => $weeks,
            'entries'      => $entries
        ];

        return view('mahasiswa/logbook', $data);
    }

    /**
     * Add new weekly block structure.
     */
    public function addLogbookWeek()
    {
        if (!$this->checkAccess('logbook')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $rules = [
            'week_number' => 'required|integer',
            'start_date'  => 'required|valid_date',
            'end_date'    => 'required|valid_date',
        ];

        $messages = [
            'week_number' => ['required' => 'Nomor minggu wajib diisi.', 'integer' => 'Nomor minggu harus angka.'],
            'start_date'  => ['required' => 'Tanggal awal wajib diisi.'],
            'end_date'    => ['required' => 'Tanggal akhir wajib diisi.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verify week_number is unique for this registration
        $existing = $db->table('logbook_weeks')
            ->where('registration_id', $registration->id)
            ->where('week_number', $this->request->getPost('week_number'))
            ->get()
            ->getRow();

        if ($existing) {
            return redirect()->back()->with('error', 'Minggu ke-' . $this->request->getPost('week_number') . ' sudah terdaftar.');
        }

        $weekData = [
            'uuid'            => bin2hex(random_bytes(16)),
            'registration_id' => $registration->id,
            'week_number'     => $this->request->getPost('week_number'),
            'start_date'      => $this->request->getPost('start_date'),
            'end_date'        => $this->request->getPost('end_date'),
            'weekly_target'   => $this->request->getPost('weekly_target'),
            'status'          => 'draft',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ];

        $db->table('logbook_weeks')->insert($weekData);

        return redirect()->to(base_url('mahasiswa/logbook'))->with('success', 'Minggu logbook berhasil ditambahkan.');
    }

    /**
     * Add daily entry to active week logs.
     */
    public function addLogbookEntry()
    {
        if (!$this->checkAccess('logbook')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        
        $rules = [
            'logbook_week_id'      => 'required|integer',
            'activity_date'        => 'required|valid_date',
            'start_time'           => 'required',
            'end_time'             => 'required',
            'activity_description' => 'required',
            'result_description'   => 'required',
        ];

        $messages = [
            'activity_date'        => ['required' => 'Tanggal kegiatan wajib diisi.'],
            'start_time'           => ['required' => 'Jam mulai wajib diisi.'],
            'end_time'             => ['required' => 'Jam selesai wajib diisi.'],
            'activity_description' => ['required' => 'Uraian kegiatan wajib diisi.'],
            'result_description'   => ['required' => 'Uraian hasil kegiatan wajib diisi.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $weekId = $this->request->getPost('logbook_week_id');
        $week = $db->table('logbook_weeks')->where('id', $weekId)->get()->getRow();

        if ($week->status === 'disetujui' || $week->status === 'terkunci') {
            return redirect()->back()->with('error', 'Logbook minggu ini sudah dikunci dan tidak dapat diedit.');
        }

        $entryData = [
            'logbook_week_id'      => $weekId,
            'activity_date'        => $this->request->getPost('activity_date'),
            'start_time'           => $this->request->getPost('start_time'),
            'end_time'             => $this->request->getPost('end_time'),
            'activity_description' => $this->request->getPost('activity_description'),
            'result_description'   => $this->request->getPost('result_description'),
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s')
        ];

        $db->table('logbook_daily_entries')->insert($entryData);

        return redirect()->to(base_url('mahasiswa/logbook'))->with('success', 'Kegiatan harian berhasil dicatat.');
    }

    /**
     * Submit weekly block to Dosen for review.
     */
    public function submitLogbookWeek($id)
    {
        if (!$this->checkAccess('logbook')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $week = $db->table('logbook_weeks')->where('id', $id)->get()->getRow();

        if (!$week) {
            return redirect()->back()->with('error', 'Data minggu tidak ditemukan.');
        }

        if ($week->status === 'disetujui' || $week->status === 'terkunci') {
            return redirect()->back()->with('error', 'Minggu ini sudah disetujui.');
        }

        $weekly_result = $this->request->getPost('weekly_result');
        $obstacle = $this->request->getPost('obstacle');
        $next_plan = $this->request->getPost('next_plan');

        $db->table('logbook_weeks')
            ->where('id', $id)
            ->update([
                'weekly_result' => $weekly_result,
                'obstacle'      => $obstacle,
                'next_plan'     => $next_plan,
                'status'        => 'dikirim',
                'submitted_at'  => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

        return redirect()->to(base_url('mahasiswa/logbook'))->with('success', 'Logbook mingguan berhasil dikirim untuk diperiksa Dosen.');
    }

    /**
     * Dosen comments and notes.
     */
    public function catatanDosen()
    {
        if (!$this->checkAccess('logbook')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        // Get reviews
        $reviews = $db->table('logbook_reviews')
            ->select('logbook_reviews.*, logbook_weeks.week_number, lecturer_profiles.full_name as lecturer_name')
            ->join('logbook_weeks', 'logbook_weeks.id = logbook_reviews.logbook_week_id')
            ->join('lecturer_profiles', 'lecturer_profiles.id = logbook_reviews.reviewed_by')
            ->where('logbook_weeks.registration_id', $registration->id)
            ->orderBy('logbook_reviews.id', 'DESC')
            ->get()
            ->getResult();

        $data = [
            'title'   => 'Catatan Review Dosen Pembimbing',
            'reviews' => $reviews
        ];

        return view('mahasiswa/catatan_dosen', $data);
    }

    /**
     * Final report upload form.
     */
    public function laporan()
    {
        if (!$this->checkAccess('laporan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Menu Laporan Akhir belum dibuka.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $laporan = $db->table('final_reports')
            ->where('registration_id', $registration->id)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRow();

        $data = [
            'title'    => 'Laporan Akhir KP/KPL',
            'laporan'  => $laporan
        ];

        return view('mahasiswa/laporan', $data);
    }

    /**
     * Upload final report PDF.
     */
    public function submitLaporan()
    {
        if (!$this->checkAccess('laporan')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $rules = [
            'title'       => 'required',
            'report_file' => 'uploaded[report_file]|max_size[report_file,15360]|ext_in[report_file,pdf]',
        ];

        $messages = [
            'title'       => ['required' => 'Judul laporan akhir wajib diisi.'],
            'report_file' => ['uploaded' => 'File laporan akhir wajib diunggah.', 'max_size' => 'Ukuran file laporan maksimal 15 MB.', 'ext_in' => 'Format file laporan harus PDF.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploadDir = WRITEPATH . 'uploads/kp-pkl/';
        $reportFile = $this->request->getFile('report_file');
        $reportStored = $reportFile->getRandomName();
        $reportFile->move($uploadDir, $reportStored);

        $db->transStart();

        // Check if report exists
        $existing = $db->table('final_reports')->where('registration_id', $registration->id)->get()->getRow();
        $ver = $existing ? ($existing->version + 1) : 1;

        $db->table('final_reports')->insert([
            'uuid'            => bin2hex(random_bytes(16)),
            'registration_id' => $registration->id,
            'uploaded_by'     => session()->get('user_id'),
            'title'           => $this->request->getPost('title'),
            'file_path'       => 'uploads/kp-pkl/' . $reportStored,
            'original_name'   => $reportFile->getClientName(),
            'stored_name'     => $reportStored,
            'file_ext'        => $reportFile->getClientExtension(),
            'file_size_kb'    => round($reportFile->getSize() / 1024),
            'version'         => $ver,
            'status'          => 'dikirim',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ]);
        $reportId = $db->insertID();

        // Also reference in student_documents
        $db->table('student_documents')->insert([
            'uuid'            => bin2hex(random_bytes(16)),
            'registration_id' => $registration->id,
            'uploaded_by'     => session()->get('user_id'),
            'document_name'   => 'Laporan Akhir KP/KPL',
            'document_code'   => 'laporan_akhir',
            'original_name'   => $reportFile->getClientName(),
            'stored_name'     => $reportStored,
            'file_path'       => 'uploads/kp-pkl/' . $reportStored,
            'file_ext'        => $reportFile->getClientExtension(),
            'file_size_kb'    => round($reportFile->getSize() / 1024),
            'mime_type'       => $reportFile->getMimeType(),
            'version'         => $ver,
            'status'          => 'menunggu_verifikasi',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Update registration status to waiting for report review
        $db->table('kp_registrations')
            ->where('id', $registration->id)
            ->update([
                'current_status' => 'laporan_akhir_dikirim',
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

        // Write status logs
        $db->table('registration_status_logs')->insert([
            'registration_id' => $registration->id,
            'old_status'      => $registration->current_status,
            'new_status'      => 'laporan_akhir_dikirim',
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'mahasiswa',
            'note'            => 'Mahasiswa mengupload berkas laporan akhir KP/KPL.',
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        // Log Audit
        AuditService::log(
            'UPLOAD_FINAL_REPORT',
            'final_reports',
            $reportId,
            null,
            null,
            'Mahasiswa mengunggah berkas laporan akhir'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses unggahan laporan. Silakan coba kembali.');
        }

        return redirect()->to(base_url('mahasiswa/dashboard'))->with('success', 'Laporan akhir berhasil diunggah.');
    }

    /**
     * Grades breakdowns view.
     */
    public function penilaian()
    {
        if (!$this->checkAccess('penilaian')) {
            return redirect()->to(base_url('mahasiswa/dashboard'))->with('error', 'Menu Penilaian belum dibuka.');
        }

        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $scores = $db->table('final_scores')
            ->where('registration_id', $registration->id)
            ->get()
            ->getRow();

        $breakdowns = $db->table('assessment_scores')
            ->select('assessment_scores.*, assessment_components.component_name, assessment_components.weight')
            ->join('assessment_components', 'assessment_components.id = assessment_scores.component_id')
            ->where('assessment_scores.registration_id', $registration->id)
            ->get()
            ->getResult();

        $data = [
            'title'      => 'Penilaian KP/KPL',
            'scores'     => $scores,
            'breakdowns' => $breakdowns
        ];

        return view('mahasiswa/penilaian', $data);
    }

    /**
     * Student history of previous activity registrations.
     */
    public function riwayat()
    {
        $db = \Config\Database::connect();
        list($profile, $registration) = $this->getStudentData();

        $history = [];
        if ($profile) {
            $history = $db->table('kp_registrations')
                ->select('kp_registrations.*, kp_periods.name as period_name, kp_periods.academic_year, kp_periods.activity_type')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->where('kp_registrations.student_id', $profile->id)
                ->orderBy('kp_registrations.id', 'DESC')
                ->get()
                ->getResult();
        }

        $data = [
            'title'   => 'Riwayat KP/KPL',
            'history' => $history
        ];

        return view('mahasiswa/riwayat', $data);
    }

    /**
     * In-system alerts / messages.
     */
    public function notifikasi()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        $notifications = $db->table('notifications')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->limit(100)
            ->get()
            ->getResult();

        // Mark as read
        $db->table('notifications')
            ->where('user_id', $userId)
            ->update([
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s')
            ]);

        $data = [
            'title'         => 'Notifikasi Saya',
            'notifications' => $notifications
        ];

        return view('mahasiswa/notifikasi', $data);
    }
}
