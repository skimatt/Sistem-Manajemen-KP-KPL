<?php
$role = session()->get('role') ?? 'mahasiswa';
$name = session()->get('name') ?? 'User';

// Student dynamic status logic
$studentStatus = 'draft';
$profileStatus = 'incomplete';
if ($role === 'mahasiswa') {
    $db = \Config\Database::connect();
    $profile = $db->table('student_profiles')->where('user_id', session()->get('user_id'))->get()->getRow();
    if ($profile) {
        $profileStatus = $profile->profile_status;
        $registration = $db->table('kp_registrations')->where('student_id', $profile->id)->orderBy('id', 'DESC')->get()->getRow();
        if ($registration) {
            $studentStatus = $registration->current_status;
        }
    }
}

// Check access stages
$hasAccess = function($stage) use ($studentStatus, $profileStatus) {
    if ($stage === 'profil') return true;
    if ($stage === 'registrasi') return $profileStatus === 'complete';
    
    $stages = [
        'draft' => 1,
        'menunggu_verifikasi' => 2,
        'revisi_registrasi' => 2,
        'registrasi_ditolak' => 2,
        'registrasi_disetujui' => 3,
        'penempatan_diajukan' => 4,
        'penempatan_disetujui' => 5,
        'diterima_instansi' => 6,
        'dosen_ditetapkan' => 7,
        'sedang_berjalan' => 8,
        'selesai' => 9,
        'diarsipkan' => 10
    ];
    
    $currentRank = $stages[$studentStatus] ?? 0;
    
    switch ($stage) {
        case 'penempatan':
            return $currentRank >= 3;
        case 'dokumen':
            return $currentRank >= 5;
        case 'pembimbing':
        case 'logbook':
            return $currentRank >= 7;
        case 'laporan':
            return $currentRank >= 8;
        case 'penilaian':
            return $currentRank >= 9;
        default:
            return false;
    }
};

$getLockMessage = function($stage) {
    switch ($stage) {
        case 'registrasi':
            return 'Lengkapi profil Anda terlebih dahulu untuk membuka menu Registrasi.';
        case 'penempatan':
            return 'Menu Penempatan belum dapat dibuka karena pendaftaran/registrasi KP/KPL Anda belum disetujui oleh Koordinator.';
        case 'dokumen':
            return 'Menu Surat & Dokumen belum dapat dibuka karena pengajuan penempatan Anda belum disetujui.';
        case 'pembimbing':
            return 'Menu Pembimbing Saya belum dapat dibuka karena dosen pembimbing belum ditetapkan oleh Koordinator.';
        case 'logbook':
            return 'Menu Logbook belum dapat dibuka karena Anda belum masuk tahap berjalan dan dosen pembimbing belum ditetapkan.';
        case 'laporan':
            return 'Menu Laporan Akhir belum dapat dibuka karena tahap pelaksanaan KP/KPL belum berjalan.';
        case 'penilaian':
            return 'Menu Penilaian belum dapat dibuka karena kegiatan KP/KPL Anda belum selesai.';
        default:
            return 'Menu ini masih terkunci.';
    }
};

