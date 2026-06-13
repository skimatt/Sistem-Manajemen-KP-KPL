<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Dokumen Terkait</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar dokumen yang digenerate sistem untuk mahasiswa di instansi Anda.</p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <table class="w-full text-xs">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 uppercase text-[10px]"><tr><th class="p-3 text-left">Dokumen</th><th class="p-3 text-left">Mahasiswa</th><th class="p-3 text-left">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php if (empty($documents)): ?><tr><td colspan="3" class="p-6 text-center text-slate-500">Belum ada dokumen.</td></tr><?php endif; ?>
                <?php foreach ($documents as $row): ?>
                    <tr><td class="p-3 font-semibold"><?= esc($row['document_name']) ?></td><td class="p-3"><?= esc($row['full_name']) ?> · <?= esc($row['npm']) ?></td><td class="p-3"><?= esc($row['status']) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
