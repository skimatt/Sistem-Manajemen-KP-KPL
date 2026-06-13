<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Status Registrasi</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pantau status pengajuan verifikasi berkas pendaftaran KP/KPL Anda.</p>
    </div>

    <?php if (!$registration): ?>
        <!-- No Registration Found -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center space-y-4 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 mx-auto">
                <i class="ti ti-id text-xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Belum Ada Pengajuan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                    Anda belum mengisi formulir pendaftaran registrasi KP/KPL. Silakan masuk ke menu Registrasi terlebih dahulu.
                </p>
            </div>
            <a href="<?= base_url('mahasiswa/registrasi') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow shadow-blue-500/10">
                Mulai Registrasi <i class="ti ti-arrow-right"></i>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Document List & Status Detail -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Detail Pengajuan Anda</h3>
                    
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block">SKS Lulus:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100 mt-1 block"><?= esc($registration->academic_sks) ?> SKS</span>
                        </div>
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block">IPK Terakhir:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100 mt-1 block"><?= esc($registration->academic_gpa) ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block">Dosen PA:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100 mt-1 block"><?= esc($registration->academic_advisor_name) ?></span>
                        </div>
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block">Tanggal Pengajuan:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100 mt-1 block"><?= date('d M Y H:i', strtotime($registration->submitted_at)) ?> WIB</span>
                        </div>
                    </div>

                    <?php if (!empty($registration->final_note)): ?>
                        <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg p-3.5 text-xs text-slate-700 dark:text-slate-300">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block mb-1">Catatan Koordinator:</span>
                            <p class="leading-relaxed"><?= esc($registration->final_note) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Document Upload List -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Berkas Persyaratan yang Diunggah</h3>
                    
                    <div class="space-y-3">
                        <?php foreach ($docs as $doc): ?>
                            <div class="flex items-center justify-between p-3 border border-slate-100 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-950/10 rounded-lg text-xs">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-slate-700 dark:text-slate-200"><?= esc($doc->document_name) ?></h4>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-550 block">File: <?= esc($doc->original_name) ?> (<?= esc($doc->file_size_kb) ?> KB)</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <?php 
                                    $badge = 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-950/40 dark:text-slate-400 dark:border-slate-800';
                                    if ($doc->status === 'valid') {
                                        $badge = 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50';
                                    } elseif ($doc->status === 'perlu_revisi') {
                                        $badge = 'bg-orange-50 text-orange-700 border-orange-100 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50';
                                    } elseif ($doc->status === 'ditolak') {
                                        $badge = 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50';
                                    } elseif ($doc->status === 'menunggu_verifikasi') {
                                        $badge = 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/50';
                                    }
                                    ?>
                                    <span class="px-2 py-0.5 rounded border text-[10px] font-semibold capitalize <?= $badge ?>"><?= esc(str_replace('_', ' ', $doc->status)) ?></span>
                                    <a href="<?= base_url('mahasiswa/download-file/' . $doc->id . '/uploaded') ?>" class="h-7 w-7 flex items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 transition shadow-sm" title="Unduh Berkas">
                                        <i class="ti ti-download text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Right: Log History Timeline -->
            <div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Histori Perjalanan</h3>
                    
                    <div class="relative pl-5 border-l-2 border-slate-100 dark:border-slate-800 space-y-6 text-xs py-2">
                        <?php foreach ($logs as $log): ?>
                            <div class="relative">
                                <!-- Dot -->
                                <div class="absolute -left-[27px] top-1.5 h-3.5 w-3.5 rounded-full border-2 border-white dark:border-slate-900 bg-blue-500"></div>
                                
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 capitalize">
                                            Status: <?= esc(str_replace('_', ' ', $log->new_status)) ?>
                                        </span>
                                        <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">
                                            <?= date('d M H:i', strtotime($log->created_at)) ?>
                                        </span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-[11px]">
                                        <?= esc($log->note) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
