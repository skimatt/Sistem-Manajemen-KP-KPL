<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FormTemplateModel;
use App\Models\FormFieldModel;
use App\Models\PeriodModel;
use App\Services\AuditService;

class FormBuilderController extends BaseController
{
    protected $formTemplateModel;
    protected $formFieldModel;
    protected $periodModel;

    public function __construct()
    {
        $this->formTemplateModel = new FormTemplateModel();
        $this->formFieldModel    = new FormFieldModel();
        $this->periodModel       = new PeriodModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Form Builder',
            'templates' => $this->formTemplateModel->getTemplatesWithPeriod(),
        ];
        return view('admin/form-builder/index', $data);
    }

    public function create()
    {
        $data = [
            'title'   => 'Tambah Template Formulir',
            'periods' => $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/form-builder/create', $data);
    }

    public function store()
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[150]',
            'form_type' => 'required|max_length[50]',
            'version'   => 'required|integer|greater_than[0]',
            'period_id' => 'permit_empty',
            'status'    => 'required|in_list[draft,active,inactive,archived]',
        ];

        $errors = [
            'name' => [
                'required'   => 'Nama formulir wajib diisi.',
                'min_length' => 'Nama formulir minimal 3 karakter.',
                'max_length' => 'Nama formulir maksimal 150 karakter.',
            ],
            'form_type' => [
                'required'   => 'Tipe formulir wajib diisi.',
                'max_length' => 'Tipe formulir maksimal 50 karakter.',
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
        if (empty($postData['period_id'])) {
            $postData['period_id'] = null;
        }
        $postData['created_by'] = session()->get('user_id');

        $this->formTemplateModel->insert($postData);
        $insertedId = $this->formTemplateModel->getInsertID();

        AuditService::log(
            'CREATE_FORM_TEMPLATE',
            'form_templates',
            $insertedId,
            null,
            $postData,
            'Membuat template formulir baru: ' . $postData['name']
        );

        return redirect()->to(base_url('admin/form-builder'))->with('success', 'Template formulir berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $template = $this->formTemplateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/form-builder'))->with('error', 'Template formulir tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Template Formulir',
            'template' => $template,
            'periods'  => $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll(),
        ];
        return view('admin/form-builder/edit', $data);
    }

    public function update($id)
    {
        $template = $this->formTemplateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/form-builder'))->with('error', 'Template formulir tidak ditemukan.');
        }

        $rules = [
            'name'      => 'required|min_length[3]|max_length[150]',
            'form_type' => 'required|max_length[50]',
            'version'   => 'required|integer|greater_than[0]',
            'period_id' => 'permit_empty',
            'status'    => 'required|in_list[draft,active,inactive,archived]',
        ];

        $errors = [
            'name' => [
                'required'   => 'Nama formulir wajib diisi.',
                'min_length' => 'Nama formulir minimal 3 karakter.',
                'max_length' => 'Nama formulir maksimal 150 karakter.',
            ],
            'form_type' => [
                'required'   => 'Tipe formulir wajib diisi.',
                'max_length' => 'Tipe formulir maksimal 50 karakter.',
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
        if (empty($postData['period_id'])) {
            $postData['period_id'] = null;
        }

        $this->formTemplateModel->update($id, $postData);

        AuditService::log(
            'UPDATE_FORM_TEMPLATE',
            'form_templates',
            $id,
            $template,
            $postData,
            'Mengubah template formulir: ' . $postData['name']
        );

        return redirect()->to(base_url('admin/form-builder'))->with('success', 'Template formulir berhasil diperbarui.');
    }

    public function delete($id)
    {
        $template = $this->formTemplateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/form-builder'))->with('error', 'Template formulir tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $isUsed = $db->table('form_responses')->where('form_template_id', $id)->countAllResults();
        if ($isUsed > 0) {
            return redirect()->to(base_url('admin/form-builder'))->with('error', 'Gagal menghapus! Template formulir ini telah memiliki data respon yang diisi oleh user.');
        }

        $this->formTemplateModel->delete($id);

        AuditService::log(
            'DELETE_FORM_TEMPLATE',
            'form_templates',
            $id,
            $template,
            null,
            'Menghapus template formulir: ' . $template['name']
        );

        return redirect()->to(base_url('admin/form-builder'))->with('success', 'Template formulir berhasil dihapus.');
    }

    public function fields($id)
    {
        $template = $this->formTemplateModel->find($id);
        if (!$template) {
            return redirect()->to(base_url('admin/form-builder'))->with('error', 'Template formulir tidak ditemukan.');
        }

        $data = [
            'title'    => 'Kelola Field Formulir - ' . esc($template['name']),
            'template' => $template,
            'fields'   => $this->formFieldModel->where('form_template_id', $id)->orderBy('sort_order', 'ASC')->findAll(),
        ];
        return view('admin/form-builder/fields', $data);
    }

    public function addField($formTemplateId)
    {
        $template = $this->formTemplateModel->find($formTemplateId);
        if (!$template) {
            return redirect()->to(base_url('admin/form-builder'))->with('error', 'Template formulir tidak ditemukan.');
        }

        $rules = [
            'field_name'       => 'required|alpha_dash|max_length[100]',
            'label'            => 'required|max_length[150]',
            'field_type'       => 'required|in_list[text,select,file,date,number,textarea]',
            'options_json'     => 'permit_empty',
            'validation_rules' => 'permit_empty',
            'is_required'      => 'required|in_list[0,1]',
            'sort_order'       => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $postData = $this->request->getPost();
        $postData['form_template_id'] = $formTemplateId;
        $postData['status']           = 'active';

        // Parse options_json if not empty
        if (!empty($postData['options_json'])) {
            $options = explode(',', $postData['options_json']);
            $options = array_map('trim', $options);
            $postData['options_json'] = json_encode($options);
        } else {
            $postData['options_json'] = null;
        }

        $this->formFieldModel->insert($postData);

        AuditService::log(
            'ADD_FORM_FIELD',
            'form_fields',
            $this->formFieldModel->getInsertID(),
            null,
            $postData,
            'Menambahkan field "' . $postData['label'] . '" ke formulir: ' . $template['name']
        );

        return redirect()->to(base_url('admin/form-builder/fields/' . $formTemplateId))->with('success', 'Field berhasil ditambahkan.');
    }

    public function deleteField($formTemplateId, $fieldId)
    {
        $field = $this->formFieldModel->find($fieldId);
        if (!$field) {
            return redirect()->to(base_url('admin/form-builder/fields/' . $formTemplateId))->with('error', 'Field tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $isUsed = $db->table('form_response_values')->where('form_field_id', $fieldId)->countAllResults();
        if ($isUsed > 0) {
            return redirect()->to(base_url('admin/form-builder/fields/' . $formTemplateId))->with('error', 'Gagal menghapus! Field ini sudah memiliki data jawaban dari user.');
        }

        $this->formFieldModel->delete($fieldId);

        AuditService::log(
            'DELETE_FORM_FIELD',
            'form_fields',
            $fieldId,
            $field,
            null,
            'Menghapus field "' . $field['label'] . '" dari template ID: ' . $formTemplateId
        );

        return redirect()->to(base_url('admin/form-builder/fields/' . $formTemplateId))->with('success', 'Field berhasil dihapus.');
    }
}
