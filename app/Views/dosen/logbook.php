<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Logbook Mingguan</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review laporan kegiatan mingguan mahasiswa bimbingan Anda. Berikan persetujuan atau catatan revisi.</p>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table id="logbookTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-3 px-4 font-semibold">Nama Mahasiswa</th>
                        <th class="py-3 px-4 font-semibold">NPM</th>
                        <th class="py-3 px-4 font-semibold">Minggu Ke</th>
                        <th class="py-3 px-4 font-semibold">Rentang Tanggal</th>
                        <th class="py-3 px-4 font-semibold">Tanggal Kirim</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php foreach ($weeks as $w): ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors duration-150">
                            <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                <?= esc($w['full_name']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400 font-mono">
                                <?= esc($w['npm']) ?>
                            </td>
                            <td class="py-3 px-4 font-bold text-center text-slate-700 dark:text-slate-300">
                                <?= esc($w['week_number']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= date('d M Y', strtotime($w['start_date'])) ?> s.d <?= date('d M Y', strtotime($w['end_date'])) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= $w['submitted_at'] ? date('d/m/Y H:i', strtotime($w['submitted_at'])) : '-' ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php 
                                $status = $w['status'];
                                $label = ucwords(str_replace('_', ' ', $status));
                                $color = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-150/50 dark:border-slate-800/50';
                                
                                if ($status === 'disetujui') {
                                    $color = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
                                } elseif ($status === 'perlu_revisi') {
                                    $color = 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border-amber-100/50 dark:border-amber-900/50';
                                } elseif ($status === 'dikirim') {
                                    $color = 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-100/50 dark:border-blue-900/50';
                                }
                                ?>
                                <span class="inline-flex items-center gap-1 font-bold px-2 py-0.5 rounded text-[10px] border <?= $color ?>">
                                    <?= esc($label) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <?php if ($status === 'dikirim'): ?>
                                    <a id="action_review_<?= esc($w['id']) ?>" href="<?= base_url('dosen/logbook/review/' . $w['id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-semibold shadow shadow-blue-500/10 transition">
                                        <i class="ti ti-edit"></i> Periksa
                                    </a>
                                <?php else: ?>
                                    <a id="action_view_<?= esc($w['id']) ?>" href="<?= base_url('dosen/logbook/review/' . $w['id']) ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-[10px] font-semibold transition">
                                        <i class="ti ti-eye"></i> Detail
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#logbookTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            responsive: true,
            order: [[5, 'asc'], [4, 'asc']], // Orders 'dikirim' first, then date submitted
            dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4 gap-4"fr>t<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4"ip>'
        });
    });
</script>
<?= $this->endSection() ?>
