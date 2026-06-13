<?php

namespace App\Models;

use CodeIgniter\Model;

class FinalReportModel extends Model
{
    protected $table            = 'final_reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'uuid',
        'registration_id',
        'uploaded_by',
        'title',
        'file_path',
        'original_name',
        'stored_name',
        'file_ext',
        'file_size_kb',
        'version',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
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

    public function getReportsWithDetails()
    {
        return $this->select('final_reports.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name, reviewer.name as reviewer_name')
            ->join('kp_registrations', 'kp_registrations.id = final_reports.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->join('users as reviewer', 'reviewer.id = final_reports.reviewed_by', 'left')
            ->where('final_reports.deleted_at', null)
            ->orderBy('final_reports.id', 'DESC')
            ->findAll();
    }
}
