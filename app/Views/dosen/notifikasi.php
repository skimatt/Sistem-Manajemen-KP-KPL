<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Notifikasi Saya</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar pemberitahuan tugas pemeriksaan berkas, logbook, dan update kegiatan bimbingan.</p>
        </div>
        <div>
            <form action="<?= base_url('dosen/notifikasi/read-all') ?>" method="POST" id="read_all_form">
                <?= csrf_field() ?>
                <button type="submit" id="btn_read_all" class="px-4 py-2 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-850 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold shadow-sm transition">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>

    <!-- Notification Feed -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-8 text-slate-400 dark:text-slate-500 text-xs">
                    <i class="ti ti-bell-off text-3xl mb-2 text-slate-350"></i>
                    <p>Tidak ada notifikasi bimbingan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                    <?php 
                    $isUnread = !$n->is_read;
                    $iconClass = 'ti-info-circle text-blue-500 bg-blue-50 dark:bg-blue-950/20';
                    if ($n->type === 'success') {
                        $iconClass = 'ti-circle-check text-emerald-500 bg-emerald-50 dark:bg-emerald-950/20';
                    } elseif ($n->type === 'warning') {
                        $iconClass = 'ti-alert-triangle text-amber-500 bg-amber-50 dark:bg-amber-950/20';
                    } elseif ($n->type === 'danger') {
                        $iconClass = 'ti-alert-circle text-rose-500 bg-rose-50 dark:bg-rose-950/20';
                    }
                    ?>
                    <a id="notif_link_<?= esc($n->id) ?>" href="<?= base_url('dosen/notifikasi/read/' . $n->id) ?>" class="flex items-start gap-4 p-4 hover:bg-slate-50/50 dark:hover:bg-slate-800/10 transition rounded-xl relative group">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg <?= $iconClass ?> flex-shrink-0">
                            <i class="ti <?= $iconClass ?> text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0 text-xs space-y-1">
                            <div class="flex items-center justify-between gap-4">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors <?= $isUnread ? 'font-extrabold text-slate-900 dark:text-white' : '' ?>">
                                    <?= esc($n->title) ?>
                                </h4>
                                <span class="text-[10px] text-slate-400 dark:text-slate-505 whitespace-nowrap font-medium">
                                    <?= date('d M Y H:i', strtotime($n->created_at)) ?>
                                </span>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 leading-relaxed <?= $isUnread ? 'text-slate-700 dark:text-slate-300 font-medium' : '' ?>">
                                <?= esc($n->message) ?>
                            </p>
                        </div>
                        <?php if ($isUnread): ?>
                            <!-- Unread Indicator Dot -->
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 h-2 w-2 rounded-full bg-blue-600 dark:bg-blue-500"></div>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
