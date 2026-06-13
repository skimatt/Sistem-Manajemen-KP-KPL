<?php

namespace App\Models;

use CodeIgniter\Model;

class FinalScoreModel extends Model
{
    protected $table            = 'final_scores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'registration_id',
        'institution_score',
        'lecturer_score',
        'admin_score',
        'final_score',
        'final_grade',
        'weight_snapshot',
        'status',
        'validated_by',
        'validated_at',
        'validation_note',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getFinalScoresWithDetails()
    {
        return $this->select('final_scores.*, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name, validator.name as validator_name')
            ->join('kp_registrations', 'kp_registrations.id = final_scores.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->join('users as validator', 'validator.id = final_scores.validated_by', 'left')
            ->orderBy('final_scores.id', 'DESC')
            ->findAll();
    }
}
