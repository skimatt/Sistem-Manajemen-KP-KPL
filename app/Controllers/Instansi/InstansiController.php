<?php

namespace App\Controllers\Instansi;

use App\Controllers\BaseController;

class InstansiController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Get institution profile
        $instansi = $db->table('institution_profiles')->where('user_id', session()->get('user_id'))->get()->getRow();
        $instansiId = $instansi ? $instansi->id : null;

        // Fetch students placed
        $students = [];
        $pendingPlacements = 0;

        if ($instansiId) {
            $students = $db->table('placement_requests')
                ->select('placement_requests.*, student_profiles.npm, student_profiles.full_name, kp_registrations.current_status')
                ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
                ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
                ->where('placement_requests.institution_id', $instansiId)
                ->where('placement_requests.status', 'disetujui') // Confirmed placements
                ->get()
                ->getResultArray();

            $pendingPlacements = $db->table('placement_requests')
                ->where('institution_id', $instansiId)
                ->where('status', 'diajukan')
                ->countAllResults();
        }

        $stats = [
            'total_students'     => count($students),
            'pending_placements' => $pendingPlacements,
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

    public function placeholder($menuName)
    {
        return view('placeholder', [
            'title'    => $menuName,
            'menuName' => $menuName
        ]);
    }
}
