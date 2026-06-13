<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
        <a href="<?= base_url('dosen/logbook') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200/60 dark:border-slate-700/60 transition">
            <i class="ti ti-chevron-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Pemeriksaan Logbook</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review aktivitas mingguan dan harian mahasiswa.</p>
        </div>
    </div>

    <!-- Student and Week Summary Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
        <div>
            <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Mahasiswa</label>
            <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($week->full_name) ?></p>
            <p class="font-mono text-slate-500 dark:text-slate-400 mt-0.5"><?= esc($week->npm) ?></p>
        </div>
        <div>
            <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Rentang Logbook</label>
            <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5">Minggu ke-<?= esc($week->week_number) ?></p>
            <p class="text-slate-500 dark:text-slate-400 mt-0.5"><?= date('d M Y', strtotime($week->start_date)) ?> s.d <?= date('d M Y', strtotime($week->end_date)) ?></p>
        </div>
        <div>
            <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Status Saat Ini</label>
            <?php 
            $status = $week->status;
            $label = ucwords(str_replace('_', ' ', $status));
            $color = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-150/50 dark:border-slate-800/50';
            
            if ($status === 'disetujui') {
                $color = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
            } elseif ($status === 'perlu_revisi') {
                $color = 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border-amber-100/50 dark:border-amber-900/50';
            } elseif ($status === 'dikirim') {
                $color = 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-100/50 dark:border-blue-900/50';
            }
            ?>
            <span class="inline-flex items-center gap-1 font-bold px-2 py-0.5 rounded text-[10px] border <?= $color ?> mt-1">
                <?= esc($label) ?>
            </span>
        </div>
    </div>

    <!-- Layout Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Activities Details (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Weekly Summary Details -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 space-y-4">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-1.5">
                    <i class="ti ti-target text-blue-500"></i> Rangkuman Rencana & Hasil Kegiatan Mingguan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50/50 dark:bg-slate-800/30 p-3 rounded-lg border border-slate-150/50 dark:border-slate-800/50">
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Rencana/Target Mingguan:</label>
                        <p class="text-slate-800 dark:text-slate-350 leading-relaxed font-medium whitespace-pre-line"><?= esc($week->weekly_target) ?: 'Tidak diisi' ?></p>
                    </div>
                    <div class="bg-slate-50/50 dark:bg-slate-800/30 p-3 rounded-lg border border-slate-150/50 dark:border-slate-800/50">
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Hasil yang Dicapai:</label>
                        <p class="text-slate-800 dark:text-slate-350 leading-relaxed font-medium whitespace-pre-line"><?= esc($week->weekly_result) ?: 'Belum diisi' ?></p>
                    </div>
                    <div class="bg-slate-50/50 dark:bg-slate-800/30 p-3 rounded-lg border border-slate-150/50 dark:border-slate-800/50">
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Kendala/Hambatan:</label>
                        <p class="text-slate-800 dark:text-slate-350 leading-relaxed font-medium whitespace-pre-line"><?= esc($week->obstacle) ?: 'Tidak ada kendala' ?></p>
                    </div>
                    <div class="bg-slate-50/50 dark:bg-slate-800/30 p-3 rounded-lg border border-slate-150/50 dark:border-slate-800/50">
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Rencana Minggu Berikutnya:</label>
                        <p class="text-slate-800 dark:text-slate-350 leading-relaxed font-medium whitespace-pre-line"><?= esc($week->next_plan) ?: 'Tidak diisi' ?></p>
                    </div>
                </div>
            </div>

            <!-- Daily Entries -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-1.5">
                    <i class="ti ti-calendar-event text-blue-500"></i> Detail Catatan Kegiatan Harian
                </h3>
                <div class="space-y-4">
                    <?php if (empty($dailyEntries)): ?>
                        <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs">
                            <p>Tidak ada catatan kegiatan harian untuk minggu ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($dailyEntries as $entry): ?>
                            <div class="border border-slate-150 dark:border-slate-800 rounded-xl p-3 space-y-2 text-xs hover:border-slate-200 dark:hover:border-slate-700 transition">
                                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80 pb-1.5">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                                        <i class="ti ti-calendar"></i> <?= date('d M Y', strtotime($entry['activity_date'])) ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1 font-semibold px-2 py-0.5 rounded text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        <i class="ti ti-clock"></i> <?= substr($entry['start_time'], 0, 5) ?> - <?= substr($entry['end_time'], 0, 5) ?>
                                    </span>
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Uraian Kegiatan:</label>
                                    <p class="text-slate-800 dark:text-slate-350 leading-relaxed whitespace-pre-line mt-0.5"><?= esc($entry['activity_description']) ?></p>
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase block">Uraian Hasil/Output:</label>
                                    <p class="text-slate-800 dark:text-slate-350 leading-relaxed whitespace-pre-line mt-0.5"><?= esc($entry['result_description']) ?: '-' ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Feedback & Logbook reviews panel -->
        <div class="space-y-6">
            <!-- Review Form (Only show if status is 'dikirim') -->
            <?php if ($week->status === 'dikirim'): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300">
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 mb-4 flex items-center gap-1.5">
                        <i class="ti ti-message-report text-blue-500"></i> Form Pemeriksaan Logbook
                    </h3>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 p-3 rounded-lg text-xs border border-rose-100/50 dark:border-rose-900/50 mb-4">
                            <ul class="list-disc pl-4 space-y-1">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form id="logbook_review_form" action="<?= base_url('dosen/logbook/review/submit/' . $week->id) ?>" method="POST" class="space-y-4 text-xs">
                        <?= csrf_field() ?>
                        <div>
                            <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Keputusan Persetujuan <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3 mt-1.5">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200/60 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer">
                                    <input type="radio" id="review_status_approve" name="status" value="disetujui" class="text-blue-600" required <?= old('status') === 'disetujui' ? 'checked' : '' ?> />
                                    <span class="font-semibold text-slate-700 dark:text-slate-350">Setujui</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200/60 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer">
                                    <input type="radio" id="review_status_revision" name="status" value="perlu_revisi" class="text-blue-600" required <?= old('status') === 'perlu_revisi' ? 'checked' : '' ?> />
                                    <span class="font-semibold text-slate-700 dark:text-slate-350">Minta Revisi</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Catatan / Komentar Review <span class="text-rose-500">*</span></label>
                            <textarea id="review_comment" name="comment" rows="4" placeholder="Berikan arahan, koreksi, atau alasan jika meminta revisi..." class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" required><?= old('comment') ?></textarea>
                        </div>

                        <button type="submit" id="btn_submit_review" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow shadow-blue-500/10 transition">
                            Simpan Review Logbook
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Review History -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 space-y-4">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-1.5">
                    <i class="ti ti-history text-slate-400"></i> Riwayat Review Pembimbing
                </h3>
                <div class="space-y-4">
                    <?php if (empty($reviews)): ?>
                        <p class="text-slate-400 dark:text-slate-500 text-xs py-4 text-center">Belum ada riwayat review pada minggu logbook ini.</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <div class="bg-slate-50/50 dark:bg-slate-800/35 border border-slate-150 dark:border-slate-800 p-3 rounded-lg text-xs space-y-1.5">
                                <div class="flex items-center justify-between text-[10px] text-slate-400 dark:text-slate-500">
                                    <span class="font-bold"><?= esc($rev['reviewer_name']) ?></span>
                                    <span><?= date('d M Y H:i', strtotime($rev['reviewed_at'])) ?></span>
                                </div>
                                <p class="text-slate-800 dark:text-slate-300 italic leading-relaxed whitespace-pre-line font-medium">"<?= esc($rev['comment']) ?>"</p>
                                <div class="mt-1">
                                    <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold <?= $rev['status'] === 'disetujui' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/50' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 border border-amber-100/50 dark:border-amber-900/50' ?>">
                                        <?= esc(ucwords(str_replace('_', ' ', $rev['status']))) ?>
                                    </span>
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
