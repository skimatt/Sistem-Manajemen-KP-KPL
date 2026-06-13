<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Validasi Tempat Mandiri</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Review dan validasi usulan tempat KP/KPL Mandiri yang diajukan oleh mahasiswa di luar daftar mitra resmi kampus.</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="mandiriTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Mahasiswa</th>
                            <th class="px-6 py-3.5 font-bold">NPM</th>
                            <th class="px-6 py-3.5 font-bold">Usulan Instansi Mandiri</th>
                            <th class="px-6 py-3.5 font-bold">Bidang Pekerjaan</th>
                            <th class="px-6 py-3.5 font-bold">Tanggal Kirim</th>
                            <th class="px-6 py-3.5 font-bold">Status</th>
                            <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($placements)): ?>
                            <?php foreach ($placements as $place): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($place['full_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-500 dark:text-slate-350">
                                        <?= esc($place['npm']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-250"><?= esc($place['proposed_institution_name']) ?></div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 truncate max-w-xs"><?= esc($place['proposed_address']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-600 dark:text-slate-400">
                                        <?= esc($place['proposed_field']) ?: 'Lainnya' ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-450 font-medium">
                                        <?= $place['submitted_at'] ? date('d M Y, H:i', strtotime($place['submitted_at'])) : '-' ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClass = '';
                                        $statusLabel = '';
                                        switch ($place['status']) {
                                            case 'diajukan':
                                                $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                                $statusLabel = 'Menunggu Validasi';
                                                break;
                                            case 'disetujui':
                                                $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                                                $statusLabel = 'Disetujui';
                                                break;
                                            case 'perlu_revisi':
                                                $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                                                $statusLabel = 'Perlu Revisi';
                                                break;
                                            case 'ditolak':
                                                $statusClass = 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400';
                                                $statusLabel = 'Ditolak';
                                                break;
                                            default:
                                                $statusClass = 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-450';
                                                $statusLabel = esc($place['status']);
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= base_url('koordinator/validasi-mandiri/review/' . $place['id']) ?>" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-950/75 rounded-lg font-semibold transition">
                                            <i class="ti ti-file-search text-sm"></i>
                                            <?= $place['status'] === 'diajukan' ? 'Review & Validasi' : 'Detail' ?>
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
        $('#mandiriTable').DataTable({
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
