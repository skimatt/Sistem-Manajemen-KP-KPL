<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentRequirementModel extends Model
{
    protected $table            = 'document_requirements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'period_id',
        'document_name',
        'document_code',
        'allowed_extensions',
        'max_size_kb',
        'is_required',
        'stage',
        'sort_order',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getRequirementsWithPeriod()
    {
        return $this->select('document_requirements.*, kp_periods.name as period_name')
            ->join('kp_periods', 'kp_periods.id = document_requirements.period_id')
            ->orderBy('document_requirements.period_id', 'DESC')
            ->orderBy('document_requirements.sort_order', 'ASC')
            ->findAll();
    }
}
