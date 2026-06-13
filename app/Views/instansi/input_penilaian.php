<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Input Nilai Instansi</h1>
        <p class="text-xs text-slate-500 mt-1"><?= esc($student->full_name) ?> · <?= esc($student->npm) ?> · <?= esc($student->period_name ?? '-') ?></p>
    </div>
    <form method="post" action="<?= base_url('instansi/penilaian/input/submit/' . $student->registration_id) ?>" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 space-y-4">
        <?= csrf_field() ?>
        <?php if (empty($components)): ?>
            <div class="rounded-lg bg-amber-50 border border-amber-100 p-4 text-sm text-amber-700">Komponen penilaian instansi belum tersedia.</div>
        <?php endif; ?>
        <?php foreach ($components as $component): ?>
            <?php $oldScore = $existing[$component['id']]['score'] ?? ''; ?>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-start border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="md:col-span-5">
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100"><?= esc($component['component_name']) ?></p>
                    <p class="text-xs text-slate-500">Bobot <?= esc($component['weight']) ?>%, maksimal <?= esc($component['max_score']) ?></p>
                </div>
                <div class="md:col-span-2">
                    <input type="number" min="0" max="<?= esc($component['max_score']) ?>" step="0.01" name="scores[<?= esc($component['id']) ?>]" value="<?= old('scores.' . $component['id'], $oldScore) ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm" required>
                </div>
                <div class="md:col-span-5">
                    <input name="notes[<?= esc($component['id']) ?>]" value="<?= esc($existing[$component['id']]['note'] ?? '') ?>" placeholder="Catatan opsional" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
                </div>
            </div>
        <?php endforeach; ?>
        <div class="flex justify-end gap-2">
            <a href="<?= base_url('instansi/penilaian') ?>" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300">Batal</a>
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">Simpan Nilai</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
