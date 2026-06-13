<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Daftar Mahasiswa Bimbingan</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar seluruh mahasiswa aktif yang ditugaskan kepada Anda untuk bimbingan KP/KPL.</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table id="studentsTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-3 px-4 font-semibold">Nama Mahasiswa</th>
                        <th class="py-3 px-4 font-semibold">NPM</th>
                        <th class="py-3 px-4 font-semibold">Periode</th>
                        <th class="py-3 px-4 font-semibold">No. HP</th>
                        <th class="py-3 px-4 font-semibold">Status Workflow</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php foreach ($students as $student): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors duration-150">
                            <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                <?= esc($student['full_name']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400 font-mono">
                                <?= esc($student['npm']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= esc($student['period_name']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= esc($student['phone']) ?: '-' ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php 
                                $statusLabel = str_replace('_', ' ', $student['current_status']);
                                $statusLabel = ucwords($statusLabel);
                                $badgeColor = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-150/50 dark:border-slate-800/50';
                                
                                if (in_array($student['current_status'], ['selesai', 'diarsipkan'])) {
                                    $badgeColor = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
                                } elseif (in_array($student['current_status'], ['sedang_berjalan', 'logbook_berjalan'])) {
                                    $badgeColor = 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-100/50 dark:border-blue-900/50';
                                } elseif (strpos($student['current_status'], 'menunggu') !== false) {
                                    $badgeColor = 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border-amber-100/50 dark:border-amber-900/50';
                                }
                                ?>
                                <span class="inline-flex items-center gap-1 font-medium px-2 py-0.5 rounded text-[10px] border <?= $badgeColor ?>">
                                    <?= esc($statusLabel) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a id="action_detail_<?= esc($student['registration_id']) ?>" href="<?= base_url('dosen/mahasiswa/detail/' . $student['registration_id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-[10px] font-semibold transition">
                                    <i class="ti ti-eye"></i> Detail
                                </a>
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
        $('#studentsTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            responsive: true,
            dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4 gap-4"fr>t<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4"ip>'
        });
    });
</script>
<?= $this->endSection() ?>
