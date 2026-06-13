<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentProfileModel extends Model
{
    protected $table            = 'student_profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'npm',
        'full_name',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'address',
        'district',
        'city',
        'province',
        'phone',
        'parent_name',
        'parent_phone',
        'study_program_id',
        'generation_year',
        'current_semester',
        'profile_status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Helper to join with users and prodi
    public function getStudentsWithDetails()
    {
        return $this->select('student_profiles.*, users.email, users.status as user_status, study_programs.name as prodi_name')
            ->join('users', 'users.id = student_profiles.user_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->where('student_profiles.deleted_at', null)
            ->findAll();
    }
}
