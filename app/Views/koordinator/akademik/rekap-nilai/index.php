<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Rekapitulasi Nilai Akhir</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Laporan rekapitulasi nilai akhir mahasiswa KP/KPL untuk periode yang dipilih.</p>
        </div>
        <!-- Period Filter -->
        <form method="GET" action="<?= base_url('koordinator/rekap-nilai') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-slate-500 dark:text-slate-400">Periode:</span>
            <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none">
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodId ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= strtoupper($p['activity_type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Quick Stats Cards -->
    <?php
    $totalStudents = count($scores);
    $avgScore = 0;
    $maxScore = 0;
    $minScore = 100;
    
    if ($totalStudents > 0) {
        $scoreValues = array_column($scores, 'final_score');
        $avgScore = array_sum($scoreValues) / $totalStudents;
        $maxScore = max($scoreValues);
        $minScore = min($scoreValues);
    } else {
        $minScore = 0;
    }
    ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat 1 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">
                <i class="ti ti-users text-xl"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Total Mahasiswa</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= $totalStudents ?> Orang</span>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                <i class="ti ti-chart-bar text-xl"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Rata-rata Nilai</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= number_format($avgScore, 2) ?></span>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                <i class="ti ti-trending-up text-xl"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Nilai Tertinggi</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= number_format($maxScore, 2) ?></span>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400">
                <i class="ti ti-trending-down text-xl"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 dark:text-slate-500 block">Nilai Terendah</span>
                <span class="text-base font-black text-slate-800 dark:text-slate-100"><?= number_format($minScore, 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Main List Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <!-- Data Grid -->
            <div class="table-responsive">
                <table id="rekapNilaiTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-4 py-3.5 font-bold text-center w-12">Peringkat</th>
                            <th class="px-4 py-3.5 font-bold">Mahasiswa</th>
                            <th class="px-4 py-3.5 font-bold">NPM / Prodi</th>
                            <th class="px-4 py-3.5 font-bold text-center">Instansi (40%)</th>
                            <th class="px-4 py-3.5 font-bold text-center">Dosen (50%)</th>
                            <th class="px-4 py-3.5 font-bold text-center">Logbook/Adm (10%)</th>
                            <th class="px-4 py-3.5 font-bold text-center">Total Nilai</th>
                            <th class="px-4 py-3.5 font-bold text-center">Grade</th>
                            <th class="px-4 py-3.5 font-bold">Status</th>
                            <th class="px-4 py-3.5 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($scores)): ?>
                            <?php $rank = 1; foreach ($scores as $sc): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-4 py-4 text-center font-bold text-slate-400">
                                        #<?= $rank++ ?>
                                    </td>
                                    <td class="px-4 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($sc['full_name']) ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-500 dark:text-slate-350"><?= esc($sc['npm']) ?></div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($sc['prodi_name']) ?></div>
                                    </td>
                                    <td class="px-4 py-4 text-center font-semibold text-slate-700 dark:text-slate-300">
                                        <?= number_format($sc['institution_score'], 2) ?>
                                    </td>
                                    <td class="px-4 py-4 text-center font-semibold text-slate-700 dark:text-slate-300">
                                        <?= number_format($sc['lecturer_score'], 2) ?>
                                    </td>
                                    <td class="px-4 py-4 text-center font-semibold text-slate-700 dark:text-slate-300">
                                        <?= number_format($sc['admin_score'], 2) ?>
                                    </td>
                                    <td class="px-4 py-4 text-center font-bold text-blue-600 dark:text-blue-400 text-sm">
                                        <?= number_format($sc['final_score'], 2) ?>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 font-black">
                                            <?= esc($sc['final_grade']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <?php
                                        $valClass = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400';
                                        $valLabel = esc($sc['status']);
                                        if ($sc['status'] === 'draft') {
                                            $valClass = 'bg-amber-100/60 text-amber-800 dark:bg-amber-950/20 dark:text-amber-400';
                                            $valLabel = 'Draft Dosen';
                                        } elseif ($sc['status'] === 'menunggu_validasi') {
                                            $valClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                            $valLabel = 'Menunggu Validasi';
                                        } elseif ($sc['status'] === 'divalidasi') {
                                            $valClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                                            $valLabel = 'Disahkan (Sah)';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold <?= $valClass ?>">
                                            <?= $valLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <a href="<?= base_url('koordinator/validasi-penilaian/review/' . $sc['registration_id']) ?>" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded font-semibold transition">
                                            <i class="ti ti-eye text-xs"></i>
                                            Detail
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
<!-- DataTables & Buttons CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.min.css" />

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function() {
        $('#rekapNilaiTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-semibold"><i class="ti ti-file-spreadsheet"></i> Export Excel</span>',
                    className: 'border-none p-0 bg-transparent',
                    title: 'Rekapitulasi Nilai Mahasiswa KP-KPL',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-xs font-semibold"><i class="ti ti-file-type-pdf"></i> Export PDF</span>',
                    className: 'border-none p-0 bg-transparent',
                    title: 'Rekapitulasi Nilai Mahasiswa KP-KPL',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    extend: 'print',
                    text: '<span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-700 hover:bg-slate-800 text-white rounded text-xs font-semibold"><i class="ti ti-printer"></i> Cetak</span>',
                    className: 'border-none p-0 bg-transparent',
                    title: 'Rekapitulasi Nilai Mahasiswa KP-KPL',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }
            ],
            columnDefs: [
                { orderable: false, targets: 9 }
            ]
        });
    });
</script>
<style>
    /* Styling adjustments for Datatable buttons */
    .dt-buttons {
        margin-bottom: 1.5rem !important;
        display: flex;
        gap: 0.5rem;
    }
</style>
<?= $this->endSection() ?>
