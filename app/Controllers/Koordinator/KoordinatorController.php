<?php

namespace App\Controllers\Koordinator;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\AuditService;

class KoordinatorController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Stats queries
        $stats = [
            'pending_registrations' => $db->table('kp_registrations')->where('current_status', 'menunggu_verifikasi')->countAllResults(),
            'pending_placements'    => $db->table('placement_requests')->where('status', 'diajukan')->countAllResults(),
            'total_students_active' => $db->table('kp_registrations')->whereNotIn('current_status', ['draft', 'registrasi_ditolak', 'diarsipkan'])->countAllResults(),
            'full_lecturer_quota'   => $db->table('lecturer_profiles')->where('is_available', 0)->countAllResults(),
            'active_period'         => $db->table('kp_periods')->where('status', 'aktif')->get()->getRow()->name ?? 'Tidak ada periode aktif',
        ];

        // Fetch pending validation requests
        $pendingRegList = $db->table('kp_registrations')
            ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->where('kp_registrations.current_status', 'menunggu_verifikasi')
            ->orderBy('kp_registrations.updated_at', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data = [
            'title'            => 'Dashboard Koordinator',
            'stats'            => $stats,
            'pending_regs'     => $pendingRegList,
        ];

        return view('koordinator/index', $data);
    }

    public function profile()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        if (!$user) {
            return redirect()->to(base_url('koordinator/dashboard'))->with('error', 'Akun tidak ditemukan.');
        }

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user,
        ];

        return view('koordinator/profile', $data);
    }

    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('koordinator/dashboard'))->with('error', 'Akun tidak ditemukan.');
        }

        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email',
            'phone'            => 'required|numeric|min_length[10]|max_length[15]',
            'password'         => 'permit_empty|min_length[8]',
            'password_confirm' => 'required_with[password]|matches[password]',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
                'max_length' => 'Nama lengkap maksimal 100 karakter.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'phone' => [
                'required'   => 'Nomor telepon wajib diisi.',
                'numeric'    => 'Nomor telepon hanya boleh berisi angka.',
                'min_length' => 'Nomor telepon minimal 10 digit.',
                'max_length' => 'Nomor telepon maksimal 15 digit.',
            ],
            'password' => [
                'min_length' => 'Kata sandi baru minimal 8 karakter.',
            ],
            'password_confirm' => [
                'required_with' => 'Konfirmasi kata sandi wajib diisi jika mengubah kata sandi.',
                'matches'       => 'Konfirmasi kata sandi tidak cocok dengan kata sandi baru.',
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $password = $this->request->getPost('password');

        // Check if email already taken by someone else
        $existing = $userModel->where('email', $email)->where('id !=', $userId)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Alamat email tersebut sudah digunakan oleh akun lain.');
        }

        $updateData = [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
        ];

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $userModel->update($userId, $updateData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil karena kesalahan internal.');
        }

        // Write audit log
        $oldValues = [
            'name'  => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
        ];
        AuditService::log('UPDATE_PROFILE', 'users', $userId, $oldValues, [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
        ], 'Memperbarui profil akun Koordinator.');

        // Update session info
        session()->set([
            'name'  => $name,
            'email' => $email
        ]);

        return redirect()->to(base_url('koordinator/profile'))->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function placeholder($menuName)
    {
        return view('placeholder', [
            'title'    => $menuName,
            'menuName' => $menuName
        ]);
    }
}
