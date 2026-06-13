<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Riwayat Mahasiswa</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Riwayat penempatan mahasiswa pada instansi Anda.</p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 uppercase text-[10px]">
                    <tr><th class="p-3 text-left">Mahasiswa</th><th class="p-3 text-left">Periode</th><th class="p-3 text-left">Status Penempatan</th><th class="p-3 text-left">Status Workflow</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($students)): ?>
                        <tr><td colspan="4" class="p-6 text-center text-slate-500">Belum ada riwayat mahasiswa.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($students as $row): ?>
                        <tr>
                            <td class="p-3"><p class="font-semibold text-slate-800 dark:text-slate-100"><?= esc($row['full_name']) ?></p><p class="text-slate-500"><?= esc($row['npm']) ?> · <?= esc($row['prodi_name'] ?? '-') ?></p></td>
                            <td class="p-3 text-slate-600 dark:text-slate-300"><?= esc($row['period_name'] ?? '-') ?></td>
                            <td class="p-3 text-slate-600 dark:text-slate-300"><?= esc($row['placement_status']) ?></td>
                            <td class="p-3 text-slate-600 dark:text-slate-300"><?= esc($row['current_status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
