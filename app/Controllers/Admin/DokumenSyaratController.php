<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DocumentRequirementModel;
use App\Models\PeriodModel;
use App\Services\AuditService;

class DokumenSyaratController extends BaseController
{
    protected $requirementModel;
    protected $periodModel;

    public function __construct()
    {
        $this->requirementModel = new DocumentRequirementModel();
        $this->periodModel      = new PeriodModel();
    }

    public function index()
    {
        $data = [
            'title'        => 'Dokumen Persyaratan',
            'requirements' => $this->requirementModel->getRequirementsWithPeriod(),
        ];
        return view('admin/dokumen-syarat/index', $data);
    }

    public function create()
    {
        $data = [
            'title'   => 'Tambah Dokumen Persyaratan',
            'periods' => $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/dokumen-syarat/create', $data);
    }

    public function store()
    {
        $rules = [
            'period_id'          => 'required',
            'document_name'      => 'required|min_length[3]|max_length[150]',
            'document_code'      => 'required|alpha_dash|max_length[80]',
            'allowed_extensions' => 'required',
            'max_size_kb'        => 'required|integer|greater_than[0]',
            'is_required'        => 'required|in_list[0,1]',
            'stage'              => 'required|in_list[registrasi,penempatan,penilaian]',
            'sort_order'         => 'required|integer',
            'status'             => 'required|in_list[active,inactive]',
        ];

        $errors = [
            'period_id' => [
                'required' => 'Periode wajib dipilih.',
            ],
            'document_name' => [
                'required'   => 'Nama dokumen wajib diisi.',
                'min_length' => 'Nama dokumen minimal 3 karakter.',
                'max_length' => 'Nama dokumen maksimal 150 karakter.',
            ],
            'document_code' => [
                'required'   => 'Kode dokumen wajib diisi.',
                'alpha_dash' => 'Kode dokumen hanya boleh berisi huruf, angka, strip, dan underscore.',
                'max_length' => 'Kode dokumen maksimal 80 karakter.',
            ],
            'allowed_extensions' => [
                'required' => 'Ekstensi file yang diperbolehkan wajib diisi.',
            ],
            'max_size_kb' => [
                'required'     => 'Ukuran file maksimal wajib diisi.',
                'integer'      => 'Ukuran file harus berupa angka bulat.',
                'greater_than' => 'Ukuran file harus lebih besar dari 0.',
            ],
            'is_required' => [
                'required' => 'Status wajib diisi.',
                'in_list'  => 'Status wajib tidak valid.',
            ],
            'stage' => [
                'required' => 'Tahapan alur wajib diisi.',
                'in_list'  => 'Tahapan alur tidak valid.',
            ],
            'sort_order' => [
                'required' => 'Urutan tampil wajib diisi.',
                'integer'  => 'Urutan tampil harus berupa angka.',
            ],
            'status' => [
                'required' => 'Status keaktifan wajib diisi.',
                'in_list'  => 'Status keaktifan tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        
        $this->requirementModel->insert($postData);
        $insertedId = $this->requirementModel->getInsertID();

        AuditService::log(
            'CREATE_DOCUMENT_REQUIREMENT',
            'document_requirements',
            $insertedId,
            null,
            $postData,
            'Membuat dokumen persyaratan baru: ' . $postData['document_name']
        );

        return redirect()->to(base_url('admin/dokumen-syarat'))->with('success', 'Dokumen persyaratan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $requirement = $this->requirementModel->find($id);
        if (!$requirement) {
            return redirect()->to(base_url('admin/dokumen-syarat'))->with('error', 'Dokumen persyaratan tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Dokumen Persyaratan',
            'requirement' => $requirement,
            'periods'     => $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/dokumen-syarat/edit', $data);
    }

    public function update($id)
    {
        $requirement = $this->requirementModel->find($id);
        if (!$requirement) {
            return redirect()->to(base_url('admin/dokumen-syarat'))->with('error', 'Dokumen persyaratan tidak ditemukan.');
        }

        $rules = [
            'period_id'          => 'required',
            'document_name'      => 'required|min_length[3]|max_length[150]',
            'document_code'      => 'required|alpha_dash|max_length[80]',
            'allowed_extensions' => 'required',
            'max_size_kb'        => 'required|integer|greater_than[0]',
            'is_required'        => 'required|in_list[0,1]',
            'stage'              => 'required|in_list[registrasi,penempatan,penilaian]',
            'sort_order'         => 'required|integer',
            'status'             => 'required|in_list[active,inactive]',
        ];

        $errors = [
            'period_id' => [
                'required' => 'Periode wajib dipilih.',
            ],
            'document_name' => [
                'required'   => 'Nama dokumen wajib diisi.',
                'min_length' => 'Nama dokumen minimal 3 karakter.',
                'max_length' => 'Nama dokumen maksimal 150 karakter.',
            ],
            'document_code' => [
                'required'   => 'Kode dokumen wajib diisi.',
                'alpha_dash' => 'Kode dokumen hanya boleh berisi huruf, angka, strip, dan underscore.',
                'max_length' => 'Kode dokumen maksimal 80 karakter.',
            ],
            'allowed_extensions' => [
                'required' => 'Ekstensi file yang diperbolehkan wajib diisi.',
            ],
            'max_size_kb' => [
                'required'     => 'Ukuran file maksimal wajib diisi.',
                'integer'      => 'Ukuran file harus berupa angka bulat.',
                'greater_than' => 'Ukuran file harus lebih besar dari 0.',
            ],
            'is_required' => [
                'required' => 'Status wajib diisi.',
                'in_list'  => 'Status wajib tidak valid.',
            ],
            'stage' => [
                'required' => 'Tahapan alur wajib diisi.',
                'in_list'  => 'Tahapan alur tidak valid.',
            ],
            'sort_order' => [
                'required' => 'Urutan tampil wajib diisi.',
                'integer'  => 'Urutan tampil harus berupa angka.',
            ],
            'status' => [
                'required' => 'Status keaktifan wajib diisi.',
                'in_list'  => 'Status keaktifan tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        
        $this->requirementModel->update($id, $postData);

        AuditService::log(
            'UPDATE_DOCUMENT_REQUIREMENT',
            'document_requirements',
            $id,
            $requirement,
            $postData,
            'Mengubah dokumen persyaratan: ' . $postData['document_name']
        );

        return redirect()->to(base_url('admin/dokumen-syarat'))->with('success', 'Dokumen persyaratan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $requirement = $this->requirementModel->find($id);
        if (!$requirement) {
            return redirect()->to(base_url('admin/dokumen-syarat'))->with('error', 'Dokumen persyaratan tidak ditemukan.');
        }

        // Safety check: is it used by student_documents?
        $db = \Config\Database::connect();
        $isUsed = $db->table('student_documents')->where('requirement_id', $id)->countAllResults();
        if ($isUsed > 0) {
            return redirect()->to(base_url('admin/dokumen-syarat'))->with('error', 'Gagal menghapus! Dokumen persyaratan ini sedang digunakan oleh berkas mahasiswa.');
        }

        $this->requirementModel->delete($id);

        AuditService::log(
            'DELETE_DOCUMENT_REQUIREMENT',
            'document_requirements',
            $id,
            $requirement,
            null,
            'Menghapus dokumen persyaratan: ' . $requirement['document_name']
        );

        return redirect()->to(base_url('admin/dokumen-syarat'))->with('success', 'Dokumen persyaratan berhasil dihapus.');
    }
}
