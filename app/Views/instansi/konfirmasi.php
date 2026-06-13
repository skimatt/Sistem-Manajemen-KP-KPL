<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Konfirmasi Penerimaan</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Konfirmasi mahasiswa yang ditempatkan oleh Koordinator pada instansi Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <?php if (empty($placements)): ?>
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center text-sm text-slate-500">Belum ada pengajuan penempatan untuk dikonfirmasi.</div>
        <?php endif; ?>
        <?php foreach ($placements as $row): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100"><?= esc($row['full_name']) ?></p>
                        <p class="text-xs text-slate-500"><?= esc($row['npm']) ?> · <?= esc($row['prodi_name'] ?? '-') ?></p>
                    </div>
                    <span class="rounded-full bg-slate-100 dark:bg-slate-800 px-2 py-1 text-[10px] font-semibold text-slate-600 dark:text-slate-300"><?= esc($row['status']) ?></span>
                </div>
                <p class="text-xs text-slate-500">Periode: <?= esc($row['period_name'] ?? '-') ?></p>
                <?php if ($row['status'] === 'disetujui'): ?>
                    <form method="post" action="<?= base_url('instansi/konfirmasi/submit/' . $row['id']) ?>" class="space-y-2">
                        <?= csrf_field() ?>
                        <textarea name="note" rows="2" placeholder="Catatan penerimaan atau alasan penolakan" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-xs"></textarea>
                        <div class="flex justify-end gap-2">
                            <button name="decision" value="tolak" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">Tolak</button>
                            <button name="decision" value="terima" class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Terima</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-xs text-slate-500">Catatan: <?= esc($row['review_note'] ?: '-') ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
