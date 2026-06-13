<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Root URL handler
$routes->get('/', function() {
    if (session()->get('logged_in')) {
        return redirect()->to(base_url(session()->get('role') . '/dashboard'));
    }
    return redirect()->to(base_url('login'));
});

// Authentication Routes
$routes->get('login', '\App\Controllers\Auth\LoginController::index');
$routes->post('login/auth', '\App\Controllers\Auth\LoginController::authenticate');
$routes->get('logout', '\App\Controllers\Auth\LoginController::logout');

// Group for Admin
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('dashboard', '\App\Controllers\Admin\AdminController::index');
    // Placeholders for other menus
    // Data Master - Mahasiswa CRUD
    $routes->get('mahasiswa', '\App\Controllers\Admin\MahasiswaController::index');
    $routes->get('mahasiswa/create', '\App\Controllers\Admin\MahasiswaController::create');
    $routes->post('mahasiswa/store', '\App\Controllers\Admin\MahasiswaController::store');
    $routes->get('mahasiswa/edit/(:num)', '\App\Controllers\Admin\MahasiswaController::edit/$1');
    $routes->post('mahasiswa/update/(:num)', '\App\Controllers\Admin\MahasiswaController::update/$1');
    $routes->get('mahasiswa/delete/(:num)', '\App\Controllers\Admin\MahasiswaController::delete/$1');

    // Data Master - Dosen CRUD
    $routes->get('dosen', '\App\Controllers\Admin\DosenController::index');
    $routes->get('dosen/create', '\App\Controllers\Admin\DosenController::create');
    $routes->post('dosen/store', '\App\Controllers\Admin\DosenController::store');
    $routes->get('dosen/edit/(:num)', '\App\Controllers\Admin\DosenController::edit/$1');
    $routes->post('dosen/update/(:num)', '\App\Controllers\Admin\DosenController::update/$1');
    $routes->get('dosen/delete/(:num)', '\App\Controllers\Admin\DosenController::delete/$1');

    // Data Master - Instansi CRUD
    $routes->get('instansi', '\App\Controllers\Admin\InstansiController::index');
    $routes->get('instansi/create', '\App\Controllers\Admin\InstansiController::create');
    $routes->post('instansi/store', '\App\Controllers\Admin\InstansiController::store');
    $routes->get('instansi/edit/(:num)', '\App\Controllers\Admin\InstansiController::edit/$1');
    $routes->post('instansi/update/(:num)', '\App\Controllers\Admin\InstansiController::update/$1');
    $routes->get('instansi/delete/(:num)', '\App\Controllers\Admin\InstansiController::delete/$1');

    // Data Master - Program Studi CRUD
    $routes->get('prodi', '\App\Controllers\Admin\ProdiController::index');
    $routes->get('prodi/create', '\App\Controllers\Admin\ProdiController::create');
    $routes->post('prodi/store', '\App\Controllers\Admin\ProdiController::store');
    $routes->get('prodi/edit/(:num)', '\App\Controllers\Admin\ProdiController::edit/$1');
    $routes->post('prodi/update/(:num)', '\App\Controllers\Admin\ProdiController::update/$1');
    $routes->get('prodi/delete/(:num)', '\App\Controllers\Admin\ProdiController::delete/$1');

    // Data Master - Manajemen Akun CRUD
    $routes->get('akun', '\App\Controllers\Admin\AkunController::index');
    $routes->get('akun/create', '\App\Controllers\Admin\AkunController::create');
    $routes->post('akun/store', '\App\Controllers\Admin\AkunController::store');
    $routes->get('akun/edit/(:num)', '\App\Controllers\Admin\AkunController::edit/$1');
    $routes->post('akun/update/(:num)', '\App\Controllers\Admin\AkunController::update/$1');
    $routes->get('akun/delete/(:num)', '\App\Controllers\Admin\AkunController::delete/$1');
    // Pelaksanaan - Periode KP/KPL CRUD
    $routes->get('periode', '\App\Controllers\Admin\PeriodeController::index');
    $routes->get('periode/create', '\App\Controllers\Admin\PeriodeController::create');
    $routes->post('periode/store', '\App\Controllers\Admin\PeriodeController::store');
    $routes->get('periode/edit/(:num)', '\App\Controllers\Admin\PeriodeController::edit/$1');
    $routes->post('periode/update/(:num)', '\App\Controllers\Admin\PeriodeController::update/$1');
    $routes->get('periode/delete/(:num)', '\App\Controllers\Admin\PeriodeController::delete/$1');
    // Pelaksanaan - Data Registrasi
    $routes->get('registrasi', '\App\Controllers\Admin\RegistrasiController::index');
    $routes->get('registrasi/view/(:num)', '\App\Controllers\Admin\RegistrasiController::view/$1');

    // Pelaksanaan - Data Penempatan
    $routes->get('penempatan', '\App\Controllers\Admin\PenempatanController::index');
    $routes->get('penempatan/view/(:num)', '\App\Controllers\Admin\PenempatanController::view/$1');

    // Pelaksanaan - Verifikasi Administrasi
    $routes->get('verifikasi-administrasi', '\App\Controllers\Admin\VerifikasiController::index');
    $routes->get('verifikasi-administrasi/review/(:num)', '\App\Controllers\Admin\VerifikasiController::review/$1');
    $routes->post('verifikasi-administrasi/update-document/(:num)', '\App\Controllers\Admin\VerifikasiController::updateDocument/$1');

    // Pelaksanaan - Logbook Mahasiswa
    $routes->get('logbook', '\App\Controllers\Admin\LogbookController::index');
    $routes->get('logbook/view/(:num)', '\App\Controllers\Admin\LogbookController::view/$1');

    // Pelaksanaan - Laporan Akhir
    $routes->get('laporan', '\App\Controllers\Admin\LaporanController::index');
    $routes->get('laporan/download/(:num)', '\App\Controllers\Admin\LaporanController::download/$1');

    // Pelaksanaan - Monitoring Penilaian
    $routes->get('penilaian', '\App\Controllers\Admin\PenilaianController::index');
    $routes->get('penilaian/view/(:num)', '\App\Controllers\Admin\PenilaianController::view/$1');
    // Pelaksanaan - Dokumen Syarat CRUD
    $routes->get('dokumen-syarat', '\App\Controllers\Admin\DokumenSyaratController::index');
    $routes->get('dokumen-syarat/create', '\App\Controllers\Admin\DokumenSyaratController::create');
    $routes->post('dokumen-syarat/store', '\App\Controllers\Admin\DokumenSyaratController::store');
    $routes->get('dokumen-syarat/edit/(:num)', '\App\Controllers\Admin\DokumenSyaratController::edit/$1');
    $routes->post('dokumen-syarat/update/(:num)', '\App\Controllers\Admin\DokumenSyaratController::update/$1');
    $routes->get('dokumen-syarat/delete/(:num)', '\App\Controllers\Admin\DokumenSyaratController::delete/$1');

    // Pelaksanaan - Template Surat CRUD
    $routes->get('template-surat', '\App\Controllers\Admin\TemplateSuratController::index');
    $routes->get('template-surat/create', '\App\Controllers\Admin\TemplateSuratController::create');
    $routes->post('template-surat/store', '\App\Controllers\Admin\TemplateSuratController::store');
    $routes->get('template-surat/edit/(:num)', '\App\Controllers\Admin\TemplateSuratController::edit/$1');
    $routes->post('template-surat/update/(:num)', '\App\Controllers\Admin\TemplateSuratController::update/$1');
    $routes->get('template-surat/delete/(:num)', '\App\Controllers\Admin\TemplateSuratController::delete/$1');

    // Pelaksanaan - Form Builder CRUD
    $routes->get('form-builder', '\App\Controllers\Admin\FormBuilderController::index');
    $routes->get('form-builder/create', '\App\Controllers\Admin\FormBuilderController::create');
    $routes->post('form-builder/store', '\App\Controllers\Admin\FormBuilderController::store');
    $routes->get('form-builder/edit/(:num)', '\App\Controllers\Admin\FormBuilderController::edit/$1');
    $routes->post('form-builder/update/(:num)', '\App\Controllers\Admin\FormBuilderController::update/$1');
    $routes->get('form-builder/delete/(:num)', '\App\Controllers\Admin\FormBuilderController::delete/$1');
    $routes->get('form-builder/fields/(:num)', '\App\Controllers\Admin\FormBuilderController::fields/$1');
    $routes->post('form-builder/fields/(:num)/add', '\App\Controllers\Admin\FormBuilderController::addField/$1');
    $routes->get('form-builder/fields/(:num)/delete/(:num)', '\App\Controllers\Admin\FormBuilderController::deleteField/$1/$2');

    // Pelaksanaan - Kriteria TOPSIS
    $routes->get('topsis', '\App\Controllers\Admin\TopsisController::index');
    $routes->post('topsis/update-weights', '\App\Controllers\Admin\TopsisController::updateWeights');

    // Pelaksanaan - Audit Log
    $routes->get('audit-log', '\App\Controllers\Admin\AuditLogController::index');

    // Pelaksanaan - Pengaturan Sistem
    $routes->get('pengaturan', '\App\Controllers\Admin\PengaturanController::index');
    $routes->post('pengaturan/save', '\App\Controllers\Admin\PengaturanController::save');

    // Pelaksanaan - Arsip Periode
    $routes->get('arsip', '\App\Controllers\Admin\ArsipController::index');
    $routes->post('arsip/archive-action/(:num)', '\App\Controllers\Admin\ArsipController::archiveAction/$1');

    // Pelaksanaan - Laporan dan Export
    $routes->get('laporan-export', '\App\Controllers\Admin\ExportController::index');
    $routes->get('laporan-export/excel', '\App\Controllers\Admin\ExportController::exportExcel');
    $routes->get('laporan-export/pdf', '\App\Controllers\Admin\ExportController::exportPdf');
    $routes->get('profile', '\App\Controllers\Admin\AdminController::placeholder/Profil Saya');
});

