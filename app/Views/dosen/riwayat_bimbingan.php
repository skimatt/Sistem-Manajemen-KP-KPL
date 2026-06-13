<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Riwayat Bimbingan</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar riwayat bimbingan mahasiswa pada periode-periode akademik sebelumnya.</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table id="historyTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-505 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-3 px-4 font-semibold">Nama Mahasiswa</th>
                        <th class="py-3 px-4 font-semibold">NPM</th>
                        <th class="py-3 px-4 font-semibold">Periode</th>
                        <th class="py-3 px-4 font-semibold">Nilai Akhir</th>
                        <th class="py-3 px-4 font-semibold">Index Grade</th>
                        <th class="py-3 px-4 font-semibold">Status Workflow</th>
                        <th class="py-3 px-4 font-semibold">Tanggal Berakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php foreach ($riwayat as $row): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors duration-150">
                            <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                <?= esc($row['full_name']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400 font-mono">
                                <?= esc($row['npm']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= esc($row['period_name']) ?>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-700 dark:text-slate-300">
                                <?= $row['final_score'] !== null ? number_format($row['final_score'], 2) : '-' ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php if ($row['final_grade']): ?>
                                    <span class="inline-flex items-center justify-center font-black h-5 w-7 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px]">
                                        <?= esc($row['final_grade']) ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php 
                                $status = $row['current_status'];
                                $label = ucwords(str_replace('_', ' ', $status));
                                $color = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-150/50 dark:border-slate-800/50';
                                
                                if ($status === 'diarsipkan') {
                                    $color = 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-450 border-slate-200/50 dark:border-slate-700/50';
                                } elseif ($status === 'selesai') {
                                    $color = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
                                }
                                ?>
                                <span class="inline-flex items-center gap-1 font-bold px-2 py-0.5 rounded text-[10px] border <?= $color ?>">
                                    <?= esc($label) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= date('d M Y H:i', strtotime($row['updated_at'])) ?>
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
        $('#historyTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            responsive: true,
            dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4 gap-4"fr>t<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4"ip>'
        });
    });
</script>
<?= $this->endSection() ?>
