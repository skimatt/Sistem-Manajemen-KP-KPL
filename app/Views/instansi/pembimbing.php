<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Pembimbing Lapangan</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Narahubung instansi yang dipakai sebagai kontak pembimbing lapangan.</p>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5">
        <p class="text-sm font-bold text-slate-800 dark:text-slate-100"><?= esc($instansi->contact_person ?: 'Belum diisi') ?></p>
        <p class="text-xs text-slate-500 mt-1"><?= esc($instansi->contact_position ?: '-') ?> · <?= esc($instansi->contact_phone ?: '-') ?> · <?= esc($instansi->contact_email ?: '-') ?></p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($students as $row): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4">
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100"><?= esc($row['full_name']) ?></p>
                <p class="text-xs text-slate-500 mt-1"><?= esc($row['npm']) ?> · Dosen: <?= esc($row['lecturer_name'] ?? 'Belum ditetapkan') ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