// Group for Koordinator
$routes->group('koordinator', ['filter' => ['auth', 'role:koordinator']], function($routes) {
    $routes->get('dashboard', '\App\Controllers\Koordinator\KoordinatorController::index');
    // Akademik Routes
    $routes->get('validasi-registrasi', '\App\Controllers\Koordinator\AkademikController::validasiRegistrasi');
    $routes->get('validasi-registrasi/review/(:num)', '\App\Controllers\Koordinator\AkademikController::reviewRegistrasi/$1');
    $routes->post('validasi-registrasi/submit/(:num)', '\App\Controllers\Koordinator\AkademikController::submitRegistrasi/$1');
    
    $routes->get('pengajuan-penempatan', '\App\Controllers\Koordinator\AkademikController::pengajuanPenempatan');
    $routes->get('pengajuan-penempatan/review/(:num)', '\App\Controllers\Koordinator\AkademikController::reviewPenempatan/$1');
    $routes->post('pengajuan-penempatan/submit/(:num)', '\App\Controllers\Koordinator\AkademikController::submitPenempatan/$1');
    
    $routes->get('topsis', '\App\Controllers\Koordinator\AkademikController::topsis');
    $routes->post('topsis/calculate/(:num)', '\App\Controllers\Koordinator\AkademikController::calculateTopsis/$1');
    $routes->post('topsis/save-scores/(:num)', '\App\Controllers\Koordinator\AkademikController::saveTopsisScores/$1');
    
    $routes->get('validasi-mandiri', '\App\Controllers\Koordinator\AkademikController::validasiMandiri');
    $routes->get('validasi-mandiri/review/(:num)', '\App\Controllers\Koordinator\AkademikController::reviewMandiri/$1');
    $routes->post('validasi-mandiri/submit/(:num)', '\App\Controllers\Koordinator\AkademikController::submitMandiri/$1');
    
    $routes->get('penetapan-pembimbing', '\App\Controllers\Koordinator\AkademikController::penetapanPembimbing');
    $routes->post('penetapan-pembimbing/assign', '\App\Controllers\Koordinator\AkademikController::submitPembimbing');
    
    $routes->get('monitoring-mahasiswa', '\App\Controllers\Koordinator\AkademikController::monitoringMahasiswa');
    
    $routes->get('monitoring-logbook', '\App\Controllers\Koordinator\AkademikController::monitoringLogbook');
    $routes->get('monitoring-logbook/view/(:num)', '\App\Controllers\Koordinator\AkademikController::viewLogbook/$1');
    
    $routes->get('monitoring-laporan', '\App\Controllers\Koordinator\AkademikController::monitoringLaporan');
    
    $routes->get('validasi-penilaian', '\App\Controllers\Koordinator\AkademikController::validasiPenilaian');
    $routes->get('validasi-penilaian/review/(:num)', '\App\Controllers\Koordinator\AkademikController::reviewPenilaian/$1');
    $routes->post('validasi-penilaian/submit/(:num)', '\App\Controllers\Koordinator\AkademikController::submitPenilaian/$1');
    
    $routes->get('rekap-nilai', '\App\Controllers\Koordinator\AkademikController::rekapNilai');
    $routes->get('periode', '\App\Controllers\Koordinator\PeriodeController::index');
    $routes->post('periode/update-status/(:num)', '\App\Controllers\Koordinator\PeriodeController::updateStatus/$1');
    $routes->get('arsip', '\App\Controllers\Koordinator\ArsipController::index');
    $routes->get('arsip/view/(:num)', '\App\Controllers\Koordinator\ArsipController::view/$1');
    $routes->get('laporan', '\App\Controllers\Koordinator\LaporanController::index');
    $routes->get('laporan/excel', '\App\Controllers\Koordinator\LaporanController::exportExcel');
    $routes->get('laporan/pdf', '\App\Controllers\Koordinator\LaporanController::exportPdf');
    $routes->get('keputusan', '\App\Controllers\Koordinator\KeputusanController::index');
    $routes->get('profile', '\App\Controllers\Koordinator\KoordinatorController::placeholder/Profil Saya');
});

