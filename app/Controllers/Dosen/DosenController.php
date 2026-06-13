<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;

class DosenController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Find lecturer profile
        $lecturer = $db->table('lecturer_profiles')->where('user_id', session()->get('user_id'))->get()->getRow();
        $lecturerId = $lecturer ? $lecturer->id : null;

        // Supervised students list
        $supervised = [];
        $pendingLogbooks = 0;
        $pendingReports = 0;

        if ($lecturerId) {
            $supervised = $db->table('supervisor_assignments')
                ->select('supervisor_assignments.*, student_profiles.npm, student_profiles.full_name, kp_registrations.current_status, kp_registrations.id as registration_id')
                ->join('kp_registrations', 'kp_registrations.id = supervisor_assignments.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->where('supervisor_assignments.lecturer_id', $lecturerId)
                ->where('supervisor_assignments.status', 'active')
                ->get()
                ->getResultArray();

            // Counts
            $regIds = array_column($supervised, 'registration_id');
            if (!empty($regIds)) {
                $pendingLogbooks = $db->table('logbook_weeks')
                    ->whereIn('registration_id', $regIds)
                    ->where('status', 'dikirim')
                    ->countAllResults();

                $pendingReports = $db->table('final_reports')
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
        ];

        return view('dosen/index', $data);
    }

    public function placeholder($menuName)
    {
        return view('placeholder', [
            'title'    => $menuName,
            'menuName' => $menuName
        ]);
    }
}
