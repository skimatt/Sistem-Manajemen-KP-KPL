<?php

namespace App\Controllers\Koordinator;

use App\Controllers\BaseController;
use App\Models\RegistrationModel;
use App\Models\StudentProfileModel;
use App\Models\StudentDocumentModel;
use App\Models\PlacementRequestModel;
use App\Models\LecturerProfileModel;
use App\Models\InstitutionProfileModel;
use App\Models\FinalReportModel;
use App\Models\FinalScoreModel;
use App\Models\AssessmentScoreModel;
use App\Models\PeriodModel;
use App\Services\TopsisService;
use App\Services\AuditService;

class AkademikController extends BaseController
{
    protected $registrationModel;
    protected $studentProfileModel;
    protected $documentModel;
    protected $placementModel;
    protected $lecturerModel;
    protected $institutionModel;
    protected $finalReportModel;
    protected $finalScoreModel;
    protected $assessmentScoreModel;
    protected $periodModel;

    public function __construct()
    {
        $this->registrationModel    = new RegistrationModel();
        $this->studentProfileModel  = new StudentProfileModel();
        $this->documentModel        = new StudentDocumentModel();
        $this->placementModel       = new PlacementRequestModel();
        $this->lecturerModel        = new LecturerProfileModel();
        $this->institutionModel     = new InstitutionProfileModel();
        $this->finalReportModel     = new FinalReportModel();
        $this->finalScoreModel      = new FinalScoreModel();
        $this->assessmentScoreModel = new AssessmentScoreModel();
        $this->periodModel          = new PeriodModel();
    }

    // ==========================================
    // 1. Validasi Registrasi
    // ==========================================
    public function validasiRegistrasi()
    {
        $db = \Config\Database::connect();
        $registrations = $db->table('kp_registrations')
            ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->where('kp_registrations.deleted_at', null)
            ->orderBy('kp_registrations.current_status = \'menunggu_verifikasi\'', 'DESC', false)
            ->orderBy('kp_registrations.updated_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'         => 'Validasi Registrasi Mahasiswa',
            'registrations' => $registrations,
        ];

        return view('koordinator/akademik/validasi-registrasi/index', $data);
    }

    public function reviewRegistrasi($id)
    {
        $registration = $this->registrationModel->getRegistrationDetails($id);
        if (!$registration) {
            return redirect()->to(base_url('koordinator/validasi-registrasi'))->with('error', 'Registrasi tidak ditemukan.');
        }

        // Fetch uploaded documents
        $documents = $this->documentModel->getDocumentsByRegistration($id);

        $data = [
            'title'        => 'Detail & Review Registrasi Mahasiswa',
            'registration' => $registration,
            'documents'    => $documents,
        ];

        return view('koordinator/akademik/validasi-registrasi/review', $data);
    }

