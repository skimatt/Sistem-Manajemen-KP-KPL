<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Validasi Penilaian Akhir</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Review dan sahkan nilai akhir gabungan (Dosen Pembimbing, Pembimbing Lapangan/Instansi, Administrasi) untuk mahasiswa.</p>
        </div>
        <!-- Period Filter -->
        <form method="GET" action="<?= base_url('koordinator/validasi-penilaian') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-slate-500 dark:text-slate-400">Periode:</span>
            <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none">
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodId ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= strtoupper($p['activity_type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Alert success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-xs border border-emerald-100 dark:border-emerald-950/30 font-semibold mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Main List Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="validasiPenilaianTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Mahasiswa</th>
                            <th class="px-6 py-3.5 font-bold">NPM / Prodi</th>
                            <th class="px-6 py-3.5 font-bold text-center">Nilai Angka</th>
                            <th class="px-6 py-3.5 font-bold text-center">Grade Akhir</th>
                            <th class="px-6 py-3.5 font-bold">Status Alur</th>
                            <th class="px-6 py-3.5 font-bold">Status Nilai</th>
                            <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($scores)): ?>
                            <?php foreach ($scores as $sc): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($sc['full_name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-500 dark:text-slate-350"><?= esc($sc['npm']) ?></div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($sc['prodi_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-900 dark:text-white">
                                        <?= $sc['final_score'] !== null ? number_format($sc['final_score'], 2) : '-' ?>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold">
                                        <span class="px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[11px]">
                                            <?= esc($sc['final_grade']) ?: '-' ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClass = 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400';
                                        $statusLabel = esc($sc['current_status']);
                                        if ($sc['current_status'] === 'sedang_berjalan') {
                                            $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                                            $statusLabel = 'Kegiatan Berjalan';
                                        } elseif ($sc['current_status'] === 'selesai') {
                                            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                                            $statusLabel = 'Selesai';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $valClass = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400';
                                        $valLabel = 'Belum Dihitung';
                                        if ($sc['score_status'] === 'draft') {
                                            $valClass = 'bg-amber-150 text-amber-800 dark:bg-amber-950/20 dark:text-amber-400';
                                            $valLabel = 'Draft Dosen';
                                        } elseif ($sc['score_status'] === 'menunggu_validasi') {
                                            $valClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 font-bold';
                                            $valLabel = 'Menunggu Validasi';
                                        } elseif ($sc['score_status'] === 'divalidasi') {
                                            $valClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 font-bold';
                                            $valLabel = 'Sah (Divalidasi)';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold <?= $valClass ?>">
                                            <?= $valLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($sc['score_status'] !== null): ?>
                                            <a href="<?= base_url('koordinator/validasi-penilaian/review/' . $sc['registration_id']) ?>" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-950/75 rounded-lg font-semibold transition">
                                                <i class="ti ti-file-certificate text-sm"></i>
                                                <?= $sc['score_status'] === 'menunggu_validasi' ? 'Sahkan Nilai' : 'Detail' ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic font-medium">Bimbingan Berjalan</span>
                                        <?php endif; ?>
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
        $('#validasiPenilaianTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
            order: [[5, 'desc']]
        });
    });
</script>
<?= $this->endSection() ?>
