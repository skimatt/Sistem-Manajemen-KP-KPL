<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LecturerProfileModel;
use App\Models\StudentProfileModel;
use App\Models\RegistrationModel;
use App\Models\LogbookWeekModel;
use App\Models\FinalReportModel;
use App\Models\AssessmentScoreModel;
use App\Models\FinalScoreModel;
use App\Services\AuditService;
use CodeIgniter\Database\Exceptions\DatabaseException;

class DosenController extends BaseController
{
    protected $db;
    protected $userModel;
    protected $lecturerModel;
    protected $studentModel;
    protected $registrationModel;
    protected $logbookWeekModel;
    protected $finalReportModel;
    protected $assessmentScoreModel;
    protected $finalScoreModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userModel = new UserModel();
        $this->lecturerModel = new LecturerProfileModel();
        $this->studentModel = new StudentProfileModel();
        $this->registrationModel = new RegistrationModel();
        $this->logbookWeekModel = new LogbookWeekModel();
        $this->finalReportModel = new FinalReportModel();
        $this->assessmentScoreModel = new AssessmentScoreModel();
        $this->finalScoreModel = new FinalScoreModel();
    }

    /**
     * Helper to get lecturer profile linked to current user.
     */
    protected function getLecturerProfile()
    {
        $userId = session()->get('user_id');
        return $this->db->table('lecturer_profiles')->where('user_id', $userId)->get()->getRow();
    }

    /**
     * Dashboard.
     */
    public function index()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;

        $supervised = [];
        $pendingLogbooks = 0;
        $pendingReports = 0;

        if ($lecturerId) {
            $supervised = $this->db->table('supervisor_assignments')
                ->select('supervisor_assignments.*, student_profiles.npm, student_profiles.full_name, kp_registrations.current_status, kp_registrations.id as registration_id, kp_periods.name as period_name')
                ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->get()
                ->getResultArray();

            $regIds = array_column($supervised, 'registration_id');
            if (!empty($regIds)) {
                $pendingLogbooks = $this->db->table('logbook_weeks')
                    ->whereIn('registration_id', $regIds)
                    ->where('status', 'dikirim')
                    ->countAllResults();

                $pendingReports = $this->db->table('final_reports')
                    ->whereIn('registration_id', $regIds)
                    ->where('status', 'dikirim')
                    ->countAllResults();
            }
        }

        $stats = [
            'total_students'   => count($supervised),
            'pending_logbooks' => $pendingLogbooks,
            'pending_reports'  => $pendingReports,
            'max_quota'        => $lecturer ? $lecturer->max_supervision_quota : 10,
        ];

        $data = [
            'title'      => 'Dashboard Dosen Pembimbing',
            'stats'      => $stats,
            'students'   => $supervised,
            'lecturer'   => $lecturer
        ];

        return view('dosen/index', $data);
    }

    /**
     * Mahasiswa Bimbingan.
     */
    public function mahasiswa()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;
        $students = [];

        if ($lecturerId) {
            $students = $this->db->table('supervisor_assignments')
                ->select('supervisor_assignments.*, student_profiles.npm, student_profiles.full_name, student_profiles.phone, kp_registrations.current_status, kp_registrations.id as registration_id, kp_periods.name as period_name')
                ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'    => 'Daftar Mahasiswa Bimbingan',
            'students' => $students
        ];

        return view('dosen/mahasiswa', $data);
    }

    /**
     * Detail Mahasiswa.
     */
    public function detailMahasiswa($registrationId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $registrationId)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/mahasiswa'))->with('error', 'Akses ditolak. Anda tidak ditugaskan untuk mahasiswa ini.');
        }

        $registration = $this->registrationModel->getRegistrationDetails($registrationId);
        if (!$registration) {
            return redirect()->to(base_url('dosen/mahasiswa'))->with('error', 'Data registrasi tidak ditemukan.');
        }

        // Get placement details
        $placement = $this->db->table('placement_requests')
            ->select('placement_requests.*, institution_profiles.name as instansi_name, institution_profiles.address as instansi_address')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->where('placement_requests.registration_id', $registrationId)
            ->orderBy('placement_requests.id', 'DESC')
            ->get()
            ->getRow();

        // Get uploaded files
        $documents = $this->db->table('student_documents')
            ->where('registration_id', $registrationId)
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Detail Progres Mahasiswa: ' . $registration['full_name'],
            'registration' => $registration,
            'placement'    => $placement,
            'documents'    => $documents
        ];

        return view('dosen/detail_mahasiswa', $data);
    }

    /**
     * Logbook Review List.
     */
    public function logbook()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;
        $weeks = [];

        if ($lecturerId) {
            $weeks = $this->db->table('logbook_weeks')
                ->select('logbook_weeks.*, student_profiles.npm, student_profiles.full_name, kp_periods.name as period_name')
                ->join('kp_registrations', 'kp_registrations.id = logbook_weeks.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->orderBy('logbook_weeks.status = \'dikirim\'', 'DESC', false)
                ->orderBy('logbook_weeks.submitted_at', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title' => 'Review Logbook Mingguan',
            'weeks' => $weeks
        ];

        return view('dosen/logbook', $data);
    }

    /**
     * Review Specific Logbook Week.
     */
    public function reviewLogbook($weekId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        $week = $this->db->table('logbook_weeks')
            ->select('logbook_weeks.*, kp_registrations.id as registration_id, student_profiles.full_name, student_profiles.npm')
            ->join('kp_registrations', 'kp_registrations.id = logbook_weeks.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->where('logbook_weeks.id', $weekId)
            ->get()
            ->getRow();

        if (!$week) {
            return redirect()->to(base_url('dosen/logbook'))->with('error', 'Data logbook tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $week->registration_id)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/logbook'))->with('error', 'Akses ditolak. Anda tidak dibebani bimbingan untuk mahasiswa ini.');
        }

        // Fetch daily entries
        $dailyEntries = $this->db->table('logbook_daily_entries')
            ->where('logbook_week_id', $weekId)
            ->orderBy('activity_date', 'ASC')
            ->get()
            ->getResultArray();

        // Fetch past reviews
        $reviews = $this->db->table('logbook_reviews')
            ->select('logbook_reviews.*, users.name as reviewer_name')
            ->join('users', 'users.id = logbook_reviews.reviewed_by')
            ->where('logbook_week_id', $weekId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Review Logbook Minggu ke-' . $week->week_number . ': ' . $week->full_name,
            'week'         => $week,
            'dailyEntries' => $dailyEntries,
            'reviews'      => $reviews
        ];

        return view('dosen/review_logbook', $data);
    }

    /**
     * Submit Logbook Review.
     */
    public function submitReviewLogbook($weekId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        $week = $this->db->table('logbook_weeks')->where('id', $weekId)->get()->getRow();
        if (!$week) {
            return redirect()->to(base_url('dosen/logbook'))->with('error', 'Data logbook tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $week->registration_id)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/logbook'))->with('error', 'Akses ditolak.');
        }

        $rules = [
            'status'  => 'required|in_list[disetujui,perlu_revisi]',
            'comment' => 'required|string|min_length[5]',
        ];

        $messages = [
            'status'  => ['required' => 'Status persetujuan wajib dipilih.'],
            'comment' => ['required' => 'Catatan review wajib diisi.', 'min_length' => 'Catatan review minimal 5 karakter.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $comment = $this->request->getPost('comment');

        $this->db->transStart();

        // Update week
        $updateWeek = [
            'status'      => $status,
            'approved_by' => ($status === 'disetujui') ? session()->get('user_id') : null,
            'approved_at' => ($status === 'disetujui') ? date('Y-m-d H:i:s') : null,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        $this->db->table('logbook_weeks')->where('id', $weekId)->update($updateWeek);

        // Add review log
        $this->db->table('logbook_reviews')->insert([
            'logbook_week_id' => $weekId,
            'reviewed_by'     => session()->get('user_id'),
            'status'          => $status,
            'comment'         => $comment,
            'reviewed_at'     => date('Y-m-d H:i:s'),
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        // Add notification for student
        $registration = $this->db->table('kp_registrations')->where('id', $week->registration_id)->get()->getRow();
        $studentProfile = $this->db->table('student_profiles')->where('id', $registration->student_id)->get()->getRow();
        
        $title = $status === 'disetujui' ? 'Logbook Disetujui' : 'Revisi Logbook';
        $msg = $status === 'disetujui' 
            ? "Logbook Minggu ke-{$week->week_number} Anda telah disetujui oleh Dosen Pembimbing."
            : "Ada catatan revisi dari Dosen Pembimbing untuk Logbook Minggu ke-{$week->week_number}: \"{$comment}\"";

        $this->db->table('notifications')->insert([
            'user_id'    => $studentProfile->user_id,
            'title'      => $title,
            'message'    => $msg,
            'type'       => $status === 'disetujui' ? 'success' : 'warning',
            'url'        => 'mahasiswa/logbook',
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan review logbook.');
        }

        // Audit Log
        AuditService::log(
            'REVIEW_LOGBOOK',
            'logbook_weeks',
            $weekId,
            ['status' => $week->status],
            ['status' => $status, 'comment' => $comment],
            "Review logbook minggu ke-{$week->week_number} mahasiswa (Status: {$status})"
        );

        return redirect()->to(base_url('dosen/logbook'))->with('success', 'Review logbook berhasil disimpan.');
    }

    /**
     * Catatan Bimbingan list.
     */
    public function catatanBimbingan()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;
        $students = [];

        if ($lecturerId) {
            $students = $this->db->table('supervisor_assignments')
                ->select('supervisor_assignments.*, student_profiles.npm, student_profiles.full_name, student_profiles.phone, kp_registrations.current_status, kp_registrations.id as registration_id, kp_periods.name as period_name')
                ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'    => 'Catatan Bimbingan Akademik',
            'students' => $students
        ];

        return view('dosen/catatan_bimbingan', $data);
    }

    /**
     * Details of Catatan Bimbingan (Timeline of reviews and general note).
     */
    public function detailCatatanBimbingan($registrationId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->select('supervisor_assignments.*, lecturer_profiles.full_name as lecturer_name')
            ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id')
            ->where('supervisor_assignments.registration_id', $registrationId)
            ->where('supervisor_assignments.lecturer_id', $lecturer->id)
            ->where('supervisor_assignments.status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/catatan-bimbingan'))->with('error', 'Akses ditolak.');
        }

        $registration = $this->registrationModel->getRegistrationDetails($registrationId);
        if (!$registration) {
            return redirect()->to(base_url('dosen/catatan-bimbingan'))->with('error', 'Data registrasi tidak ditemukan.');
        }

        // Get all reviews written by this advisor for this student's weeks
        $reviews = $this->db->table('logbook_reviews')
            ->select('logbook_reviews.*, logbook_weeks.week_number')
            ->join('logbook_weeks', 'logbook_weeks.id = logbook_reviews.logbook_week_id')
            ->where('logbook_weeks.registration_id', $registrationId)
            ->where('logbook_reviews.reviewed_by', session()->get('user_id'))
            ->orderBy('logbook_reviews.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Catatan Bimbingan Mahasiswa: ' . $registration['full_name'],
            'registration' => $registration,
            'assignment'   => $assignment,
            'reviews'      => $reviews
        ];

        return view('dosen/detail_catatan_bimbingan', $data);
    }

    /**
     * Submit General guidance note for student.
     */
    public function submitBimbinganNote($registrationId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $registrationId)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'note' => 'required|string|min_length[5]',
        ];

        $messages = [
            'note' => ['required' => 'Catatan bimbingan wajib diisi.', 'min_length' => 'Catatan bimbingan minimal 5 karakter.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $note = $this->request->getPost('note');

        $this->db->table('supervisor_assignments')
            ->where('id', $assignment->id)
            ->update([
                'note'       => $note,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        // Notify Student
        $reg = $this->db->table('kp_registrations')->where('id', $registrationId)->get()->getRow();
        $student = $this->db->table('student_profiles')->where('id', $reg->student_id)->get()->getRow();

        $this->db->table('notifications')->insert([
            'user_id'    => $student->user_id,
            'title'      => 'Catatan Dosen Pembimbing',
            'message'    => "Dosen Pembimbing memberikan catatan bimbingan baru untuk Anda.",
            'type'       => 'info',
            'url'        => 'mahasiswa/catatan-dosen',
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Audit Log
        AuditService::log(
            'UPDATE_SUPERVISOR_NOTE',
            'supervisor_assignments',
            $assignment->id,
            ['note' => $assignment->note],
            ['note' => $note],
            "Memperbarui catatan bimbingan mahasiswa oleh dosen pembimbing."
        );

        return redirect()->to(base_url('dosen/catatan-bimbingan/detail/' . $registrationId))->with('success', 'Catatan bimbingan berhasil diperbarui.');
    }

    /**
     * Review Laporan list.
     */
    public function laporan()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;
        $reports = [];

        if ($lecturerId) {
            $reports = $this->db->table('final_reports')
                ->select('final_reports.*, student_profiles.npm, student_profiles.full_name, kp_periods.name as period_name')
                ->join('kp_registrations', 'kp_registrations.id = final_reports.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->orderBy('final_reports.status = \'dikirim\'', 'DESC', false)
                ->orderBy('final_reports.updated_at', 'ASC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'   => 'Review Laporan Akhir',
            'reports' => $reports
        ];

        return view('dosen/laporan', $data);
    }

    /**
     * Review Specific Report.
     */
    public function reviewLaporan($reportId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        $report = $this->db->table('final_reports')
            ->select('final_reports.*, kp_registrations.id as registration_id, student_profiles.full_name, student_profiles.npm')
            ->join('kp_registrations', 'kp_registrations.id = final_reports.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->where('final_reports.id', $reportId)
            ->get()
            ->getRow();

        if (!$report) {
            return redirect()->to(base_url('dosen/laporan'))->with('error', 'Laporan akhir tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $report->registration_id)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/laporan'))->with('error', 'Akses ditolak.');
        }

        $data = [
            'title'  => 'Review Laporan Akhir: ' . $report->full_name,
            'report' => $report
        ];

        return view('dosen/review_laporan', $data);
    }

    /**
     * Submit Report Review decision.
     */
    public function submitReviewLaporan($reportId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        $report = $this->db->table('final_reports')->where('id', $reportId)->get()->getRow();
        if (!$report) {
            return redirect()->to(base_url('dosen/laporan'))->with('error', 'Data laporan tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $report->registration_id)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/laporan'))->with('error', 'Akses ditolak.');
        }

        $rules = [
            'status'      => 'required|in_list[disetujui,perlu_revisi]',
            'review_note' => 'required|string|min_length[5]',
        ];

        $messages = [
            'status'      => ['required' => 'Status review wajib dipilih.'],
            'review_note' => ['required' => 'Catatan review wajib diisi.', 'min_length' => 'Catatan review minimal 5 karakter.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $note = $this->request->getPost('review_note');

        $this->db->transStart();

        // Update Report
        $updateReport = [
            'status'      => $status,
            'reviewed_by' => session()->get('user_id'),
            'reviewed_at' => date('Y-m-d H:i:s'),
            'review_note' => $note,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        $this->db->table('final_reports')->where('id', $reportId)->update($updateReport);

        // Update Student registration status
        $registration = $this->db->table('kp_registrations')->where('id', $report->registration_id)->get()->getRow();
        $student = $this->db->table('student_profiles')->where('id', $registration->student_id)->get()->getRow();
        
        $newStatus = $status === 'disetujui' ? 'menunggu_penilaian_instansi' : 'sedang_berjalan';
        
        $this->db->table('kp_registrations')
            ->where('id', $report->registration_id)
            ->update([
                'current_status' => $newStatus,
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);

        // Insert Status Log
        $this->db->table('registration_status_logs')->insert([
            'registration_id' => $report->registration_id,
            'old_status'      => $registration->current_status,
            'new_status'      => $newStatus,
            'changed_by'      => session()->get('user_id'),
            'changed_by_role' => 'dosen',
            'note'            => $status === 'disetujui' 
                ? 'Laporan akhir disetujui oleh Dosen Pembimbing.' 
                : 'Laporan akhir perlu revisi oleh Dosen Pembimbing. Catatan: ' . $note,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        // Notify student
        $notifyTitle = $status === 'disetujui' ? 'Laporan Akhir Disetujui' : 'Revisi Laporan Akhir';
        $notifyMsg = $status === 'disetujui'
            ? 'Laporan akhir KP/KPL Anda telah disetujui oleh Dosen Pembimbing.'
            : 'Ada catatan revisi untuk laporan akhir Anda dari Dosen Pembimbing: "' . $note . '"';

        $this->db->table('notifications')->insert([
            'user_id'    => $student->user_id,
            'title'      => $notifyTitle,
            'message'    => $notifyMsg,
            'type'       => $status === 'disetujui' ? 'success' : 'warning',
            'url'        => 'mahasiswa/laporan',
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses review laporan.');
        }

        // Audit Log
        AuditService::log(
            'REVIEW_FINAL_REPORT',
            'final_reports',
            $reportId,
            ['status' => $report->status],
            ['status' => $status, 'review_note' => $note],
            "Mengevaluasi laporan akhir mahasiswa (Hasil: {$status})"
        );

        return redirect()->to(base_url('dosen/laporan'))->with('success', 'Review laporan akhir berhasil disimpan.');
    }

    /**
     * Secure PDF Report Download.
     */
    public function downloadLaporan($reportId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $report = $this->db->table('final_reports')->where('id', $reportId)->get()->getRow();
        if (!$report) {
            return redirect()->back()->with('error', 'Berkas tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $report->registration_id)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $fullPath = WRITEPATH . $report->file_path;
        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'File fisik tidak ditemukan pada server.');
        }

        return $this->response->download($fullPath, null)->setFileName($report->original_name);
    }

    /**
     * Input Penilaian List.
     */
    public function penilaian()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;
        $students = [];

        if ($lecturerId) {
            // Dosen can only input grades for students whose registration status is in grading phases
            $students = $this->db->table('supervisor_assignments')
                ->select('supervisor_assignments.registration_id, student_profiles.npm, student_profiles.full_name, kp_periods.name as period_name, kp_registrations.current_status,
                          final_scores.lecturer_score, final_scores.final_score, final_scores.final_grade, final_scores.status as score_status')
                ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->whereIn('kp_registrations.current_status', ['sedang_berjalan', 'laporan_akhir_dikirim', 'menunggu_penilaian_instansi', 'nilai_instansi_masuk', 'nilai_dosen_masuk', 'menunggu_validasi_akhir', 'selesai'])
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'    => 'Input Penilaian Akademik',
            'students' => $students
        ];

        return view('dosen/penilaian', $data);
    }

    /**
     * Form grading screen.
     */
    public function inputPenilaian($registrationId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $registrationId)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/penilaian'))->with('error', 'Akses ditolak.');
        }

        $registration = $this->registrationModel->getRegistrationDetails($registrationId);
        if (!$registration) {
            return redirect()->to(base_url('dosen/penilaian'))->with('error', 'Registrasi tidak ditemukan.');
        }

        // Fetch assessment template of type 'dosen' for this period, or default (period_id = null)
        $template = $this->db->table('assessment_templates')
            ->where('assessment_type', 'dosen')
            ->where('period_id', $registration['period_id'])
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$template) {
            $template = $this->db->table('assessment_templates')
                ->where('assessment_type', 'dosen')
                ->where('period_id', null)
                ->where('status', 'active')
                ->get()
                ->getRow();
        }

        if (!$template) {
            return redirect()->to(base_url('dosen/penilaian'))->with('error', 'Template penilaian dosen untuk periode ini belum dikonfigurasi.');
        }

        $components = $this->db->table('assessment_components')
            ->where('assessment_template_id', $template->id)
            ->where('status', 'active')
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();

        // Fetch pre-existing scores if any
        $preexisting = $this->db->table('assessment_scores')
            ->where('registration_id', $registrationId)
            ->where('assessor_user_id', session()->get('user_id'))
            ->where('assessor_role', 'dosen')
            ->get()
            ->getResultArray();

        $scoresMap = [];
        foreach ($preexisting as $p) {
            $scoresMap[$p['component_id']] = $p;
        }

        $data = [
            'title'        => 'Input Nilai Akademik: ' . $registration['full_name'],
            'registration' => $registration,
            'template'     => $template,
            'components'   => $components,
            'scoresMap'    => $scoresMap
        ];

        return view('dosen/input_penilaian', $data);
    }

    /**
     * Submit grades.
     */
    public function submitPenilaian($registrationId)
    {
        $lecturer = $this->getLecturerProfile();
        if (!$lecturer) {
            return redirect()->back()->with('error', 'Profil dosen Anda tidak ditemukan.');
        }

        // Verify assignment ownership
        $assignment = $this->db->table('supervisor_assignments')
            ->where('registration_id', $registrationId)
            ->where('lecturer_id', $lecturer->id)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$assignment) {
            return redirect()->to(base_url('dosen/penilaian'))->with('error', 'Akses ditolak.');
        }

        $registration = $this->registrationModel->find($registrationId);
        if (!$registration) {
            return redirect()->to(base_url('dosen/penilaian'))->with('error', 'Registrasi tidak ditemukan.');
        }

        $templateId = $this->request->getPost('template_id');
        $components = $this->db->table('assessment_components')
            ->where('assessment_template_id', $templateId)
            ->where('status', 'active')
            ->get()
            ->getResultArray();

        // Dynamically build validation rules based on components
        $rules = [];
        $messages = [];
        foreach ($components as $c) {
            $key = 'score_' . $c['id'];
            $rules[$key] = "required|numeric|greater_than_equal_to[0]|less_than_equal_to[{$c['max_score']}]";
            $messages[$key] = [
                'required'              => "Nilai komponen \"{$c['component_name']}\" wajib diisi.",
                'numeric'               => "Nilai komponen \"{$c['component_name']}\" harus berupa angka.",
                'greater_than_equal_to' => "Nilai komponen \"{$c['component_name']}\" minimal 0.",
                'less_than_equal_to'    => "Nilai komponen \"{$c['component_name']}\" maksimal {$c['max_score']}.",
            ];
        }

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->transStart();

        // Delete old assessment scores for this assessor & registration
        $this->db->table('assessment_scores')
            ->where('registration_id', $registrationId)
            ->where('assessor_user_id', session()->get('user_id'))
            ->where('assessor_role', 'dosen')
            ->delete();

        // Insert new component scores & sum up weighted score
        $lecturerWeightedScore = 0;
        $totalWeight = 0;
        foreach ($components as $c) {
            $scoreVal = floatval($this->request->getPost('score_' . $c['id']));
            $noteVal  = $this->request->getPost('note_' . $c['id']);

            $this->db->table('assessment_scores')->insert([
                'registration_id'        => $registrationId,
                'assessment_template_id' => $templateId,
                'component_id'           => $c['id'],
                'component_name'         => $c['component_name'],
                'assessor_user_id'       => session()->get('user_id'),
                'assessor_role'          => 'dosen',
                'score'                  => $scoreVal,
                'note'                   => $noteVal ?: null,
                'status'                 => 'submitted',
                'created_at'             => date('Y-m-d H:i:s'),
                'updated_at'             => date('Y-m-d H:i:s'),
            ]);

            // Accumulate weighted score
            // Lecturer Score = sum(score * weight / 100)
            $lecturerWeightedScore += ($scoreVal * ($c['weight'] / 100));
            $totalWeight += $c['weight'];
        }

        // Fetch final_scores if exists
        $finalScoreRecord = $this->db->table('final_scores')->where('registration_id', $registrationId)->get()->getRow();

        $instScore = $finalScoreRecord ? floatval($finalScoreRecord->institution_score) : null;
        $admScore  = $finalScoreRecord ? floatval($finalScoreRecord->admin_score) : null;

        // Calculate global final score
        // Default weights: Instansi 40%, Dosen 50%, Admin/Logbook 10%
        $wInst = 40;
        $wLect = 50;
        $wAdm  = 10;

        $globalFinalScore = 
            (($instScore ?? 0) * ($wInst / 100)) + 
            ($lecturerWeightedScore * ($wLect / 100)) + 
            (($admScore ?? 0) * ($wAdm / 100));

        // Determine grade letter
        $finalGrade = 'E';
        if ($globalFinalScore >= 85) {
            $finalGrade = 'A';
        } elseif ($globalFinalScore >= 78) {
            $finalGrade = 'B+';
        } elseif ($globalFinalScore >= 70) {
            $finalGrade = 'B';
        } elseif ($globalFinalScore >= 63) {
            $finalGrade = 'C+';
        } elseif ($globalFinalScore >= 55) {
            $finalGrade = 'C';
        } elseif ($globalFinalScore >= 40) {
            $finalGrade = 'D';
        }

        // Decide status transition
        $oldRegStatus = $registration['current_status'];
        $newRegStatus = $oldRegStatus;

        // Check if both instansi score and lecturer score are submitted to move to final validation
        $scoreStatus = 'draft';
        if ($instScore !== null) {
            $newRegStatus = 'menunggu_validasi_akhir';
            $scoreStatus = 'menunggu_validasi';
        } else {
            $newRegStatus = 'nilai_dosen_masuk';
        }

        $weightsSnapshot = json_encode([
            'institution' => $wInst,
            'lecturer'    => $wLect,
            'admin'       => $wAdm
        ]);

        if ($finalScoreRecord) {
            $this->db->table('final_scores')
                ->where('id', $finalScoreRecord->id)
                ->update([
                    'lecturer_score'  => $lecturerWeightedScore,
                    'final_score'     => $globalFinalScore,
                    'final_grade'     => $finalGrade,
                    'weight_snapshot' => $weightsSnapshot,
                    'status'          => $scoreStatus,
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);
        } else {
            $this->db->table('final_scores')->insert([
                'registration_id'   => $registrationId,
                'lecturer_score'    => $lecturerWeightedScore,
                'final_score'       => $globalFinalScore,
                'final_grade'       => $finalGrade,
                'weight_snapshot'   => $weightsSnapshot,
                'status'            => $scoreStatus,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
        }

        // Update registration status
        if ($newRegStatus !== $oldRegStatus) {
            $this->db->table('kp_registrations')
                ->where('id', $registrationId)
                ->update([
                    'current_status' => $newRegStatus,
                    'updated_at'     => date('Y-m-d H:i:s')
                ]);

            // Add Status Log
            $this->db->table('registration_status_logs')->insert([
                'registration_id' => $registrationId,
                'old_status'      => $oldRegStatus,
                'new_status'      => $newRegStatus,
                'changed_by'      => session()->get('user_id'),
                'changed_by_role' => 'dosen',
                'note'            => 'Nilai akademik dimasukkan oleh Dosen Pembimbing.',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses penyimpanan penilaian.');
        }

        // Notify student
        $studentProf = $this->db->table('student_profiles')->where('id', $registration['student_id'])->get()->getRow();
        $this->db->table('notifications')->insert([
            'user_id'    => $studentProf->user_id,
            'title'      => 'Nilai Dosen Masuk',
            'message'    => 'Nilai akademik Anda telah diinput oleh Dosen Pembimbing.',
            'type'       => 'info',
            'url'        => 'mahasiswa/penilaian',
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Audit Log
        AuditService::log(
            'INPUT_LECTURER_SCORE',
            'final_scores',
            $registrationId,
            ['lecturer_score' => $finalScoreRecord ? $finalScoreRecord->lecturer_score : null],
            ['lecturer_score' => $lecturerWeightedScore, 'final_score' => $globalFinalScore, 'final_grade' => $finalGrade],
            "Menginput nilai akademik bimbingan mahasiswa (Nilai Dosen: {$lecturerWeightedScore})"
        );

        return redirect()->to(base_url('dosen/penilaian'))->with('success', 'Penilaian akademik berhasil disimpan.');
    }

    /**
     * Kuota Bimbingan.
     */
    public function kuotaBimbingan()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;
        $activeStudents = [];

        if ($lecturerId) {
            $activeStudents = $this->db->table('supervisor_assignments')
                ->select('supervisor_assignments.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_registrations.current_status, kp_periods.name as period_name')
                ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->get()
                ->getResultArray();
        }

        $stats = [
            'total_students' => count($activeStudents),
            'max_quota'      => $lecturer ? $lecturer->max_supervision_quota : 10,
        ];

        $data = [
            'title'    => 'Kuota Bimbingan Saya',
            'stats'    => $stats,
            'students' => $activeStudents,
            'lecturer' => $lecturer
        ];

        return view('dosen/kuota_bimbingan', $data);
    }

    /**
     * Riwayat Bimbingan (Archived / completed periods).
     */
    public function riwayatBimbingan()
    {
        $lecturer = $this->getLecturerProfile();
        $lecturerId = $lecturer ? $lecturer->id : null;
        $riwayat = [];

        if ($lecturerId) {
            // Archived or completed students
            $riwayat = $this->db->table('supervisor_assignments')
                ->select('supervisor_assignments.*, student_profiles.npm, student_profiles.full_name, kp_periods.name as period_name, kp_registrations.current_status,
                          final_scores.final_score, final_scores.final_grade')
                ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
                ->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->groupStart()
                    ->where('supervisor_assignments.status', 'inactive')
                    ->orWhereIn('kp_registrations.current_status', ['selesai', 'diarsipkan'])
                    ->orWhereIn('kp_periods.status', ['ditutup', 'diarsipkan'])
                ->groupEnd()
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'   => 'Riwayat Bimbingan',
            'riwayat' => $riwayat
        ];

        return view('dosen/riwayat_bimbingan', $data);
    }

    /**
     * Notifications.
     */
    public function notifikasi()
    {
        $userId = session()->get('user_id');
        $notifications = $this->db->table('notifications')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->limit(100)
            ->get()
            ->getResult();

        $data = [
            'title'         => 'Notifikasi Saya',
            'notifications' => $notifications
        ];

        return view('dosen/notifikasi', $data);
    }

    /**
     * Mark single notification as read.
     */
    public function readNotifikasi($id)
    {
        $userId = session()->get('user_id');
        $notif = $this->db->table('notifications')->where('id', $id)->where('user_id', $userId)->get()->getRow();

        if ($notif) {
            $this->db->table('notifications')
                ->where('id', $id)
                ->update([
                    'is_read' => 1,
                    'read_at' => date('Y-m-d H:i:s')
                ]);
            
            if (!empty($notif->url)) {
                return redirect()->to(base_url($notif->url));
            }
        }

        return redirect()->to(base_url('dosen/notifikasi'));
    }

    /**
     * Mark all notifications as read.
     */
    public function readAllNotifikasi()
    {
        $userId = session()->get('user_id');
        $this->db->table('notifications')
            ->where('user_id', $userId)
            ->update([
                'is_read' => 1,
                'read_at' => date('Y-m-d H:i:s')
            ]);

        return redirect()->to(base_url('dosen/notifikasi'))->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    /**
     * Lecturer Profile view.
     */
    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to(base_url('dosen/dashboard'))->with('error', 'Akun tidak ditemukan.');
        }

        $lecturer = $this->db->table('lecturer_profiles')->where('user_id', $userId)->get()->getRow();
        $prodis = $this->db->table('study_programs')->where('status', 'active')->get()->getResult();

        $data = [
            'title'    => 'Profil Dosen',
            'user'     => $user,
            'lecturer' => $lecturer,
            'prodis'   => $prodis
        ];

        return view('dosen/profile', $data);
    }

    /**
     * Update Lecturer Profile details.
     */
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to(base_url('dosen/dashboard'))->with('error', 'Akun tidak ditemukan.');
        }

        $lecturer = $this->db->table('lecturer_profiles')->where('user_id', $userId)->get()->getRow();

        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email',
            'phone'            => 'required|numeric|min_length[10]|max_length[15]',
            'expertise'        => 'permit_empty|string|max_length[150]',
            'password'         => 'permit_empty|min_length[8]',
            'password_confirm' => 'required_with[password]|matches[password]',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
                'max_length' => 'Nama lengkap maksimal 100 karakter.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'phone' => [
                'required'   => 'Nomor HP wajib diisi.',
                'numeric'    => 'Nomor HP hanya boleh berisi angka.',
                'min_length' => 'Nomor HP minimal 10 digit.',
                'max_length' => 'Nomor HP maksimal 15 digit.',
            ],
            'password' => [
                'min_length' => 'Kata sandi baru minimal 8 karakter.',
            ],
            'password_confirm' => [
                'required_with' => 'Konfirmasi kata sandi wajib diisi jika mengubah kata sandi.',
                'matches'       => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $expertise = $this->request->getPost('expertise');
        $password = $this->request->getPost('password');

        // Check if email already taken by someone else
        $existing = $this->userModel->where('email', $email)->where('id !=', $userId)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Alamat email tersebut sudah digunakan oleh akun lain.');
        }

        $updateUser = [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
        ];

        if (!empty($password)) {
            $updateUser['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->db->transStart();

        $this->userModel->update($userId, $updateUser);

        if ($lecturer) {
            $this->db->table('lecturer_profiles')
                ->where('id', $lecturer->id)
                ->update([
                    'full_name'  => $name,
                    'expertise'  => $expertise,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil karena kesalahan internal.');
        }

        // Audit Log
        AuditService::log('UPDATE_PROFILE', 'users', $userId, [
            'name'  => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
        ], [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
        ], 'Memperbarui profil akun Dosen Pembimbing.');

        // Update session info
        session()->set([
            'name'  => $name,
            'email' => $email
        ]);

        return redirect()->to(base_url('dosen/profile'))->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
