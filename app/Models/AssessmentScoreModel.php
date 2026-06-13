<?php

namespace App\Models;

use CodeIgniter\Model;

class AssessmentScoreModel extends Model
{
    protected $table            = 'assessment_scores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'registration_id',
        'assessment_template_id',
        'component_id',
        'assessor_user_id',
        'assessor_role',
        'score',
        'note',
        'source_document_id',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