    public function submitRegistrasi($id)
    {
        $registration = $this->registrationModel->find($id);
        if (!$registration) {
            return redirect()->to(base_url('koordinator/validasi-registrasi'))->with('error', 'Registrasi tidak ditemukan.');
        }

        $rules = [
            'status'     => 'required|in_list[registrasi_disetujui,revisi_registrasi,registrasi_ditolak]',
            'final_note' => 'permit_empty|string',
        ];

        $messages = [
            'status' => [
                'required' => 'Keputusan verifikasi registrasi wajib dipilih.',
                'in_list'  => 'Pilihan keputusan tidak valid.',
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $finalNote = $this->request->getPost('final_note');

        if (in_array($status, ['revisi_registrasi', 'registrasi_ditolak']) && empty(trim($finalNote))) {
            return redirect()->back()->withInput()->with('error', 'Catatan/alasan wajib diisi apabila status registrasi direvisi atau ditolak.');
        }

        $oldValues = [
            'current_status' => $registration['current_status'],
            'final_note'     => $registration['final_note'],
            'verified_by'    => $registration['verified_by'],
            'verified_at'    => $registration['verified_at'],
        ];

        $updateData = [
            'current_status' => $status,
            'final_note'     => $finalNote ?: null,
            'verified_by'    => session()->get('user_id'),
            'verified_at'    => date('Y-m-d H:i:s'),
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $this->registrationModel->update($id, $updateData);

        // Save status log in registration_status_logs
        $db->table('registration_status_logs')->insert([
            'registration_id' => $id,
            'old_status'      => $registration['current_status'],
            'new_status'      => $status,
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'koordinator',
            'note'            => $finalNote ?: null,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Kesalahan internal saat menyimpan validasi registrasi.');
        }

        // Audit Log
        $student = $this->studentProfileModel->find($registration['student_id']);
        AuditService::log(
            'VALIDATE_REGISTRATION',
            'kp_registrations',
            $id,
            $oldValues,
            $updateData,
            "Memvalidasi registrasi mahasiswa '" . ($student['full_name'] ?? $id) . "' dengan status: " . strtoupper($status)
        );

        return redirect()->to(base_url('koordinator/validasi-registrasi'))->with('success', 'Validasi registrasi mahasiswa berhasil diperbarui.');
    }

    // ==========================================
    // 2. Pengajuan Penempatan (Mitra)
    // ==========================================
    public function pengajuanPenempatan()
    {
        $db = \Config\Database::connect();
        $placements = $db->table('placement_requests')
            ->select('placement_requests.*, student_profiles.npm, student_profiles.full_name, kp_periods.name as period_name, institution_profiles.name as instansi_name')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->where('placement_requests.placement_type', 'mitra')
            ->where('placement_requests.deleted_at', null)
            ->orderBy('placement_requests.status = \'diajukan\'', 'DESC', false)
            ->orderBy('placement_requests.updated_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'      => 'Pengajuan Penempatan Mitra Kampus',
            'placements' => $placements,
        ];

        return view('koordinator/akademik/pengajuan-penempatan/index', $data);
    }

    public function reviewPenempatan($id)
    {
        $db = \Config\Database::connect();
        $placement = $db->table('placement_requests')
            ->select('placement_requests.*, student_profiles.npm, student_profiles.full_name, student_profiles.academic_gpa, study_programs.name as prodi_name, kp_periods.name as period_name, institution_profiles.name as instansi_name, institution_profiles.field as instansi_field, institution_profiles.address as instansi_address')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->where('placement_requests.id', $id)
            ->get()
            ->getRowArray();

        if (!$placement) {
            return redirect()->to(base_url('koordinator/pengajuan-penempatan'))->with('error', 'Data pengajuan penempatan tidak ditemukan.');
        }

        // Get choices priority
        $choices = $db->table('placement_choices')
            ->where('registration_id', $placement['registration_id'])
            ->orderBy('priority_order', 'ASC')
            ->get()
            ->getResultArray();

        // Get TOPSIS rank if calculated
        $topsisRank = $db->table('topsis_results')
            ->where('registration_id', $placement['registration_id'])
            ->where('institution_id', $placement['institution_id'])
            ->get()
            ->getRowArray();

        $data = [
            'title'      => 'Review Pengajuan Penempatan Mitra',
            'placement'  => $placement,
            'choices'    => $choices,
            'topsisRank' => $topsisRank,
        ];

        return view('koordinator/akademik/pengajuan-penempatan/review', $data);
    }

    public function submitPenempatan($id)
    {
        $placement = $this->placementModel->find($id);
        if (!$placement) {
            return redirect()->to(base_url('koordinator/pengajuan-penempatan'))->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'status'      => 'required|in_list[disetujui,perlu_revisi,ditolak]',
            'review_note' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $reviewNote = $this->request->getPost('review_note');

        if (in_array($status, ['perlu_revisi', 'ditolak']) && empty(trim($reviewNote))) {
            return redirect()->back()->withInput()->with('error', 'Catatan review wajib diisi jika status penempatan direvisi atau ditolak.');
        }

        $db = \Config\Database::connect();
        $registration = $this->registrationModel->find($placement['registration_id']);

        if ($status === 'disetujui') {
            // Check quota availability for the period
            $quotaRow = $db->table('institution_quotas')
                ->where('period_id', $registration['period_id'])
                ->where('institution_id', $placement['institution_id'])
                ->get()
                ->getRowArray();
            
            if ($quotaRow) {
                $rem = intval($quotaRow['quota_total']) - intval($quotaRow['quota_used']);
                if ($rem <= 0) {
                    // Check if quota override check was marked
                    $override = $this->request->getPost('override_quota');
                    if (!$override) {
                        return redirect()->back()->withInput()->with('error', 'Kuota instansi terpilih sudah penuh untuk periode ini. Centang "Buka kuota khusus/override" jika ingin menyetujui di luar batas kuota.');
                    }
                }
            }
        }

        $db->transStart();

        // 1. Update placement request status
        $this->placementModel->update($id, [
            'status'      => $status,
            'review_note' => $reviewNote ?: null,
            'reviewed_by' => session()->get('user_id'),
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);

        // 2. Update student registration status
        $regStatus = 'penempatan_diajukan';
        if ($status === 'disetujui') {
            $regStatus = 'penempatan_disetujui';
            
            // Increment quota used
            $db->table('institution_quotas')
                ->where('period_id', $registration['period_id'])
                ->where('institution_id', $placement['institution_id'])
                ->increment('quota_used', 1);

            // Mark the selected choice in placement_choices
            $db->table('placement_choices')
                ->where('registration_id', $placement['registration_id'])
                ->update(['is_selected' => 0]);

            $db->table('placement_choices')
                ->where('registration_id', $placement['registration_id'])
                ->where('institution_id', $placement['institution_id'])
                ->update(['is_selected' => 1]);
        } elseif ($status === 'perlu_revisi') {
            $regStatus = 'penempatan_revisi';
        } elseif ($status === 'ditolak') {
            $regStatus = 'penempatan_ditolak';
        }

        $this->registrationModel->update($placement['registration_id'], [
            'current_status' => $regStatus
        ]);

        // Status logs
        $db->table('registration_status_logs')->insert([
            'registration_id' => $placement['registration_id'],
            'old_status'      => $registration['current_status'],
            'new_status'      => $regStatus,
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'koordinator',
            'note'            => 'Penempatan: ' . ($reviewNote ?: strtoupper($status)),
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses keputusan penempatan.');
        }

        // Audit Log
        AuditService::log(
            'VALIDATE_PLACEMENT',
            'placement_requests',
            $id,
            $placement,
            ['status' => $status, 'review_note' => $reviewNote],
            "Memvalidasi penempatan instansi mahasiswa dengan keputusan: " . strtoupper($status)
        );

        return redirect()->to(base_url('koordinator/pengajuan-penempatan'))->with('success', 'Keputusan penempatan berhasil disimpan.');
    }

    // ==========================================
    // 3. Rekomendasi TOPSIS
    // ==========================================
    public function topsis()
    {
        $db = \Config\Database::connect();
        
        // Fetch active periods
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        // Fetch students under registrations in the period who need placement
        $students = [];
        if ($selectedPeriodId) {
            $students = $db->table('kp_registrations')
                ->select('kp_registrations.id, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_registrations.current_status,
                          (SELECT COUNT(*) FROM topsis_results WHERE topsis_results.registration_id = kp_registrations.id) as has_topsis')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->whereIn('kp_registrations.current_status', ['registrasi_disetujui', 'penempatan_diajukan', 'penempatan_revisi', 'penempatan_ditolak', 'penempatan_disetujui'])
                ->where('kp_registrations.deleted_at', null)
                ->get()
                ->getResultArray();
        }

        $selectedRegId = $this->request->getVar('registration_id');
        $topsisData = null;
        $topsisError = null;
        $studentSelected = null;
        $rawScoresList = [];

        if ($selectedRegId) {
            $studentSelected = $this->registrationModel->getRegistrationDetails($selectedRegId);
            try {
                // Generate calculations
                $topsisData = TopsisService::calculate($selectedRegId);
                
                // Fetch raw scores to let the Koordinator edit them in UI if desired
                $rawScoresList = $db->table('topsis_scores')
                    ->select('topsis_scores.*, topsis_criteria.code, topsis_criteria.name as crit_name, institution_profiles.name as inst_name')
                    ->join('topsis_criteria', 'topsis_criteria.id = topsis_scores.criteria_id')
                    ->join('institution_profiles', 'institution_profiles.id = topsis_scores.institution_id')
                    ->where('topsis_scores.registration_id', $selectedRegId)
                    ->orderBy('topsis_scores.institution_id', 'ASC')
                    ->orderBy('topsis_criteria.code', 'ASC')
                    ->get()
                    ->getResultArray();

            } catch (\Exception $e) {
                $topsisError = $e->getMessage();
            }
        }

        $data = [
            'title'            => 'Rekomendasi Penempatan TOPSIS',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'students'         => $students,
            'selectedRegId'    => $selectedRegId,
            'studentSelected'  => $studentSelected,
            'topsisData'       => $topsisData,
            'rawScoresList'    => $rawScoresList,
            'topsisError'      => $topsisError,
        ];

        return view('koordinator/akademik/topsis/index', $data);
    }

    public function calculateTopsis($id)
    {
        try {
            TopsisService::calculate($id);
            return redirect()->to(base_url('koordinator/topsis?registration_id=' . $id))->with('success', 'Rekomendasi TOPSIS berhasil dihitung ulang.');
        } catch (\Exception $e) {
            return redirect()->to(base_url('koordinator/topsis?registration_id=' . $id))->with('error', 'Gagal menghitung TOPSIS: ' . $e->getMessage());
        }
    }

    public function saveTopsisScores($id)
    {
        $scoresInput = $this->request->getPost('scores'); // array [inst_id][crit_id] = score
        if (empty($scoresInput) || !is_array($scoresInput)) {
            return redirect()->back()->with('error', 'Data nilai input tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($scoresInput as $instId => $critArray) {
            foreach ($critArray as $critId => $score) {
                // Check if exists
                $existing = $db->table('topsis_scores')
                    ->where('registration_id', $id)
                    ->where('institution_id', $instId)
                    ->where('criteria_id', $critId)
                    ->get()
                    ->getRow();

                if ($existing) {
                    $db->table('topsis_scores')
                        ->where('id', $existing->id)
                        ->update([
                            'score'      => floatval($score),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    $db->table('topsis_scores')->insert([
                        'registration_id' => $id,
                        'institution_id'  => $instId,
                        'criteria_id'     => $critId,
                        'score'           => floatval($score),
                        'created_at'      => date('Y-m-d H:i:s'),
                        'updated_at'      => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memperbarui matriks keputusan TOPSIS.');
        }

        // Recalculate
        try {
            TopsisService::calculate($id);
            return redirect()->to(base_url('koordinator/topsis?registration_id=' . $id))->with('success', 'Nilai matriks berhasil disimpan dan rekomendasi TOPSIS telah dihitung ulang.');
        } catch (\Exception $e) {
            return redirect()->to(base_url('koordinator/topsis?registration_id=' . $id))->with('warning', 'Nilai tersimpan, tetapi gagal menghitung ulang TOPSIS: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 4. Validasi Tempat Mandiri
    // ==========================================
    public function validasiMandiri()
    {
        $db = \Config\Database::connect();
        $placements = $db->table('placement_requests')
            ->select('placement_requests.*, student_profiles.npm, student_profiles.full_name, kp_periods.name as period_name')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->where('placement_requests.placement_type', 'mandiri')
            ->where('placement_requests.deleted_at', null)
            ->orderBy('placement_requests.status = \'diajukan\'', 'DESC', false)
            ->orderBy('placement_requests.updated_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'      => 'Validasi Tempat Mandiri (Mahasiswa)',
            'placements' => $placements,
        ];

        return view('koordinator/akademik/validasi-mandiri/index', $data);
    }

    public function reviewMandiri($id)
    {
        $db = \Config\Database::connect();
        $placement = $db->table('placement_requests')
            ->select('placement_requests.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->where('placement_requests.id', $id)
            ->get()
            ->getRowArray();

        if (!$placement) {
            return redirect()->to(base_url('koordinator/validasi-mandiri'))->with('error', 'Data pengajuan tempat mandiri tidak ditemukan.');
        }

        // Fetch proposed document acceptance stempel (if uploaded as a student document)
        $acceptanceDoc = $db->table('student_documents')
            ->where('registration_id', $placement['registration_id'])
            ->where('document_code', 'surat_penerimaan_mandiri')
            ->get()
            ->getRowArray();

        $data = [
            'title'         => 'Review Kelayakan Tempat Mandiri',
            'placement'     => $placement,
            'acceptanceDoc' => $acceptanceDoc,
        ];

        return view('koordinator/akademik/validasi-mandiri/review', $data);
    }

    public function submitMandiri($id)
    {
        $placement = $this->placementModel->find($id);
        if (!$placement) {
            return redirect()->to(base_url('koordinator/validasi-mandiri'))->with('error', 'Data tidak ditemukan.');
        }

        $registration = $this->registrationModel->find($placement['registration_id']);
        if (!$registration) {
            return redirect()->to(base_url('koordinator/validasi-mandiri'))->with('error', 'Data registrasi tidak ditemukan.');
        }

        $rules = [
            'status'      => 'required|in_list[disetujui,perlu_revisi,ditolak]',
            'review_note' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $reviewNote = $this->request->getPost('review_note');

        if (in_array($status, ['perlu_revisi', 'ditolak']) && empty(trim($reviewNote))) {
            return redirect()->back()->withInput()->with('error', 'Catatan review wajib diisi jika tempat mandiri direvisi atau ditolak.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $institutionId = null;

        if ($status === 'disetujui') {
            // Check if institution already exists under this name in database, if not create it
            $existingInst = $this->institutionModel->where('name', $placement['proposed_institution_name'])->first();
            if (!$existingInst) {
                // Insert a new institution profile of type mandiri
                $this->institutionModel->insert([
                    'name'               => $placement['proposed_institution_name'],
                    'field'              => $placement['proposed_field'] ?: 'Lainnya',
                    'address'            => $placement['proposed_address'] ?: 'Tidak tersedia',
                    'contact_person'     => $placement['contact_person'] ?: 'Pimpinan Instansi',
                    'contact_position'   => $placement['contact_position'] ?: 'Staff',
                    'contact_phone'      => $placement['contact_phone'] ?: '081234567890',
                    'partnership_status' => 'mandiri', // mark as mandiri
                    'created_at'         => date('Y-m-d H:i:s'),
                    'updated_at'         => date('Y-m-d H:i:s'),
                ]);
                $institutionId = $this->institutionModel->getInsertID();

                // Create a quota record for the current period
                $registration = $this->registrationModel->find($placement['registration_id']);
                $db->table('institution_quotas')->insert([
                    'period_id'      => $registration['period_id'],
                    'institution_id' => $institutionId,
                    'quota_total'    => 1,
                    'quota_used'     => 1,
                    'status'         => 'active',
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);
            } else {
                $institutionId = $existingInst['id'];
                
                // Increment quota used or check if quota exists
                $registration = $this->registrationModel->find($placement['registration_id']);
                $quotaRow = $db->table('institution_quotas')
                    ->where('period_id', $registration['period_id'])
                    ->where('institution_id', $institutionId)
                    ->get()
                    ->getRowArray();

                if ($quotaRow) {
                    $db->table('institution_quotas')
                        ->where('id', $quotaRow['id'])
                        ->increment('quota_used', 1);
                } else {
                    $db->table('institution_quotas')->insert([
                        'period_id'      => $registration['period_id'],
                        'institution_id' => $institutionId,
                        'quota_total'    => 1,
                        'quota_used'     => 1,
                        'status'         => 'active',
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // Update placement request
        $updateData = [
            'status'      => $status,
            'review_note' => $reviewNote ?: null,
            'reviewed_by' => session()->get('user_id'),
            'reviewed_at' => date('Y-m-d H:i:s'),
        ];
        if ($institutionId) {
            $updateData['institution_id'] = $institutionId;
        }
        $this->placementModel->update($id, $updateData);

        // Update student registration status
        $regStatus = 'penempatan_diajukan';
        if ($status === 'disetujui') {
            $regStatus = 'diterima_instansi'; // Approved mandiri means they are automatically accepted since they carry stempel of acceptance
            
            // Mark selected in placement_choices
            $db->table('placement_choices')
                ->where('registration_id', $placement['registration_id'])
                ->delete();

            $db->table('placement_choices')->insert([
                'registration_id'     => $placement['registration_id'],
                'institution_id'      => $institutionId,
                'institution_name'    => $placement['proposed_institution_name'],
                'institution_address' => $placement['proposed_address'],
                'priority_order'      => 1,
                'is_selected'         => 1,
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);

        } elseif ($status === 'perlu_revisi') {
            $regStatus = 'penempatan_revisi';
        } elseif ($status === 'ditolak') {
            $regStatus = 'penempatan_ditolak';
        }

        $this->registrationModel->update($placement['registration_id'], [
            'current_status' => $regStatus
        ]);

        // Status logs
        $db->table('registration_status_logs')->insert([
            'registration_id' => $placement['registration_id'],
            'old_status'      => $registration['current_status'],
            'new_status'      => $regStatus,
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'koordinator',
            'note'            => 'Tempat Mandiri: ' . ($reviewNote ?: strtoupper($status)),
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses validasi tempat mandiri.');
        }

        // Audit Log
        AuditService::log(
            'VALIDATE_MANDIRI_PLACEMENT',
            'placement_requests',
            $id,
            $placement,
            ['status' => $status, 'review_note' => $reviewNote],
            "Memvalidasi pengajuan tempat mandiri '" . $placement['proposed_institution_name'] . "' dengan keputusan: " . strtoupper($status)
        );

        return redirect()->to(base_url('koordinator/validasi-mandiri'))->with('success', 'Keputusan validasi tempat mandiri berhasil disimpan.');
    }

    // ==========================================
    // 5. Penetapan Pembimbing
    // ==========================================
    public function penetapanPembimbing()
    {
        $db = \Config\Database::connect();
        
        // Fetch active periods
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        // Fetch students who are accepted by instansi (either diterima_instansi or penempatan_disetujui or dosen_ditetapkan)
        $students = [];
        if ($selectedPeriodId) {
            $students = $db->table('kp_registrations')
                ->select('kp_registrations.id as registration_id, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_registrations.current_status,
                          institution_profiles.name as instansi_name, lecturer_profiles.full_name as dosen_name, supervisor_assignments.lecturer_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('placement_choices', 'placement_choices.registration_id = kp_registrations.id AND placement_choices.is_selected = 1', 'left')
                ->join('institution_profiles', 'institution_profiles.id = placement_choices.institution_id', 'left')
                ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id AND supervisor_assignments.status = "active"', 'left')
                ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id', 'left')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->whereIn('kp_registrations.current_status', ['diterima_instansi', 'dosen_ditetapkan', 'sedang_berjalan'])
                ->where('kp_registrations.deleted_at', null)
                ->get()
                ->getResultArray();
        }

        // Fetch Dosen Pembimbing with their quota logs
        $lecturers = [];
        if ($selectedPeriodId) {
            // count their active supervision bimbingan
            $lecturers = $db->table('lecturer_profiles')
                ->select('lecturer_profiles.*,
                          (SELECT COUNT(*) FROM supervisor_assignments 
                           JOIN kp_registrations ON kp_registrations.id = supervisor_assignments.registration_id
                           WHERE supervisor_assignments.lecturer_id = lecturer_profiles.id 
                           AND supervisor_assignments.status = "active"
                           AND kp_registrations.period_id = ' . $db->escape($selectedPeriodId) . ') as active_bimbingan')
                ->where('lecturer_profiles.deleted_at', null)
                ->where('lecturer_profiles.is_available', 1)
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'            => 'Penetapan Dosen Pembimbing',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'students'         => $students,
            'lecturers'        => $lecturers,
        ];

        return view('koordinator/akademik/penetapan-pembimbing/index', $data);
    }

    public function submitPembimbing()
    {
        $rules = [
            'registration_id' => 'required|numeric',
            'lecturer_id'     => 'required|numeric',
            'note'            => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Data input penetapan pembimbing tidak valid.');
        }

        $regId = $this->request->getPost('registration_id');
        $lecturerId = $this->request->getPost('lecturer_id');
        $note = $this->request->getPost('note');

        $registration = $this->registrationModel->find($regId);
        if (!$registration) {
            return redirect()->back()->with('error', 'Registrasi mahasiswa tidak ditemukan.');
        }

        $lecturer = $this->lecturerModel->find($lecturerId);
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        
        // Check active quota
        $activeBimbingan = $db->table('supervisor_assignments')
            ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
            ->where('supervisor_assignments.lecturer_id', $lecturerId)
            ->where('supervisor_assignments.status', 'active')
            ->where('kp_registrations.period_id', $registration['period_id'])
            ->countAllResults();

        $maxQuota = intval($lecturer['max_students_quota'] ?: 5);
        if ($activeBimbingan >= $maxQuota) {
            $override = $this->request->getPost('override_quota');
            if (!$override) {
                return redirect()->back()->with('error', 'Kuota bimbingan dosen (' . $lecturer['full_name'] . ') sudah penuh untuk periode ini. Aktifkan checkbox "Override kuota bimbingan" untuk tetap menetapkan.');
            }
        }

        $db->transStart();

        // Check if there is already an active supervisor for this student
        $oldSupervisor = $db->table('supervisor_assignments')
            ->where('registration_id', $regId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if ($oldSupervisor) {
            // Set old to replaced
            $db->table('supervisor_assignments')
                ->where('id', $oldSupervisor['id'])
                ->update([
                    'status'     => 'replaced',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        // Insert new supervisor assignment
        $db->table('supervisor_assignments')->insert([
            'uuid'            => sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)),
            'registration_id' => $regId,
            'lecturer_id'     => $lecturerId,
            'assigned_by'     => session()->get('user_id'),
            'assigned_at'     => date('Y-m-d H:i:s'),
            'status'          => 'active',
            'note'            => $note ?: null,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        // Update student status to dosen_ditetapkan and sedang_berjalan
        $this->registrationModel->update($regId, [
            'current_status' => 'sedang_berjalan'
        ]);

        // Add status log
        $db->table('registration_status_logs')->insert([
            'registration_id' => $regId,
            'old_status'      => $registration['current_status'],
            'new_status'      => 'sedang_berjalan',
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'koordinator',
            'note'            => 'Dosen pembimbing ditetapkan: ' . $lecturer['full_name'],
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses penetapan dosen pembimbing.');
        }

        // Audit Log
        $student = $this->studentProfileModel->find($registration['student_id']);
        AuditService::log(
            'ASSIGN_SUPERVISOR',
            'supervisor_assignments',
            $regId,
            $oldSupervisor,
            ['lecturer_id' => $lecturerId, 'status' => 'active'],
            "Menetapkan dosen pembimbing '" . $lecturer['full_name'] . "' untuk mahasiswa '" . ($student['full_name'] ?? $regId) . "'"
        );

        return redirect()->back()->with('success', 'Dosen pembimbing berhasil ditetapkan dan status kegiatan mahasiswa berubah menjadi Sedang Berjalan.');
    }

    // ==========================================
    // 6. Monitoring Mahasiswa
    // ==========================================
    public function monitoringMahasiswa()
    {
        $db = \Config\Database::connect();
        
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        $students = [];
        if ($selectedPeriodId) {
            $students = $db->table('kp_registrations')
                ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name,
                          institution_profiles.name as instansi_name, lecturer_profiles.full_name as dosen_name')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('placement_choices', 'placement_choices.registration_id = kp_registrations.id AND placement_choices.is_selected = 1', 'left')
                ->join('institution_profiles', 'institution_profiles.id = placement_choices.institution_id', 'left')
                ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id AND supervisor_assignments.status = "active"', 'left')
                ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id', 'left')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->where('kp_registrations.deleted_at', null)
                ->orderBy('kp_registrations.id', 'DESC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'            => 'Monitoring Progres Mahasiswa',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'students'         => $students,
        ];

        return view('koordinator/akademik/monitoring-mahasiswa/index', $data);
    }

    // ==========================================
    // 7. Monitoring Logbook
    // ==========================================
    public function monitoringLogbook()
    {
        $db = \Config\Database::connect();
        
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        $students = [];
        if ($selectedPeriodId) {
            $students = $db->table('kp_registrations')
                ->select('kp_registrations.id as registration_id, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name,
                          (SELECT COUNT(*) FROM logbook_weeks WHERE logbook_weeks.registration_id = kp_registrations.id AND logbook_weeks.status = "disetujui") as logbook_approved,
                          (SELECT COUNT(*) FROM logbook_weeks WHERE logbook_weeks.registration_id = kp_registrations.id) as logbook_total,
                          lecturer_profiles.full_name as dosen_name')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id AND supervisor_assignments.status = "active"', 'left')
                ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id', 'left')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->whereIn('kp_registrations.current_status', ['sedang_berjalan', 'laporan_akhir_dikirim', 'menunggu_penilaian', 'selesai'])
                ->where('kp_registrations.deleted_at', null)
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'            => 'Monitoring Logbook Mingguan',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'students'         => $students,
        ];

        return view('koordinator/akademik/monitoring-logbook/index', $data);
    }

    public function viewLogbook($id)
    {
        $registration = $this->registrationModel->getRegistrationDetails($id);
        if (!$registration) {
            return redirect()->to(base_url('koordinator/monitoring-logbook'))->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        
        // Fetch logbook weeks
        $weeks = $db->table('logbook_weeks')
            ->where('registration_id', $id)
            ->orderBy('week_number', 'ASC')
            ->get()
            ->getResultArray();

        // Fetch daily entries for all weeks
        $dailyEntries = [];
        $reviews = [];
        foreach ($weeks as $w) {
            $daily = $db->table('logbook_daily_entries')
                ->where('logbook_week_id', $w['id'])
                ->orderBy('activity_date', 'ASC')
                ->get()
                ->getResultArray();
            $dailyEntries[$w['id']] = $daily;

            $rev = $db->table('logbook_reviews')
                ->select('logbook_reviews.*, lecturer_profiles.full_name as reviewer_name')
                ->join('lecturer_profiles', 'lecturer_profiles.user_id = logbook_reviews.reviewed_by')
                ->where('logbook_week_id', $w['id'])
                ->orderBy('logbook_reviews.id', 'DESC')
                ->get()
                ->getResultArray();
            $reviews[$w['id']] = $rev;
        }

        $data = [
            'title'        => 'Logbook Mahasiswa: ' . $registration['full_name'],
            'registration' => $registration,
            'weeks'        => $weeks,
            'dailyEntries' => $dailyEntries,
            'reviews'      => $reviews,
        ];

        return view('koordinator/akademik/monitoring-logbook/view', $data);
    }

    // ==========================================
    // 8. Monitoring Laporan
    // ==========================================
    public function monitoringLaporan()
    {
        $db = \Config\Database::connect();
        
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        $reports = [];
        if ($selectedPeriodId) {
            $reports = $db->table('final_reports')
                ->select('final_reports.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, lecturer_profiles.full_name as dosen_name')
                ->join('kp_registrations', 'kp_registrations.id = final_reports.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id AND supervisor_assignments.status = "active"', 'left')
                ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id', 'left')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->where('final_reports.deleted_at', null)
                ->orderBy('final_reports.id', 'DESC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'            => 'Monitoring Laporan Akhir',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'reports'          => $reports,
        ];

        return view('koordinator/akademik/monitoring-laporan/index', $data);
    }

    // ==========================================
    // 9. Validasi Penilaian
    // ==========================================
    public function validasiPenilaian()
    {
        $db = \Config\Database::connect();
        
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        $scores = [];
        if ($selectedPeriodId) {
            // fetch students and final_scores (even if final_scores doesn't exist, we can show status)
            $scores = $db->table('kp_registrations')
                ->select('kp_registrations.id as registration_id, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_registrations.current_status,
                          final_scores.final_score, final_scores.final_grade, final_scores.status as score_status')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->whereIn('kp_registrations.current_status', ['sedang_berjalan', 'laporan_akhir_dikirim', 'menunggu_penilaian', 'menunggu_validasi_akhir', 'selesai'])
                ->where('kp_registrations.deleted_at', null)
                ->orderBy('final_scores.status = \'menunggu_validasi\'', 'DESC', false)
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'            => 'Validasi & Penilaian Akhir',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'scores'           => $scores,
        ];

        return view('koordinator/akademik/validasi-penilaian/index', $data);
    }

    public function reviewPenilaian($id)
    {
        $registration = $this->registrationModel->getRegistrationDetails($id);
        if (!$registration) {
            return redirect()->to(base_url('koordinator/validasi-penilaian'))->with('error', 'Registrasi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        
        // Fetch component scores
        $finalScore = $this->finalScoreModel->where('registration_id', $id)->first();
        
        // Fetch component details
        $scoresList = $db->table('assessment_scores')
            ->select('assessment_scores.*, assessment_components.component_name, assessment_components.weight')
            ->join('assessment_components', 'assessment_components.id = assessment_scores.component_id')
            ->where('assessment_scores.registration_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Detail Nilai & Validasi: ' . $registration['full_name'],
            'registration' => $registration,
            'finalScore'   => $finalScore,
            'scoresList'   => $scoresList,
        ];

        return view('koordinator/akademik/validasi-penilaian/review', $data);
    }

    public function submitPenilaian($id)
    {
        $finalScore = $this->finalScoreModel->where('registration_id', $id)->first();
        if (!$finalScore) {
            return redirect()->back()->with('error', 'Skor akhir belum dihitung/ditemukan.');
        }

        $registration = $this->registrationModel->find($id);
        if (!$registration) {
            return redirect()->back()->with('error', 'Data registrasi tidak ditemukan.');
        }

        $rules = [
            'validation_note' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $note = $this->request->getPost('validation_note');

        $db = \Config\Database::connect();
        $db->transStart();

        // Update final score status to divalidasi
        $this->finalScoreModel->update($finalScore['id'], [
            'status'          => 'divalidasi',
            'validated_by'    => session()->get('user_id'),
            'validated_at'    => date('Y-m-d H:i:s'),
            'validation_note' => $note ?: null
        ]);

        // Update registration status to selesai
        $this->registrationModel->update($id, [
            'current_status' => 'selesai'
        ]);

        // Add log
        $db->table('registration_status_logs')->insert([
            'registration_id' => $id,
            'old_status'      => $registration['current_status'],
            'new_status'      => 'selesai',
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'koordinator',
            'note'            => 'Nilai akhir divalidasi oleh Koordinator.',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses validasi penilaian akhir.');
        }

        // Audit Log
        AuditService::log(
            'VALIDATE_FINAL_SCORE',
            'final_scores',
            $finalScore['id'],
            $finalScore,
            ['status' => 'divalidasi', 'validation_note' => $note],
            "Mengesahkan nilai akhir mahasiswa (Nilai: " . $finalScore['final_score'] . " / Grade: " . $finalScore['final_grade'] . ")"
        );

        return redirect()->to(base_url('koordinator/validasi-penilaian'))->with('success', 'Nilai akhir berhasil disahkan dan status proses mahasiswa diubah menjadi Selesai.');
    }

    // ==========================================
    // 10. Rekap Nilai Akhir
    // ==========================================
    public function rekapNilai()
    {
        $db = \Config\Database::connect();
        
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();
        $selectedPeriodId = $this->request->getVar('period_id');
        if (empty($selectedPeriodId)) {
            $activePeriod = $this->periodModel->where('status', 'aktif')->first();
            $selectedPeriodId = $activePeriod ? $activePeriod['id'] : ($periods ? $periods[0]['id'] : null);
        }

        $scores = [];
        if ($selectedPeriodId) {
            $scores = $db->table('final_scores')
                ->select('final_scores.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name')
                ->join('kp_registrations', 'kp_registrations.id = final_scores.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->where('kp_registrations.period_id', $selectedPeriodId)
                ->where('kp_registrations.deleted_at', null)
                ->orderBy('final_scores.final_score', 'DESC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'            => 'Rekapitulasi Nilai Akhir Mahasiswa',
            'periods'          => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'scores'           => $scores,
        ];

        return view('koordinator/akademik/rekap-nilai/index', $data);
    }

    /**
     * Securely download student uploaded documents.
     */
    public function downloadDokumen($id)
    {
        $db = \Config\Database::connect();
        $doc = $db->table('student_documents')->where('id', $id)->get()->getRow();
        if (!$doc) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $filePath = $doc->file_path;
        if (!str_starts_with($filePath, '/') && !str_contains($filePath, ':')) {
            $filePath = WRITEPATH . $filePath;
        }

        if (!file_exists($filePath)) {
            $checkPath = WRITEPATH . 'uploads/kp-pkl/' . $doc->stored_name;
            if (file_exists($checkPath)) {
                $filePath = $checkPath;
            } else {
                return redirect()->back()->with('error', 'Berkas fisik tidak ditemukan di server.');
            }
        }

        return $this->response->download($filePath, null)
            ->setFileName($doc->original_name);
    }

    /**
     * Securely download student final reports.
     */
    public function downloadLaporan($id)
    {
        $db = \Config\Database::connect();
        $report = $db->table('final_reports')->where('id', $id)->get()->getRow();
        if (!$report) {
            return redirect()->back()->with('error', 'Laporan akhir tidak ditemukan.');
        }

        $filePath = $report->file_path;
        if (!str_starts_with($filePath, '/') && !str_contains($filePath, ':')) {
            $filePath = WRITEPATH . $filePath;
        }

        if (!file_exists($filePath)) {
            $checkPath = WRITEPATH . 'uploads/' . $report->stored_name;
            if (file_exists($checkPath)) {
                $filePath = $checkPath;
            } else {
                return redirect()->back()->with('error', 'Berkas fisik laporan tidak ditemukan di server.');
            }
        }

        return $this->response->download($filePath, null)
            ->setFileName($report->original_name);
    }
}
