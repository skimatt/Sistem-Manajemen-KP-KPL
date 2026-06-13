<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Kuota Bimbingan Saya</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Status ketersediaan kuota bimbingan akademik Anda untuk periode aktif saat ini.</p>
    </div>

    <!-- Stats and Progress Card -->
    <?php 
    $total = esc($stats['total_students']);
    $max = esc($stats['max_quota']);
    $percentage = $max > 0 ? round(($total / $max) * 100) : 0;
    
    $barColor = 'bg-blue-600 dark:bg-blue-500';
    if ($percentage >= 100) {
        $barColor = 'bg-rose-600 dark:bg-rose-500';
    } elseif ($percentage >= 80) {
        $barColor = 'bg-amber-600 dark:bg-amber-500';
    }
    ?>
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
        <div class="md:col-span-2 space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-700 dark:text-slate-350">Kuota Terpakai</span>
                <span class="font-bold text-slate-500 dark:text-slate-400 font-mono"><?= $total ?> / <?= $max ?> Mahasiswa (<?= $percentage ?>%)</span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 h-3.5 rounded-full overflow-hidden border border-slate-200/20 dark:border-slate-700/20">
                <div class="h-full <?= $barColor ?> transition-all duration-500" style="width: <?= $percentage ?>%"></div>
            </div>
            <p class="text-[10px] text-slate-400 leading-relaxed font-medium">Berdasarkan peraturan akademik, setiap dosen memiliki batasan kuota bimbingan demi menjaga efektivitas bimbingan mahasiswa.</p>
        </div>
        
        <div class="bg-slate-50 dark:bg-slate-850 p-4 border border-slate-200/50 dark:border-slate-800 rounded-xl flex flex-col items-center justify-center text-center">
            <span class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] font-bold block mb-1">Kuota Tersisa</span>
            <p class="text-4xl font-extrabold text-slate-800 dark:text-slate-100 font-mono"><?= max(0, $max - $total) ?></p>
            <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold mt-2.5 <?= $percentage < 100 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/50' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400 border border-rose-100/50 dark:border-rose-900/50' ?>">
                <?= $percentage < 100 ? 'Tersedia' : 'Penuh' ?>
            </span>
        </div>
    </div>

    <!-- Active Students List Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-1.5">
            <i class="ti ti-users text-slate-400"></i> Detail Mahasiswa Aktif
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-505 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-2.5 px-3 font-semibold">Nama Mahasiswa</th>
                        <th class="py-2.5 px-3 font-semibold">NPM</th>
                        <th class="py-2.5 px-3 font-semibold">Program Studi</th>
                        <th class="py-2.5 px-3 font-semibold">Periode</th>
                        <th class="py-2.5 px-3 font-semibold">Status Tahap</th>
                        <th class="py-2.5 px-3 font-semibold">Tanggal Penetapan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 dark:text-slate-500">Belum ada bimbingan mahasiswa aktif.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $s): ?>
                            <tr>
                                <td class="py-3 px-3 font-semibold text-slate-800 dark:text-slate-200"><?= esc($s['full_name']) ?></td>
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400 font-mono"><?= esc($s['npm']) ?></td>
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400"><?= esc($s['prodi_name']) ?></td>
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400"><?= esc($s['period_name']) ?></td>
                                <td class="py-3 px-3">
                                    <?php 
                                    $statusLabel = str_replace('_', ' ', $s['current_status']);
                                    $statusLabel = ucwords($statusLabel);
                                    ?>
                                    <span class="inline-flex items-center gap-1 font-semibold px-2 py-0.5 rounded text-[10px] bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border border-blue-100/50 dark:border-blue-900/50">
                                        <?= esc($statusLabel) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-slate-500 dark:text-slate-400">
                                    <?= date('d M Y H:i', strtotime($s['assigned_at'])) ?>
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
