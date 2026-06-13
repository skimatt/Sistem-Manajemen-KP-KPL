<?php

namespace App\Models;

use CodeIgniter\Model;

class LogbookWeekModel extends Model
{
    protected $table            = 'logbook_weeks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'uuid',
        'registration_id',
        'week_number',
        'start_date',
        'end_date',
        'weekly_target',
        'weekly_result',
        'obstacle',
        'next_plan',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateUuid'];

    protected function generateUuid(array $data)
    {
        if (!isset($data['data']['uuid'])) {
            $data['data']['uuid'] = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }
        return $data;
    }

    public function getActiveStudentsLogbooksSummary()
    {
        $db = \Config\Database::connect();
        return $db->table('kp_registrations')
            ->select('kp_registrations.id as registration_id, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name, 
                      lecturer_profiles.full_name as supervisor_name, institution_profiles.name as institution_name,
                      (SELECT COUNT(*) FROM logbook_weeks WHERE logbook_weeks.registration_id = kp_registrations.id AND logbook_weeks.deleted_at IS NULL) as total_weeks,
                      (SELECT COUNT(*) FROM logbook_weeks WHERE logbook_weeks.registration_id = kp_registrations.id AND logbook_weeks.status = "disetujui" AND logbook_weeks.deleted_at IS NULL) as approved_weeks')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->join('supervisor_assignments', 'supervisor_assignments.registration_id = kp_registrations.id AND supervisor_assignments.status = "active"', 'left')
            ->join('lecturer_profiles', 'lecturer_profiles.id = supervisor_assignments.lecturer_id', 'left')
            ->join('placement_requests', 'placement_requests.registration_id = kp_registrations.id AND placement_requests.status = "disetujui"', 'left')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->where('kp_registrations.deleted_at', null)
            ->get()
            ->getResultArray();
    }
}
