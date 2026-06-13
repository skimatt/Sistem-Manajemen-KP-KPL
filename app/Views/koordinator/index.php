<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Keputusan Akademik</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan panel ini untuk mengelola validasi pendaftaran dan penempatan mahasiswa.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Periode -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                <i class="ti ti-calendar-stats text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Periode Aktif</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5 truncate max-w-[150px]" title="<?= esc($stats['active_period']) ?>">
                    <?= esc($stats['active_period']) ?>
                </p>
            </div>
        </div>

        <!-- Card 2: Registrasi Menunggu Validasi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400">
                <i class="ti ti-user-check text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Registrasi Menunggu</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['pending_registrations']) ?></p>
            </div>
        </div>

        <!-- Card 3: Penempatan Menunggu -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
                <i class="ti ti-map-pin-check text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Penempatan Menunggu</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['pending_placements']) ?></p>
            </div>
        </div>

        <!-- Card 4: Mahasiswa Aktif -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                <i class="ti ti-users text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Mahasiswa Aktif</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['total_students_active']) ?></p>
            </div>
        </div>
    </div>

    <!-- Actions Area: Pending Registrations List -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                <i class="ti ti-user-check text-slate-400 dark:text-slate-500"></i> Registrasi Menunggu Validasi (Terbaru)
            </h2>
            <a href="<?= base_url('koordinator/validasi-registrasi') ?>" class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-2.5 font-semibold">Nama Mahasiswa</th>
                        <th class="py-2.5 font-semibold">NPM</th>
                        <th class="py-2.5 font-semibold">SKS</th>
                        <th class="py-2.5 font-semibold">IPK</th>
                        <th class="py-2.5 font-semibold">Status</th>
                        <th class="py-2.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php if (empty($pending_regs)): ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 dark:text-slate-500">Tidak ada pendaftaran baru yang menunggu keputusan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_regs as $reg): ?>
                            <tr>
                                <td class="py-3 font-semibold text-slate-800 dark:text-slate-200"><?= esc($reg['full_name']) ?></td>
                                <td class="py-3 text-slate-500 dark:text-slate-400 font-mono"><?= esc($reg['npm']) ?></td>
                                <td class="py-3 dark:text-slate-300"><?= esc($reg['academic_sks']) ?> SKS</td>
                                <td class="py-3 dark:text-slate-300"><?= number_format(esc($reg['academic_gpa']), 2) ?></td>
                                <td class="py-3">
                                    <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-100/50 dark:border-amber-900/50 font-medium px-2 py-0.5 rounded text-[10px]">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Verifikasi
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="<?= base_url('koordinator/validasi-registrasi') ?>" class="inline-flex items-center justify-center px-2.5 py-1 bg-blue-600 text-white rounded text-[11px] font-semibold hover:bg-blue-700 shadow shadow-blue-500/10 transition">
                                        Periksa Berkas
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
