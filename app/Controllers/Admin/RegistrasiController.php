<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegistrationModel;

class RegistrasiController extends BaseController
{
    protected $registrationModel;

    public function __construct()
    {
        $this->registrationModel = new RegistrationModel();
    }

    public function index()
    {
        $data = [
            'title'         => 'Data Registrasi',
            'registrations' => $this->registrationModel->getRegistrationsWithDetails(),
        ];
        return view('admin/registrasi/index', $data);
    }

    public function view($id)
    {
        $registration = $this->registrationModel->getRegistrationDetails($id);
        if (!$registration) {
            return redirect()->to(base_url('admin/registrasi'))->with('error', 'Data registrasi tidak ditemukan.');
        }

        // Fetch logs
        $db = \Config\Database::connect();
        $statusLogs = $db->table('registration_status_logs')
            ->select('registration_status_logs.*, users.name as user_name')
            ->join('users', 'users.id = registration_status_logs.changed_by', 'left')
            ->where('registration_id', $id)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        // Fetch uploaded documents
        $documents = $db->table('student_documents')
            ->where('registration_id', $id)
            ->where('deleted_at', null)
            ->get()
            ->getResultArray();

        $data = [
            'title'        => 'Detail Registrasi Mahasiswa',
            'registration' => $registration,
            'statusLogs'   => $statusLogs,
            'documents'    => $documents,
        ];
        return view('admin/registrasi/view', $data);
    }
}
