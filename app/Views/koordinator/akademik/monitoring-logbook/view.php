<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('koordinator/monitoring-logbook') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-base"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Logbook Mahasiswa</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Lacak dan pantau keaktifan bimbingan mahasiswa per minggu selama masa pelaksanaan KP/KPL.</p>
        </div>
    </div>

    <!-- Student Summary Box -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-5 shadow-sm text-xs space-y-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                <?= strtoupper(substr($registration['full_name'], 0, 1)) ?>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-2 flex-1">
                <div>
                    <h3 class="font-bold text-slate-850 dark:text-slate-200 text-sm leading-none"><?= esc($registration['full_name']) ?></h3>
                    <p class="text-slate-400 dark:text-slate-500 mt-1.5"><?= esc($registration['npm']) ?> / <?= esc($registration['prodi_name']) ?></p>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Dosen Pembimbing</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300 block mt-1"><?= esc($registration['academic_advisor_name']) ?: 'Belum Ditetapkan' ?></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold">Periode</span>
                    <span class="font-bold text-slate-700 dark:text-slate-300 block mt-1"><?= esc($registration['period_name']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Logbook Weeks Accordion -->
    <div class="space-y-4" x-data="{ activeWeek: null }">
        <?php if (!empty($weeks)): ?>
            <?php foreach ($weeks as $w): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                    <!-- Trigger -->
                    <button @click="activeWeek = activeWeek === <?= $w['id'] ?> ? null : <?= $w['id'] ?>" 
                            class="w-full px-6 py-4 flex items-center justify-between hover:bg-slate-55/40 dark:hover:bg-slate-850/20 transition focus:outline-none">
                        <div class="flex items-center gap-4 text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-100 text-sm">Minggu ke-<?= esc($w['week_number']) ?></span>
                            <span class="text-slate-400 dark:text-slate-500"><?= date('d M Y', strtotime($w['start_date'])) ?> &mdash; <?= date('d M Y', strtotime($w['end_date'])) ?></span>
                            
                            <!-- Status -->
                            <?php
                            $statusBadge = '';
                            if ($w['status'] === 'disetujui') {
                                $statusBadge = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400';
                            } elseif ($w['status'] === 'perlu_revisi') {
                                $statusBadge = 'bg-blue-100 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400';
                            } else {
                                $statusBadge = 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400';
                            }
                            ?>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $statusBadge ?> ml-2"><?= esc($w['status']) ?></span>
                        </div>
                        <i class="ti ti-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="activeWeek === <?= $w['id'] ?> ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Content -->
                    <div x-show="activeWeek === <?= $w['id'] ?> " x-collapse x-cloak>
                        <div class="px-6 pb-6 pt-2 border-t border-slate-100 dark:border-slate-850/60 space-y-6 text-xs text-slate-600 dark:text-slate-400">
                            <!-- Targets and Plans -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/20 border border-slate-200/40 rounded-lg">
                                    <span class="font-bold text-slate-850 dark:text-slate-300 block mb-1">Target Mingguan</span>
                                    <p class="leading-relaxed"><?= nl2br(esc($w['weekly_target'])) ?: '-' ?></p>
                                </div>
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/20 border border-slate-200/40 rounded-lg">
                                    <span class="font-bold text-slate-850 dark:text-slate-300 block mb-1">Rencana Tindak Lanjut</span>
                                    <p class="leading-relaxed"><?= nl2br(esc($w['next_plan'])) ?: '-' ?></p>
                                </div>
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/20 border border-slate-200/40 rounded-lg">
                                    <span class="font-bold text-slate-850 dark:text-slate-300 block mb-1">Pencapaian Mingguan</span>
                                    <p class="leading-relaxed"><?= nl2br(esc($w['weekly_result'])) ?: '-' ?></p>
                                </div>
                                <div class="p-3 bg-slate-50 dark:bg-slate-800/20 border border-slate-200/40 rounded-lg">
                                    <span class="font-bold text-slate-850 dark:text-slate-300 block mb-1">Kendala / Hambatan</span>
                                    <p class="leading-relaxed"><?= nl2br(esc($w['obstacle'])) ?: '-' ?></p>
                                </div>
                            </div>

                            <!-- Daily Entries Table -->
                            <div class="space-y-2">
                                <h4 class="font-bold text-slate-850 dark:text-slate-200 flex items-center gap-1.5"><i class="ti ti-calendar text-sm text-blue-500"></i> Kegiatan Harian</h4>
                                <div class="table-responsive">
                                    <table class="w-full text-left text-[11px]">
                                        <thead class="bg-slate-50 dark:bg-slate-800/40 text-[10px] text-slate-500 border-b border-slate-200/40 dark:border-slate-850">
                                            <tr>
                                                <th class="px-3 py-2">Tanggal</th>
                                                <th class="px-3 py-2 text-center">Jam</th>
                                                <th class="px-3 py-2">Uraian Kegiatan</th>
                                                <th class="px-3 py-2">Hasil Kegiatan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            <?php if (!empty($dailyEntries[$w['id']])): ?>
                                                <?php foreach ($dailyEntries[$w['id']] as $day): ?>
                                                    <tr>
                                                        <td class="px-3 py-2.5 font-semibold text-slate-800 dark:text-slate-350 whitespace-nowrap"><?= date('d M Y', strtotime($day['activity_date'])) ?></td>
                                                        <td class="px-3 py-2.5 text-center whitespace-nowrap"><?= date('H:i', strtotime($day['start_time'])) ?> - <?= date('H:i', strtotime($day['end_time'])) ?></td>
                                                        <td class="px-3 py-2.5 leading-normal"><?= esc($day['activity_description']) ?></td>
                                                        <td class="px-3 py-2.5 leading-normal"><?= esc($day['result_description']) ?: '-' ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-slate-400">Tidak ada detail kegiatan harian untuk minggu ini.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Reviews History -->
                            <div class="space-y-2 p-3 bg-blue-50/20 dark:bg-blue-950/10 border border-blue-100/40 dark:border-blue-900/40 rounded-xl">
                                <h4 class="font-bold text-slate-850 dark:text-slate-200 flex items-center gap-1.5"><i class="ti ti-message text-sm text-blue-500"></i> Catatan Review Dosen</h4>
                                <?php if (!empty($reviews[$w['id']])): ?>
                                    <div class="space-y-3 mt-2">
                                        <?php foreach ($reviews[$w['id']] as $rev): ?>
                                            <div class="p-2.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-200/50 dark:border-slate-850">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-bold text-slate-800 dark:text-slate-300"><?= esc($rev['reviewer_name']) ?></span>
                                                    <span class="text-[10px] text-slate-400"><?= date('d M Y, H:i', strtotime($rev['reviewed_at'])) ?></span>
                                                </div>
                                                <div class="text-[10px] font-bold text-blue-600 dark:text-blue-400 mt-0.5">Keputusan: <?= strtoupper($rev['status']) ?></div>
                                                <p class="mt-1 text-slate-600 dark:text-slate-450 leading-relaxed"><?= esc($rev['comment']) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-[11px] text-slate-400 italic">Belum ada catatan review untuk minggu ini.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-12 text-center shadow-sm text-slate-400">
                <i class="ti ti-notes text-2xl"></i>
                <h4 class="font-bold text-slate-700 dark:text-slate-300 mt-2">Belum Ada Logbook</h4>
                <p class="text-xs text-slate-400 mt-1">Mahasiswa yang bersangkutan belum mengisikan logbook kegiatan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
