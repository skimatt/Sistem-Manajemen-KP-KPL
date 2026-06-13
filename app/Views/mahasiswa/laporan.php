<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Laporan Akhir KP/KPL</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Unggah berkas laporan akhir pelaksanaan Kerja Praktek / Kerja Praktek Lapangan Anda yang telah ditandatangani.</p>
    </div>

    <!-- Alert Box for Validation Errors -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl p-4 text-xs text-red-600 dark:text-red-400 space-y-1">
            <h4 class="font-bold flex items-center gap-1.5"><i class="ti ti-alert-triangle"></i> Terjadi Kesalahan:</h4>
            <ul class="list-disc list-inside pl-1 space-y-0.5">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-6">
        
        <?php if ($laporan): ?>
            <!-- Current report status details -->
            <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-xs space-y-3">
                <h3 class="font-bold text-slate-700 dark:text-slate-350 flex items-center gap-1.5 border-b border-slate-150 dark:border-slate-800/60 pb-1.5">
                    <i class="ti ti-file-check text-blue-500"></i> Berkas Laporan Terkirim
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block">Judul Laporan:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($laporan->title) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Nama File Asli:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 block mt-0.5"><?= esc($laporan->original_name) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Status Laporan:</span>
                        <?php 
                        $statusBadge = 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-950 dark:text-slate-400 dark:border-slate-800';
                        if ($laporan->status === 'disetujui') {
                            $statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50';
                        } elseif ($laporan->status === 'perlu_revisi') {
                            $statusBadge = 'bg-orange-50 text-orange-700 border-orange-100 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50';
                        } elseif ($laporan->status === 'dikirim') {
                            $statusBadge = 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/50';
                        }
                        ?>
                        <span class="inline-flex items-center gap-1 font-semibold px-2 py-0.5 rounded text-[10px] border mt-1 capitalize <?= $statusBadge ?>">
                            <?= esc(str_replace('_', ' ', $laporan->status)) ?> (Versi <?= esc($laporan->version) ?>)
                        </span>
                    </div>
                    <?php if (!empty($laporan->review_note)): ?>
                        <div class="md:col-span-2 bg-amber-50/50 dark:bg-amber-950/15 border border-amber-100 dark:border-amber-900/35 rounded-lg p-3 text-slate-750 dark:text-slate-350">
                            <span class="font-semibold block mb-0.5">Catatan Dosen Pembimbing:</span>
                            <p class="leading-relaxed"><?= esc($laporan->review_note) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="flex justify-end pt-2">
                    <a href="<?= base_url('mahasiswa/download-file/' . $laporan->id . '/uploaded') ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg font-bold shadow-sm">
                        <i class="ti ti-download"></i> Unduh Laporan Terkirim
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Laporan -->
        <?php if (!$laporan || $laporan->status === 'perlu_revisi' || $laporan->status === 'ditolak'): ?>
            <form action="<?= base_url('mahasiswa/laporan/submit') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Judul Laporan Akhir</label>
                    <input type="text" name="title" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Masukkan judul laporan lengkap Anda..." value="<?= old('title', $laporan->title ?? '') ?>" required />
                </div>

                <div class="bg-slate-50/50 dark:bg-slate-950/20 p-5 rounded-xl border border-slate-200/50 dark:border-slate-800 text-xs space-y-3">
                    <label class="font-bold text-slate-700 dark:text-slate-300 block">Pilih Berkas Laporan Akhir (PDF)</label>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Berkas laporan harus menyertakan lembar pengesahan yang sudah ditandatangani pembimbing lapangan, dosen pembimbing, dan dekan/kaprodi.</p>
                    <input type="file" name="report_file" class="block w-full text-slate-500 text-[11px] mt-1.5 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-850 dark:file:text-slate-200 cursor-pointer" required />
                    <span class="text-[9px] text-slate-450 dark:text-slate-500 block mt-1">Format: PDF saja. Ukuran maksimal 15 MB.</span>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs shadow shadow-blue-500/10 transition">
                        Kirim Laporan Akhir
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="bg-blue-50/50 dark:bg-blue-950/10 border border-blue-100 dark:border-blue-900/35 rounded-xl p-4 text-xs text-blue-700 dark:text-blue-400">
                <h4 class="font-bold mb-1 flex items-center gap-1"><i class="ti ti-info-circle"></i> Pengajuan Laporan Terkunci</h4>
                <p class="leading-relaxed">Berkas laporan akhir Anda sedang dalam proses peninjauan atau sudah disetujui oleh Dosen Pembimbing. Anda tidak dapat melakukan revisi/unggah ulang kecuali diminta oleh Dosen Pembimbing.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
