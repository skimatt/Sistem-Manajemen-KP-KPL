<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeriodModel;
use App\Models\RegistrationModel;
use App\Services\AuditService;

class ArsipController extends BaseController
{
    protected $periodModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->periodModel       = new PeriodModel();
        $this->registrationModel = new RegistrationModel();
    }

    public function index()
    {
        // Fetch periods grouped by status (active/closed vs archived)
        $periods = $this->periodModel->where('deleted_at', null)->orderBy('id', 'DESC')->findAll();

        $data = [
            'title'   => 'Arsip Periode Akademik',
            'periods' => $periods,
        ];
        return view('admin/arsip/index', $data);
    }

    public function archiveAction($id)
    {
        $period = $this->periodModel->find($id);
        if (!$period) {
            return redirect()->to(base_url('admin/arsip'))->with('error', 'Periode tidak ditemukan.');
        }

        if ($period['status'] === 'diarsipkan') {
            return redirect()->to(base_url('admin/arsip'))->with('error', 'Periode ini sudah berada dalam arsip.');
        }

        // Business Rule Check: Ensure no active students are in progress (draft, pending, or sedang_berjalan)
        // If there are students who are not yet completed/finished, we can block or require attention.
        $db = \Config\Database::connect();
        $unfinishedStudentsCount = $db->table('kp_registrations')
            ->where('period_id', $id)
            ->whereNotIn('current_status', ['selesai', 'diarsipkan', 'registrasi_ditolak', 'penempatan_ditolak'])
            ->where('deleted_at', null)
            ->countAllResults();

        if ($unfinishedStudentsCount > 0) {
            return redirect()->to(base_url('admin/arsip'))->with('error', 'Gagal mengarsipkan! Terdapat ' . $unfinishedStudentsCount . ' mahasiswa dalam periode ini yang belum menyelesaikan tahapan workflow (belum bernilai akhir atau disetujui selesai).');
        }

        // Start transaction
        $db->transStart();

        // 1. Set period status to 'diarsipkan'
        $this->periodModel->update($id, ['status' => 'diarsipkan']);

        // 2. Set all associated registration statuses to 'diarsipkan'
        $db->table('kp_registrations')
            ->where('period_id', $id)
            ->where('deleted_at', null)
            ->update(['current_status' => 'diarsipkan']);

        // 3. Log each registration status history
        $registrations = $this->registrationModel->where('period_id', $id)->findAll();
        foreach ($registrations as $reg) {
            $db->table('registration_status_logs')->insert([
                'registration_id' => $reg['id'],
                'old_status'      => $reg['current_status'],
                'new_status'      => 'diarsipkan',
                'changed_by'      => session()->get('user_id'),
                'changed_by_role' => 'admin',
                'note'            => 'Diarsipkan secara otomatis saat penutupan periode oleh Admin.',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('admin/arsip'))->with('error', 'Gagal memproses arsip karena kesalahan internal.');
        }

        AuditService::log(
            'ARCHIVE_PERIOD',
            'kp_periods',
            $id,
            $period,
            ['status' => 'diarsipkan'],
            'Mengarsipkan periode akademik: ' . $period['name']
        );

        return redirect()->to(base_url('admin/arsip'))->with('success', 'Periode ' . esc($period['name']) . ' berhasil diarsipkan dan dikunci.');
    }
}
