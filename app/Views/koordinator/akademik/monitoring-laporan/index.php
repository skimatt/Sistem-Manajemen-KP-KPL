<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Monitoring Laporan Akhir</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Lacak status unggahan dokumen laporan akhir mahasiswa yang dikirim untuk bimbingan.</p>
        </div>
        <!-- Period Filter -->
        <form method="GET" action="<?= base_url('koordinator/monitoring-laporan') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-slate-500 dark:text-slate-400">Periode:</span>
            <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none">
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodId ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= strtoupper($p['activity_type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="monitoringLaporanTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Mahasiswa</th>
                            <th class="px-6 py-3.5 font-bold">NPM / Prodi</th>
                            <th class="px-6 py-3.5 font-bold">Judul Laporan</th>
                            <th class="px-6 py-3.5 font-bold">Dosen Pembimbing</th>
                            <th class="px-6 py-3.5 font-bold">Status Laporan</th>
                            <th class="px-6 py-3.5 font-bold text-center">Unduh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($reports)): ?>
                            <?php foreach ($reports as $rep): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($rep['full_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-500 dark:text-slate-350"><?= esc($rep['npm']) ?></div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($rep['prodi_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="font-bold text-slate-700 dark:text-slate-300 leading-normal"><?= esc($rep['title']) ?></div>
                                        <div class="text-[9px] text-slate-450 dark:text-slate-500 mt-1">Versi: v<?= esc($rep['version']) ?> | Ukuran: <?= number_format($rep['file_size_kb'], 0) ?> KB</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                        <?= esc($rep['dosen_name']) ?: '<span class="text-slate-450 italic">Belum Ditetapkan</span>' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $statusClass = 'bg-slate-100 text-slate-850 dark:bg-slate-800 dark:text-slate-400';
                                        $statusLabel = esc($rep['status']);
                                        if ($rep['status'] === 'dikirim') {
                                            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                            $statusLabel = 'Menunggu Review';
                                        } elseif ($rep['status'] === 'disetujui') {
                                            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                                            $statusLabel = 'Disetujui Dosen';
                                        } elseif ($rep['status'] === 'perlu_revisi') {
                                            $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                                            $statusLabel = 'Perlu Revisi';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <a href="<?= base_url('koordinator/monitoring-laporan/download/' . $rep['id']) ?>" 
                                           class="inline-flex h-7 px-3.5 items-center justify-center rounded bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold transition"
                                           target="_blank">
                                            <i class="ti ti-download text-xs mr-1"></i> Unduh
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
        $('#monitoringLaporanTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            order: [[0, 'asc']]
        });
    });
</script>
<?= $this->endSection() ?>
