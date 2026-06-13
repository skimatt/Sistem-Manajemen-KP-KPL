<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Monitoring Logbook Mahasiswa</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Pantau keaktifan harian dan kemajuan pengisian logbook mingguan bimbingan mahasiswa.</p>
        </div>
        <!-- Period Filter -->
        <form method="GET" action="<?= base_url('koordinator/monitoring-logbook') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-slate-500 dark:text-slate-400">Periode:</span>
            <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none">
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodId ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= strtoupper($p['activity_type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="monitoringLogbookTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Mahasiswa</th>
                            <th class="px-6 py-3.5 font-bold">NPM / Prodi</th>
                            <th class="px-6 py-3.5 font-bold">Dosen Pembimbing</th>
                            <th class="px-6 py-3.5 font-bold text-center">Minggu Disetujui</th>
                            <th class="px-6 py-3.5 font-bold text-center">Progress Logbook</th>
                            <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $stu): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($stu['full_name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-500 dark:text-slate-350"><?= esc($stu['npm']) ?></div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($stu['prodi_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                        <?= esc($stu['dosen_name']) ?: '<span class="text-slate-400 font-medium italic">Belum Ditetapkan</span>' ?>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($stu['logbook_approved']) ?> / <?= esc($stu['logbook_total']) ?> Minggu
                                    </td>
                                    <td class="px-6 py-4">
                                        <!-- Progress Bar -->
                                        <?php 
                                        $percent = $stu['logbook_total'] > 0 ? (intval($stu['logbook_approved']) / intval($stu['logbook_total'])) * 100 : 0;
                                        ?>
                                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 max-w-[120px] mx-auto overflow-hidden">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?= $percent ?>%"></div>
                                        </div>
                                        <div class="text-[9px] font-semibold text-slate-450 dark:text-slate-500 text-center mt-1"><?= number_format($percent, 0) ?>% Selesai</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= base_url('koordinator/monitoring-logbook/view/' . $stu['registration_id']) ?>" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-950/75 rounded-lg font-semibold transition">
                                            <i class="ti ti-file-text text-sm"></i>
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#monitoringLogbookTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });
    });
</script>
<?= $this->endSection() ?>
