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
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Form Builder (Formulir Dinamis)</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Buat, susun, dan kelola field form pendaftaran atau penilaian yang disesuaikan per periode.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <form action="<?= base_url('admin/form-builder/generate-kp-kpl-registration') ?>" method="POST" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                    <i class="ti ti-forms text-sm"></i> Generate Form KP/KPL
                </button>
            </form>
            <a href="<?= base_url('admin/form-builder/create') ?>" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition">
                <i class="ti ti-plus text-sm"></i> Tambah Formulir
            </a>
        </div>
    </div>

    <!-- Table Card Wrapper -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table id="formsTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider font-semibold">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Nama Formulir</th>
                        <th class="pb-3">Tipe Form</th>
                        <th class="pb-3">Versi</th>
                        <th class="pb-3">Terikat Periode</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($templates as $tmpl): ?>
                        <tr>
                            <td class="py-3.5 font-mono text-slate-500"><?= esc($tmpl['id']) ?></td>
                            <td class="py-3.5 font-semibold text-slate-850 dark:text-slate-200"><?= esc($tmpl['name']) ?></td>
                            <td class="py-3.5 capitalize"><?= esc($tmpl['form_type']) ?></td>
                            <td class="py-3.5 font-mono">v<?= esc($tmpl['version']) ?></td>
                            <td class="py-3.5 text-slate-700 dark:text-slate-300 font-medium"><?= esc($tmpl['period_name'] ?? 'Semua / Global') ?></td>
                            <td class="py-3.5">
                                <?php
                                $statusColors = [
                                    'draft' => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-800/40 dark:text-slate-400 dark:border-slate-800',
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
                                    'inactive' => 'bg-slate-100 text-slate-655 border-slate-250 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                                    'archived' => 'bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-900/40',
                                ];
                                $color = $statusColors[$tmpl['status']] ?? 'bg-slate-50 text-slate-650';
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border capitalize <?= $color ?>">
                                    <?= esc($tmpl['status']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 text-right space-x-1">
                                <a href="<?= base_url('admin/form-builder/fields/' . $tmpl['id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 dark:bg-blue-950/30 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-blue-700 dark:text-blue-400 rounded-md transition text-[10px] font-bold" title="Kelola Field">
                                    <i class="ti ti-list-details text-xs"></i> Kelola Field
                                </a>
                                <a href="<?= base_url('admin/form-builder/edit/' . $tmpl['id']) ?>" class="inline-flex items-center gap-1 px-2 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-md transition text-[10px] font-bold" title="Edit">
                                    <i class="ti ti-edit text-xs"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $tmpl['id'] ?>, '<?= esc($tmpl['name']) ?>')" class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-700 dark:text-red-400 rounded-md transition text-[10px] font-bold" title="Hapus">
                                    <i class="ti ti-trash text-xs"></i>
                                </button>
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
        $('#formsTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                search: "",
                searchPlaceholder: "Cari formulir..."
            },
            pageLength: 10,
            ordering: true,
            info: true,
            lengthChange: true,
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });
    });

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menghapus template formulir: " + name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'dark:bg-slate-900 dark:border-slate-800 text-slate-800 dark:text-slate-100',
                title: 'text-slate-800 dark:text-slate-100',
                htmlContainer: 'text-slate-600 dark:text-slate-300'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('admin/form-builder/delete') ?>/" + id;
            }
        });
    }
</script>
<?= $this->endSection() ?>
