<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class LoginController extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url(session()->get('role') . '/dashboard'));
        }
        return view('auth/login', ['title' => 'Masuk ke Sistem']);
    }

    public function authenticate()
    {
        // Validation rules in Indonesian
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[5]'
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.'
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal harus 5 karakter.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('email', $email)->get()->getRow();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar pada sistem.');
        }

        if ($user->status !== 'active') {
            return redirect()->back()->withInput()->with('error', 'Akun Anda sedang dinonaktifkan atau ditangguhkan. Silakan hubungi Admin.');
        }

        if (!password_verify($password, $user->password_hash)) {
            return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
        }

        // Set session variables
        $sessionData = [
            'user_id'   => $user->id,
            'uuid'      => $user->uuid,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $user->role,
            'logged_in' => true
        ];
        session()->set($sessionData);

        // Record simple audit log if table exists
        $db->table('audit_logs')->insert([
            'user_id'    => $user->id,
            'role'       => $user->role,
            'action'     => 'login',
            'table_name' => 'users',
            'record_id'  => $user->id,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'note'       => 'User sukses login ke sistem',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url($user->role . '/dashboard'))->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function logout()
    {
        // Record audit log before destroying session if logged in
        if (session()->get('logged_in')) {
            $db = \Config\Database::connect();
            $db->table('audit_logs')->insert([
                'user_id'    => session()->get('user_id'),
                'role'       => session()->get('role'),
                'action'     => 'logout',
                'table_name' => 'users',
                'record_id'  => session()->get('user_id'),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'note'       => 'User sukses logout dari sistem',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda telah sukses keluar dari sistem.');
    }
}
