<?php

namespace App\Controllers\Instansi;

use App\Controllers\BaseController;
use App\Services\AuditService;

class InstansiController extends BaseController
{
    protected $db;
    protected $userId;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userId = (int) session()->get('user_id');
    }

    public function index()
    {
        $instansi = $this->getInstitutionProfile();
        $students = $instansi ? $this->getPlacedStudents($instansi->id, false) : [];
        $pendingPlacements = $instansi ? $this->countPlacementByStatus($instansi->id, ['disetujui']) : 0;
        $pendingScores = $instansi ? $this->countPendingScores($instansi->id) : 0;

        $stats = [
            'total_students'     => count($students),
            'pending_placements' => $pendingPlacements,
            'pending_scores'     => $pendingScores,
            'field_category'     => $instansi ? $instansi->field_category : 'Belum diisi',
            'partnership'        => $instansi ? $instansi->partnership_status : 'pending',
        ];

        $data = [
            'title'      => 'Dashboard Instansi Mitra',
            'stats'      => $stats,
            'students'   => $students,
        ];

        return view('instansi/index', $data);
    }

    public function profil()
    {
        return view('instansi/profil', [
            'title'    => 'Profil Instansi',
            'instansi' => $this->getInstitutionProfile(),
        ]);
    }

    public function updateProfil()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->back();
        }

        $rules = [
            'name'             => 'required|min_length[3]',
            'field_category'   => 'permit_empty|max_length[150]',
            'address'          => 'required|min_length[10]',
            'city'             => 'permit_empty|max_length[100]',
            'province'         => 'permit_empty|max_length[100]',
            'contact_person'   => 'permit_empty|max_length[150]',
            'contact_position' => 'permit_empty|max_length[100]',
            'contact_phone'    => 'permit_empty|max_length[30]',
            'contact_email'    => 'permit_empty|valid_email|max_length[150]',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama instansi wajib diisi.',
                'min_length' => 'Nama instansi minimal 3 karakter.',
            ],
            'address' => [
                'required'   => 'Alamat instansi wajib diisi.',
                'min_length' => 'Alamat instansi minimal 10 karakter.',
            ],
            'contact_email' => [
                'valid_email' => 'Email narahubung tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $payload = [
            'name'             => $this->request->getPost('name'),
            'field_category'   => $this->request->getPost('field_category'),
            'address'          => $this->request->getPost('address'),
            'city'             => $this->request->getPost('city'),
            'province'         => $this->request->getPost('province'),
            'contact_person'   => $this->request->getPost('contact_person'),
            'contact_position' => $this->request->getPost('contact_position'),
            'contact_phone'    => $this->request->getPost('contact_phone'),
            'contact_email'    => $this->request->getPost('contact_email'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $this->db->table('institution_profiles')->where('id', $instansi->id)->update($payload);
        AuditService::log('UPDATE_INSTITUTION_PROFILE', 'institution_profiles', $instansi->id, (array) $instansi, $payload, 'Instansi memperbarui profil.');

        return redirect()->to(base_url('instansi/profil'))->with('success', 'Profil instansi berhasil diperbarui.');
    }

    public function konfirmasi()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        return view('instansi/konfirmasi', [
            'title'      => 'Konfirmasi Penerimaan',
            'placements' => $this->getPlacementRows($instansi->id, ['disetujui', 'diterima_instansi', 'ditolak_instansi']),
        ]);
    }

    public function submitKonfirmasi($placementId)
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        $placement = $this->getOwnedPlacement((int) $placementId, $instansi->id);
        if (!$placement) {
            return redirect()->to(base_url('instansi/konfirmasi'))->with('error', 'Data penempatan tidak ditemukan atau bukan milik instansi Anda.');
        }

        $decision = $this->request->getPost('decision');
        $note = trim((string) $this->request->getPost('note'));

        if (!in_array($decision, ['terima', 'tolak'], true)) {
            return redirect()->back()->with('error', 'Keputusan penerimaan tidak valid.');
        }

        if ($decision === 'tolak' && $note === '') {
            return redirect()->back()->with('error', 'Alasan penolakan wajib diisi.');
        }

        $newPlacementStatus = $decision === 'terima' ? 'diterima_instansi' : 'ditolak_instansi';
        $newRegistrationStatus = $decision === 'terima' ? 'diterima_instansi' : 'penempatan_revisi';
        $now = date('Y-m-d H:i:s');

        $this->db->transStart();
        $this->db->table('placement_requests')->where('id', $placement->id)->update([
            'status'      => $newPlacementStatus,
            'review_note' => $note ?: 'Penerimaan dikonfirmasi oleh instansi.',
            'reviewed_at' => $now,
            'updated_at'  => $now,
        ]);
        $this->db->table('kp_registrations')->where('id', $placement->registration_id)->update([
            'current_status' => $newRegistrationStatus,
            'updated_at'     => $now,
        ]);
        $this->db->table('registration_status_logs')->insert([
            'registration_id'  => $placement->registration_id,
            'old_status'       => $placement->current_status,
            'new_status'       => $newRegistrationStatus,
            'changed_by'       => $this->userId,
            'changed_by_role'  => 'instansi',
            'note'             => $note ?: 'Penerimaan mahasiswa dikonfirmasi oleh instansi.',
            'created_at'       => $now,
        ]);
        $this->db->transComplete();

        AuditService::log('CONFIRM_INSTITUTION_ACCEPTANCE', 'placement_requests', $placement->id, (array) $placement, [
            'status' => $newPlacementStatus,
            'registration_status' => $newRegistrationStatus,
        ], 'Instansi mengonfirmasi penerimaan mahasiswa.');

        return redirect()->to(base_url('instansi/konfirmasi'))->with('success', 'Konfirmasi penerimaan berhasil disimpan.');
    }

    public function mahasiswa()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        return view('instansi/mahasiswa', [
            'title'    => 'Mahasiswa KP/KPL',
            'students' => $this->getPlacedStudents($instansi->id, false),
        ]);
    }

    public function pembimbing()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        return view('instansi/pembimbing', [
            'title'    => 'Pembimbing Lapangan',
            'students' => $this->getPlacedStudents($instansi->id, false),
            'instansi' => $instansi,
        ]);
    }

    public function logbook()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        return view('instansi/logbook', [
            'title'    => 'Logbook Mahasiswa',
            'logbooks' => $this->getLogbookRows($instansi->id),
        ]);
    }

    public function validasiLogbook()
    {
        return $this->logbook();
    }

    public function reviewLogbook($weekId)
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        $week = $this->getOwnedLogbookWeek((int) $weekId, $instansi->id);
        if (!$week) {
            return redirect()->to(base_url('instansi/logbook'))->with('error', 'Logbook tidak ditemukan atau bukan milik instansi Anda.');
        }

        $entries = $this->db->table('logbook_daily_entries')
            ->where('logbook_week_id', $week->id)
            ->orderBy('activity_date', 'ASC')
            ->get()
            ->getResultArray();

        $reviews = $this->db->table('logbook_reviews')
            ->where('logbook_week_id', $week->id)
            ->orderBy('reviewed_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('instansi/review_logbook', [
            'title'   => 'Review Logbook Mahasiswa',
            'week'    => $week,
            'entries' => $entries,
            'reviews' => $reviews,
        ]);
    }

    public function submitReviewLogbook($weekId)
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        $week = $this->getOwnedLogbookWeek((int) $weekId, $instansi->id);
        if (!$week) {
            return redirect()->to(base_url('instansi/logbook'))->with('error', 'Logbook tidak ditemukan atau bukan milik instansi Anda.');
        }

        $status = $this->request->getPost('status');
        $comment = trim((string) $this->request->getPost('comment'));

        if (!in_array($status, ['disetujui', 'perlu_revisi'], true)) {
            return redirect()->back()->with('error', 'Status review logbook tidak valid.');
        }

        if ($status === 'perlu_revisi' && $comment === '') {
            return redirect()->back()->with('error', 'Catatan revisi wajib diisi.');
        }

        $now = date('Y-m-d H:i:s');
        $this->db->table('logbook_reviews')->insert([
            'logbook_week_id' => $week->id,
            'reviewed_by'     => $this->userId,
            'status'          => $status,
            'comment'         => $comment,
            'reviewed_at'     => $now,
            'created_at'      => $now,
        ]);

        AuditService::log('REVIEW_LOGBOOK_BY_INSTITUTION', 'logbook_weeks', $week->id, ['status' => $week->status], [
            'status' => $status,
            'comment' => $comment,
        ], 'Instansi memberi validasi opsional logbook.');

        return redirect()->to(base_url('instansi/logbook'))->with('success', 'Review logbook berhasil disimpan.');
    }

    public function evaluasi()
    {
        return $this->penilaian();
    }

    public function penilaian()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        return view('instansi/penilaian', [
            'title'    => 'Input Nilai Instansi',
            'students' => $this->getPlacedStudents($instansi->id, true),
        ]);
    }

    public function inputPenilaian($registrationId)
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        $student = $this->getOwnedRegistration((int) $registrationId, $instansi->id);
        if (!$student) {
            return redirect()->to(base_url('instansi/penilaian'))->with('error', 'Mahasiswa tidak ditemukan atau bukan penempatan instansi Anda.');
        }

        $template = $this->getAssessmentTemplate((int) $student->period_id);
        $components = $template ? $this->db->table('assessment_components')
            ->where('assessment_template_id', $template->id)
            ->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray() : [];

        $existing = $this->db->table('assessment_scores')
            ->where('registration_id', $student->registration_id)
            ->where('assessor_role', 'instansi')
            ->get()
            ->getResultArray();

        return view('instansi/input_penilaian', [
            'title'      => 'Input Nilai Instansi',
            'student'    => $student,
            'template'   => $template,
            'components' => $components,
            'existing'   => array_column($existing, null, 'component_id'),
        ]);
    }

    public function submitPenilaian($registrationId)
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        $student = $this->getOwnedRegistration((int) $registrationId, $instansi->id);
        if (!$student) {
            return redirect()->to(base_url('instansi/penilaian'))->with('error', 'Mahasiswa tidak ditemukan atau bukan penempatan instansi Anda.');
        }

        $template = $this->getAssessmentTemplate((int) $student->period_id);
        if (!$template) {
            return redirect()->back()->with('error', 'Template penilaian instansi belum tersedia.');
        }

        $components = $this->db->table('assessment_components')
            ->where('assessment_template_id', $template->id)
            ->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($components)) {
            return redirect()->back()->with('error', 'Komponen penilaian instansi belum tersedia.');
        }

        $scores = $this->request->getPost('scores') ?? [];
        $notes = $this->request->getPost('notes') ?? [];
        $weightedTotal = 0.0;
        $weightTotal = 0.0;
        $now = date('Y-m-d H:i:s');

        foreach ($components as $component) {
            $componentId = (int) $component['id'];
            if (!isset($scores[$componentId]) || $scores[$componentId] === '') {
                return redirect()->back()->withInput()->with('error', 'Semua komponen nilai wajib diisi.');
            }

            $score = (float) $scores[$componentId];
            if ($score < 0 || $score > (float) $component['max_score']) {
                return redirect()->back()->withInput()->with('error', 'Nilai komponen harus berada pada rentang 0 sampai ' . $component['max_score'] . '.');
            }

            $weightedTotal += $score * ((float) $component['weight']);
            $weightTotal += (float) $component['weight'];
        }

        $institutionScore = $weightTotal > 0 ? round($weightedTotal / $weightTotal, 2) : 0.0;

        $this->db->transStart();
        $this->db->table('assessment_scores')
            ->where('registration_id', $student->registration_id)
            ->where('assessor_role', 'instansi')
            ->delete();

        foreach ($components as $component) {
            $componentId = (int) $component['id'];
            $this->db->table('assessment_scores')->insert([
                'registration_id'         => $student->registration_id,
                'assessment_template_id'  => $template->id,
                'component_id'            => $componentId,
                'component_name'          => $component['component_name'],
                'assessor_user_id'        => $this->userId,
                'assessor_role'           => 'instansi',
                'score'                   => (float) $scores[$componentId],
                'note'                    => $notes[$componentId] ?? null,
                'status'                  => 'submitted',
                'created_at'              => $now,
                'updated_at'              => $now,
            ]);
        }

        $finalScore = $this->db->table('final_scores')->where('registration_id', $student->registration_id)->get()->getRow();
        $lecturerScore = $finalScore ? $finalScore->lecturer_score : null;
        $adminScore = $finalScore ? ($finalScore->admin_score ?? 0) : 0;
        $combinedScore = $this->calculateCombinedScore($institutionScore, $lecturerScore, $adminScore);
        $scoreStatus = $lecturerScore !== null ? 'menunggu_validasi' : 'draft';

        $finalPayload = [
            'institution_score' => $institutionScore,
            'lecturer_score'    => $lecturerScore,
            'admin_score'       => $adminScore,
            'final_score'       => $combinedScore,
            'final_grade'       => $this->grade($combinedScore),
            'weight_snapshot'   => json_encode(['instansi' => 40, 'dosen' => 50, 'admin_logbook' => 10]),
            'status'            => $scoreStatus,
            'updated_at'        => $now,
        ];

        if ($finalScore) {
            $this->db->table('final_scores')->where('registration_id', $student->registration_id)->update($finalPayload);
        } else {
            $finalPayload['registration_id'] = $student->registration_id;
            $finalPayload['created_at'] = $now;
            $this->db->table('final_scores')->insert($finalPayload);
        }

        $newStatus = $lecturerScore !== null ? 'menunggu_validasi_akhir' : 'nilai_instansi_masuk';
        $this->db->table('kp_registrations')->where('id', $student->registration_id)->update([
            'current_status' => $newStatus,
            'updated_at'     => $now,
        ]);
        $this->db->table('registration_status_logs')->insert([
            'registration_id' => $student->registration_id,
            'old_status' => $student->current_status,
            'new_status' => $newStatus,
            'changed_by' => $this->userId,
            'changed_by_role' => 'instansi',
            'note' => 'Nilai instansi telah dikirim.',
            'created_at' => $now,
        ]);
        $this->db->transComplete();

        AuditService::log('SUBMIT_INSTITUTION_SCORE', 'assessment_scores', $student->registration_id, null, [
            'institution_score' => $institutionScore,
            'registration_status' => $newStatus,
        ], 'Instansi mengirim nilai evaluasi mahasiswa.');

        return redirect()->to(base_url('instansi/penilaian'))->with('success', 'Nilai instansi berhasil disimpan.');
    }

    public function dokumen()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        $documents = $this->db->table('generated_documents')
            ->select('generated_documents.*, student_profiles.full_name, student_profiles.npm')
            ->join('kp_registrations', 'kp_registrations.id = generated_documents.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('placement_requests', 'placement_requests.registration_id = kp_registrations.id')
            ->where('placement_requests.institution_id', $instansi->id)
            ->whereIn('placement_requests.status', ['disetujui', 'diterima_instansi'])
            ->orderBy('generated_documents.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('instansi/dokumen', [
            'title'     => 'Dokumen Terkait',
            'documents' => $documents,
        ]);
    }

    public function riwayat()
    {
        $instansi = $this->requireInstitutionProfile();
        if (!$instansi) {
            return redirect()->to(base_url('instansi/dashboard'));
        }

        return view('instansi/riwayat', [
            'title'    => 'Riwayat Mahasiswa',
            'students' => $this->getPlacedStudents($instansi->id, false, true),
        ]);
    }

    public function notifikasi()
    {
        return view('instansi/notifikasi', [
            'title' => 'Notifikasi',
        ]);
    }

    private function getInstitutionProfile()
    {
        return $this->db->table('institution_profiles')
            ->where('user_id', $this->userId)
            ->where('deleted_at', null)
            ->get()
            ->getRow();
    }

    private function requireInstitutionProfile()
    {
        $instansi = $this->getInstitutionProfile();
        if (!$instansi) {
            session()->setFlashdata('error', 'Profil instansi belum terhubung dengan akun ini. Hubungi Admin untuk verifikasi akun.');
            return null;
        }

        return $instansi;
    }

    private function getPlacementRows($institutionId, array $statuses)
    {
        return $this->db->table('placement_requests')
            ->select('placement_requests.*, kp_registrations.current_status, student_profiles.full_name, student_profiles.npm, student_profiles.phone, study_programs.name as prodi_name, kp_periods.name as period_name')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id', 'left')
            ->where('placement_requests.institution_id', $institutionId)
            ->whereIn('placement_requests.status', $statuses)
            ->orderBy('placement_requests.updated_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function getPlacedStudents($institutionId, $withScores = false, $includeHistory = false)
    {
        $builder = $this->db->table('placement_requests')
            ->select('placement_requests.id as placement_id, placement_requests.status as placement_status, kp_registrations.id as registration_id, kp_registrations.current_status, kp_registrations.period_id, student_profiles.full_name, student_profiles.npm, student_profiles.phone, student_profiles.address, study_programs.name as prodi_name, kp_periods.name as period_name, lecturer_profiles.full_name as lecturer_name');

        if ($withScores) {
            $builder->select('final_scores.institution_score, final_scores.lecturer_score, final_scores.final_score, final_scores.final_grade, final_scores.status as score_status');
        }

        $builder->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id', 'left')
            ->join('supervisor_assignments', "supervisor_assignments.registration_id = kp_registrations.id AND supervisor_assignments.status = 'active'", 'left')
            ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id', 'left');

        if ($withScores) {
            $builder->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left');
        }

        return $builder
            ->where('placement_requests.institution_id', $institutionId)
            ->whereIn('placement_requests.status', $includeHistory ? ['disetujui', 'diterima_instansi', 'ditolak_instansi'] : ['disetujui', 'diterima_instansi'])
            ->orderBy('student_profiles.full_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function getOwnedPlacement($placementId, $institutionId)
    {
        return $this->db->table('placement_requests')
            ->select('placement_requests.*, kp_registrations.current_status')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->where('placement_requests.id', $placementId)
            ->where('placement_requests.institution_id', $institutionId)
            ->get()
            ->getRow();
    }

    private function getOwnedRegistration($registrationId, $institutionId)
    {
        return $this->db->table('placement_requests')
            ->select('placement_requests.id as placement_id, kp_registrations.id as registration_id, kp_registrations.period_id, kp_registrations.current_status, student_profiles.full_name, student_profiles.npm, study_programs.name as prodi_name, kp_periods.name as period_name')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id', 'left')
            ->where('kp_registrations.id', $registrationId)
            ->where('placement_requests.institution_id', $institutionId)
            ->whereIn('placement_requests.status', ['disetujui', 'diterima_instansi'])
            ->get()
            ->getRow();
    }

    private function getLogbookRows($institutionId)
    {
        return $this->db->table('logbook_weeks')
            ->select('logbook_weeks.*, student_profiles.full_name, student_profiles.npm, kp_registrations.current_status')
            ->join('kp_registrations', 'kp_registrations.id = logbook_weeks.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('placement_requests', 'placement_requests.registration_id = kp_registrations.id')
            ->where('placement_requests.institution_id', $institutionId)
            ->whereIn('placement_requests.status', ['disetujui', 'diterima_instansi'])
            ->orderBy('logbook_weeks.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function getOwnedLogbookWeek($weekId, $institutionId)
    {
        return $this->db->table('logbook_weeks')
            ->select('logbook_weeks.*, student_profiles.full_name, student_profiles.npm')
            ->join('kp_registrations', 'kp_registrations.id = logbook_weeks.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('placement_requests', 'placement_requests.registration_id = kp_registrations.id')
            ->where('logbook_weeks.id', $weekId)
            ->where('placement_requests.institution_id', $institutionId)
            ->whereIn('placement_requests.status', ['disetujui', 'diterima_instansi'])
            ->get()
            ->getRow();
    }

    private function getAssessmentTemplate($periodId)
    {
        $template = $this->db->table('assessment_templates')
            ->where('assessment_type', 'instansi')
            ->where('period_id', $periodId)
            ->where('status', 'active')
            ->orderBy('version', 'DESC')
            ->get()
            ->getRow();

        if ($template) {
            return $template;
        }

        return $this->db->table('assessment_templates')
            ->where('assessment_type', 'instansi')
            ->where('period_id', null)
            ->where('status', 'active')
            ->orderBy('version', 'DESC')
            ->get()
            ->getRow();
    }

    private function countPlacementByStatus($institutionId, array $statuses)
    {
        return $this->db->table('placement_requests')
            ->where('institution_id', $institutionId)
            ->whereIn('status', $statuses)
            ->countAllResults();
    }

    private function countPendingScores($institutionId)
    {
        return count(array_filter($this->getPlacedStudents($institutionId, true), static function ($student) {
            return empty($student['institution_score']);
        }));
    }

    private function calculateCombinedScore($institutionScore, $lecturerScore, $adminScore)
    {
        $institution = $institutionScore !== null ? (float) $institutionScore : 0.0;
        $lecturer = $lecturerScore !== null ? (float) $lecturerScore : 0.0;
        $admin = $adminScore !== null ? (float) $adminScore : 0.0;

        return round(($institution * 0.4) + ($lecturer * 0.5) + ($admin * 0.1), 2);
    }

    private function grade($score)
    {
        if ($score >= 85) {
            return 'A';
        }
        if ($score >= 75) {
            return 'B';
        }
        if ($score >= 65) {
            return 'C';
        }
        if ($score >= 55) {
            return 'D';
        }
        return 'E';
    }
}
