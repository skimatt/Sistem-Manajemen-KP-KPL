<?php

namespace App\Controllers\Koordinator;

use App\Controllers\BaseController;
use App\Models\PeriodModel;
use App\Models\RegistrationModel;
use App\Services\AuditService;

class PeriodeController extends BaseController
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
        $db = \Config\Database::connect();

        // Get periods joined with study program details
        $periods = $db->table('kp_periods')
            ->select('kp_periods.*, study_programs.name as prodi_name,
                      (SELECT COUNT(*) FROM kp_registrations WHERE kp_registrations.period_id = kp_periods.id AND kp_registrations.deleted_at IS NULL) as total_students,
                      (SELECT COUNT(*) FROM kp_registrations WHERE kp_registrations.period_id = kp_periods.id AND kp_registrations.current_status NOT IN ("draft", "registrasi_ditolak", "penempatan_ditolak", "selesai", "diarsipkan") AND kp_registrations.deleted_at IS NULL) as active_students')
            ->join('study_programs', 'study_programs.id = kp_periods.study_program_id')
            ->where('kp_periods.deleted_at', null)
            ->orderBy('kp_periods.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'   => 'Manajemen Periode Akademik',
            'periods' => $periods,
        ];

        return view('koordinator/manajemen/periode/index', $data);
    }

    public function updateStatus($id)
    {
        $period = $this->periodModel->find($id);
        if (!$period) {
            return redirect()->to(base_url('koordinator/periode'))->with('error', 'Periode tidak ditemukan.');
        }

        $rules = [
            'status' => 'required|in_list[aktif,ditutup,diarsipkan]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('koordinator/periode'))->with('error', 'Status tidak valid.');
        }

        $status = $this->request->getPost('status');

        if ($period['status'] === $status) {
            return redirect()->to(base_url('koordinator/periode'))->with('info', 'Status periode sudah ' . esc($status) . '.');
        }

        $db = \Config\Database::connect();

        if ($status === 'diarsipkan') {
            // Check for unfinished students
            $unfinishedCount = $db->table('kp_registrations')
                ->where('period_id', $id)
                ->whereNotIn('current_status', ['selesai', 'diarsipkan', 'registrasi_ditolak', 'penempatan_ditolak'])
                ->where('deleted_at', null)
                ->countAllResults();

            if ($unfinishedCount > 0) {
                return redirect()->to(base_url('koordinator/periode'))->with('error', 'Gagal mengarsipkan! Terdapat ' . $unfinishedCount . ' mahasiswa dalam periode ini yang masih aktif / belum menyelesaikan tahapan workflow.');
            }

            // Start transaction to archive the period and lock all student profiles in it
            $db->transStart();

            $this->periodModel->update($id, ['status' => 'diarsipkan']);

            $db->table('kp_registrations')
                ->where('period_id', $id)
                ->where('deleted_at', null)
                ->update(['current_status' => 'diarsipkan']);

            $registrations = $this->registrationModel->where('period_id', $id)->findAll();
            foreach ($registrations as $reg) {
                $db->table('registration_status_logs')->insert([
                    'registration_id' => $reg['id'],
                    'old_status'      => $reg['current_status'],
                    'new_status'      => 'diarsipkan',
                    'changed_by'      => session()->get('user_id'),
                    'changed_by_role' => 'koordinator',
                    'note'            => 'Diarsipkan secara otomatis saat penutupan periode oleh Koordinator.',
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to(base_url('koordinator/periode'))->with('error', 'Gagal memproses arsip karena kesalahan internal.');
            }

            AuditService::log('ARCHIVE_PERIOD', 'kp_periods', $id, $period, ['status' => 'diarsipkan'], 'Mengarsipkan periode akademik: ' . $period['name']);

            return redirect()->to(base_url('koordinator/periode'))->with('success', 'Periode ' . esc($period['name']) . ' berhasil diarsipkan dan dikunci secara permanen.');
        }

        // Toggling between active (aktif) and closed (ditutup)
        $db->transStart();

        // If setting to active, we must ensure other periods for the same study program & activity type are closed
        if ($status === 'aktif') {
            $db->table('kp_periods')
                ->where('study_program_id', $period['study_program_id'])
                ->where('activity_type', $period['activity_type'])
                ->where('status', 'aktif')
                ->update(['status' => 'ditutup']);
        }

        $this->periodModel->update($id, ['status' => $status]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('koordinator/periode'))->with('error', 'Gagal memperbarui status periode.');
        }

        AuditService::log('UPDATE_PERIOD_STATUS', 'kp_periods', $id, $period, ['status' => $status], 'Mengubah status periode dari ' . strtoupper($period['status']) . ' menjadi ' . strtoupper($status));

        return redirect()->to(base_url('koordinator/periode'))->with('success', 'Status periode ' . esc($period['name']) . ' berhasil diubah menjadi ' . esc($status) . '.');
    }
}
