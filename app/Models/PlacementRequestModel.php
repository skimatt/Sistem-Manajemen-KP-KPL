<?php

namespace App\Models;

use CodeIgniter\Model;

class PlacementRequestModel extends Model
{
    protected $table            = 'placement_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'uuid',
        'registration_id',
        'placement_type',
        'institution_id',
        'proposed_institution_name',
        'proposed_address',
        'proposed_field',
        'contact_person',
        'contact_position',
        'contact_phone',
        'contact_email',
        'reason',
        'status',
        'submitted_at',
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

    public function getPlacementRequestsWithDetails()
    {
        return $this->select('placement_requests.*, student_profiles.npm, student_profiles.full_name, institution_profiles.name as partner_institution_name, study_programs.name as prodi_name')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->where('placement_requests.deleted_at', null)
            ->orderBy('placement_requests.id', 'DESC')
            ->findAll();
    }

    public function getPlacementRequestDetails($id)
    {
        return $this->select('placement_requests.*, student_profiles.npm, student_profiles.full_name, institution_profiles.name as partner_institution_name, study_programs.name as prodi_name, kp_periods.name as period_name, reviewer.name as reviewer_name')
            ->join('kp_registrations', 'kp_registrations.id = placement_requests.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->join('institution_profiles', 'institution_profiles.id = placement_requests.institution_id', 'left')
            ->join('users as reviewer', 'reviewer.id = placement_requests.reviewed_by', 'left')
            ->where('placement_requests.id', $id)
            ->first();
    }
}
