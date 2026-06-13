<?php

namespace App\Models;

use CodeIgniter\Model;

class LecturerProfileModel extends Model
{
    protected $table            = 'lecturer_profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nidn',
        'full_name',
        'study_program_id',
        'expertise',
        'max_supervision_quota',
        'is_available',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Join helper
    public function getLecturersWithDetails()
    {
        return $this->select('lecturer_profiles.*, users.email, users.status as user_status, study_programs.name as prodi_name')
            ->join('users', 'users.id = lecturer_profiles.user_id')
            ->join('study_programs', 'study_programs.id = lecturer_profiles.study_program_id', 'left')
            ->where('lecturer_profiles.deleted_at', null)
            ->findAll();
    }
}
