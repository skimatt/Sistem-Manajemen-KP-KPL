<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Notifikasi Saya</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar pemberitahuan sistem dan pesan peringatan terkait aktivitas KP/KPL Anda.</p>
    </div>

    <!-- Notification List -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Pemberitahuan Terbaru</h3>
        
        <?php if (empty($notifications)): ?>
            <div class="text-center py-8 space-y-2">
                <div class="h-10 w-10 bg-slate-50 dark:bg-slate-950 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto">
                    <i class="ti ti-bell-off text-lg"></i>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Tidak ada notifikasi baru.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($notifications as $notif): ?>
                    <?php 
                    $typeColor = 'border-slate-200 bg-slate-50/50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/20 dark:text-slate-300';
                    $icon = 'ti-bell';
                    
                    if ($notif->type === 'success') {
                        $typeColor = 'border-emerald-100 bg-emerald-50/30 text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-950/15 dark:text-emerald-350';
                        $icon = 'ti-circle-check';
                    } elseif ($notif->type === 'warning') {
                        $typeColor = 'border-amber-100 bg-amber-50/30 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/15 dark:text-amber-350';
                        $icon = 'ti-alert-triangle';
                    } elseif ($notif->type === 'error') {
                        $typeColor = 'border-red-100 bg-red-50/30 text-red-800 dark:border-red-900/40 dark:bg-red-950/15 dark:text-red-350';
                        $icon = 'ti-alert-circle';
                    }
                    ?>
                    <div class="p-3.5 rounded-xl border flex items-start gap-3 text-xs <?= $typeColor ?>">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-white dark:bg-slate-900 shadow-sm border border-slate-100 dark:border-slate-800">
                            <i class="ti <?= $icon ?> text-sm"></i>
                        </div>
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-slate-850 dark:text-slate-150"><?= esc($notif->title) ?></h4>
                                <span class="text-[9px] text-slate-400 dark:text-slate-500"><?= date('d M Y H:i', strtotime($notif->created_at)) ?> WIB</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-[11px]"><?= esc($notif->message) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
