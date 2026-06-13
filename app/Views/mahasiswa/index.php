<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header / Welcome Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Tahapan KP/KPL Anda</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ikuti proses akademik Kerja Praktek (KP) atau Kerja Praktek Lapangan (KPL) secara bertahap.</p>
    </div>

    <!-- Stepper Progress Bar -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm overflow-x-auto">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-6">Alur Pelaksanaan</h3>
        
        <?php
        $stepperItems = [
            ['label' => 'Profil', 'icon' => 'ti-user'],
            ['label' => 'Registrasi', 'icon' => 'ti-id'],
            ['label' => 'Penempatan', 'icon' => 'ti-map-pin'],
            ['label' => 'Dokumen', 'icon' => 'ti-file-text'],
            ['label' => 'Pembimbing', 'icon' => 'ti-school'],
            ['label' => 'Logbook', 'icon' => 'ti-book'],
            ['label' => 'Laporan Akhir', 'icon' => 'ti-file-upload'],
            ['label' => 'Penilaian', 'icon' => 'ti-award'],
            ['label' => 'Selesai', 'icon' => 'ti-circle-check'],
        ];
        ?>

        <div class="flex items-center min-w-[800px] justify-between relative">
            <!-- Background line connecting steps -->
            <div class="absolute left-4 right-4 top-5 h-0.5 bg-slate-100 dark:bg-slate-800 -z-10"></div>
            <!-- Progress Line -->
            <div class="absolute left-4 top-5 h-0.5 bg-blue-500 -z-10 transition-all duration-500" 
                 style="width: <?= (($activeStage - 1) / (count($stepperItems) - 1)) * 100 ?>%"></div>

            <?php foreach ($stepperItems as $index => $item): ?>
                <?php 
                $stepNum = $index + 1;
                $isCompleted = $stepNum < $activeStage;
                $isActive = $stepNum === $activeStage;
                $isLocked = $stepNum > $activeStage;
                
                $bgClass = 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-500';
                if ($isCompleted) {
                    $bgClass = 'bg-blue-600 border-blue-600 dark:bg-blue-600 dark:border-blue-600 text-white';
                } elseif ($isActive) {
                    $bgClass = 'bg-white dark:bg-slate-900 border-2 border-blue-500 dark:border-blue-500 text-blue-600 dark:text-blue-400 ring-4 ring-blue-500/10 dark:ring-blue-500/20';
                }
                ?>
                <div class="flex flex-col items-center flex-1 text-center relative">
                    <!-- Circle -->
                    <div class="flex h-10 w-10 items-center justify-center rounded-full border transition duration-300 font-bold text-sm shadow-sm <?= $bgClass ?>">
                        <?php if ($isCompleted): ?>
                            <i class="ti ti-check text-base"></i>
                        <?php else: ?>
                            <i class="ti <?= esc($item['icon']) ?> text-base"></i>
                        <?php endif; ?>
                    </div>
                    <!-- Label -->
                    <span class="text-[11px] font-semibold mt-3 <?= $isActive ? 'text-blue-600 dark:text-blue-400 font-bold' : ($isCompleted ? 'text-slate-700 dark:text-slate-350' : 'text-slate-400 dark:text-slate-500') ?>">
                        <?= esc($item['label']) ?>
                    </span>
                    <!-- Label Status Subtext (only active) -->
                    <?php if ($isActive): ?>
                        <span class="text-[9px] font-bold text-blue-500 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 px-1 py-0.5 rounded mt-1">Aktif</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Layout Details Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Action & Info Card -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Next Action Card -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl p-5 md:p-6 text-white shadow-md shadow-blue-500/10 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -left-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
                
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-200">Aksi Anda Berikutnya</h3>
                <h2 class="text-base font-bold mt-2 leading-snug"><?= esc($nextAction) ?></h2>
                
                <?php if (!empty($actionUrl)): ?>
                    <a href="<?= base_url($actionUrl) ?>" class="inline-flex items-center gap-1.5 px-4  py-2 bg-white text-blue-700 rounded-lg text-xs font-bold hover:bg-slate-50 transition duration-150 mt-5 shadow">
                        Mulai Sekarang <i class="ti ti-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Notes or Advisor Section -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 mb-3">Informasi Pembimbing Akademik & Pembimbing Lapangan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Advisor PA -->
                    <div class="bg-slate-50 dark:bg-slate-950/40 rounded-lg p-3 border border-slate-200/50 dark:border-slate-800">
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 font-semibold mb-1">
                            <i class="ti ti-user-check text-sm text-blue-500"></i> Dosen Pembimbing Akademik
                        </div>
                        <p class="font-bold text-slate-800 dark:text-slate-200"><?= esc($registration->academic_advisor_name ?? 'Belum terdaftar') ?></p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Status Rekomendasi: 
                            <span class="capitalize text-slate-600 dark:text-slate-350 font-semibold"><?= esc($registration->advisor_recommendation_status ?? 'menunggu') ?></span>
                        </p>
                    </div>

                    <!-- Supervisor Instansi -->
                    <div class="bg-slate-50 dark:bg-slate-950/40 rounded-lg p-3 border border-slate-200/50 dark:border-slate-800">
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 font-semibold mb-1">
                            <i class="ti ti-building-community text-sm text-emerald-500"></i> Dosen Pembimbing Lapangan
                        </div>
                        <p class="font-bold text-slate-800 dark:text-slate-200">Dr. Khairil, M.Kom.</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Instansi: 
                            <span class="text-slate-600 dark:text-slate-350 font-semibold">PT. Teknologi Nusantara</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Status Metrics Cards -->
        <div class="space-y-4">
            <!-- Status Detail Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 mb-3">Detail Status Kegiatan</h3>
                
                <div class="space-y-3">
                    <!-- Status -->
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 block">Status Registrasi:</span>
                        <?php 
                        $statusLabel = 'Draft';
                        $statusColor = 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-950/40 dark:text-slate-400 dark:border-slate-800';
                        if ($status === 'menunggu_verifikasi') {
                            $statusLabel = 'Menunggu Verifikasi';
                            $statusColor = 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/50';
                        } elseif ($status === 'revisi_registrasi') {
                            $statusLabel = 'Perlu Revisi';
                            $statusColor = 'bg-orange-50 text-orange-700 border-orange-100 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50';
                        } elseif ($status === 'registrasi_disetujui') {
                            $statusLabel = 'Registrasi Disetujui';
                            $statusColor = 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50';
                        } elseif ($status === 'registrasi_ditolak') {
                            $statusLabel = 'Registrasi Ditolak';
                            $statusColor = 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50';
                        }
                        ?>
                        <span class="inline-flex items-center gap-1 font-semibold px-2 py-0.5 rounded text-[10px] border mt-1.5 <?= $statusColor ?>">
                            <?= $statusLabel ?>
                        </span>
                    </div>

                    <!-- Period -->
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 block">Periode Pelaksanaan:</span>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1"><?= esc($period->name ?? 'Belum Terdaftar Periode') ?></p>
                        <?php if ($period): ?>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($period->academic_year) ?> - <?= esc($period->activity_type) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Logbook Count -->
                    <div>
                        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 block">Logbook Terkirim:</span>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-1"><?= esc($logbook_count) ?> Minggu</p>
                    </div>
                </div>
            </div>

            <!-- Need Help Info Box -->
            <div class="bg-blue-50/50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/30 rounded-xl p-4 shadow-sm text-xs">
                <h4 class="font-bold text-blue-800 dark:text-blue-400 flex items-center gap-1">
                    <i class="ti ti-help-circle"></i> Butuh bantuan?
                </h4>
                <p class="text-slate-600 dark:text-slate-400 mt-1.5 leading-relaxed">
                    Jika ada kesalahan data atau kendala teknis pada alur KP/KPL Anda, silakan hubungi Koordinator Prodi atau Admin Fakultas melalui sekretariat FIKOM.
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
