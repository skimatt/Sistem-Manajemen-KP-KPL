<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudyProgramModel;
use App\Services\AuditService;

class ProdiController extends BaseController
{
    protected $prodiModel;

    public function __construct()
    {
        $this->prodiModel = new StudyProgramModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Program Studi',
            'prodis' => $this->prodiModel->findAll(),
        ];
        return view('admin/prodi/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Program Studi',
        ];
        return view('admin/prodi/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'code'     => 'required|alpha_dash|is_unique[study_programs.code]',
            'name'     => 'required|min_length[3]',
            'faculty'  => 'required',
            'kp_label' => 'required|in_list[KP,KPL]',
            'status'   => 'required|in_list[active,inactive]',
        ];

        $messages = [
            'code' => [
                'required'   => 'Kode prodi wajib diisi.',
                'alpha_dash' => 'Kode prodi hanya boleh berisi huruf, angka, dash, dan underscore.',
                'is_unique'  => 'Kode prodi sudah terdaftar.',
            ],
            'name' => [
                'required'   => 'Nama prodi wajib diisi.',
                'min_length' => 'Nama prodi minimal 3 karakter.',
            ],
            'faculty' => [
                'required' => 'Nama fakultas wajib diisi.',
            ],
            'kp_label' => [
                'required' => 'Label penugasan wajib dipilih.',
                'in_list'  => 'Label penugasan harus KP atau KPL.',
            ],
            'status' => [
                'required' => 'Status prodi wajib dipilih.',
                'in_list'  => 'Status prodi tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $prodiData = [
            'code'     => $this->request->getPost('code'),
            'name'     => $this->request->getPost('name'),
            'faculty'  => $this->request->getPost('faculty'),
            'kp_label' => $this->request->getPost('kp_label'),
            'status'   => $this->request->getPost('status'),
        ];

        $prodiId = $this->prodiModel->insert($prodiData);

        if ($prodiId) {
            AuditService::log(
                'CREATE_STUDY_PROGRAM',
                'study_programs',
                $prodiId,
                null,
                $prodiData,
                "Membuat program studi baru: " . $prodiData['name'] . " (" . $prodiData['code'] . ")"
            );
            return redirect()->to(base_url('admin/prodi'))->with('success', 'Program studi berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan program studi.');
    }

    public function edit($id)
    {
        $prodi = $this->prodiModel->find($id);
        if (!$prodi) {
            return redirect()->to(base_url('admin/prodi'))->with('error', 'Program studi tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Program Studi',
            'prodi' => $prodi,
        ];
        return view('admin/prodi/edit', $data);
    }

    public function update($id)
    {
        $prodi = $this->prodiModel->find($id);
        if (!$prodi) {
            return redirect()->to(base_url('admin/prodi'))->with('error', 'Program studi tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'code'     => "required|alpha_dash|is_unique[study_programs.code,id,$id]",
            'name'     => 'required|min_length[3]',
            'faculty'  => 'required',
            'kp_label' => 'required|in_list[KP,KPL]',
            'status'   => 'required|in_list[active,inactive]',
        ];

        $messages = [
            'code' => [
                'required'   => 'Kode prodi wajib diisi.',
                'alpha_dash' => 'Kode prodi hanya boleh berisi huruf, angka, dash, dan underscore.',
                'is_unique'  => 'Kode prodi sudah terdaftar.',
            ],
            'name' => [
                'required'   => 'Nama prodi wajib diisi.',
                'min_length' => 'Nama prodi minimal 3 karakter.',
            ],
            'faculty' => [
                'required' => 'Nama fakultas wajib diisi.',
            ],
            'kp_label' => [
                'required' => 'Label penugasan wajib dipilih.',
                'in_list'  => 'Label penugasan harus KP atau KPL.',
            ],
            'status' => [
                'required' => 'Status prodi wajib dipilih.',
                'in_list'  => 'Status prodi tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $prodiData = [
            'code'     => $this->request->getPost('code'),
            'name'     => $this->request->getPost('name'),
            'faculty'  => $this->request->getPost('faculty'),
            'kp_label' => $this->request->getPost('kp_label'),
            'status'   => $this->request->getPost('status'),
        ];

        $oldValues = [
            'code'     => $prodi['code'],
            'name'     => $prodi['name'],
            'faculty'  => $prodi['faculty'],
            'kp_label' => $prodi['kp_label'],
            'status'   => $prodi['status'],
        ];

        if ($this->prodiModel->update($id, $prodiData)) {
            AuditService::log(
                'UPDATE_STUDY_PROGRAM',
                'study_programs',
                $id,
                $oldValues,
                $prodiData,
                "Memperbarui program studi: " . $prodiData['name'] . " (" . $prodiData['code'] . ")"
            );
            return redirect()->to(base_url('admin/prodi'))->with('success', 'Program studi berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui program studi.');
    }

    public function delete($id)
    {
        $prodi = $this->prodiModel->find($id);
        if (!$prodi) {
            return redirect()->to(base_url('admin/prodi'))->with('error', 'Program studi tidak ditemukan.');
        }

        try {
            if ($this->prodiModel->delete($id)) {
                AuditService::log(
                    'DELETE_STUDY_PROGRAM',
                    'study_programs',
                    $id,
                    [
                        'code' => $prodi['code'],
                        'name' => $prodi['name'],
                    ],
                    null,
                    "Menghapus program studi: " . $prodi['name'] . " (" . $prodi['code'] . ")"
                );
                return redirect()->to(base_url('admin/prodi'))->with('success', 'Program studi berhasil dihapus.');
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // Handle foreign key constraint exception gracefully
            return redirect()->to(base_url('admin/prodi'))->with('error', 'Gagal menghapus program studi. Data ini sedang digunakan oleh profil mahasiswa atau dosen.');
        }

        return redirect()->to(base_url('admin/prodi'))->with('error', 'Gagal menghapus program studi.');
    }
}
