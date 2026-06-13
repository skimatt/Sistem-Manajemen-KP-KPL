<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Bimbingan Akademik</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan panel ini untuk memonitor progres logbook mingguan dan laporan akhir mahasiswa bimbingan Anda.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Bimbingan -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                <i class="ti ti-users-group text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Mahasiswa Bimbingan</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5">
                    <?= esc($stats['total_students']) ?> <span class="text-xs text-slate-400 dark:text-slate-505 font-normal">/ <?= esc($stats['max_quota']) ?></span>
                </p>
            </div>
        </div>

        <!-- Card 2: Logbook Perlu Review -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400">
                <i class="ti ti-notebook text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Logbook Perlu Review</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['pending_logbooks']) ?></p>
            </div>
        </div>

        <!-- Card 3: Laporan Perlu Review -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
                <i class="ti ti-file-analytics text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Laporan Perlu Review</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['pending_reports']) ?></p>
            </div>
        </div>

        <!-- Card 4: Kuota Sisa -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                <i class="ti ti-percentage text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kuota Tersedia</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5">
                    <?= max(0, esc($stats['max_quota']) - esc($stats['total_students'])) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Supervised Students list -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                <i class="ti ti-users text-slate-400 dark:text-slate-505"></i> Daftar Mahasiswa Bimbingan
            </h2>
            <a href="<?= base_url('dosen/mahasiswa') ?>" class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500">Mulai Membimbing</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-505 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-2.5 font-semibold">Nama Mahasiswa</th>
                        <th class="py-2.5 font-semibold">NPM</th>
                        <th class="py-2.5 font-semibold">Status Workflow</th>
                        <th class="py-2.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">Belum ada mahasiswa yang didelegasikan ke Anda.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="py-3 font-semibold text-slate-800 dark:text-slate-200"><?= esc($student['full_name']) ?></td>
                                <td class="py-3 text-slate-500 dark:text-slate-400 font-mono"><?= esc($student['npm']) ?></td>
                                <td class="py-3">
                                    <?php 
                                    $statusLabel = 'Sedang Berjalan';
                                    $badgeColor = 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-100/50 dark:border-blue-900/50';
                                    if ($student['current_status'] === 'selesai') {
                                        $statusLabel = 'Selesai';
                                        $badgeColor = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
                                    } elseif ($student['current_status'] === 'diarsipkan') {
                                        $statusLabel = 'Diarsipkan';
                                        $badgeColor = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-100/50 dark:border-slate-800/50';
                                    }
                                    ?>
                                    <span class="inline-flex items-center gap-1 font-medium px-2 py-0.5 rounded text-[10px] <?= $badgeColor ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="<?= base_url('dosen/logbook') ?>" class="inline-flex items-center justify-center px-2.5 py-1 bg-blue-600 text-white rounded text-[11px] font-semibold hover:bg-blue-700 shadow shadow-blue-500/10 transition">
                                        Periksa Logbook
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
