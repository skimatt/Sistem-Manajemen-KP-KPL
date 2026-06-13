<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/logbook') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Logbook Mahasiswa</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Riwayat laporan logbook harian dan mingguan dari <?= esc($registration['full_name']) ?>.</p>
        </div>
    </div>

    <!-- Student Detail Profile Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
            <div class="space-y-1">
                <span class="text-slate-400 dark:text-slate-500 block uppercase tracking-wider font-semibold text-[10px]">Mahasiswa</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block text-sm"><?= esc($registration['full_name']) ?></span>
                <span class="font-mono text-slate-500 dark:text-slate-400 block"><?= esc($registration['npm']) ?></span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 dark:text-slate-500 block uppercase tracking-wider font-semibold text-[10px]">Program Studi / Periode</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 block"><?= esc($registration['prodi_name']) ?></span>
                <span class="text-slate-500 dark:text-slate-400 block"><?= esc($registration['period_name']) ?></span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 dark:text-slate-500 block uppercase tracking-wider font-semibold text-[10px]">Lokasi KP/KPL / Dosen PA</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 block"><?= esc($registration['proposed_institution_name'] ?? ($registration['partner_institution_name'] ?? 'Mandiri / Belum Diplot')) ?></span>
                <span class="text-slate-500 dark:text-slate-400 block"><?= esc($registration['supervisor_name'] ?? 'Dosen PA / Belum Diplot') ?></span>
            </div>
        </div>
    </div>

    <!-- Timeline of Logbooks -->
    <div class="space-y-4">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-3">Daftar Laporan Mingguan</h3>

        <?php if (empty($weeks)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center shadow-sm">
                <span class="text-xs text-slate-400 dark:text-slate-500 italic block">Mahasiswa belum membuat atau mengunggah entri logbook.</span>
            </div>
        <?php else: ?>
            <div class="space-y-4" x-data="{ activeWeek: null }">
                <?php foreach ($weeks as $index => $week): ?>
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                        
                        <!-- Accordion Header -->
                        <div @click="activeWeek = activeWeek === <?= $index ?> ? null : <?= $index ?>" 
                             class="p-5 flex items-center justify-between cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-850/50 transition">
                            <div class="flex items-center gap-4">
                                <div class="h-9 w-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                    M<?= esc($week['week_number']) ?>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                        Minggu Ke-<?= esc($week['week_number']) ?>
                                    </h4>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                                        <?= date('d M Y', strtotime($week['start_date'])) ?> s.d <?= date('d M Y', strtotime($week['end_date'])) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <?php
                                $statusColors = [
                                    'draft' => 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-800/40 dark:text-slate-400 dark:border-slate-800',
                                    'dikirim' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/40',
                                    'perlu_revisi' => 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/40',
                                    'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
                                    'terkunci' => 'bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-900/40',
                                ];
                                $color = $statusColors[$week['status']] ?? 'bg-slate-50 text-slate-650';
                                ?>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border capitalize <?= $color ?>">
                                    <?= esc($week['status']) ?>
                                </span>
                                <i class="ti text-slate-400 transition duration-200" 
                                   :class="activeWeek === <?= $index ?> ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
                            </div>
                        </div>

                        <!-- Accordion Content -->
                        <div x-show="activeWeek === <?= $index ?>" x-collapse x-cloak>
                            <div class="p-6 border-t border-slate-150 dark:border-slate-800 space-y-6">
                                
                                <!-- Weekly Target, Accomplishment, Obstacles & Next Steps -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                                    <div class="bg-slate-50 dark:bg-slate-950/30 border border-slate-150 dark:border-slate-800/60 rounded-xl p-4 space-y-2">
                                        <div class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                                            <i class="ti ti-target text-sm text-blue-500"></i>
                                            Target Mingguan
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-350 leading-relaxed italic whitespace-pre-line"><?= esc($week['weekly_target'] ?: '-') ?></p>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-950/30 border border-slate-150 dark:border-slate-800/60 rounded-xl p-4 space-y-2">
                                        <div class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                                            <i class="ti ti-circle-check text-sm text-emerald-500"></i>
                                            Hasil Pencapaian
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-350 leading-relaxed italic whitespace-pre-line"><?= esc($week['weekly_result'] ?: '-') ?></p>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-950/30 border border-slate-150 dark:border-slate-800/60 rounded-xl p-4 space-y-2">
                                        <div class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                                            <i class="ti ti-alert-triangle text-sm text-amber-500"></i>
                                            Kendala Hambatan
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-350 leading-relaxed italic whitespace-pre-line"><?= esc($week['obstacle'] ?: 'Tidak ada kendala') ?></p>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-950/30 border border-slate-150 dark:border-slate-800/60 rounded-xl p-4 space-y-2">
                                        <div class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                                            <i class="ti ti-calendar-event text-sm text-indigo-500"></i>
                                            Rencana Minggu Depan
                                        </div>
                                        <p class="text-slate-600 dark:text-slate-350 leading-relaxed italic whitespace-pre-line"><?= esc($week['next_plan'] ?: '-') ?></p>
                                    </div>
                                </div>

                                <!-- Review Details -->
                                <?php if ($week['approved_at']): ?>
                                    <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-2">
                                            <i class="ti ti-user-check text-base text-emerald-600 dark:text-emerald-400"></i>
                                            <span>
                                                Direview oleh: <strong class="text-slate-850 dark:text-slate-200"><?= esc($week['reviewer_name']) ?></strong>
                                            </span>
                                        </div>
                                        <span class="text-slate-500 dark:text-slate-400 text-[10px]">
                                            Tanggal Review: <?= date('d M Y H:i', strtotime($week['approved_at'])) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Daily Entries Inside the Week -->
                                <div class="space-y-3">
                                    <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 border-b border-slate-100 dark:border-slate-800 pb-2">
                                        <i class="ti ti-list-details text-base text-slate-400"></i>
                                        Rincian Kegiatan Harian
                                    </h5>
                                    
                                    <?php if (empty($week['daily_entries'])): ?>
                                        <span class="text-xs text-slate-400 dark:text-slate-550 italic block text-center py-2">Tidak ada log kegiatan harian terdaftar pada minggu ini.</span>
                                    <?php else: ?>
                                        <div class="overflow-x-auto border border-slate-200 dark:border-slate-800 rounded-lg">
                                            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-350 divide-y divide-slate-100 dark:divide-slate-800">
                                                <thead class="bg-slate-50 dark:bg-slate-900/40 text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                                    <tr>
                                                        <th class="p-3">Tanggal / Waktu</th>
                                                        <th class="p-3">Uraian Deskripsi Kegiatan</th>
                                                        <th class="p-3">Hasil / Output</th>
                                                        <th class="p-3">Kendala</th>
                                                        <th class="p-3 text-right">Lampiran</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                                                    <?php foreach ($week['daily_entries'] as $day): ?>
                                                        <tr class="align-top">
                                                            <td class="p-3 font-semibold text-slate-850 dark:text-slate-250 whitespace-nowrap">
                                                                <div><?= date('d M Y', strtotime($day['activity_date'])) ?></div>
                                                                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono font-normal mt-0.5"><?= esc(substr($day['start_time'], 0, 5)) ?> - <?= esc(substr($day['end_time'], 0, 5)) ?></div>
                                                            </td>
                                                            <td class="p-3 leading-relaxed whitespace-pre-wrap max-w-xs"><?= esc($day['activity_description']) ?></td>
                                                            <td class="p-3 leading-relaxed whitespace-pre-wrap max-w-xs"><?= esc($day['activity_result'] ?: '-') ?></td>
                                                            <td class="p-3 leading-relaxed whitespace-pre-wrap max-w-xs text-slate-500 dark:text-slate-400 italic"><?= esc($day['obstacle'] ?: 'Tidak ada') ?></td>
                                                            <td class="p-3 text-right">
                                                                <?php if ($day['attachment_path']): ?>
                                                                    <a href="<?= base_url('uploads/logbooks/' . esc($day['attachment_path'])) ?>" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                                                        <i class="ti ti-paperclip"></i> Lihat
                                                                    </a>
                                                                <?php else: ?>
                                                                    <span class="text-[10px] text-slate-400">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
