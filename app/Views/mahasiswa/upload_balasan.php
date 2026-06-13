<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Upload Surat Balasan Instansi</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Unggah surat balasan resmi dari instansi penerima yang menyatakan Anda diterima melaksanakan KP/KPL.</p>
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

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-6">
        
        <?php if ($replyDoc): ?>
            <!-- Current uploaded document status -->
            <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-xs space-y-3">
                <h3 class="font-bold text-slate-700 dark:text-slate-350 flex items-center gap-1.5 border-b border-slate-150 dark:border-slate-800/60 pb-1.5">
                    <i class="ti ti-file-check text-blue-500"></i> Berkas Terunggah Saat Ini
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Nama File Asli:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 block mt-0.5"><?= esc($replyDoc->original_name) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Status Verifikasi:</span>
                        <?php 
                        $statusBadge = 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-950 dark:text-slate-400 dark:border-slate-800';
                        if ($replyDoc->status === 'valid') {
                            $statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50';
                        } elseif ($replyDoc->status === 'perlu_revisi') {
                            $statusBadge = 'bg-orange-50 text-orange-700 border-orange-100 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50';
                        } elseif ($replyDoc->status === 'menunggu_verifikasi') {
                            $statusBadge = 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/50';
                        }
                        ?>
                        <span class="inline-flex items-center gap-1 font-semibold px-2 py-0.5 rounded text-[10px] border mt-1 capitalize <?= $statusBadge ?>">
                            <?= esc(str_replace('_', ' ', $replyDoc->status)) ?>
                        </span>
                    </div>
                    <?php if (!empty($replyDoc->verification_note)): ?>
                        <div class="md:col-span-2 bg-amber-50/50 dark:bg-amber-950/15 border border-amber-100 dark:border-amber-900/35 rounded-lg p-2 text-slate-750 dark:text-slate-350 mt-1">
                            <span class="font-semibold block">Catatan Verifikator:</span>
                            <p class="leading-relaxed"><?= esc($replyDoc->verification_note) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="flex justify-end pt-2">
                    <a href="<?= base_url('mahasiswa/download-file/' . $replyDoc->id . '/uploaded') ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg font-bold shadow-sm">
                        <i class="ti ti-download"></i> Unduh File Saya
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Upload Form -->
        <form action="<?= base_url('mahasiswa/upload-balasan/submit') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            
            <div class="bg-slate-50/50 dark:bg-slate-950/20 p-5 rounded-xl border border-slate-200/50 dark:border-slate-800 text-xs space-y-3">
                <label class="font-bold text-slate-700 dark:text-slate-300 block">Pilih Berkas Surat Balasan</label>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Berkas surat balasan/penerimaan wajib dibubuhi tanda tangan pejabat instansi dan stempel resmi basah. Pindai berkas dalam format PDF sebelum diunggah.</p>
                <input type="file" name="reply_letter" class="block w-full text-slate-500 text-[11px] mt-1.5 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-850 dark:file:text-slate-200 cursor-pointer" required />
                <span class="text-[9px] text-slate-450 dark:text-slate-500 block mt-1">Format: PDF saja. Ukuran maksimal 10 MB.</span>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="<?= base_url('mahasiswa/dokumen') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-950 rounded-lg text-xs font-semibold transition">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs shadow shadow-blue-500/10 transition">
                    Unggah Surat Balasan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
