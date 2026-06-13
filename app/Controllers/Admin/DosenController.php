<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LecturerProfileModel;
use App\Models\StudyProgramModel;
use App\Services\AuditService;

class DosenController extends BaseController
{
    protected $userModel;
    protected $lecturerModel;
    protected $prodiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->lecturerModel = new LecturerProfileModel();
        $this->prodiModel = new StudyProgramModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Data Dosen',
            'lecturers' => $this->lecturerModel->getLecturersWithDetails(),
        ];
        return view('admin/dosen/index', $data);
    }

    public function create()
    {
        $data = [
            'title'  => 'Tambah Dosen',
            'prodis' => $this->prodiModel->where('status', 'active')->findAll(),
        ];
        return view('admin/dosen/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nidn'                  => 'required|numeric|is_unique[lecturer_profiles.nidn]',
            'full_name'             => 'required|min_length[3]',
            'email'                 => 'required|valid_email|is_unique[users.email]',
            'password'              => 'required|min_length[6]',
            'study_program_id'      => 'required|integer',
            'max_supervision_quota' => 'required|integer|greater_than_equal_to[1]',
            'expertise'             => 'permit_empty|min_length[2]',
            'phone'                 => 'permit_empty|min_length[5]',
            'is_available'          => 'required|in_list[0,1]',
        ];

        $messages = [
            'nidn' => [
                'required'  => 'NIDN/NIP wajib diisi.',
                'numeric'   => 'NIDN/NIP hanya boleh berisi angka.',
                'is_unique' => 'NIDN/NIP sudah terdaftar.',
            ],
            'full_name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.',
            ],
            'study_program_id' => [
                'required' => 'Program studi wajib dipilih.',
            ],
            'max_supervision_quota' => [
                'required'              => 'Kuota bimbingan wajib diisi.',
                'greater_than_equal_to' => 'Kuota minimal adalah 1.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Create User
        $userData = [
            'name'          => $this->request->getPost('full_name'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => 'dosen',
            'phone'         => $this->request->getPost('phone') ?: null,
            'status'        => 'active',
        ];
        $userId = $this->userModel->insert($userData);

        // 2. Create Profile
        $profileData = [
            'user_id'               => $userId,
            'nidn'                  => $this->request->getPost('nidn'),
            'full_name'             => $this->request->getPost('full_name'),
            'study_program_id'      => $this->request->getPost('study_program_id'),
            'expertise'             => $this->request->getPost('expertise') ?: null,
            'max_supervision_quota' => $this->request->getPost('max_supervision_quota'),
            'is_available'          => $this->request->getPost('is_available'),
        ];
        $profileId = $this->lecturerModel->insert($profileData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data dosen.');
        }

        AuditService::log(
            'CREATE_LECTURER_PROFILE',
            'lecturer_profiles',
            $profileId,
            null,
            [
                'nidn'      => $profileData['nidn'],
                'full_name' => $profileData['full_name'],
                'email'     => $userData['email'],
            ],
            "Membuat profil dosen baru: " . $profileData['full_name'] . " (" . $profileData['nidn'] . ")"
        );

        return redirect()->to(base_url('admin/dosen'))->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lecturer = $this->lecturerModel->find($id);
        if (!$lecturer) {
            return redirect()->to(base_url('admin/dosen'))->with('error', 'Dosen tidak ditemukan.');
        }

        $user = $this->userModel->find($lecturer['user_id']);
        if (!$user) {
            return redirect()->to(base_url('admin/dosen'))->with('error', 'Akun pengguna dosen tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Dosen',
            'lecturer' => $lecturer,
            'user'     => $user,
            'prodis'   => $this->prodiModel->where('status', 'active')->findAll(),
        ];
        return view('admin/dosen/edit', $data);
    }

    public function update($id)
    {
        $lecturer = $this->lecturerModel->find($id);
        if (!$lecturer) {
            return redirect()->to(base_url('admin/dosen'))->with('error', 'Dosen tidak ditemukan.');
        }

        $userId = $lecturer['user_id'];
        $validation = \Config\Services::validation();

        $rules = [
            'nidn'                  => "required|numeric|is_unique[lecturer_profiles.nidn,id,$id]",
            'full_name'             => 'required|min_length[3]',
            'email'                 => "required|valid_email|is_unique[users.email,id,$userId]",
            'study_program_id'      => 'required|integer',
            'max_supervision_quota' => 'required|integer|greater_than_equal_to[1]',
            'expertise'             => 'permit_empty|min_length[2]',
            'phone'                 => 'permit_empty|min_length[5]',
            'is_available'          => 'required|in_list[0,1]',
        ];

        $messages = [
            'nidn' => [
                'required'  => 'NIDN/NIP wajib diisi.',
                'numeric'   => 'NIDN/NIP hanya boleh berisi angka.',
                'is_unique' => 'NIDN/NIP sudah terdaftar.',
            ],
            'full_name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar.',
            ],
            'study_program_id' => [
                'required' => 'Program studi wajib dipilih.',
            ],
            'max_supervision_quota' => [
                'required'              => 'Kuota bimbingan wajib diisi.',
                'greater_than_equal_to' => 'Kuota minimal adalah 1.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $oldUser = $this->userModel->find($userId);
        $oldValues = [
            'nidn'                  => $lecturer['nidn'],
            'full_name'             => $lecturer['full_name'],
            'email'                 => $oldUser['email'],
            'study_program_id'      => $lecturer['study_program_id'],
            'max_supervision_quota' => $lecturer['max_supervision_quota'],
            'is_available'          => $lecturer['is_available']
        ];

        // 1. Update User
        $userData = [
            'name'  => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone') ?: null,
        ];
        $this->userModel->update($userId, $userData);

        // 2. Update Profile
        $profileData = [
            'nidn'                  => $this->request->getPost('nidn'),
            'full_name'             => $this->request->getPost('full_name'),
            'study_program_id'      => $this->request->getPost('study_program_id'),
            'expertise'             => $this->request->getPost('expertise') ?: null,
            'max_supervision_quota' => $this->request->getPost('max_supervision_quota'),
            'is_available'          => $this->request->getPost('is_available'),
        ];
        $this->lecturerModel->update($id, $profileData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data dosen.');
        }

        $updatedUser = $this->userModel->find($userId);
        $updatedLecturer = $this->lecturerModel->find($id);

        AuditService::log(
            'UPDATE_LECTURER_PROFILE',
            'lecturer_profiles',
            $id,
            $oldValues,
            [
                'nidn'                  => $updatedLecturer['nidn'],
                'full_name'             => $updatedLecturer['full_name'],
                'email'                 => $updatedUser['email'],
                'study_program_id'      => $updatedLecturer['study_program_id'],
                'max_supervision_quota' => $updatedLecturer['max_supervision_quota'],
                'is_available'          => $updatedLecturer['is_available']
            ],
            "Memperbarui profil dosen: " . $updatedLecturer['full_name'] . " (" . $updatedLecturer['nidn'] . ")"
        );

        return redirect()->to(base_url('admin/dosen'))->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function delete($id)
    {
        $lecturer = $this->lecturerModel->find($id);
        if (!$lecturer) {
            return redirect()->to(base_url('admin/dosen'))->with('error', 'Dosen tidak ditemukan.');
        }

        $userId = $lecturer['user_id'];
        $db = \Config\Database::connect();
        $db->transStart();

        $this->lecturerModel->delete($id);
        $this->userModel->delete($userId);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/dosen'))->with('error', 'Gagal menghapus data dosen.');
        }

        AuditService::log(
            'DELETE_LECTURER_PROFILE',
            'lecturer_profiles',
            $id,
            [
                'nidn'      => $lecturer['nidn'],
                'full_name' => $lecturer['full_name']
            ],
            null,
            "Menghapus (soft delete) profil dosen dan akun terkait: " . $lecturer['full_name'] . " (" . $lecturer['nidn'] . ")"
        );

        return redirect()->to(base_url('admin/dosen'))->with('success', 'Data dosen berhasil dihapus.');
    }
}
