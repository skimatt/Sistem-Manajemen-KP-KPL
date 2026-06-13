<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Riwayat Pengajuan KP/KPL</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar riwayat pendaftaran dan keikutsertaan KP/KPL Anda pada periode-periode akademik sebelumnya.</p>
    </div>

    <!-- History Grid Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-150 dark:border-slate-850 text-slate-400 dark:text-slate-500 font-semibold">
                        <th class="py-2.5">Nama Periode</th>
                        <th class="py-2.5">Tahun Akademik</th>
                        <th class="py-2.5">Jenis Kegiatan</th>
                        <th class="py-2.5">SKS Lulus</th>
                        <th class="py-2.5">IPK</th>
                        <th class="py-2.5 text-right">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-850 text-slate-700 dark:text-slate-300">
                    <?php if (empty($history)): ?>
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500 dark:text-slate-400">Belum ada riwayat pendaftaran yang tercatat di sistem.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($history as $h): ?>
                            <tr class="hover:bg-slate-50/20 dark:hover:bg-slate-950/20 transition">
                                <td class="py-3 font-bold text-slate-800 dark:text-slate-200"><?= esc($h->period_name) ?></td>
                                <td class="py-3"><?= esc($h->academic_year) ?></td>
                                <td class="py-3">
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-[10px] font-semibold uppercase">
                                        <?= esc($h->activity_type) ?>
                                    </span>
                                </td>
                                <td class="py-3"><?= esc($h->academic_sks) ?> SKS</td>
                                <td class="py-3"><?= esc($h->academic_gpa) ?></td>
                                <td class="py-3 text-right">
                                    <?php 
                                    $statusBadge = 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-950 dark:text-slate-400 dark:border-slate-800';
                                    if ($h->current_status === 'selesai') {
                                        $statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50';
                                    } elseif ($h->current_status === 'diarsipkan') {
                                        $statusBadge = 'bg-slate-100 text-slate-800 border-slate-200 dark:bg-slate-900 dark:text-slate-300 dark:border-slate-850';
                                    } elseif ($h->current_status === 'registrasi_ditolak' || $h->current_status === 'penempatan_ditolak') {
                                        $statusBadge = 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50';
                                    }
                                    ?>
                                    <span class="inline-flex items-center gap-1 font-semibold px-2 py-0.5 rounded text-[10px] border capitalize <?= $statusBadge ?>">
                                        <?= esc(str_replace('_', ' ', $h->current_status)) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
