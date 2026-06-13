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

    public function generateKpKplRegistration()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $template = $db->table('form_templates')
            ->where('form_type', 'registration')
            ->where('name', 'Formulir Pendaftaran KP/KPL - Data Diri & Akademik')
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if (!$template) {
            $this->formTemplateModel->insert([
                'name'       => 'Formulir Pendaftaran KP/KPL - Data Diri & Akademik',
                'form_type'  => 'registration',
                'version'    => 1,
                'period_id'  => null,
                'status'     => 'active',
                'created_by' => session()->get('user_id'),
            ]);
            $templateId = $this->formTemplateModel->getInsertID();
        } else {
            $templateId = (int) $template['id'];
        }

        $created = 0;
        foreach ($this->kpKplRegistrationFields() as $field) {
            $exists = $db->table('form_fields')
                ->where('form_template_id', $templateId)
                ->where('field_name', $field['field_name'])
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $field['form_template_id'] = $templateId;
            $field['status'] = 'active';
            $field['created_at'] = $now;
            $field['updated_at'] = $now;
            $db->table('form_fields')->insert($field);
            $created++;
        }

        AuditService::log(
            'GENERATE_KP_KPL_REGISTRATION_FORM',
            'form_templates',
            $templateId,
            null,
            ['fields_created' => $created],
            'Generate template formulir pendaftaran KP/KPL Data Diri dan Data Akademik.'
        );

        return redirect()
            ->to(base_url('admin/form-builder/fields/' . $templateId))
            ->with('success', 'Formulir KP/KPL berhasil dibuat/diperbarui. Field baru ditambahkan: ' . $created . '.');
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
            'field_type'       => 'required|in_list[text,select,file,date,number,textarea,heading,static_text,link]',
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

    private function kpKplRegistrationFields(): array
    {
        $yesNo = json_encode(['Ya', 'Tidak']);

        return [
            $this->field('section_data_diri', 'Header Pengisian Formulir KP/KPL (Data Diri)', 'heading', 0, 1),
            $this->field('nama_lengkap', 'Nama Lengkap', 'text', 1, 2, null, 'required|max_length[150]'),
            $this->field('npm', 'Nomor Pokok Mahasiswa (NPM)', 'text', 1, 3, null, 'required|numeric|max_length[30]'),
            $this->field('tempat_tanggal_lahir', 'Tempat, Tanggal Lahir', 'text', 1, 4, null, 'required|max_length[150]'),
            $this->field('jenis_kelamin', 'Jenis Kelamin (L/P)', 'select', 1, 5, json_encode(['L', 'P']), 'required|in_list[L,P]'),
            $this->field('agama', 'Agama', 'text', 1, 6, null, 'required|max_length[50]'),
            $this->field('alamat_lengkap_ktp', 'Alamat Lengkap (sesuai KTP)', 'textarea', 1, 7, null, 'required'),
            $this->field('kecamatan', 'Kecamatan', 'text', 1, 8, null, 'required|max_length[100]'),
            $this->field('kabupaten', 'Kabupaten', 'text', 1, 9, null, 'required|max_length[100]'),
            $this->field('provinsi', 'Provinsi', 'text', 1, 10, null, 'required|max_length[100]'),
            $this->field('nomor_hp', 'Nomor Telepon/HP (aktif)', 'text', 1, 11, null, 'required|max_length[30]'),
            $this->field('email_aktif', 'Alamat Email (aktif)', 'text', 1, 12, null, 'required|valid_email|max_length[150]'),
            $this->field('nama_orang_tua_wali', 'Nama Orang Tua/Wali', 'text', 1, 13, null, 'required|max_length[150]'),
            $this->field('nomor_hp_orang_tua_wali', 'Nomor Telepon Orang Tua/Wali', 'text', 1, 14, null, 'required|max_length[30]'),
            $this->field('semester_pendaftaran', 'Semester saat pendaftaran', 'number', 1, 15, null, 'required|integer|greater_than[0]'),
            $this->field('tahun_akademik', 'Tahun Akademik', 'text', 1, 16, null, 'required|max_length[20]'),
            $this->field('angkatan', 'Angkatan', 'number', 1, 17, null, 'required|integer'),
            $this->field('program_studi', 'Program Studi (Prodi)', 'text', 1, 18, null, 'required|max_length[150]'),

            $this->field('section_data_akademik', 'Header Pengisian Formulir KP/KPL (Data Akademik)', 'heading', 0, 100),
            $this->field('jumlah_sks', 'Jumlah SKS yang telah ditempuh', 'number', 1, 101, null, 'required|integer|greater_than_equal_to[0]'),
            $this->field('ipk_terakhir', 'IPK terakhir (skala 4,00)', 'number', 1, 102, null, 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[4]'),
            $this->field('ipk_minimal_250', 'Apakah IPK >= 2,50?', 'select', 1, 103, $yesNo, 'required|in_list[Ya,Tidak]'),
            $this->field('lulus_pemrograman_dasar', 'Apakah telah lulus mata kuliah Pemrograman Dasar?', 'select', 1, 104, $yesNo, 'required|in_list[Ya,Tidak]'),
            $this->field('lulus_struktur_data', 'Apakah telah lulus mata kuliah Struktur Data?', 'select', 1, 105, $yesNo, 'required|in_list[Ya,Tidak]'),
            $this->field('lulus_basis_data', 'Apakah telah lulus mata kuliah Basis Data / Database System?', 'select', 1, 106, $yesNo, 'required|in_list[Ya,Tidak]'),
            $this->field('lulus_apsi', 'Apakah telah lulus mata kuliah Analisis dan Perancangan Sistem Informasi?', 'select', 1, 107, $yesNo, 'required|in_list[Ya,Tidak]'),
            $this->field('lulus_jaringan_komputer', 'Apakah telah lulus mata kuliah Jaringan Komputer / Data Communication?', 'select', 1, 108, $yesNo, 'required|in_list[Ya,Tidak]'),
            $this->field('lulus_mk_konsentrasi', 'Apakah telah lulus minimal salah satu mata kuliah konsentrasi keahlian?', 'select', 1, 109, $yesNo, 'required|in_list[Ya,Tidak]'),
            $this->field('status_biaya_pendidikan', 'Status Biaya Pendidikan', 'select', 1, 110, json_encode(['KIP', 'Mandiri', 'Internal']), 'required|in_list[KIP,Mandiri,Internal]'),
            $this->field('bukti_pembayaran_kp', 'Bukti Pembayaran KP', 'file', 1, 111, null, 'required|ext_in[bukti_pembayaran_kp,jpg,jpeg]|max_size[bukti_pembayaran_kp,10240]'),
            $this->field('catatan_bukti_pembayaran', 'Format file: JPG/JPEG, nama file Nama+NPM, maksimal 10 MB.', 'static_text', 0, 112),
            $this->field('download_dokumen_kp_kpl', 'PENTING: Silakan unduh dokumen melalui tautan di bawah ini.', 'link', 0, 113, json_encode(['label' => 'Link Download', 'url' => '#'])),
            $this->field('catatan_dokumen_cetak', 'Dokumen wajib dicetak fisik dan diserahkan langsung kepada Koordinator KP/KPL.', 'static_text', 0, 114),
        ];
    }

    private function field(string $name, string $label, string $type, int $required, int $order, ?string $options = null, ?string $rules = null): array
    {
        return [
            'field_name' => $name,
            'label' => $label,
            'field_type' => $type,
            'options_json' => $options,
            'validation_rules' => $rules,
            'is_required' => $required,
            'sort_order' => $order,
        ];
    }
}
