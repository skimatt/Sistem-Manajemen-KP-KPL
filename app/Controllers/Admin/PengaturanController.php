<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AuditService;

class PengaturanController extends BaseController
{
    protected $settingsPath;

    public function __construct()
    {
        $this->settingsPath = WRITEPATH . 'settings.json';
    }

    protected function getSettings()
    {
        $defaultSettings = [
            'app_name'      => 'Sistem Informasi KP/KPL Universitas Almuslim',
            'gpa_minimum'   => '2.50',
            'sks_minimum'   => '100',
            'smtp_host'     => 'smtp.gmail.com',
            'smtp_port'     => '587',
            'smtp_user'     => '',
            'smtp_pass'     => '',
            'system_status' => 'open', // open, maintenance
        ];

        if (!file_exists($this->settingsPath)) {
            // Write defaults
            file_put_contents($this->settingsPath, json_encode($defaultSettings, JSON_PRETTY_PRINT));
            return $defaultSettings;
        }

        $content = file_get_contents($this->settingsPath);
        $settings = json_decode($content, true);

        return array_merge($defaultSettings, $settings ?: []);
    }

    public function index()
    {
        $data = [
            'title'    => 'Pengaturan Sistem',
            'settings' => $this->getSettings(),
        ];
        return view('admin/pengaturan/index', $data);
    }

    public function save()
    {
        $rules = [
            'app_name'      => 'required|min_length[3]|max_length[150]',
            'gpa_minimum'   => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[4]',
            'sks_minimum'   => 'required|integer|greater_than_equal_to[0]',
            'smtp_host'     => 'permit_empty|max_length[255]',
            'smtp_port'     => 'permit_empty|integer',
            'smtp_user'     => 'permit_empty|valid_email',
            'smtp_pass'     => 'permit_empty',
            'system_status' => 'required|in_list[open,maintenance]',
        ];

        $errors = [
            'app_name' => [
                'required'   => 'Nama aplikasi wajib diisi.',
                'min_length' => 'Nama aplikasi minimal 3 karakter.',
                'max_length' => 'Nama aplikasi maksimal 150 karakter.',
            ],
            'gpa_minimum' => [
                'required'              => 'IPK minimal wajib diisi.',
                'decimal'               => 'IPK minimal harus berupa desimal (contoh: 2.50).',
                'greater_than_equal_to' => 'IPK minimal tidak boleh kurang dari 0.00.',
                'less_than_equal_to'    => 'IPK minimal tidak boleh lebih dari 4.00.',
            ],
            'sks_minimum' => [
                'required'              => 'SKS minimal wajib diisi.',
                'integer'               => 'SKS minimal harus berupa angka bulat.',
                'greater_than_equal_to' => 'SKS minimal tidak boleh kurang dari 0.',
            ],
            'smtp_user' => [
                'valid_email' => 'Username SMTP harus berupa email valid.',
            ],
            'system_status' => [
                'required' => 'Status sistem wajib diisi.',
                'in_list'  => 'Status sistem tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $oldSettings = $this->getSettings();
        $newSettings = $this->request->getPost();

        // Remove CSRF token
        unset($newSettings['csrf_test_name']);

        // Write to settings.json
        file_put_contents($this->settingsPath, json_encode($newSettings, JSON_PRETTY_PRINT));

        AuditService::log(
            'UPDATE_SYSTEM_SETTINGS',
            'settings.json',
            null,
            $oldSettings,
            $newSettings,
            'Memperbarui konfigurasi sistem'
        );

        return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
