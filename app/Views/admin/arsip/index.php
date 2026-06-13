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
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Arsip Periode Akademik</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola arsip periode lama. Periode yang telah diarsipkan akan dikunci secara permanen menjadi status read-only demi keamanan data.</p>
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

    <!-- Guide / Alert Rules -->
    <div class="p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 rounded-xl text-xs text-amber-800 dark:text-amber-300">
        <div class="flex gap-2">
            <i class="ti ti-alert-triangle text-base text-amber-500 mt-0.5 flex-shrink-0"></i>
            <div>
                <h5 class="font-bold mb-1">Ketentuan Pengarsipan Periode</h5>
                <ul class="list-disc list-inside space-y-1 leading-relaxed">
                    <li>Semua mahasiswa terdaftar dalam periode harus sudah berada dalam tahapan final (Bernilai akhir atau Ditolak).</li>
                    <li>Setelah diarsipkan, seluruh berkas transaksional mahasiswa (nilai, logbook, dokumen) tidak dapat dimodifikasi/dihapus.</li>
                    <li>Sistem secara otomatis mengalihkan seluruh status pendaftaran mahasiswa dalam periode tersebut menjadi read-only.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Table Card Wrapper -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table id="periodsTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider font-semibold">
                        <th class="pb-3">ID</th>
                        <th class="pb-3">Nama Periode</th>
                        <th class="pb-3">Pendaftaran Mulai / Selesai</th>
                        <th class="pb-3">Tipe Kegiatan</th>
                        <th class="pb-3">Status Saat Ini</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php foreach ($periods as $period): ?>
                        <tr>
                            <td class="py-3.5 font-mono text-slate-500"><?= esc($period['id']) ?></td>
                            <td class="py-3.5 font-semibold text-slate-850 dark:text-slate-200"><?= esc($period['name']) ?></td>
                            <td class="py-3.5">
                                <span class="font-medium"><?= date('d M Y', strtotime($period['registration_start'])) ?></span>
                                <span class="text-slate-400 dark:text-slate-500 text-[10px] block">s.d <?= date('d M Y', strtotime($period['registration_end'])) ?></span>
                            </td>
                            <td class="py-3.5 font-mono uppercase text-blue-600 dark:text-blue-450"><?= esc($period['activity_type']) ?></td>
                            <td class="py-3.5">
                                <?php
                                $statusColors = [
                                    'draft' => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-800/40 dark:text-slate-400 dark:border-slate-800',
                                    'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
                                    'ditutup' => 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/40',
                                    'diarsipkan' => 'bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-900/40',
                                ];
                                $color = $statusColors[$period['status']] ?? 'bg-slate-50 text-slate-650';
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border capitalize <?= $color ?>">
                                    <?= esc($period['status']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <?php if ($period['status'] === 'diarsipkan'): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-400 dark:text-slate-550 italic">
                                        <i class="ti ti-lock text-xs"></i> Terkunci dalam Arsip
                                    </span>
                                <?php else: ?>
                                    <button onclick="confirmArchive(<?= $period['id'] ?>, '<?= esc($period['name']) ?>')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-[10px] font-bold shadow-sm">
                                        <i class="ti ti-archive text-xs"></i> Arsipkan Periode
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Invisible form for posting -->
<form id="archiveForm" method="POST" action="" class="hidden">
    <?= csrf_field() ?>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- jQuery and DataTables JS from CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#periodsTable').DataTable({
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
                { orderable: false, targets: [5] }
            ]
        });
    });

    function confirmArchive(id, name) {
        Swal.fire({
            title: 'Kunci & Arsipkan Periode?',
            text: "Periode '" + name + "' akan dikunci permanen. Seluruh data mahasiswa di dalamnya akan menjadi Read-Only. Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Arsipkan!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'dark:bg-slate-900 dark:border-slate-800 text-slate-800 dark:text-slate-100',
                title: 'text-slate-800 dark:text-slate-100',
                htmlContainer: 'text-slate-600 dark:text-slate-350'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('archiveForm');
                form.action = "<?= base_url('admin/arsip/archive-action') ?>/" + id;
                form.submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>
