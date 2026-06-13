<?php

namespace App\Models;

use CodeIgniter\Model;

class TopsisWeightModel extends Model
{
    protected $table            = 'topsis_weights';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'period_id',
        'criteria_id',
        'weight',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWeightsByPeriod($periodId)
    {
        return $this->select('topsis_weights.*, topsis_criteria.code, topsis_criteria.name, topsis_criteria.type')
            ->join('topsis_criteria', 'topsis_criteria.id = topsis_weights.criteria_id')
            ->where('topsis_weights.period_id', $periodId)
            ->findAll();
    }
}
