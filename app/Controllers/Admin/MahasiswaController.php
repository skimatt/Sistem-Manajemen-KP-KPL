<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StudentProfileModel;
use App\Models\StudyProgramModel;
use App\Services\AuditService;

class MahasiswaController extends BaseController
{
    protected $userModel;
    protected $studentModel;
    protected $prodiModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->studentModel = new StudentProfileModel();
        $this->prodiModel = new StudyProgramModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Data Mahasiswa',
            'students' => $this->studentModel->getStudentsWithDetails(),
        ];
        return view('admin/mahasiswa/index', $data);
    }

    public function create()
    {
        $data = [
            'title'  => 'Tambah Mahasiswa',
            'prodis' => $this->prodiModel->where('status', 'active')->findAll(),
        ];
        return view('admin/mahasiswa/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'npm'              => 'required|numeric|is_unique[student_profiles.npm]',
            'full_name'        => 'required|min_length[3]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'study_program_id' => 'required|integer',
            'generation_year'  => 'required|integer',
            'current_semester' => 'required|integer',
            'gender'           => 'permit_empty|in_list[L,P]',
            'phone'            => 'permit_empty|min_length[5]',
        ];

        $messages = [
            'npm' => [
                'required'  => 'NPM wajib diisi.',
                'numeric'   => 'NPM hanya boleh berisi angka.',
                'is_unique' => 'NPM sudah terdaftar.',
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
            'generation_year' => [
                'required' => 'Tahun angkatan wajib diisi.',
            ],
            'current_semester' => [
                'required' => 'Semester saat ini wajib diisi.',
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
            'role'          => 'mahasiswa',
            'phone'         => $this->request->getPost('phone') ?: null,
            'status'        => 'active',
        ];
        $userId = $this->userModel->insert($userData);

        // 2. Create Profile
        $profileData = [
            'user_id'          => $userId,
            'npm'              => $this->request->getPost('npm'),
            'full_name'        => $this->request->getPost('full_name'),
            'study_program_id' => $this->request->getPost('study_program_id'),
            'generation_year'  => $this->request->getPost('generation_year'),
            'current_semester' => $this->request->getPost('current_semester'),
            'gender'           => $this->request->getPost('gender') ?: null,
            'phone'            => $this->request->getPost('phone') ?: null,
            'profile_status'   => 'incomplete',
        ];
        $profileId = $this->studentModel->insert($profileData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data mahasiswa.');
        }

        AuditService::log(
            'CREATE_STUDENT_PROFILE',
            'student_profiles',
            $profileId,
            null,
            [
                'npm'       => $profileData['npm'],
                'full_name' => $profileData['full_name'],
                'email'     => $userData['email'],
            ],
            "Membuat profil mahasiswa baru: " . $profileData['full_name'] . " (" . $profileData['npm'] . ")"
        );

        return redirect()->to(base_url('admin/mahasiswa'))->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $student = $this->studentModel->find($id);
        if (!$student) {
            return redirect()->to(base_url('admin/mahasiswa'))->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $user = $this->userModel->find($student['user_id']);
        if (!$user) {
            return redirect()->to(base_url('admin/mahasiswa'))->with('error', 'Akun pengguna mahasiswa tidak ditemukan.');
        }

        $data = [
            'title'   => 'Edit Mahasiswa',
            'student' => $student,
            'user'    => $user,
            'prodis'  => $this->prodiModel->where('status', 'active')->findAll(),
        ];
        return view('admin/mahasiswa/edit', $data);
    }

    public function update($id)
    {
        $student = $this->studentModel->find($id);
        if (!$student) {
            return redirect()->to(base_url('admin/mahasiswa'))->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $userId = $student['user_id'];
        $validation = \Config\Services::validation();

        $rules = [
            'npm'              => "required|numeric|is_unique[student_profiles.npm,id,$id]",
            'full_name'        => 'required|min_length[3]',
            'email'            => "required|valid_email|is_unique[users.email,id,$userId]",
            'study_program_id' => 'required|integer',
            'generation_year'  => 'required|integer',
            'current_semester' => 'required|integer',
            'gender'           => 'permit_empty|in_list[L,P]',
            'phone'            => 'permit_empty|min_length[5]',
        ];

        $messages = [
            'npm' => [
                'required'  => 'NPM wajib diisi.',
                'numeric'   => 'NPM hanya boleh berisi angka.',
                'is_unique' => 'NPM sudah terdaftar.',
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
            'generation_year' => [
                'required' => 'Tahun angkatan wajib diisi.',
            ],
            'current_semester' => [
                'required' => 'Semester saat ini wajib diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Save old values for audit logging
        $oldUser = $this->userModel->find($userId);
        $oldValues = [
            'npm'              => $student['npm'],
            'full_name'        => $student['full_name'],
            'email'            => $oldUser['email'],
            'study_program_id' => $student['study_program_id'],
            'generation_year'  => $student['generation_year'],
            'current_semester' => $student['current_semester']
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
            'npm'              => $this->request->getPost('npm'),
            'full_name'        => $this->request->getPost('full_name'),
            'study_program_id' => $this->request->getPost('study_program_id'),
            'generation_year'  => $this->request->getPost('generation_year'),
            'current_semester' => $this->request->getPost('current_semester'),
            'gender'           => $this->request->getPost('gender') ?: null,
            'phone'            => $this->request->getPost('phone') ?: null,
        ];
        $this->studentModel->update($id, $profileData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data mahasiswa.');
        }

        $updatedUser = $this->userModel->find($userId);
        $updatedStudent = $this->studentModel->find($id);

        AuditService::log(
            'UPDATE_STUDENT_PROFILE',
            'student_profiles',
            $id,
            $oldValues,
            [
                'npm'              => $updatedStudent['npm'],
                'full_name'        => $updatedStudent['full_name'],
                'email'            => $updatedUser['email'],
                'study_program_id' => $updatedStudent['study_program_id'],
                'generation_year'  => $updatedStudent['generation_year'],
                'current_semester' => $updatedStudent['current_semester']
            ],
            "Memperbarui profil mahasiswa: " . $updatedStudent['full_name'] . " (" . $updatedStudent['npm'] . ")"
        );

        return redirect()->to(base_url('admin/mahasiswa'))->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function delete($id)
    {
        $student = $this->studentModel->find($id);
        if (!$student) {
            return redirect()->to(base_url('admin/mahasiswa'))->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $userId = $student['user_id'];
        $db = \Config\Database::connect();
        $db->transStart();

        $this->studentModel->delete($id);
        $this->userModel->delete($userId);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/mahasiswa'))->with('error', 'Gagal menghapus data mahasiswa.');
        }

        AuditService::log(
            'DELETE_STUDENT_PROFILE',
            'student_profiles',
            $id,
            [
                'npm'       => $student['npm'],
                'full_name' => $student['full_name']
            ],
            null,
            "Menghapus (soft delete) profil mahasiswa dan akun terkait: " . $student['full_name'] . " (" . $student['npm'] . ")"
        );

        return redirect()->to(base_url('admin/mahasiswa'))->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
