<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;

class MahasiswaController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Fetch student profile
        $profile = $db->table('student_profiles')->where('user_id', session()->get('user_id'))->get()->getRow();
        
        $registration = null;
        $activePeriod = null;
        $studentStatus = 'draft';
        $profileStatus = 'incomplete';
        $activeStage = 1; // 1: Profil, 2: Registrasi, 3: Penempatan, 4: Dokumen, 5: Pembimbing, 6: Logbook, 7: Laporan, 8: Penilaian, 9: Selesai

        if ($profile) {
            $profileStatus = $profile->profile_status;
            
            // Get student registration
            $registration = $db->table('kp_registrations')->where('student_id', $profile->id)->orderBy('id', 'DESC')->get()->getRow();
            if ($registration) {
                $studentStatus = $registration->current_status;
                // Get period
                $activePeriod = $db->table('kp_periods')->where('id', $registration->period_id)->get()->getRow();
            }
        }

        // Calculate dynamic active stage
        if ($profileStatus !== 'complete') {
            $activeStage = 1;
        } elseif (!$registration) {
            $activeStage = 2;
        } else {
            $stages = [
                'draft'                => 2,
                'menunggu_verifikasi'  => 2,
                'revisi_registrasi'    => 2,
                'registrasi_ditolak'   => 2,
                'registrasi_disetujui' => 3,
                'penempatan_diajukan'  => 3,
                'penempatan_disetujui' => 4,
                'diterima_instansi'    => 5,
                'dosen_ditetapkan'     => 6,
                'sedang_berjalan'      => 6,
                'selesai'              => 9,
                'diarsipkan'           => 9
            ];
            $activeStage = $stages[$studentStatus] ?? 2;
        }

        // Action steps recommendation text
        $nextAction = '';
        $actionUrl = '';
        if ($activeStage === 1) {
            $nextAction = 'Lengkapi data profil diri dan akademik Anda untuk memulai pengajuan KP/KPL.';
            $actionUrl = 'mahasiswa/profile';
        } elseif ($activeStage === 2) {
            if (!$registration) {
                $nextAction = 'Silakan lakukan pendaftaran/registrasi KP/KPL baru pada periode aktif.';
                $actionUrl = 'mahasiswa/registrasi';
            } elseif ($studentStatus === 'draft') {
                $nextAction = 'Kirim draft registrasi KP/KPL Anda agar dapat diverifikasi oleh Koordinator.';
                $actionUrl = 'mahasiswa/registrasi';
            } elseif ($studentStatus === 'revisi_registrasi') {
                $nextAction = 'Ada catatan revisi dari Koordinator pada berkas registrasi Anda. Harap segera diperbaiki.';
                $actionUrl = 'mahasiswa/registrasi';
            } elseif ($studentStatus === 'registrasi_ditolak') {
                $nextAction = 'Pendaftaran Anda ditolak. Silakan hubungi Koordinator atau periksa kembali syarat pendaftaran.';
                $actionUrl = 'mahasiswa/registrasi';
            } else {
                $nextAction = 'Registrasi Anda berhasil dikirim. Menunggu verifikasi dokumen oleh Koordinator.';
                $actionUrl = '';
            }
        } elseif ($activeStage === 3) {
            if ($studentStatus === 'registrasi_disetujui') {
                $nextAction = 'Registrasi disetujui! Silakan ajukan penempatan instansi mitra atau tempat mandiri.';
                $actionUrl = 'mahasiswa/penempatan';
            } else {
                $nextAction = 'Pengajuan penempatan instansi telah dikirim. Menunggu persetujuan Koordinator.';
                $actionUrl = '';
            }
        } elseif ($activeStage === 4) {
            $nextAction = 'Penempatan disetujui! Silakan unduh surat pengantar instansi pada menu Dokumen.';
            $actionUrl = 'mahasiswa/dokumen';
        } elseif ($activeStage === 5) {
            $nextAction = 'Instansi telah menerima Anda. Menunggu penetapan dosen pembimbing oleh Koordinator.';
            $actionUrl = '';
        } elseif ($activeStage === 6) {
            $nextAction = 'KP/KPL sedang berjalan. Jangan lupa isi logbook kegiatan harian secara mingguan.';
            $actionUrl = 'mahasiswa/logbook';
        } elseif ($activeStage === 9) {
            $nextAction = 'Selamat! Anda telah menyelesaikan seluruh rangkaian kegiatan KP/KPL.';
            $actionUrl = '';
        }

        // Logbook count
        $logbookCount = 0;
        if ($registration) {
            $logbookCount = $db->table('logbook_weeks')->where('registration_id', $registration->id)->countAllResults();
        }

        $data = [
            'title'          => 'Dashboard Mahasiswa',
            'profile'        => $profile,
            'registration'   => $registration,
            'period'         => $activePeriod,
            'status'         => $studentStatus,
            'activeStage'    => $activeStage,
            'nextAction'     => $nextAction,
            'actionUrl'      => $actionUrl,
            'logbook_count'  => $logbookCount
        ];

        return view('mahasiswa/index', $data);
    }

    public function profile()
    {
        $db = \Config\Database::connect();
        $profile = $db->table('student_profiles')->where('user_id', session()->get('user_id'))->get()->getRow();
        
        $data = [
            'title'   => 'Profil Saya',
            'profile' => $profile
        ];

        return view('mahasiswa/profile', $data);
    }

    public function placeholder($menuName)
    {
        return view('placeholder', [
            'title'    => $menuName,
            'menuName' => $menuName
        ]);
    }
}
