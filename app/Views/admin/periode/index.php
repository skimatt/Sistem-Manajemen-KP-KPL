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
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Periode KP/KPL</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola periode pelaksanaan Kerja Praktek (KP) dan Kerja Praktek Lapangan (KPL).</p>
        </div>
        <div>
            <a href="<?= base_url('admin/periode/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                <i class="ti ti-plus"></i> Tambah Periode
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-xl text-xs text-emerald-700 dark:text-emerald-450 flex items-center gap-2">
            <i class="ti ti-circle-check text-base"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl text-xs text-red-700 dark:text-red-405 flex items-center gap-2">
            <i class="ti ti-alert-circle text-base"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Table Card Wrapper -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table id="periodeTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider font-semibold">
                        <th class="pb-3">Nama Periode</th>
                        <th class="pb-3">Program Studi</th>
                        <th class="pb-3">Tahun Akademik</th>
                        <th class="pb-3">Semester</th>
                        <th class="pb-3">Tipe</th>
                        <th class="pb-3">Pendaftaran</th>
                        <th class="pb-3">Kegiatan</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($periods as $period): ?>
                        <tr>
                            <td class="py-3.5 font-semibold text-slate-800 dark:text-slate-250"><?= esc($period['name']) ?></td>
                            <td class="py-3.5 text-slate-700 dark:text-slate-300"><?= esc($period['prodi_name'] ?? '-') ?></td>
                            <td class="py-3.5 font-mono text-slate-500 dark:text-slate-400"><?= esc($period['academic_year']) ?></td>
                            <td class="py-3.5 text-slate-600 dark:text-slate-350"><?= esc($period['semester']) ?></td>
                            <td class="py-3.5">
                                <?php if ($period['activity_type'] === 'KP'): ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/40">
                                        KP
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 border border-purple-100 dark:border-purple-900/40">
                                        KPL
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-400">
                                <div class="font-mono text-[10px]">
                                    <?= date('d M Y', strtotime($period['registration_start'])) ?> s.d.<br/>
                                    <?= date('d M Y', strtotime($period['registration_end'])) ?>
                                </div>
                            </td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-400">
                                <div class="font-mono text-[10px]">
                                    <?= date('d M Y', strtotime($period['activity_start'])) ?> s.d.<br/>
                                    <?= date('d M Y', strtotime($period['activity_end'])) ?>
                                </div>
                            </td>
                            <td class="py-3.5">
                                <?php if ($period['status'] === 'aktif'): ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/40">
                                        Aktif
                                    </span>
                                <?php elseif ($period['status'] === 'draft'): ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Draft
                                    </span>
                                <?php elseif ($period['status'] === 'ditutup'): ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/40">
                                        Ditutup
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-705 dark:text-blue-400 border border-blue-100 dark:border-blue-900/40">
                                        Diarsipkan
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= base_url('admin/periode/edit/' . $period['id']) ?>" class="p-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-md transition" title="Edit">
                                        <i class="ti ti-edit text-base"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $period['id'] ?>, '<?= esc($period['name']) ?>')" class="p-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-red-650 dark:hover:text-red-400 rounded-md transition" title="Hapus">
                                        <i class="ti ti-trash text-base"></i>
                                    </button>
                                </div>
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
        $('#periodeTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                search: "",
                searchPlaceholder: "Cari periode..."
            },
            pageLength: 10,
            ordering: true,
            info: true,
            lengthChange: true,
            columnDefs: [
                { orderable: false, targets: [8] } // disable order for actions
            ]
        });
    });

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus periode akademik?',
            text: `Apakah Anda yakin ingin menghapus (soft delete) "${name}"? Data pendaftaran yang terikat tidak akan hilang jika sudah terdaftar, namun periode ini tidak akan muncul kembali di daftar aktif.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('admin/periode/delete/') ?>${id}`;
            }
        });
    }
</script>
<?= $this->endSection() ?>
