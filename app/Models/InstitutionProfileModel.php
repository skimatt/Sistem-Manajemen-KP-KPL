<?php

namespace App\Models;

use CodeIgniter\Model;

class InstitutionProfileModel extends Model
{
    protected $table            = 'institution_profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'uuid',
        'name',
        'type',
        'field_category',
        'address',
        'district',
        'city',
        'province',
        'contact_person',
        'contact_position',
        'contact_phone',
        'contact_email',
        'partnership_status',
        'has_account',
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

    // Join helper
    public function getInstitutionsWithDetails()
    {
        return $this->select('institution_profiles.*, users.email, users.status as user_status')
            ->join('users', 'users.id = institution_profiles.user_id', 'left')
            ->where('institution_profiles.deleted_at', null)
            ->findAll();
    }
}
