<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DocumentTemplateModel;
use App\Services\AuditService;

class TemplateSuratController extends BaseController
{
    protected $templateModel;

    public function __construct()
    {
        $this->templateModel = new DocumentTemplateModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Template Surat & Dokumen',
            'templates' => $this->templateModel->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/template-surat/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Template Surat',
        ];
        return view('admin/template-surat/create', $data);
    }

    public function store()
    {
        $rules = [
            'name'          => 'required|min_length[3]|max_length[150]',
            'code'          => 'required|alpha_dash|max_length[50]|is_unique[document_templates.code]',
            'document_type' => 'required|max_length[50]',
            'content_html'  => 'required',
            'version'       => 'required|integer|greater_than[0]',
            'status'        => 'required|in_list[draft,active,inactive,archived]',
        ];

        $errors = [
            'name' => [
                'required'   => 'Nama template wajib diisi.',
                'min_length' => 'Nama template minimal 3 karakter.',
                'max_length' => 'Nama template maksimal 150 karakter.',
            ],
            'code' => [
                'required'   => 'Kode template wajib diisi.',
                'alpha_dash' => 'Kode template hanya boleh berisi huruf, angka, strip, dan underscore.',
                'max_length' => 'Kode template maksimal 50 karakter.',
                'is_unique'  => 'Kode template sudah digunakan.',
            ],
            'document_type' => [
                'required'   => 'Jenis dokumen wajib diisi.',
                'max_length' => 'Jenis dokumen maksimal 50 karakter.',
            ],
            'content_html' => [
                'required' => 'Isi HTML template wajib diisi.',
            ],
            'version' => [
                'required'     => 'Versi wajib diisi.',
                'integer'      => 'Versi harus berupa angka.',
                'greater_than' => 'Versi harus lebih besar dari 0.',
            ],
            'status' => [
                'required' => 'Status wajib diisi.',
                'in_list'  => 'Status tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        $postData['created_by'] = session()->get('user_id');

        $this->templateModel->insert($postData);
        $insertedId = $this->templateModel->getInsertID();

        AuditService::log(
            'CREATE_DOCUMENT_TEMPLATE',
            'document_templates',
            $insertedId,
            null,
            $postData,
            'Membuat template surat baru: ' . $postData['name']
        );

        return redirect()->to(base_url('admin/template-surat'))->with('success', 'Template surat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/template-surat'))->with('error', 'Template surat tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Template Surat',
            'template' => $template,
        ];
        return view('admin/template-surat/edit', $data);
    }

    public function update($id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/template-surat'))->with('error', 'Template surat tidak ditemukan.');
        }

        $rules = [
            'name'          => 'required|min_length[3]|max_length[150]',
            'code'          => 'required|alpha_dash|max_length[50]|is_unique[document_templates.code,id,' . $id . ']',
            'document_type' => 'required|max_length[50]',
            'content_html'  => 'required',
            'version'       => 'required|integer|greater_than[0]',
            'status'        => 'required|in_list[draft,active,inactive,archived]',
        ];

        $errors = [
            'name' => [
                'required'   => 'Nama template wajib diisi.',
                'min_length' => 'Nama template minimal 3 karakter.',
                'max_length' => 'Nama template maksimal 150 karakter.',
            ],
            'code' => [
                'required'   => 'Kode template wajib diisi.',
                'alpha_dash' => 'Kode template hanya boleh berisi huruf, angka, strip, dan underscore.',
                'max_length' => 'Kode template maksimal 50 karakter.',
                'is_unique'  => 'Kode template sudah digunakan oleh data lain.',
            ],
            'document_type' => [
                'required'   => 'Jenis dokumen wajib diisi.',
                'max_length' => 'Jenis dokumen maksimal 50 karakter.',
            ],
            'content_html' => [
                'required' => 'Isi HTML template wajib diisi.',
            ],
            'version' => [
                'required'     => 'Versi wajib diisi.',
                'integer'      => 'Versi harus berupa angka.',
                'greater_than' => 'Versi harus lebih besar dari 0.',
            ],
            'status' => [
                'required' => 'Status wajib diisi.',
                'in_list'  => 'Status tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        
        $this->templateModel->update($id, $postData);

        AuditService::log(
            'UPDATE_DOCUMENT_TEMPLATE',
            'document_templates',
            $id,
            $template,
            $postData,
            'Mengubah template surat: ' . $postData['name']
        );

        return redirect()->to(base_url('admin/template-surat'))->with('success', 'Template surat berhasil diperbarui.');
    }

    public function delete($id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/template-surat'))->with('error', 'Template surat tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $isUsed = $db->table('generated_documents')->where('template_id', $id)->countAllResults();
        if ($isUsed > 0) {
            return redirect()->to(base_url('admin/template-surat'))->with('error', 'Gagal menghapus! Template surat ini sedang digunakan oleh dokumen cetak mahasiswa.');
        }

        $this->templateModel->delete($id);

        AuditService::log(
            'DELETE_DOCUMENT_TEMPLATE',
            'document_templates',
            $id,
            $template,
            null,
            'Menghapus template surat: ' . $template['name']
        );

        return redirect()->to(base_url('admin/template-surat'))->with('success', 'Template surat berhasil dihapus.');
    }
}
