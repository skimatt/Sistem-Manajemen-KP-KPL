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
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Data Instansi</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola data mitra kampus resmi dan pengajuan instansi mandiri mahasiswa.</p>
        </div>
        <div>
            <a href="<?= base_url('admin/instansi/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                <i class="ti ti-plus"></i> Tambah Instansi
            </a>
        </div>
    </div>

    <!-- Table Card Wrapper -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table id="institutionTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="pb-3 font-semibold">Nama Instansi</th>
                        <th class="pb-3 font-semibold">Tipe</th>
                        <th class="pb-3 font-semibold">Bidang Usaha</th>
                        <th class="pb-3 font-semibold">Kota / Kabupaten</th>
                        <th class="pb-3 font-semibold">Kontak Person</th>
                        <th class="pb-3 font-semibold">Status Kemitraan</th>
                        <th class="pb-3 font-semibold">Akun Login</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($institutions as $inst): ?>
                        <tr>
                            <td class="py-3.5 font-semibold text-slate-800 dark:text-slate-250"><?= esc($inst['name']) ?></td>
                            <td class="py-3.5">
                                <?php if ($inst['type'] === 'mitra'): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/40">
                                        Mitra
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-400 border border-slate-200 dark:border-slate-800/40">
                                        Mandiri
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-400"><?= esc($inst['field_category'] ?: '-') ?></td>
                            <td class="py-3.5 text-slate-700 dark:text-slate-300 font-medium"><?= esc($inst['city'] ?: '-') ?></td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-400">
                                <?php if ($inst['contact_person']): ?>
                                    <p class="font-semibold text-slate-700 dark:text-slate-350"><?= esc($inst['contact_person']) ?></p>
                                    <p class="text-[10px] text-slate-400"><?= esc($inst['contact_phone'] ?: '-') ?></p>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5">
                                <?php if ($inst['partnership_status'] === 'active'): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/40">
                                        Aktif
                                    </span>
                                <?php elseif ($inst['partnership_status'] === 'pending'): ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/40">
                                        Pending
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-900/40">
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5">
                                <?php if ($inst['has_account'] == 1 && $inst['email']): ?>
                                    <span class="text-slate-700 dark:text-slate-300 font-medium"><?= esc($inst['email']) ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400 dark:text-slate-550 italic">Tidak Ada Akun</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= base_url('admin/instansi/edit/' . $inst['id']) ?>" class="p-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-md transition" title="Edit">
                                        <i class="ti ti-edit text-base"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $inst['id'] ?>, '<?= esc($inst['name']) ?>')" class="p-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-red-650 dark:hover:text-red-400 rounded-md transition" title="Hapus">
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
        $('#institutionTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                search: "",
                searchPlaceholder: "Cari instansi..."
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

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus data instansi?',
            text: `Anda yakin ingin menghapus profil instansi "${name}" beserta akun loginnya?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `<?= base_url('admin/instansi/delete/') ?>${id}`;
            }
        });
    }
</script>
<?= $this->endSection() ?>
