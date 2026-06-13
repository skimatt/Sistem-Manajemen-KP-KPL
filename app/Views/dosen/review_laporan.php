<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
        <a href="<?= base_url('dosen/laporan') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200/60 dark:border-slate-700/60 transition">
            <i class="ti ti-chevron-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Laporan Akhir</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review isi dokumen laporan akhir mahasiswa bimbingan Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Document Review Form & Stats (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Report Meta Details -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 space-y-4 text-xs">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-1.5">
                    <i class="ti ti-file-description text-blue-500"></i> Informasi Dokumen Laporan Akhir
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Mahasiswa</label>
                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($report->full_name) ?> (<?= esc($report->npm) ?>)</p>
                    </div>
                    <div>
                        <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Tanggal Unggah</label>
                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= date('d M Y H:i', strtotime($report->created_at)) ?></p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Judul Laporan</label>
                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 leading-relaxed"><?= esc($report->title) ?></p>
                    </div>
                </div>

                <!-- Download Card -->
                <div class="bg-slate-50 dark:bg-slate-800/40 p-4 border border-slate-200/50 dark:border-slate-800 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400">
                            <i class="ti ti-file-type-pdf text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-slate-200 text-xs"><?= esc($report->original_name) ?></p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-505 mt-0.5">Versi berkas: v<?= esc($report->version) ?> | Ukuran: <?= esc($report->file_size_kb) ?> KB</p>
                        </div>
                    </div>
                    <a id="btn_download_report" href="<?= base_url('dosen/laporan/download/' . $report->id) ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow shadow-blue-500/10 transition whitespace-nowrap">
                        <i class="ti ti-download"></i> Unduh Laporan
                    </a>
                </div>
            </div>

            <!-- PDF Preview (Embedded if possible or message) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-1.5">
                    <i class="ti ti-device-laptop text-blue-500"></i> Pratinjau Dokumen PDF
                </h3>
                <div class="w-full h-[600px] border border-slate-150 dark:border-slate-800 rounded-lg overflow-hidden bg-slate-50 dark:bg-slate-950">
                    <object data="<?= base_url('dosen/laporan/download/' . $report->id) ?>" type="application/pdf" class="w-full h-full">
                        <div class="flex flex-col items-center justify-center h-full text-center p-6 text-slate-400">
                            <i class="ti ti-alert-circle text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs">Pratinjau PDF tidak didukung oleh browser Anda, silakan unduh berkas melalui tombol di atas.</p>
                        </div>
                    </object>
                </div>
            </div>
        </div>

        <!-- Feedback Card (1 Col) -->
        <div class="space-y-6">
            <!-- Review Form -->
            <?php if ($report->status === 'dikirim'): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300">
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 mb-4 flex items-center gap-1.5">
                        <i class="ti ti-message-report text-blue-500"></i> Form Penilaian Laporan
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

                    <form id="report_review_form" action="<?= base_url('dosen/laporan/review/submit/' . $report->id) ?>" method="POST" class="space-y-4 text-xs">
                        <?= csrf_field() ?>
                        <div>
                            <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Status Laporan <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3 mt-1.5">
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200/60 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer">
                                    <input type="radio" id="report_approve" name="status" value="disetujui" class="text-blue-600" required <?= old('status') === 'disetujui' ? 'checked' : '' ?> />
                                    <span class="font-semibold text-slate-700 dark:text-slate-350">Setujui</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-lg border border-slate-200/60 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer">
                                    <input type="radio" id="report_revision" name="status" value="perlu_revisi" class="text-blue-600" required <?= old('status') === 'perlu_revisi' ? 'checked' : '' ?> />
                                    <span class="font-semibold text-slate-700 dark:text-slate-350">Revisi</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Catatan / Keterangan Review <span class="text-rose-500">*</span></label>
                            <textarea id="report_note" name="review_note" rows="5" placeholder="Tuliskan catatan perbaikan atau alasan persetujuan..." class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" required><?= old('review_note') ?></textarea>
                        </div>

                        <button type="submit" id="btn_submit_report_review" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow shadow-blue-500/10 transition">
                            Simpan Review Laporan
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Details of past review decision -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 space-y-4 text-xs">
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-1.5">
                        <i class="ti ti-check-double text-blue-500"></i> Hasil Penilaian Laporan
                    </h3>
                    <div>
                        <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Keputusan</label>
                        <span class="inline-flex items-center gap-1 font-bold px-2.5 py-0.5 rounded text-[10px] border mt-1.5 <?= $report->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50' : 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50' ?>">
                            <?= esc(ucwords(str_replace('_', ' ', $report->status))) ?>
                        </span>
                    </div>
                    <div>
                        <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Catatan / Masukan</label>
                        <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-200/50 dark:border-slate-800 p-3 rounded-lg text-slate-700 dark:text-slate-300 italic whitespace-pre-line leading-relaxed font-medium mt-1">
                            "<?= esc($report->review_note) ?>"
                        </div>
                    </div>
                    <div>
                        <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Tanggal Diperiksa</label>
                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-1"><?= date('d M Y H:i', strtotime($report->reviewed_at)) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
