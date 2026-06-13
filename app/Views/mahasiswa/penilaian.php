<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Penilaian KP/KPL</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Breakdown nilai akhir Kerja Praktek / Kerja Praktek Lapangan Anda yang telah divalidasi oleh Koordinator.</p>
    </div>

    <!-- Final Scores Breakdown Card -->
    <?php if (!$scores): ?>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center space-y-4 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-450 dark:text-slate-500 mx-auto">
                <i class="ti ti-award text-xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Nilai Belum Diterbitkan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                    Evaluasi nilai akhir Anda belum diterbitkan oleh Koordinator atau belum lengkap diisi oleh Dosen/Instansi.
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left: Circular Grade Indicator -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col items-center justify-center text-center space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nilai Akhir</h3>
                
                <div class="relative flex items-center justify-center h-28 w-28 rounded-full bg-blue-50 dark:bg-blue-950/40 border-4 border-blue-600 dark:border-blue-500 shadow-sm">
                    <div class="text-center">
                        <span class="text-2xl font-black text-blue-700 dark:text-blue-400"><?= number_format($scores->final_score, 1) ?></span>
                        <span class="block text-[10px] text-slate-450 dark:text-slate-550 font-bold mt-0.5">Skala 100</span>
                    </div>
                </div>

                <div class="space-y-1">
                    <span class="inline-flex items-center justify-center h-7 px-3 bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold rounded-lg text-xs border border-emerald-100 dark:border-emerald-900/40">
                        Indeks: <?= esc($scores->final_grade ?? '-') ?>
                    </span>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-2">Status: <span class="capitalize font-bold text-slate-700 dark:text-slate-255"><?= esc($scores->status) ?></span></p>
                </div>
            </div>

            <!-- Right: Component Grades List -->
            <div class="md:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Komponen Nilai Utama</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <!-- Instansi -->
                    <div class="p-3 bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-lg">
                        <span class="text-slate-400 dark:text-slate-500 block mb-1">Nilai Instansi (Mitra):</span>
                        <span class="text-base font-bold text-slate-800 dark:text-slate-150"><?= ($scores->institution_score !== null) ? number_format($scores->institution_score, 1) : '-' ?></span>
                    </div>

                    <!-- Dosen -->
                    <div class="p-3 bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-lg">
                        <span class="text-slate-400 dark:text-slate-500 block mb-1">Nilai Dosen Pembimbing:</span>
                        <span class="text-base font-bold text-slate-800 dark:text-slate-150"><?= ($scores->lecturer_score !== null) ? number_format($scores->lecturer_score, 1) : '-' ?></span>
                    </div>

                    <!-- Administrasi -->
                    <div class="p-3 bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-lg">
                        <span class="text-slate-400 dark:text-slate-500 block mb-1">Nilai Administrasi:</span>
                        <span class="text-base font-bold text-slate-800 dark:text-slate-150"><?= ($scores->admin_score !== null) ? number_format($scores->admin_score, 1) : '-' ?></span>
                    </div>
                </div>

                <div class="space-y-2.5 pt-2">
                    <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Rincian Nilai Detail</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-150 dark:border-slate-850 text-slate-400 dark:text-slate-550 font-semibold">
                                    <th class="py-2">Nama Komponen</th>
                                    <th class="py-2">Bobot (%)</th>
                                    <th class="py-2 text-right">Nilai Angka</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850 text-slate-700 dark:text-slate-300">
                                <?php if (empty($breakdowns)): ?>
                                    <tr>
                                        <td colspan="3" class="py-3 text-center text-slate-500">Belum ada rincian komponen nilai terdaftar.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($breakdowns as $b): ?>
                                        <tr>
                                            <td class="py-2 font-bold"><?= esc($b->component_name) ?></td>
                                            <td class="py-2"><?= esc($b->weight) ?>%</td>
                                            <td class="py-2 text-right font-semibold text-slate-800 dark:text-slate-200"><?= number_format($b->score, 1) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
