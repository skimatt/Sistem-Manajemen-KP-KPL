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
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Logbook Mahasiswa</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pantau dan tinjau kemajuan logbook mingguan dan harian mahasiswa yang sedang melaksanakan KP/KPL.</p>
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
            <table id="logbookTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider font-semibold">
                        <th class="pb-3">NPM</th>
                        <th class="pb-3">Nama Mahasiswa</th>
                        <th class="pb-3">Program Studi</th>
                        <th class="pb-3">Periode</th>
                        <th class="pb-3">Instansi</th>
                        <th class="pb-3">Dosen Pembimbing</th>
                        <th class="pb-3">Progress Logbook</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="py-3.5 font-mono font-medium text-slate-700 dark:text-slate-300"><?= esc($student['npm']) ?></td>
                            <td class="py-3.5 font-semibold text-slate-800 dark:text-slate-250"><?= esc($student['full_name']) ?></td>
                            <td class="py-3.5 text-slate-700 dark:text-slate-300"><?= esc($student['prodi_name'] ?? '-') ?></td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-450"><?= esc($student['period_name']) ?></td>
                            <td class="py-3.5 text-slate-700 dark:text-slate-300 font-medium"><?= esc($student['institution_name'] ?? 'Mandiri / Belum Diplot') ?></td>
                            <td class="py-3.5 text-slate-700 dark:text-slate-300"><?= esc($student['supervisor_name'] ?? 'Belum Ditentukan') ?></td>
                            <td class="py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden border border-slate-200 dark:border-slate-700">
                                        <?php 
                                        $percent = 0;
                                        if ($student['total_weeks'] > 0) {
                                            $percent = round(($student['approved_weeks'] / $student['total_weeks']) * 100);
                                        }
                                        ?>
                                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?= $percent ?>%"></div>
                                    </div>
                                    <span class="font-mono text-[10px] font-bold text-slate-700 dark:text-slate-300">
                                        <?= esc($student['approved_weeks']) ?>/<?= esc($student['total_weeks']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="<?= base_url('admin/logbook/view/' . $student['registration_id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-md transition text-[10px] font-bold" title="Detail Logbook">
                                    <i class="ti ti-eye text-sm"></i> Detail
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
        $('#logbookTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                search: "",
                searchPlaceholder: "Cari mahasiswa..."
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