// Group for Mahasiswa
$routes->group('mahasiswa', ['filter' => ['auth', 'role:mahasiswa']], function($routes) {
    $routes->get('dashboard', '\App\Controllers\Mahasiswa\MahasiswaController::index');
    $routes->get('profile', '\App\Controllers\Mahasiswa\MahasiswaController::profile');
    // Placeholders for other workflow steps
    $routes->get('registrasi', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Registrasi KP_KPL');
    $routes->get('status-registrasi', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Status Registrasi');
    $routes->get('penempatan', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Penempatan KP_KPL');
    $routes->get('rekomendasi-mitra', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Rekomendasi Mitra');
    $routes->get('tempat-mandiri', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Tempat Mandiri');
    $routes->get('dokumen', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Surat & Dokumen');
    $routes->get('upload-balasan', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Upload Dokumen Balasan');
    $routes->get('pembimbing', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Pembimbing Saya');
    $routes->get('logbook', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Logbook Mingguan');
    $routes->get('catatan-dosen', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Catatan Dosen');
    $routes->get('laporan', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Laporan Akhir');
    $routes->get('penilaian', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Penilaian Saya');
    $routes->get('riwayat', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Riwayat KP_KPL');
    $routes->get('notifikasi', '\App\Controllers\Mahasiswa\MahasiswaController::placeholder/Notifikasi');
});

// Group for Dosen
$routes->group('dosen', ['filter' => ['auth', 'role:dosen']], function($routes) {
    $routes->get('dashboard', '\App\Controllers\Dosen\DosenController::index');
    // Placeholders for other menus
    $routes->get('mahasiswa', '\App\Controllers\Dosen\DosenController::placeholder/Mahasiswa Bimbingan');
    $routes->get('logbook', '\App\Controllers\Dosen\DosenController::placeholder/Review Logbook');
    $routes->get('laporan', '\App\Controllers\Dosen\DosenController::placeholder/Review Laporan');
    $routes->get('penilaian', '\App\Controllers\Dosen\DosenController::placeholder/Input Nilai Dosen');
    $routes->get('catatan-bimbingan', '\App\Controllers\Dosen\DosenController::placeholder/Catatan Bimbingan');
    $routes->get('riwayat-bimbingan', '\App\Controllers\Dosen\DosenController::placeholder/Riwayat Bimbingan');
    $routes->get('kuota-bimbingan', '\App\Controllers\Dosen\DosenController::placeholder/Kuota Bimbingan');
    $routes->get('notifikasi', '\App\Controllers\Dosen\DosenController::placeholder/Notifikasi');
    $routes->get('profile', '\App\Controllers\Dosen\DosenController::placeholder/Profil Saya');
});

// Group for Instansi
$routes->group('instansi', ['filter' => ['auth', 'role:instansi']], function($routes) {
    $routes->get('dashboard', '\App\Controllers\Instansi\InstansiController::index');
    // Placeholders for other menus
    $routes->get('mahasiswa', '\App\Controllers\Instansi\InstansiController::placeholder/Mahasiswa KP_KPL');
    $routes->get('penilaian', '\App\Controllers\Instansi\InstansiController::placeholder/Input Nilai Instansi');
    $routes->get('profil', '\App\Controllers\Instansi\InstansiController::placeholder/Profil Instansi');
    $routes->get('konfirmasi', '\App\Controllers\Instansi\InstansiController::placeholder/Konfirmasi Penerimaan');
    $routes->get('pembimbing', '\App\Controllers\Instansi\InstansiController::placeholder/Pembimbing Lapangan');
    $routes->get('logbook', '\App\Controllers\Instansi\InstansiController::placeholder/Logbook Mahasiswa');
    $routes->get('validasi-logbook', '\App\Controllers\Instansi\InstansiController::placeholder/Validasi Logbook');
    $routes->get('evaluasi', '\App\Controllers\Instansi\InstansiController::placeholder/Evaluasi Mahasiswa');
    $routes->get('riwayat', '\App\Controllers\Instansi\InstansiController::placeholder/Riwayat Mahasiswa');
    $routes->get('dokumen', '\App\Controllers\Instansi\InstansiController::placeholder/Dokumen Terkait');
    $routes->get('notifikasi', '\App\Controllers\Instansi\InstansiController::placeholder/Notifikasi');
    $routes->get('profile', '\App\Controllers\Instansi\InstansiController::placeholder/Profil Saya');
});
