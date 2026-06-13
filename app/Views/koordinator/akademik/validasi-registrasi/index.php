<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Validasi Registrasi Mahasiswa</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Gunakan halaman ini untuk melakukan verifikasi persyaratan dan kelayakan akademik mahasiswa sebelum masuk tahap penempatan.</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="validasiRegTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Mahasiswa</th>
                            <th class="px-6 py-3.5 font-bold">NPM / Prodi</th>
                            <th class="px-6 py-3.5 font-bold">Periode</th>
                            <th class="px-6 py-3.5 font-bold text-center">Akademik (SKS/IPK)</th>
                            <th class="px-6 py-3.5 font-bold">Tanggal Kirim</th>
                            <th class="px-6 py-3.5 font-bold">Status</th>
                            <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($registrations)): ?>
                            <?php foreach ($registrations as $reg): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <!-- Mahasiswa -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-200"><?= esc($reg['full_name']) ?></div>
                                    </td>
                                    <!-- NPM / Prodi -->
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-500 dark:text-slate-350"><?= esc($reg['npm']) ?></div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($reg['prodi_name']) ?></div>
                                    </td>
                                    <!-- Periode -->
                                    <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-300">
                                        <?= esc($reg['period_name']) ?>
                                    </td>
                                    <!-- Akademik (SKS/IPK) -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="font-bold text-slate-800 dark:text-slate-200"><?= esc($reg['academic_sks']) ?> SKS</div>
                                        <div class="text-[10px] font-semibold <?= esc($reg['academic_gpa']) >= 2.5 ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-600 dark:text-rose-500' ?> mt-0.5">IPK: <?= esc($reg['academic_gpa']) ?></div>
                                    </td>
                                    <!-- Tanggal Kirim -->
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-450 font-medium">
                                        <?= $reg['submitted_at'] ? date('d M Y, H:i', strtotime($reg['submitted_at'])) : '-' ?>
                                    </td>
                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClass = '';
                                        $statusLabel = '';
                                        switch ($reg['current_status']) {
                                            case 'menunggu_verifikasi':
                                                $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                                $statusLabel = 'Menunggu Verifikasi';
                                                break;
                                            case 'registrasi_disetujui':
                                                $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                                                $statusLabel = 'Registrasi Disetujui';
                                                break;
                                            case 'revisi_registrasi':
                                                $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                                                $statusLabel = 'Perlu Revisi';
                                                break;
                                            case 'registrasi_ditolak':
                                                $statusClass = 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400';
                                                $statusLabel = 'Registrasi Ditolak';
                                                break;
                                            default:
                                                $statusClass = 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400';
                                                $statusLabel = esc($reg['current_status']);
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <!-- Aksi -->
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= base_url('koordinator/validasi-registrasi/review/' . $reg['id']) ?>" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-950/75 rounded-lg font-semibold transition">
                                            <i class="ti ti-file-search text-sm"></i>
                                            <?= $reg['current_status'] === 'menunggu_verifikasi' ? 'Review & Validasi' : 'Detail' ?>
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
        $('#validasiRegTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
            order: [[5, 'desc']] // prioritize showing pending verification status
        });
    });
</script>
<?= $this->endSection() ?>
