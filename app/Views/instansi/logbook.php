<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Logbook Mahasiswa</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Validasi lapangan bersifat pendukung; validasi utama tetap oleh dosen pembimbing.</p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 uppercase text-[10px]">
                    <tr><th class="p-3 text-left">Mahasiswa</th><th class="p-3 text-left">Minggu</th><th class="p-3 text-left">Periode Tanggal</th><th class="p-3 text-left">Status</th><th class="p-3 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($logbooks)): ?>
                        <tr><td colspan="5" class="p-6 text-center text-slate-500">Belum ada logbook.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($logbooks as $row): ?>
                        <tr>
                            <td class="p-3"><p class="font-semibold"><?= esc($row['full_name']) ?></p><p class="text-slate-500"><?= esc($row['npm']) ?></p></td>
                            <td class="p-3">Minggu <?= esc($row['week_number']) ?></td>
                            <td class="p-3"><?= esc($row['start_date']) ?> s.d. <?= esc($row['end_date']) ?></td>
                            <td class="p-3"><?= esc($row['status']) ?></td>
                            <td class="p-3 text-right"><a href="<?= base_url('instansi/logbook/review/' . $row['id']) ?>" class="text-blue-600 font-semibold">Review</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
