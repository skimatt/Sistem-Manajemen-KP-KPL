<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Ringkasan Sistem</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Selamat datang kembali di panel administrasi SIM KP/KPL.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat Card 1: Periode Aktif -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                <i class="ti ti-calendar-event text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Periode Aktif</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5 truncate max-w-[150px]" title="<?= esc($stats['active_period']) ?>">
                    <?= esc($stats['active_period']) ?>
                </p>
            </div>
        </div>

        <!-- Stat Card 2: Total Mahasiswa -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                <i class="ti ti-users text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Mahasiswa</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['total_students']) ?></p>
            </div>
        </div>

        <!-- Stat Card 3: Total Dosen -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
                <i class="ti ti-school text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Dosen</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['total_lecturers']) ?></p>
            </div>
        </div>

        <!-- Stat Card 4: Menunggu Verifikasi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm transition-colors duration-300">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400">
                <i class="ti ti-user-check text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Perlu Verifikasi</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($stats['pending_verifs']) ?></p>
            </div>
        </div>
    </div>

    <!-- Main Workspace Content: Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 columns: Audit Logs -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 lg:col-span-2 shadow-sm flex flex-col transition-colors duration-300">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                    <i class="ti ti-history text-slate-400 dark:text-slate-500"></i> Log Aktivitas Terbaru
                </h2>
                <a href="<?= base_url('admin/audit-log') ?>" class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead>
                        <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                            <th class="py-2.5 font-semibold">User</th>
                            <th class="py-2.5 font-semibold">Aksi</th>
                            <th class="py-2.5 font-semibold">Keterangan</th>
                            <th class="py-2.5 font-semibold">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        <?php if (empty($recent_logs)): ?>
                            <tr>
                                <td colspan="4" class="py-4 text-center text-slate-400 dark:text-slate-500">Belum ada aktivitas tercatat.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recent_logs as $log): ?>
                                <tr>
                                    <td class="py-2.5 font-medium text-slate-800 dark:text-slate-200">
                                        Role: <span class="capitalize text-[10px] bg-slate-100 dark:bg-slate-800/80 px-1 rounded text-slate-700 dark:text-slate-300"><?= esc($log['role']) ?></span>
                                    </td>
                                    <td class="py-2.5">
                                        <span class="capitalize font-semibold text-blue-600 dark:text-blue-400"><?= esc($log['action']) ?></span>
                                    </td>
                                    <td class="py-2.5 max-w-[200px] truncate" title="<?= esc($log['note']) ?>"><?= esc($log['note']) ?></td>
                                    <td class="py-2.5 text-slate-400 dark:text-slate-550"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 1 column: Recent Users -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm flex flex-col transition-colors duration-300">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                    <i class="ti ti-users-group text-slate-400 dark:text-slate-500"></i> Pengguna Baru
                </h2>
                <a href="<?= base_url('admin/mahasiswa') ?>" class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500">Kelola</a>
            </div>

            <div class="space-y-3 flex-1">
                <?php if (empty($recent_users)): ?>
                    <p class="text-center text-slate-400 dark:text-slate-500 text-xs py-4">Belum ada pengguna terdaftar.</p>
                <?php else: ?>
                    <?php foreach ($recent_users as $user): ?>
                        <div class="flex items-center gap-3 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/30 rounded-lg transition duration-150">
                            <!-- Initials Avatar -->
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs uppercase flex-shrink-0">
                                <?= substr(esc($user['name']), 0, 2) ?>
                            </div>
                            <div class="truncate flex-1">
                                <h4 class="text-xs font-semibold text-slate-800 dark:text-slate-250 truncate"><?= esc($user['name']) ?></h4>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-0.5"><?= esc($user['email']) ?></p>
                            </div>
                            <span class="capitalize text-[9px] font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded flex-shrink-0">
                                <?= esc($user['role']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
