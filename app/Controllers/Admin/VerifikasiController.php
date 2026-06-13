<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudentDocumentModel;
use App\Models\RegistrationModel;
use App\Services\AuditService;

class VerifikasiController extends BaseController
{
    protected $documentModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->documentModel     = new StudentDocumentModel();
        $this->registrationModel = new RegistrationModel();
    }

    public function index()
    {
        // Lists students who have submitted documents
        $db = \Config\Database::connect();
        $students = $db->table('kp_registrations')
            ->select('kp_registrations.id as registration_id, student_profiles.npm, student_profiles.full_name, study_programs.name as prodi_name, kp_periods.name as period_name, kp_registrations.current_status,
                      (SELECT COUNT(*) FROM student_documents WHERE student_documents.registration_id = kp_registrations.id AND student_documents.status = "menunggu_verifikasi" AND student_documents.deleted_at IS NULL) as pending_count,
                      (SELECT COUNT(*) FROM student_documents WHERE student_documents.registration_id = kp_registrations.id AND student_documents.deleted_at IS NULL) as total_uploaded')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->join('study_programs', 'study_programs.id = student_profiles.study_program_id', 'left')
            ->join('kp_periods', 'kp_periods.id = kp_registrations.period_id')
            ->where('kp_registrations.deleted_at', null)
            ->orderBy('pending_count', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Verifikasi Administrasi',
            'students' => $students,
        ];
        return view('admin/verifikasi/index', $data);
    }

    public function review($registrationId)
    {
        $registration = $this->registrationModel->getRegistrationDetails($registrationId);
        if (!$registration) {
            return redirect()->to(base_url('admin/verifikasi-administrasi'))->with('error', 'Data registrasi tidak ditemukan.');
        }

        // Fetch uploaded documents for this registration
        $documents = $this->documentModel->getDocumentsByRegistration($registrationId);

        $data = [
            'title'        => 'Review Dokumen Persyaratan',
            'registration' => $registration,
            'documents'    => $documents,
        ];
        return view('admin/verifikasi/review', $data);
    }

    public function updateDocument($documentId)
    {
        $document = $this->documentModel->find($documentId);
        if (!$document) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $validation = \Config\Services::validation();
        $rules = [
            'status'            => 'required|in_list[valid,perlu_revisi,ditolak]',
            'verification_note' => 'permit_empty|string',
        ];

        $messages = [
            'status' => [
                'required' => 'Status verifikasi wajib dipilih.',
                'in_list'  => 'Status verifikasi tidak valid.',
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        $status           = $this->request->getPost('status');
        $verificationNote = $this->request->getPost('verification_note');

        // Check validation rules: if status is 'perlu_revisi' or 'ditolak', notes should be filled
        if (in_list($status, ['perlu_revisi', 'ditolak']) && empty(trim($verificationNote))) {
            return redirect()->back()->with('error', 'Catatan wajib diisi jika status dokumen perlu revisi atau ditolak.');
        }

        $oldValues = [
            'status'            => $document['status'],
            'verification_note' => $document['verification_note'],
            'verified_by'       => $document['verified_by'],
            'verified_at'       => $document['verified_at'],
        ];

        $documentData = [
            'status'            => $status,
            'verification_note' => $verificationNote ?: null,
            'verified_by'       => session()->get('user_id'),
            'verified_at'       => date('Y-m-d H:i:s'),
        ];

        if ($this->documentModel->update($documentId, $documentData)) {
            AuditService::log(
                'VERIFY_DOCUMENT',
                'student_documents',
                $documentId,
                $oldValues,
                $documentData,
                "Memverifikasi dokumen '" . $document['document_name'] . "' menjadi: " . strtoupper($status)
            );
            return redirect()->to(base_url('admin/verifikasi-administrasi/review/' . $document['registration_id']))->with('success', 'Status verifikasi dokumen berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status dokumen.');
    }
}
