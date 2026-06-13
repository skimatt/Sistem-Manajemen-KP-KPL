<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentDocumentModel extends Model
{
    protected $table            = 'student_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'uuid',
        'registration_id',
        'requirement_id',
        'uploaded_by',
        'document_name',
        'document_code',
        'original_name',
        'stored_name',
        'file_path',
        'file_ext',
        'file_size_kb',
        'mime_type',
        'version',
        'status',
        'verified_by',
        'verified_at',
        'verification_note',
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

    public function getDocumentsByRegistration($registrationId)
    {
        return $this->where('registration_id', $registrationId)
            ->where('deleted_at', null)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getPendingStudentsWithDetails()
    {
        // Lists students who have at least one document in 'menunggu_verifikasi'
        return $this->select('kp_registrations.id as registration_id, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name, COUNT(student_documents.id) as pending_count')
            ->join('kp_registrations', 'kp_registrations.id = student_documents.registration_id')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->where('student_documents.status', 'menunggu_verifikasi')
            ->where('student_documents.deleted_at', null)
            ->groupBy('kp_registrations.id')
            ->orderBy('pending_count', 'DESC')
            ->findAll();
    }
}