// Define menu list by role
$menuStructure = [];
if ($role === 'admin') {
    $menuStructure = [
        ['type' => 'link', 'label' => 'Dashboard', 'icon' => 'ti-layout-dashboard', 'url' => 'admin/dashboard'],
        [
            'type' => 'group',
            'label' => 'Data Master',
            'icon' => 'ti-database',
            'group_id' => 'data_master',
            'items' => [
                ['label' => 'Data Mahasiswa', 'url' => 'admin/mahasiswa'],
                ['label' => 'Data Dosen', 'url' => 'admin/dosen'],
                ['label' => 'Data Instansi', 'url' => 'admin/instansi'],
                ['label' => 'Program Studi', 'url' => 'admin/prodi'],
                ['label' => 'Manajemen Akun', 'url' => 'admin/akun'],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Pelaksanaan',
            'icon' => 'ti-briefcase',
            'group_id' => 'pelaksanaan_admin',
            'items' => [
                ['label' => 'Periode KP/KPL', 'url' => 'admin/periode'],
                ['label' => 'Data Registrasi', 'url' => 'admin/registrasi'],
                ['label' => 'Data Penempatan', 'url' => 'admin/penempatan'],
                ['label' => 'Verifikasi Administrasi', 'url' => 'admin/verifikasi-administrasi'],
                ['label' => 'Logbook Mahasiswa', 'url' => 'admin/logbook'],
                ['label' => 'Laporan Akhir', 'url' => 'admin/laporan'],
                ['label' => 'Monitoring Penilaian', 'url' => 'admin/penilaian'],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Konfigurasi',
            'icon' => 'ti-settings',
            'group_id' => 'config',
            'items' => [
                ['label' => 'Dokumen Syarat', 'url' => 'admin/dokumen-syarat'],
                ['label' => 'Template Surat', 'url' => 'admin/template-surat'],
                ['label' => 'Form Builder', 'url' => 'admin/form-builder'],
                ['label' => 'Kriteria TOPSIS', 'url' => 'admin/topsis'],
                ['label' => 'Audit Log', 'url' => 'admin/audit-log'],
                ['label' => 'Pengaturan Sistem', 'url' => 'admin/pengaturan'],
                ['label' => 'Arsip Periode', 'url' => 'admin/arsip'],
                ['label' => 'Laporan & Export', 'url' => 'admin/laporan-export'],
            ]
        ],
    ];
} elseif ($role === 'koordinator') {
    $menuStructure = [
        ['type' => 'link', 'label' => 'Dashboard', 'icon' => 'ti-layout-dashboard', 'url' => 'koordinator/dashboard'],
        [
            'type' => 'group',
            'label' => 'Akademik',
            'icon' => 'ti-school',
            'group_id' => 'akademik',
            'items' => [
                ['label' => 'Validasi Registrasi', 'url' => 'koordinator/validasi-registrasi'],
                ['label' => 'Pengajuan Penempatan', 'url' => 'koordinator/pengajuan-penempatan'],
                ['label' => 'Rekomendasi TOPSIS', 'url' => 'koordinator/topsis'],
                ['label' => 'Validasi Tempat Mandiri', 'url' => 'koordinator/validasi-mandiri'],
                ['label' => 'Penetapan Pembimbing', 'url' => 'koordinator/penetapan-pembimbing'],
                ['label' => 'Monitoring Mahasiswa', 'url' => 'koordinator/monitoring-mahasiswa'],
                ['label' => 'Monitoring Logbook', 'url' => 'koordinator/monitoring-logbook'],
                ['label' => 'Monitoring Laporan', 'url' => 'koordinator/monitoring-laporan'],
                ['label' => 'Validasi Penilaian', 'url' => 'koordinator/validasi-penilaian'],
                ['label' => 'Rekap Nilai Akhir', 'url' => 'koordinator/rekap-nilai'],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Manajemen & Arsip',
            'icon' => 'ti-archive',
            'group_id' => 'management',
            'items' => [
                ['label' => 'Manajemen Periode', 'url' => 'koordinator/periode'],
                ['label' => 'Arsip KP/KPL', 'url' => 'koordinator/arsip'],
                ['label' => 'Laporan Rekapitulasi', 'url' => 'koordinator/laporan'],
                ['label' => 'Catatan Keputusan', 'url' => 'koordinator/keputusan'],
            ]
        ],
    ];
} elseif ($role === 'dosen') {
    $menuStructure = [
        ['type' => 'link', 'label' => 'Dashboard', 'icon' => 'ti-layout-dashboard', 'url' => 'dosen/dashboard'],
        [
            'type' => 'group',
            'label' => 'Bimbingan',
            'icon' => 'ti-users-group',
            'group_id' => 'bimbingan',
            'items' => [
                ['label' => 'Mahasiswa Bimbingan', 'url' => 'dosen/mahasiswa'],
                ['label' => 'Review Logbook', 'url' => 'dosen/logbook'],
                ['label' => 'Catatan Bimbingan', 'url' => 'dosen/catatan-bimbingan'],
                ['label' => 'Review Laporan', 'url' => 'dosen/laporan'],
                ['label' => 'Input Nilai Dosen', 'url' => 'dosen/penilaian'],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Informasi Dosen',
            'icon' => 'ti-info-circle',
            'group_id' => 'info_dosen',
            'items' => [
                ['label' => 'Kuota Bimbingan', 'url' => 'dosen/kuota-bimbingan'],
                ['label' => 'Riwayat Bimbingan', 'url' => 'dosen/riwayat-bimbingan'],
                ['label' => 'Notifikasi', 'url' => 'dosen/notifikasi'],
            ]
        ],
    ];
} elseif ($role === 'instansi') {
    $menuStructure = [
        ['type' => 'link', 'label' => 'Dashboard', 'icon' => 'ti-layout-dashboard', 'url' => 'instansi/dashboard'],
        ['type' => 'link', 'label' => 'Profil Instansi', 'icon' => 'ti-building', 'url' => 'instansi/profil'],
        [
            'type' => 'group',
            'label' => 'Kegiatan Lapangan',
            'icon' => 'ti-briefcase',
            'group_id' => 'kegiatan',
            'items' => [
                ['label' => 'Konfirmasi Penerimaan', 'url' => 'instansi/konfirmasi'],
                ['label' => 'Mahasiswa KP/KPL', 'url' => 'instansi/mahasiswa'],
                ['label' => 'Pembimbing Lapangan', 'url' => 'instansi/pembimbing'],
                ['label' => 'Logbook Mahasiswa', 'url' => 'instansi/logbook'],
                ['label' => 'Validasi Logbook', 'url' => 'instansi/validasi-logbook'],
                ['label' => 'Evaluasi Mahasiswa', 'url' => 'instansi/evaluasi'],
                ['label' => 'Input Nilai Instansi', 'url' => 'instansi/penilaian'],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Informasi Instansi',
            'icon' => 'ti-folder',
            'group_id' => 'info_instansi',
            'items' => [
                ['label' => 'Dokumen Terkait', 'url' => 'instansi/dokumen'],
                ['label' => 'Riwayat Mahasiswa', 'url' => 'instansi/riwayat'],
                ['label' => 'Notifikasi', 'url' => 'instansi/notifikasi'],
            ]
        ],
    ];
} else { // mahasiswa
    $menuStructure = [
        ['type' => 'link', 'label' => 'Dashboard', 'icon' => 'ti-layout-dashboard', 'url' => 'mahasiswa/dashboard', 'stage' => 'profil'],
        ['type' => 'link', 'label' => 'Profil Saya', 'icon' => 'ti-user', 'url' => 'mahasiswa/profile', 'stage' => 'profil'],
        [
            'type' => 'group',
            'label' => 'Pelaksanaan',
            'icon' => 'ti-notes',
            'group_id' => 'pelaksanaan',
            'items' => [
                ['label' => 'Registrasi KP/KPL', 'url' => 'mahasiswa/registrasi', 'stage' => 'registrasi'],
                ['label' => 'Status Registrasi', 'url' => 'mahasiswa/status-registrasi', 'stage' => 'registrasi'],
                ['label' => 'Penempatan KP/KPL', 'url' => 'mahasiswa/penempatan', 'stage' => 'penempatan'],
                ['label' => 'Rekomendasi Mitra', 'url' => 'mahasiswa/rekomendasi-mitra', 'stage' => 'penempatan'],
                ['label' => 'Tempat Mandiri', 'url' => 'mahasiswa/tempat-mandiri', 'stage' => 'penempatan'],
                ['label' => 'Surat & Dokumen', 'url' => 'mahasiswa/dokumen', 'stage' => 'dokumen'],
                ['label' => 'Upload Dokumen Balasan', 'url' => 'mahasiswa/upload-balasan', 'stage' => 'dokumen'],
                ['label' => 'Pembimbing Saya', 'url' => 'mahasiswa/pembimbing', 'stage' => 'pembimbing'],
                ['label' => 'Logbook Mingguan', 'url' => 'mahasiswa/logbook', 'stage' => 'logbook'],
                ['label' => 'Catatan Dosen', 'url' => 'mahasiswa/catatan-dosen', 'stage' => 'logbook'],
                ['label' => 'Laporan Akhir', 'url' => 'mahasiswa/laporan', 'stage' => 'laporan'],
                ['label' => 'Penilaian Saya', 'url' => 'mahasiswa/penilaian', 'stage' => 'penilaian'],
            ]
        ],
        [
            'type' => 'group',
            'label' => 'Lainnya',
            'icon' => 'ti-folder-open',
            'group_id' => 'lainnya_mahasiswa',
            'items' => [
                ['label' => 'Riwayat KP/KPL', 'url' => 'mahasiswa/riwayat', 'stage' => 'profil'],
                ['label' => 'Notifikasi', 'url' => 'mahasiswa/notifikasi', 'stage' => 'profil'],
            ]
        ]
    ];
}

$currentUri = uri_string();
?>

<!-- Mobile Overlay -->
<div x-show="isSidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden" 
     @click="toggleSidebarMobile()" 
     x-cloak></div>

<!-- Sidebar Drawer -->
<aside class="fixed bottom-0 top-0 left-0 z-50 flex flex-col border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300 md:sticky h-screen overflow-hidden"
       :class="[
           isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
           isSidebarCollapsed ? 'w-20' : 'w-64'
       ]"
       x-cloak>
    
    <!-- Sidebar Header (Logo) -->
    <div class="flex h-16 items-center justify-between px-5 border-b border-slate-100 dark:border-slate-800/60 flex-shrink-0">
        <div class="flex items-center gap-2.5" :class="isSidebarCollapsed ? 'mx-auto' : ''">
            <!-- Icon Logo -->
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 dark:bg-blue-500 text-white font-bold flex-shrink-0 shadow-sm">
                <i class="ti ti-activity text-lg"></i>
            </div>
            <!-- Text Logo -->
            <span class="font-bold text-slate-800 dark:text-slate-100 text-sm tracking-wide" x-show="!isSidebarCollapsed">SIM KP/KPL</span>
        </div>
        <!-- Close Button (Mobile Only) -->
        <button @click="toggleSidebarMobile()" class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 md:hidden focus:outline-none">
            <i class="ti ti-x text-lg"></i>
        </button>
    </div>

    <!-- Mock Search Bar (Screenshot Style) -->
    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800/60 flex-shrink-0" x-show="!isSidebarCollapsed">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400 dark:text-slate-500">
                <i class="ti ti-search text-sm"></i>
            </span>
            <input type="text" 
                   placeholder="Pencarian..." 
                   class="block w-full pl-8 pr-12 py-1.5 text-xs bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-800 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none" 
                   disabled />
            <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[9px] font-sans font-semibold text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded shadow-sm">Ctrl+K</kbd>
            </span>
        </div>
    </div>

    <!-- Navigation Scroll Area -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5">
        <?php foreach ($menuStructure as $menu): ?>
            <?php if ($menu['type'] === 'link'): ?>
                <?php 
                $stage = $menu['stage'] ?? null;
                $isLocked = ($role === 'mahasiswa' && $stage && !$hasAccess($stage));
                $isActive = ($currentUri === $menu['url']);
                $clickAction = '';
                $url = base_url($menu['url']);
                
                if ($isLocked) {
                    $url = '#';
                    $lockMsg = $getLockMessage($stage);
                    $clickAction = 'x-on:click.prevent="Swal.fire({icon: \'warning\', title: \'Menu Terkunci\', text: \'' . esc($lockMsg) . '\', confirmButtonColor: \'#3b82f6\', confirmButtonText: \'Paham\'})"';
                }
                ?>
                <a href="<?= $url ?>" <?= $clickAction ?> 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition duration-150 group relative"
                   :class="[
                       <?= $isActive ? 'true' : 'false' ?> ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-100',
                       <?= $isLocked ? 'true' : 'false' ?> ? 'opacity-45 cursor-not-allowed' : ''
                   ]">
                    <!-- Icon -->
                    <div class="flex items-center justify-center flex-shrink-0">
                        <i class="ti <?= esc($menu['icon']) ?> text-base" :class="<?= $isActive ? 'true' : 'false' ?> ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-500 dark:group-hover:text-slate-350'"></i>
                    </div>
                    <!-- Label -->
                    <span class="truncate" x-show="!isSidebarCollapsed"><?= esc($menu['label']) ?></span>
                    
                    <?php if ($isLocked): ?>
                        <i class="ti ti-lock text-[10px] ml-auto text-slate-400" x-show="!isSidebarCollapsed"></i>
                    <?php endif; ?>

                    <!-- Collapsed Hover Tooltip -->
                    <div class="absolute left-full ml-3 px-2.5 py-1.5 bg-slate-950 dark:bg-slate-800 text-white text-[10px] font-bold rounded-md shadow-lg pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-150 whitespace-nowrap z-50 hidden md:block" 
                         x-show="isSidebarCollapsed">
                        <?= esc($menu['label']) ?><?php if ($isLocked): ?> <i class="ti ti-lock text-[8px] inline-block ml-1"></i><?php endif; ?>
                    </div>
                </a>

            <?php elseif ($menu['type'] === 'group'): ?>
                <?php 
                $groupId = $menu['group_id'];
                // Check if any sub-item is active
                $hasActiveSub = false;
                foreach ($menu['items'] as $sub) {
                    if ($currentUri === $sub['url']) {
                        $hasActiveSub = true;
                        break;
                    }
                }
                ?>
                <div x-data="{ expanded: activeGroup === '<?= $groupId ?>' || <?= $hasActiveSub ? 'true' : 'false' ?> }" x-init="$watch('activeGroup', val => expanded = val === '<?= $groupId ?>')" class="space-y-1">
                    <!-- Group Toggle Button -->
                    <button @click="toggleGroup('<?= $groupId ?>')" 
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-100 transition duration-150 group relative focus:outline-none"
                            :class="[
                                expanded ? 'text-slate-900 dark:text-slate-100' : ''
                            ]">
                        <div class="flex items-center justify-center flex-shrink-0">
                            <i class="ti <?= esc($menu['icon']) ?> text-base text-slate-400 dark:text-slate-500 group-hover:text-slate-500"></i>
                        </div>
                        <span class="truncate" x-show="!isSidebarCollapsed"><?= esc($menu['label']) ?></span>
                        <i class="ti ti-chevron-down text-[10px] ml-auto text-slate-400 dark:text-slate-500 transition-transform duration-200" 
                           :class="expanded ? 'rotate-180' : ''" 
                           x-show="!isSidebarCollapsed"></i>

                        <!-- Collapsed Hover Tooltip for Group -->
                        <div class="absolute left-full ml-3 px-2.5 py-1.5 bg-slate-950 dark:bg-slate-800 text-white text-[10px] font-bold rounded-md shadow-lg pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-150 whitespace-nowrap z-50 hidden md:block" 
                             x-show="isSidebarCollapsed">
                            <?= esc($menu['label']) ?>
                        </div>
                    </button>

                    <!-- Collapsible Sub Items -->
                    <div x-show="expanded" x-collapse x-cloak>
                        <div class="border-l border-slate-150 dark:border-slate-800 pl-4 ml-4.5 py-1 space-y-1">
                            <?php foreach ($menu['items'] as $subItem): ?>
                                <?php 
                                $subStage = $subItem['stage'] ?? null;
                                $subLocked = ($role === 'mahasiswa' && $subStage && !$hasAccess($subStage));
                                $subActive = ($currentUri === $subItem['url']);
                                $subClick = '';
                                $subUrl = base_url($subItem['url']);
                                
                                if ($subLocked) {
                                    $subUrl = '#';
                                    $subLockMsg = $getLockMessage($subStage);
                                    $subClick = 'x-on:click.prevent="Swal.fire({icon: \'warning\', title: \'Menu Terkunci\', text: \'' . esc($subLockMsg) . '\', confirmButtonColor: \'#3b82f6\', confirmButtonText: \'Paham\'})"';
                                }
                                ?>
                                <a href="<?= $subUrl ?>" <?= $subClick ?>
                                   class="block px-2.5 py-1.5 rounded-md text-[11px] font-medium transition duration-150 relative group"
                                   :class="[
                                       <?= $subActive ? 'true' : 'false' ?> ? 'bg-slate-100 dark:bg-slate-850 text-slate-900 dark:text-white font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-950 dark:hover:text-slate-100 hover:bg-slate-50/50 dark:hover:bg-slate-850/50',
                                       <?= $subLocked ? 'true' : 'false' ?> ? 'opacity-45 cursor-not-allowed' : ''
                                   ]">
                                    <span class="truncate block"><?= esc($subItem['label']) ?></span>
                                    
                                    <?php if ($subLocked): ?>
                                        <i class="ti ti-lock text-[8px] absolute right-2.5 top-2.5 text-slate-400"></i>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- SIM Info Box Callout (mocking AI Upgrade bottom left in screenshot) -->
    <div x-data="{ closed: false }" 
         x-show="!closed && !isSidebarCollapsed" 
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="mx-4 my-4 p-3.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200/50 dark:border-slate-800 rounded-xl relative flex-shrink-0"
         x-cloak>
        <button @click="closed = true" class="absolute top-2 right-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none">
            <i class="ti ti-x text-xs"></i>
        </button>
        <div class="flex h-6 w-6 items-center justify-center rounded bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 mb-2">
            <i class="ti ti-help-circle text-sm"></i>
        </div>
        <h4 class="text-[11px] font-bold text-slate-800 dark:text-slate-200">Panduan Akademik</h4>
        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">Unduh buku pedoman pelaksanaan KP/KPL untuk detail alur resmi.</p>
        <button onclick="Swal.fire({icon: 'info', title: 'Unduh Panduan', text: 'Tautan buku pedoman sedang dipersiapkan oleh akademik.', confirmButtonColor: '#3b82f6'})" 
                class="w-full mt-3 py-1.5 px-2 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-lg text-[10px] font-semibold transition">
            Unduh Panduan
        </button>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-100 dark:border-slate-800/60 bg-white dark:bg-slate-900 flex-shrink-0">
        <div class="flex items-center gap-2.5 bg-slate-50 dark:bg-slate-850/60 p-2 rounded-lg border border-slate-200/40 dark:border-slate-800/40">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-bold text-xs uppercase flex-shrink-0">
                <?= strtoupper(substr($name, 0, 1)) ?>
            </div>
            <div class="truncate flex-1" x-show="!isSidebarCollapsed">
                <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate leading-none"><?= esc($name) ?></p>
                <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-1 uppercase font-bold tracking-wider leading-none"><?= esc($role) ?></p>
            </div>
        </div>
    </div>
</aside>
