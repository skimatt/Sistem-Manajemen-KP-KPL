<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Logbook</h1>
        <p class="text-xs text-slate-500 mt-1"><?= esc($week->full_name) ?> · Minggu <?= esc($week->week_number) ?></p>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-3">
            <?php foreach ($entries as $entry): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100"><?= esc($entry['activity_date']) ?> · <?= esc($entry['start_time'] ?? '-') ?> - <?= esc($entry['end_time'] ?? '-') ?></p>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-2"><?= esc($entry['activity_description']) ?></p>
                    <p class="text-xs text-slate-500 mt-1">Hasil: <?= esc($entry['result_description'] ?? '-') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="post" action="<?= base_url('instansi/logbook/review/submit/' . $week->id) ?>" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 space-y-3 h-fit">
            <?= csrf_field() ?>
            <label class="space-y-1 block">
                <span class="text-xs font-semibold">Status</span>
                <select name="status" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
                    <option value="disetujui">Disetujui</option>
                    <option value="perlu_revisi">Perlu Revisi</option>
                </select>
            </label>
            <label class="space-y-1 block">
                <span class="text-xs font-semibold">Catatan</span>
                <textarea name="comment" rows="4" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm"></textarea>
            </label>
            <button class="w-full rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Simpan Review</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
