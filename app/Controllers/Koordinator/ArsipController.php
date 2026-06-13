<?php

namespace App\Controllers\Koordinator;

use App\Controllers\BaseController;
use App\Models\PeriodModel;
use App\Models\RegistrationModel;

class ArsipController extends BaseController
{
    protected $periodModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->periodModel       = new PeriodModel();
        $this->registrationModel = new RegistrationModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // Fetch only archived periods
        $periods = $db->table('kp_periods')
            ->select('kp_periods.*, study_programs.name as prodi_name,
                      (SELECT COUNT(*) FROM kp_registrations WHERE kp_registrations.period_id = kp_periods.id AND kp_registrations.deleted_at IS NULL) as total_students')
            ->join('study_programs', 'study_programs.id = kp_periods.study_program_id')
            ->where('kp_periods.status', 'diarsipkan')
            ->where('kp_periods.deleted_at', null)
            ->orderBy('kp_periods.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Arsip Periode KP/KPL',
            'periods' => $periods,
        ];

        return view('koordinator/manajemen/arsip/index', $data);
    }

    public function view($id)
    {
        $period = $this->periodModel->find($id);
        if (!$period || $period['status'] !== 'diarsipkan') {
            return redirect()->to(base_url('koordinator/arsip'))->with('error', 'Arsip periode tidak ditemukan atau belum diarsipkan.');
        }

        $db = \Config\Database::connect();

        // Fetch all student records in the archived period
        $students = $db->table('kp_registrations')
            ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name,
                      institution_profiles.name as instansi_name,
                      lecturer_profiles.full_name as dosen_name,
                      final_scores.final_score, final_scores.final_grade,
                      final_reports.id as report_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('placement_choices', 'placement_choices.registration_id = kp_registrations.id AND placement_choices.is_selected = 1', 'left')
            ->join('institution_profiles', 'institution_profiles.id = placement_choices.institution_id', 'left')
            ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id AND supervisor_assignments.status = "active"', 'left')
            ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id', 'left')
            ->join('final_scores', 'final_scores.registration_id = kp_registrations.id', 'left')
            ->join('final_reports', 'final_reports.registration_id = kp_registrations.id AND final_reports.status = "disetujui"', 'left')
            ->where('kp_registrations.period_id', $id)
            ->where('kp_registrations.deleted_at', null)
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Detail Arsip: ' . $period['name'],
            'period'  => $period,
            'students' => $students,
        ];

        return view('koordinator/manajemen/arsip/view', $data);
    }
}
