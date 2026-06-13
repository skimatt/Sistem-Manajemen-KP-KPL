<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Surat & Dokumen Resmi</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Unduh dokumen resmi yang digenerate otomatis oleh sistem untuk keperluan administrasi KP/KPL Anda.</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl p-4 text-xs text-red-600 dark:text-red-400">
            <span class="font-bold"><i class="ti ti-alert-circle"></i> Galat:</span> <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Panel: Listing Generated Docs -->
        <div class="md:col-span-2 space-y-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Berkas Tersedia untuk Diunduh</h3>
                
                <div class="space-y-3">
                    <?php if (empty($generatedDocs)): ?>
                        <div class="text-center py-8 space-y-2">
                            <div class="h-10 w-10 bg-slate-50 dark:bg-slate-950 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto">
                                <i class="ti ti-file-off text-lg"></i>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Belum ada dokumen yang dihasilkan oleh sistem untuk Anda.</p>
                            <p class="text-[10px] text-slate-450 dark:text-slate-500">Dokumen seperti Surat Pengantar atau Lampiran A akan muncul setelah penempatan Anda disetujui Koordinator.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($generatedDocs as $doc): ?>
                            <div class="flex items-center justify-between p-3.5 border border-slate-150 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-950/10 rounded-lg text-xs hover:border-blue-300 dark:hover:border-blue-900 transition">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 font-bold">
                                        <i class="ti ti-file-type-pdf text-lg"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <h4 class="font-bold text-slate-700 dark:text-slate-200"><?= esc($doc->document_name) ?></h4>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-550">Kode: <?= esc($doc->document_code) ?> &bull; Versi <?= esc($doc->version) ?></p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-[9px] font-semibold uppercase">
                                        <?= esc($doc->status) ?>
                                    </span>
                                    <a href="<?= base_url('mahasiswa/download-file/' . $doc->id . '/generated') ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold transition shadow-sm">
                                        <i class="ti ti-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar: Upload actions & details -->
        <div class="space-y-6">
            <!-- Next Action Box -->
            <div class="bg-gradient-to-br from-indigo-600 to-blue-700 text-white rounded-xl p-5 shadow-sm space-y-3 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
                <h4 class="text-[10px] font-bold uppercase tracking-wider text-blue-200">Aksi Selanjutnya</h4>
                <p class="text-xs leading-relaxed">
                    Setelah mengunduh Surat Pengantar Instansi, serahkan berkas ke pihak mitra/instansi terkait. Jika instansi telah menyetujui, harap upload berkas balasan (Lembar Balasan/Penerimaan) yang sudah ditandatangani dan dibubuhi stempel resmi instansi.
                </p>
                <a href="<?= base_url('mahasiswa/upload-balasan') ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white text-indigo-700 rounded-lg text-[10px] font-bold hover:bg-slate-50 transition shadow-sm mt-2">
                    Upload Surat Balasan <i class="ti ti-upload"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
