<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table            = 'kp_registrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'period_id',
        'student_id',
        'current_status',
        'academic_sks',
        'academic_gpa',
        'is_gpa_eligible',
        'passed_basic_programming',
        'passed_data_structure',
        'passed_database',
        'passed_system_analysis',
        'passed_networking',
        'passed_concentration_course',
        'education_payment_status',
        'academic_advisor_name',
        'advisor_recommendation_status',
        'submitted_at',
        'verified_at',
        'verified_by',
        'final_note',
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

    public function getRegistrationsWithDetails()
    {
        return $this->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->where('kp_registrations.deleted_at', null)
            ->orderBy('kp_registrations.id', 'DESC')
            ->findAll();
    }

    public function getRegistrationDetails($id)
    {
        return $this->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name, student_profiles.birth_place, student_profiles.birth_date, student_profiles.gender, student_profiles.religion, student_profiles.address, student_profiles.phone, student_profiles.parent_name, student_profiles.parent_phone, student_profiles.generation_year, student_profiles.current_semester as profile_semester, study_programs.name as prodi_name, study_programs.code as prodi_code, kp_periods.name as period_name, users.email')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->join('users', 'users.id = student_profiles.user_id')
            ->where('kp_registrations.id', $id)
            ->first();
    }
}
