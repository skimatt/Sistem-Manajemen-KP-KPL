<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\InstitutionProfileModel;
use App\Services\AuditService;

class InstansiController extends BaseController
{
    protected $userModel;
    protected $instansiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->instansiModel = new InstitutionProfileModel();
    }

    public function index()
    {
        $data = [
            'title'        => 'Data Instansi',
            'institutions' => $this->instansiModel->getInstitutionsWithDetails(),
        ];
        return view('admin/instansi/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Instansi',
        ];
        return view('admin/instansi/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $hasAccount = $this->request->getPost('has_account') === '1';

        $rules = [
            'name'               => 'required|min_length[3]',
            'type'               => 'required|in_list[mitra,mandiri]',
            'field_category'     => 'permit_empty|min_length[2]',
            'address'            => 'required|min_length[5]',
            'district'           => 'permit_empty',
            'city'               => 'required',
            'province'           => 'permit_empty',
            'contact_person'     => 'permit_empty|min_length[3]',
            'contact_position'   => 'permit_empty',
            'contact_phone'      => 'permit_empty|min_length[5]',
            'contact_email'      => 'permit_empty|valid_email',
            'partnership_status' => 'required|in_list[active,inactive,pending]',
        ];

        if ($hasAccount) {
            $rules['email']    = 'required|valid_email|is_unique[users.email]';
            $rules['password'] = 'required|min_length[6]';
        }

        $messages = [
            'name' => [
                'required'   => 'Nama instansi wajib diisi.',
                'min_length' => 'Nama instansi minimal 3 karakter.',
            ],
            'type' => [
                'required' => 'Tipe instansi wajib dipilih.',
                'in_list'  => 'Tipe instansi tidak valid.',
            ],
            'address' => [
                'required'   => 'Alamat instansi wajib diisi.',
                'min_length' => 'Alamat instansi minimal 5 karakter.',
            ],
            'city' => [
                'required' => 'Kabupaten/Kota wajib diisi.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi jika akun diaktifkan.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar di sistem.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi jika akun diaktifkan.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $userId = null;
        if ($hasAccount) {
            // Create User account
            $userData = [
                'name'          => $this->request->getPost('name'),
                'email'         => $this->request->getPost('email'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'          => 'instansi',
                'phone'         => $this->request->getPost('contact_phone') ?: null,
                'status'        => 'active',
            ];
            $userId = $this->userModel->insert($userData);
        }

        // Create Profile
        $profileData = [
            'user_id'            => $userId,
            'name'               => $this->request->getPost('name'),
            'type'               => $this->request->getPost('type'),
            'field_category'     => $this->request->getPost('field_category') ?: null,
            'address'            => $this->request->getPost('address'),
            'district'           => $this->request->getPost('district') ?: null,
            'city'               => $this->request->getPost('city'),
            'province'           => $this->request->getPost('province') ?: null,
            'contact_person'     => $this->request->getPost('contact_person') ?: null,
            'contact_position'   => $this->request->getPost('contact_position') ?: null,
            'contact_phone'      => $this->request->getPost('contact_phone') ?: null,
            'contact_email'      => $this->request->getPost('contact_email') ?: null,
            'partnership_status' => $this->request->getPost('partnership_status'),
            'has_account'        => $hasAccount ? 1 : 0,
        ];
        $profileId = $this->instansiModel->insert($profileData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data instansi.');
        }

        AuditService::log(
            'CREATE_INSTITUTION_PROFILE',
            'institution_profiles',
            $profileId,
            null,
            [
                'name'               => $profileData['name'],
                'type'               => $profileData['type'],
                'partnership_status' => $profileData['partnership_status'],
            ],
            "Membuat profil instansi baru: " . $profileData['name'] . " (" . $profileData['type'] . ")"
        );

        return redirect()->to(base_url('admin/instansi'))->with('success', 'Data instansi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $instansi = $this->instansiModel->find($id);
        if (!$instansi) {
            return redirect()->to(base_url('admin/instansi'))->with('error', 'Instansi tidak ditemukan.');
        }

        $user = null;
        if ($instansi['user_id']) {
            $user = $this->userModel->find($instansi['user_id']);
        }

        $data = [
            'title'    => 'Edit Instansi',
            'instansi' => $instansi,
            'user'     => $user,
        ];
        return view('admin/instansi/edit', $data);
    }

    public function update($id)
    {
        $instansi = $this->instansiModel->find($id);
        if (!$instansi) {
            return redirect()->to(base_url('admin/instansi'))->with('error', 'Instansi tidak ditemukan.');
        }

        $userId = $instansi['user_id'];
        $hasAccount = $this->request->getPost('has_account') === '1';

        $validation = \Config\Services::validation();

        $rules = [
            'name'               => 'required|min_length[3]',
            'type'               => 'required|in_list[mitra,mandiri]',
            'field_category'     => 'permit_empty|min_length[2]',
            'address'            => 'required|min_length[5]',
            'district'           => 'permit_empty',
            'city'               => 'required',
            'province'           => 'permit_empty',
            'contact_person'     => 'permit_empty|min_length[3]',
            'contact_position'   => 'permit_empty',
            'contact_phone'      => 'permit_empty|min_length[5]',
            'contact_email'      => 'permit_empty|valid_email',
            'partnership_status' => 'required|in_list[active,inactive,pending]',
        ];

        if ($hasAccount) {
            if ($userId) {
                $rules['email']    = "required|valid_email|is_unique[users.email,id,$userId]";
                $rules['password'] = 'permit_empty|min_length[6]';
            } else {
                $rules['email']    = 'required|valid_email|is_unique[users.email]';
                $rules['password'] = 'required|min_length[6]';
            }
        }

        $messages = [
            'name' => [
                'required'   => 'Nama instansi wajib diisi.',
                'min_length' => 'Nama instansi minimal 3 karakter.',
            ],
            'type' => [
                'required' => 'Tipe instansi wajib dipilih.',
                'in_list'  => 'Tipe instansi tidak valid.',
            ],
            'address' => [
                'required'   => 'Alamat instansi wajib diisi.',
                'min_length' => 'Alamat instansi minimal 5 karakter.',
            ],
            'city' => [
                'required' => 'Kabupaten/Kota wajib diisi.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi jika akun diaktifkan.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar di sistem.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi jika akun diaktifkan.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $oldUser = $userId ? $this->userModel->find($userId) : null;
        $oldValues = [
            'name'               => $instansi['name'],
            'type'               => $instansi['type'],
            'partnership_status' => $instansi['partnership_status'],
            'has_account'        => $instansi['has_account'],
            'email'              => $oldUser ? $oldUser['email'] : null,
        ];

        if ($hasAccount) {
            if ($userId) {
                // Update existing account
                $userData = [
                    'name'  => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('contact_phone') ?: null,
                ];
                $password = $this->request->getPost('password');
                if (!empty($password)) {
                    $userData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                }
                $this->userModel->update($userId, $userData);
            } else {
                // Create new account
                $userData = [
                    'name'          => $this->request->getPost('name'),
                    'email'         => $this->request->getPost('email'),
                    'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role'          => 'instansi',
                    'phone'         => $this->request->getPost('contact_phone') ?: null,
                    'status'        => 'active',
                ];
                $userId = $this->userModel->insert($userData);
            }
        } else {
            if ($userId) {
                // Soft delete old user account if deactivated
                $this->userModel->delete($userId);
                $userId = null;
            }
        }

        // Update Profile
        $profileData = [
            'user_id'            => $userId,
            'name'               => $this->request->getPost('name'),
            'type'               => $this->request->getPost('type'),
            'field_category'     => $this->request->getPost('field_category') ?: null,
            'address'            => $this->request->getPost('address'),
            'district'           => $this->request->getPost('district') ?: null,
            'city'               => $this->request->getPost('city'),
            'province'           => $this->request->getPost('province') ?: null,
            'contact_person'     => $this->request->getPost('contact_person') ?: null,
            'contact_position'   => $this->request->getPost('contact_position') ?: null,
            'contact_phone'      => $this->request->getPost('contact_phone') ?: null,
            'contact_email'      => $this->request->getPost('contact_email') ?: null,
            'partnership_status' => $this->request->getPost('partnership_status'),
            'has_account'        => $hasAccount ? 1 : 0,
        ];
        $this->instansiModel->update($id, $profileData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data instansi.');
        }

        $updatedUser = $userId ? $this->userModel->find($userId) : null;
        $updatedInstansi = $this->instansiModel->find($id);

        AuditService::log(
            'UPDATE_INSTITUTION_PROFILE',
            'institution_profiles',
            $id,
            $oldValues,
            [
                'name'               => $updatedInstansi['name'],
                'type'               => $updatedInstansi['type'],
                'partnership_status' => $updatedInstansi['partnership_status'],
                'has_account'        => $updatedInstansi['has_account'],
                'email'              => $updatedUser ? $updatedUser['email'] : null,
            ],
            "Memperbarui profil instansi: " . $updatedInstansi['name']
        );

        return redirect()->to(base_url('admin/instansi'))->with('success', 'Data instansi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $instansi = $this->instansiModel->find($id);
        if (!$instansi) {
            return redirect()->to(base_url('admin/instansi'))->with('error', 'Instansi tidak ditemukan.');
        }

        $userId = $instansi['user_id'];
        $db = \Config\Database::connect();
        $db->transStart();

        $this->instansiModel->delete($id);
        if ($userId) {
            $this->userModel->delete($userId);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/instansi'))->with('error', 'Gagal menghapus data instansi.');
        }

        AuditService::log(
            'DELETE_INSTITUTION_PROFILE',
            'institution_profiles',
            $id,
            [
                'name' => $instansi['name'],
                'type' => $instansi['type']
            ],
            null,
            "Menghapus (soft delete) profil instansi dan akun terkait: " . $instansi['name']
        );

        return redirect()->to(base_url('admin/instansi'))->with('success', 'Data instansi berhasil dihapus.');
    }
}
