<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS from CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<style>
    /* Styling adjustments for custom Tailwind SaaS look */
    .dt-search input {
        background-color: transparent !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        padding: 0.35rem 0.75rem !important;
        font-size: 0.75rem !important;
        color: inherit !important;
        outline: none !important;
    }
    .dark .dt-search input {
        border-color: #334155 !important;
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
    }
    .dt-length select {
        background-color: transparent !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        padding: 0.25rem 1.5rem 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
        color: inherit !important;
        outline: none !important;
    }
    .dark .dt-length select {
        border-color: #334155 !important;
        background-color: #0f172a !important;
        color: #f1f5f9 !important;
    }
    .dt-paging .dt-paging-button {
        border-radius: 0.375rem !important;
        font-size: 0.75rem !important;
        padding: 0.25rem 0.6rem !important;
        border: 1px solid transparent !important;
        color: #475569 !important;
    }
    .dark .dt-paging .dt-paging-button {
        color: #94a3b8 !important;
    }
    .dt-paging .dt-paging-button.current {
        background: #2563eb !important;
        color: white !important;
        border-color: #2563eb !important;
    }
    .dark .dt-paging .dt-paging-button.current {
        background: #3b82f6 !important;
        color: #0f172a !important;
        border-color: #3b82f6 !important;
    }
    .dt-paging .dt-paging-button:hover:not(.current) {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }
    .dark .dt-paging .dt-paging-button:hover:not(.current) {
        background: #1e293b !important;
        color: #f1f5f9 !important;
    }
    .dt-info {
        font-size: 0.75rem !important;
        color: #64748b !important;
    }
    .dark .dt-info {
        color: #94a3b8 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Laporan Akhir Mahasiswa</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar unggahan berkas laporan akhir Kerja Praktek (KP) / Kerja Praktek Lapangan (KPL) oleh mahasiswa.</p>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-xl text-xs text-emerald-700 dark:text-emerald-450 flex items-center gap-2">
            <i class="ti ti-circle-check text-base"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl text-xs text-red-700 dark:text-red-400 flex items-center gap-2">
            <i class="ti ti-alert-circle text-base"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Table Card Wrapper -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table id="laporanTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider font-semibold">
                        <th class="pb-3">NPM</th>
                        <th class="pb-3">Nama Mahasiswa</th>
                        <th class="pb-3">Program Studi / Periode</th>
                        <th class="pb-3">Judul Laporan</th>
                        <th class="pb-3">Versi / Ukuran</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Peninjau</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td class="py-3.5 font-mono font-medium text-slate-700 dark:text-slate-300"><?= esc($report['npm']) ?></td>
                            <td class="py-3.5 font-semibold text-slate-800 dark:text-slate-250"><?= esc($report['full_name']) ?></td>
                            <td class="py-3.5">
                                <span class="font-medium text-slate-700 dark:text-slate-300 block"><?= esc($report['prodi_name'] ?? '-') ?></span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500"><?= esc($report['period_name']) ?></span>
                            </td>
                            <td class="py-3.5 font-medium text-slate-800 dark:text-slate-200 max-w-xs truncate" title="<?= esc($report['title']) ?>">
                                <?= esc($report['title']) ?>
                            </td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-450 font-mono">
                                <div>v<?= esc($report['version']) ?></div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500"><?= esc($report['file_size_kb']) ?> KB</div>
                            </td>
                            <td class="py-3.5">
                                <?php
                                $statusColors = [
                                    'draft' => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-800/40 dark:text-slate-400 dark:border-slate-800',
                                    'dikirim' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/40',
                                    'perlu_revisi' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-450 dark:border-amber-900/30',
                                    'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
                                    'terkunci' => 'bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-900/40',
                                ];
                                $color = $statusColors[$report['status']] ?? 'bg-slate-50 text-slate-650';
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border capitalize <?= $color ?>">
                                    <?= esc($report['status']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 text-slate-700 dark:text-slate-300"><?= esc($report['reviewer_name'] ?: '-') ?></td>
                            <td class="py-3.5 text-right">
                                <a href="<?= base_url('admin/laporan/download/' . $report['id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-950/30 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-blue-700 dark:text-blue-400 rounded-md transition text-[10px] font-bold" title="Unduh Berkas Laporan">
                                    <i class="ti ti-download text-sm"></i> Unduh
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- jQuery and DataTables JS from CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#laporanTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                search: "",
                searchPlaceholder: "Cari laporan..."
            },
            pageLength: 10,
            ordering: true,
            info: true,
            lengthChange: true,
            columnDefs: [
                { orderable: false, targets: [7] }
            ]
        });
    });
</script>
<?= $this->endSection() ?>
