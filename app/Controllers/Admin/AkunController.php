<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\AuditService;

class AkunController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Akun',
            'users' => $this->userModel->findAll(),
        ];
        return view('admin/akun/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Akun Pengguna',
        ];
        return view('admin/akun/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,koordinator,mahasiswa,dosen,instansi]',
            'status'   => 'required|in_list[active,inactive,suspended]',
            'phone'    => 'permit_empty|min_length[5]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email sudah terdaftar di sistem.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
            'role' => [
                'required' => 'Role wajib dipilih.',
                'in_list' => 'Role tidak valid.',
            ],
            'status' => [
                'required' => 'Status wajib dipilih.',
                'in_list' => 'Status tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $password = $this->request->getPost('password');
        $userData = [
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'phone'         => $this->request->getPost('phone') ?: null,
            'status'        => $this->request->getPost('status'),
        ];

        $userId = $this->userModel->insert($userData);

        if ($userId) {
            $insertedData = $this->userModel->find($userId);
            AuditService::log(
                'CREATE_USER',
                'users',
                $userId,
                null,
                [
                    'name'   => $insertedData['name'],
                    'email'  => $insertedData['email'],
                    'role'   => $insertedData['role'],
                    'status' => $insertedData['status']
                ],
                "Membuat akun pengguna baru: " . $insertedData['email']
            );
            return redirect()->to(base_url('admin/akun'))->with('success', 'Akun pengguna berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan akun pengguna.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/akun'))->with('error', 'Akun tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Akun Pengguna',
            'user'  => $user,
        ];
        return view('admin/akun/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/akun'))->with('error', 'Akun tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => "required|valid_email|is_unique[users.email,id,$id]",
            'password' => 'permit_empty|min_length[6]',
            'role'     => 'required|in_list[admin,koordinator,mahasiswa,dosen,instansi]',
            'status'   => 'required|in_list[active,inactive,suspended]',
            'phone'    => 'permit_empty|min_length[5]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email sudah terdaftar di sistem.',
            ],
            'password' => [
                'min_length' => 'Password minimal 6 karakter.',
            ],
            'role' => [
                'required' => 'Role wajib dipilih.',
                'in_list' => 'Role tidak valid.',
            ],
            'status' => [
                'required' => 'Status wajib dipilih.',
                'in_list' => 'Status tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userData = [
            'name'   => $this->request->getPost('name'),
            'email'  => $this->request->getPost('email'),
            'role'   => $this->request->getPost('role'),
            'phone'  => $this->request->getPost('phone') ?: null,
            'status' => $this->request->getPost('status'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $oldValues = [
            'name'   => $user['name'],
            'email'  => $user['email'],
            'role'   => $user['role'],
            'status' => $user['status']
        ];

        if ($this->userModel->update($id, $userData)) {
            $updatedUser = $this->userModel->find($id);
            AuditService::log(
                'UPDATE_USER',
                'users',
                $id,
                $oldValues,
                [
                    'name'   => $updatedUser['name'],
                    'email'  => $updatedUser['email'],
                    'role'   => $updatedUser['role'],
                    'status' => $updatedUser['status']
                ],
                "Memperbarui akun pengguna: " . $updatedUser['email']
            );
            return redirect()->to(base_url('admin/akun'))->with('success', 'Akun pengguna berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui akun pengguna.');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('admin/akun'))->with('error', 'Akun tidak ditemukan.');
        }

        if ($this->userModel->delete($id)) {
            AuditService::log(
                'DELETE_USER',
                'users',
                $id,
                [
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ],
                null,
                "Menghapus (soft delete) akun pengguna: " . $user['email']
            );
            return redirect()->to(base_url('admin/akun'))->with('success', 'Akun pengguna berhasil dihapus.');
        }

        return redirect()->to(base_url('admin/akun'))->with('error', 'Gagal menghapus akun pengguna.');
    }
}
