<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class WorkflowAccessFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('role') !== 'mahasiswa') {
            return null;
        }

        $stage = $arguments[0] ?? null;
        if (!$stage) {
            return null;
        }

        $db = \Config\Database::connect();
        $profile = $db->table('student_profiles')
            ->where('user_id', session()->get('user_id'))
            ->where('deleted_at', null)
            ->get()
            ->getRow();

        if (!$profile) {
            return redirect()->to(base_url('mahasiswa/profile'))
                ->with('error', 'Lengkapi profil mahasiswa terlebih dahulu.');
        }

        if ($stage === 'profile') {
            return null;
        }

        if ($stage === 'registration' && $profile->profile_status !== 'complete') {
            return redirect()->to(base_url('mahasiswa/profile'))
                ->with('error', 'Menu ini belum dapat dibuka karena profil Anda belum lengkap.');
        }

        $registration = $db->table('kp_registrations')
            ->where('student_id', $profile->id)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRow();

        if (!$registration && $stage !== 'registration') {
            return redirect()->to(base_url('mahasiswa/registrasi'))
                ->with('error', 'Menu ini belum dapat dibuka karena registrasi Anda belum dibuat.');
        }

        $rank = [
            'draft' => 1,
            'menunggu_verifikasi' => 2,
            'revisi_registrasi' => 2,
            'registrasi_ditolak' => 2,
            'registrasi_disetujui' => 3,
            'menunggu_penempatan' => 3,
            'penempatan_diajukan' => 4,
            'penempatan_revisi' => 4,
            'penempatan_ditolak' => 4,
            'penempatan_disetujui' => 5,
            'menunggu_surat' => 5,
            'menunggu_penerimaan_instansi' => 5,
            'diterima_instansi' => 6,
            'dosen_ditetapkan' => 7,
            'sedang_berjalan' => 8,
            'laporan_akhir_dikirim' => 8,
            'menunggu_penilaian_instansi' => 8,
            'nilai_instansi_masuk' => 8,
            'nilai_dosen_masuk' => 8,
            'menunggu_validasi_akhir' => 9,
            'selesai' => 10,
            'diarsipkan' => 11,
        ];

        $required = [
            'registration' => 1,
            'placement' => 3,
            'documents' => 5,
            'supervisor' => 7,
            'logbook' => 7,
            'report' => 8,
            'assessment' => 9,
            'history' => 1,
        ];

        $currentRank = $rank[$registration->current_status ?? 'draft'] ?? 0;
        $requiredRank = $required[$stage] ?? 0;

        if ($currentRank < $requiredRank) {
            return redirect()->to(base_url('mahasiswa/dashboard'))
                ->with('error', $this->messageFor($stage));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }

    private function messageFor(string $stage): string
    {
        return match ($stage) {
            'placement' => 'Menu ini belum dapat dibuka karena registrasi Anda belum disetujui.',
            'documents' => 'Menu ini belum dapat dibuka karena penempatan Anda belum disetujui.',
            'supervisor' => 'Menu ini belum dapat dibuka karena dosen pembimbing belum ditetapkan.',
            'logbook' => 'Logbook belum dapat dibuka karena Anda belum masuk tahap bimbingan.',
            'report' => 'Laporan akhir belum dapat dibuka karena kegiatan KP/KPL belum berjalan.',
            'assessment' => 'Penilaian belum dapat dibuka karena nilai akhir belum siap ditampilkan.',
            default => 'Menu ini belum dapat dibuka pada tahap workflow Anda saat ini.',
        };
    }
}
