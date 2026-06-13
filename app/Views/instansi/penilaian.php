<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Input Nilai Instansi</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Isi evaluasi mahasiswa berdasarkan komponen penilaian instansi.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php if (empty($students)): ?>
            <div class="md:col-span-2 xl:col-span-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center text-sm text-slate-500">Belum ada mahasiswa yang dapat dinilai.</div>
        <?php endif; ?>
        <?php foreach ($students as $row): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 space-y-3">
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100"><?= esc($row['full_name']) ?></p>
                    <p class="text-xs text-slate-500"><?= esc($row['npm']) ?> · <?= esc($row['period_name'] ?? '-') ?></p>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Nilai Instansi</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100"><?= esc($row['institution_score'] ?? 'Belum ada') ?></span>
                </div>
                <a href="<?= base_url('instansi/penilaian/input/' . $row['registration_id']) ?>" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">Input / Ubah Nilai</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
