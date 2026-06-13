<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Penilaian Akademik Mahasiswa</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Input nilai kompetensi bimbingan mahasiswa Anda. Nilai yang Anda input akan menjadi komponen nilai dosen pembimbing (bobot 50%).</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table id="gradesTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-505 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-3 px-4 font-semibold">Nama Mahasiswa</th>
                        <th class="py-3 px-4 font-semibold">NPM</th>
                        <th class="py-3 px-4 font-semibold">Periode</th>
                        <th class="py-3 px-4 font-semibold">Status Workflow</th>
                        <th class="py-3 px-4 font-semibold">Nilai Dosen</th>
                        <th class="py-3 px-4 font-semibold">Nilai Akhir</th>
                        <th class="py-3 px-4 font-semibold">Index Grade</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php foreach ($students as $s): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors duration-150">
                            <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                <?= esc($s['full_name']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400 font-mono">
                                <?= esc($s['npm']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= esc($s['period_name']) ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php 
                                $statusLabel = str_replace('_', ' ', $s['current_status']);
                                $statusLabel = ucwords($statusLabel);
                                $badgeColor = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-150/50 dark:border-slate-800/50';
                                
                                if (in_array($s['current_status'], ['selesai', 'diarsipkan'])) {
                                    $badgeColor = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
                                } elseif (in_array($s['current_status'], ['sedang_berjalan', 'logbook_berjalan'])) {
                                    $badgeColor = 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-100/50 dark:border-blue-900/50';
                                } elseif (strpos($s['current_status'], 'menunggu') !== false) {
                                    $badgeColor = 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border-amber-100/50 dark:border-amber-900/50';
                                }
                                ?>
                                <span class="inline-flex items-center gap-1 font-medium px-2 py-0.5 rounded text-[10px] border <?= $badgeColor ?>">
                                    <?= esc($statusLabel) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200">
                                <?= $s['lecturer_score'] !== null ? number_format($s['lecturer_score'], 2) : '-' ?>
                            </td>
                            <td class="py-3 px-4 font-bold text-blue-600 dark:text-blue-400">
                                <?= $s['final_score'] !== null ? number_format($s['final_score'], 2) : '-' ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <?php if ($s['final_grade']): ?>
                                    <span class="inline-flex items-center justify-center font-black h-6 w-8 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs">
                                        <?= esc($s['final_grade']) ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <?php if (in_array($s['current_status'], ['selesai', 'diarsipkan'])): ?>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold italic">Terkunci</span>
                                <?php else: ?>
                                    <a id="action_grade_<?= esc($s['registration_id']) ?>" href="<?= base_url('dosen/penilaian/input/' . $s['registration_id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-semibold shadow shadow-blue-500/10 transition">
                                        <i class="ti ti-edit"></i> Input Nilai
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#gradesTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            responsive: true,
            dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4 gap-4"fr>t<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4"ip>'
        });
    });
</script>
<?= $this->endSection() ?>
