<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Catatan Review Pembimbing</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar masukan, perbaikan, dan evaluasi mingguan dari dosen pembimbing akademik Anda.</p>
    </div>

    <!-- Review Comments Timeline -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Komentar Bimbingan Terkini</h3>
        
        <?php if (empty($reviews)): ?>
            <div class="text-center py-8 space-y-2">
                <div class="h-10 w-10 bg-slate-50 dark:bg-slate-950 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto">
                    <i class="ti ti-messages-off text-lg"></i>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Belum ada catatan review atau komentar dari Dosen Pembimbing.</p>
                <p class="text-[10px] text-slate-450 dark:text-slate-550">Komentar akan muncul setelah Anda mengirimkan laporan mingguan (Logbook) ke Dosen.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($reviews as $rev): ?>
                    <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/20 text-xs space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 dark:text-slate-150">Minggu Ke-<?= esc($rev->week_number) ?></span>
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">&bull; Diperiksa oleh: <?= esc($rev->lecturer_name) ?></span>
                            </div>
                            
                            <?php 
                            $badge = 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-900 dark:text-slate-400 dark:border-slate-800';
                            if ($rev->status === 'disetujui') {
                                $badge = 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50';
                            } elseif ($rev->status === 'perlu_revisi') {
                                $badge = 'bg-orange-50 text-orange-700 border-orange-100 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50';
                            }
                            ?>
                            <span class="px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider <?= $badge ?>">
                                <?= esc($rev->status) ?>
                            </span>
                        </div>
                        
                        <div class="text-slate-600 dark:text-slate-350 bg-white dark:bg-slate-900 border border-slate-150/40 dark:border-slate-850 p-3 rounded-lg leading-relaxed font-medium">
                            <?= nl2br(esc($rev->comment ?? 'Tidak ada komentar tertulis.')) ?>
                        </div>
                        
                        <div class="text-right text-[10px] text-slate-400 dark:text-slate-500">
                            Waktu Review: <?= date('d M Y H:i', strtotime($rev->reviewed_at)) ?> WIB
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
