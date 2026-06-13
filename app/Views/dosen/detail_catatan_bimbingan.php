<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
        <a href="<?= base_url('dosen/catatan-bimbingan') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200/60 dark:border-slate-700/60 transition">
            <i class="ti ti-chevron-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Catatan Bimbingan Mahasiswa</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Berikan catatan arahan bimbingan umum dan pantau riwayat catatan mingguan.</p>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student and General Guidance Card (1 Col) -->
        <div class="space-y-6">
            <!-- Student Bio Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300 text-xs space-y-3">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2">Data Mahasiswa</h3>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Nama Mahasiswa</label>
                    <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($registration['full_name']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">NPM</label>
                    <p class="font-mono font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($registration['npm']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Periode</label>
                    <p class="text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($registration['period_name']) ?></p>
                </div>
            </div>

            <!-- General Guidance Form -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 mb-4 flex items-center gap-1.5">
                    <i class="ti ti-notes text-blue-500"></i> Catatan Arahan Umum
                </h3>
                
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 p-2.5 rounded-lg text-xs border border-rose-100/50 dark:border-rose-900/50 mb-3">
                        <ul class="list-disc pl-4 space-y-1">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form id="bimbingan_note_form" action="<?= base_url('dosen/catatan-bimbingan/submit-note/' . $registration['id']) ?>" method="POST" class="space-y-4 text-xs">
                    <?= csrf_field() ?>
                    <div>
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Catatan Bimbingan Umum <span class="text-rose-500">*</span></label>
                        <textarea id="guidance_note" name="note" rows="5" placeholder="Tuliskan arahan umum bimbingan untuk mahasiswa (misalnya jadwal bimbingan, revisi judul laporan, dll)..." class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" required><?= old('note', $assignment->note) ?></textarea>
                    </div>

                    <button type="submit" id="btn_submit_note" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow shadow-blue-500/10 transition">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>

        <!-- Catatan Review Logbook Timeline (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-1.5">
                    <i class="ti ti-timeline text-blue-500"></i> Riwayat Catatan Review Mingguan
                </h3>

                <div class="relative pl-6 border-l border-slate-200 dark:border-slate-800 space-y-6">
                    <?php if (empty($reviews)): ?>
                        <p class="text-slate-400 dark:text-slate-500 text-xs py-4 text-center">Belum ada catatan review logbook mingguan.</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <div class="relative">
                                <!-- Marker Bullet -->
                                <div class="absolute -left-[30px] top-1 flex h-4 w-4 items-center justify-center rounded-full bg-white dark:bg-slate-900 border-2 <?= $rev['status'] === 'disetujui' ? 'border-emerald-500' : 'border-amber-500' ?>">
                                    <div class="h-1.5 w-1.5 rounded-full <?= $rev['status'] === 'disetujui' ? 'bg-emerald-500' : 'bg-amber-500' ?>"></div>
                                </div>

                                <div class="bg-slate-50/50 dark:bg-slate-850/40 p-4 border border-slate-150/60 dark:border-slate-800/80 rounded-xl text-xs space-y-2">
                                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/50 pb-1.5">
                                        <span class="font-bold text-slate-800 dark:text-slate-200">
                                            Minggu ke-<?= esc($rev['week_number']) ?>
                                        </span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-505 font-medium">
                                            <?= date('d M Y H:i', strtotime($rev['reviewed_at'])) ?>
                                        </span>
                                    </div>
                                    <p class="text-slate-700 dark:text-slate-300 italic leading-relaxed whitespace-pre-line font-medium">"<?= esc($rev['comment']) ?>"</p>
                                    <div>
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold <?= $rev['status'] === 'disetujui' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/50' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 border border-amber-100/50 dark:border-amber-900/50' ?>">
                                            <?= esc(ucwords(str_replace('_', ' ', $rev['status']))) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
