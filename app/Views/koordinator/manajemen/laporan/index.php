<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Laporan Rekapitulasi</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Lihat ringkasan statistik akademik dan ekspor laporan resmi kegiatan KP/KPL.</p>
        </div>
        <!-- Period Selector -->
        <form method="GET" action="<?= base_url('koordinator/laporan') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-slate-500 dark:text-slate-400">Pilih Periode:</span>
            <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none">
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodId ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= strtoupper($p['activity_type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Quick Export Card -->
    <?php if ($selectedPeriodId): ?>
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex flex-wrap gap-4 items-center justify-between">
            <div class="space-y-0.5">
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300">Ekspor Laporan Cetak Resmi</h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Unduh data rekapitulasi penilaian dan alur proses dalam format PDF atau Excel.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('koordinator/laporan/excel?period_id=' . $selectedPeriodId) ?>" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition">
                    <i class="ti ti-file-spreadsheet text-sm"></i> Ekspor Excel
                </a>
                <a href="<?= base_url('koordinator/laporan/pdf?period_id=' . $selectedPeriodId) ?>" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-xs transition animate-pulse">
                    <i class="ti ti-file-type-pdf text-sm"></i> Ekspor PDF
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Panel -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Stat 1 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <i class="ti ti-users text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Terdaftar</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= $stats['total_students'] ?></span>
            </div>
        </div>
        <!-- Stat 2 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                <i class="ti ti-circle-check text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Selesai</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= $stats['completed_count'] ?></span>
            </div>
        </div>
        <!-- Stat 3 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex-shrink-0">
                <i class="ti ti-refresh text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Proses Aktif</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= $stats['active_count'] ?></span>
            </div>
        </div>
        <!-- Stat 4 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex-shrink-0">
                <i class="ti ti-circle-x text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Ditolak</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= $stats['failed_count'] ?></span>
            </div>
        </div>
        <!-- Stat 5 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex-shrink-0">
                <i class="ti ti-chart-bar text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Rerata Nilai</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= number_format($stats['avg_score'], 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grade Distribution Card -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-chart-pie text-base text-blue-500"></i>
                    Distribusi Nilai Huruf (Grade)
                </h3>
                <div class="space-y-3.5 text-xs">
                    <?php 
                    $maxQty = max(array_values($stats['grade_dist']));
                    foreach ($stats['grade_dist'] as $grade => $qty): 
                        $pct = $stats['total_students'] > 0 ? ($qty / $stats['total_students']) * 100 : 0;
                    ?>
                        <div class="space-y-1">
                            <div class="flex justify-between items-center text-[11px] font-semibold text-slate-600 dark:text-slate-400">
                                <span class="flex items-center gap-2">
                                    <span class="inline-flex w-6 h-6 items-center justify-center rounded bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 font-bold"><?= $grade ?></span>
                                    <span><?= $qty ?> Mahasiswa</span>
                                </span>
                                <span><?= number_format($pct, 1) ?>%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                                <div class="bg-blue-600 dark:bg-blue-500 h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Student Grade Preview Grid -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-file-text text-base text-blue-500"></i>
                    Daftar Nilai Mahasiswa (Preview)
                </h3>
                <div class="table-responsive">
                    <table id="laporanPreviewTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                        <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                            <tr>
                                <th class="px-4 py-3 font-bold">NPM</th>
                                <th class="px-4 py-3 font-bold">Mahasiswa</th>
                                <th class="px-4 py-3 font-bold text-center">Nilai Akhir</th>
                                <th class="px-4 py-3 font-bold text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <?php if (!empty($students)): ?>
                                <?php foreach ($students as $s): ?>
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                        <td class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-350">
                                            <?= esc($s['npm']) ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-800 dark:text-slate-200"><?= esc($s['full_name']) ?></div>
                                            <div class="text-[9px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($s['prodi_name']) ?></div>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-800 dark:text-slate-300">
                                            <?= $s['final_score'] !== null ? number_format($s['final_score'], 2) : '-' ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 font-bold text-[10px]">
                                                <?= esc($s['final_grade']) ?: '-' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#laporanPreviewTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            pageLength: 5,
            lengthMenu: [5, 10, 20]
        });
    });
</script>
<?= $this->endSection() ?>
