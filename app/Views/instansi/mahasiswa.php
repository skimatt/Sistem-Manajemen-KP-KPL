<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Mahasiswa KP/KPL</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar mahasiswa yang sedang atau pernah ditempatkan pada instansi Anda.</p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 uppercase text-[10px]">
                    <tr><th class="p-3 text-left">Mahasiswa</th><th class="p-3 text-left">Periode</th><th class="p-3 text-left">Dosen</th><th class="p-3 text-left">Status</th><th class="p-3 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($students)): ?>
                        <tr><td colspan="5" class="p-6 text-center text-slate-500">Belum ada mahasiswa.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($students as $row): ?>
                        <tr>
                            <td class="p-3"><p class="font-semibold text-slate-800 dark:text-slate-100"><?= esc($row['full_name']) ?></p><p class="text-slate-500"><?= esc($row['npm']) ?> · <?= esc($row['prodi_name'] ?? '-') ?></p></td>
                            <td class="p-3 text-slate-600 dark:text-slate-300"><?= esc($row['period_name'] ?? '-') ?></td>
                            <td class="p-3 text-slate-600 dark:text-slate-300"><?= esc($row['lecturer_name'] ?? 'Belum ditetapkan') ?></td>
                            <td class="p-3"><span class="rounded-full bg-blue-50 dark:bg-blue-950/40 px-2 py-1 text-[10px] font-semibold text-blue-700 dark:text-blue-300"><?= esc($row['current_status']) ?></span></td>
                            <td class="p-3 text-right"><a href="<?= base_url('instansi/penilaian/input/' . $row['registration_id']) ?>" class="text-blue-600 font-semibold">Nilai</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
