<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Kalkulator & Rekomendasi TOPSIS</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Sistem rekomendasi penempatan instansi mitra berdasarkan kesesuaian kriteria akademik dan lokasi dengan metode TOPSIS.</p>
        </div>
        <!-- Period Filter -->
        <form method="GET" action="<?= base_url('koordinator/topsis') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-slate-500 dark:text-slate-400">Periode:</span>
            <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none">
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodId ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= strtoupper($p['activity_type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Student Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-4 shadow-sm space-y-3">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider pb-2 border-b border-slate-100 dark:border-slate-800/80">Mahasiswa Aktif</h3>
                <div class="space-y-1.5 max-h-[400px] overflow-y-auto pr-1">
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $stu): ?>
                            <a href="<?= base_url('koordinator/topsis?period_id=' . $selectedPeriodId . '&registration_id=' . $stu['id']) ?>" 
                               class="block p-2.5 rounded-lg text-xs transition <?= $stu['id'] == $selectedRegId ? 'bg-blue-600 text-white font-bold' : 'bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-850 text-slate-700 dark:text-slate-350' ?>">
                                <div class="truncate"><?= esc($stu['full_name']) ?></div>
                                <div class="text-[10px] mt-0.5 <?= $stu['id'] == $selectedRegId ? 'text-blue-100' : 'text-slate-400' ?>"><?= esc($stu['npm']) ?></div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-[11px]">
                            Tidak ada mahasiswa membutuhkan penempatan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Calculation Area -->
        <div class="lg:col-span-3 space-y-6">
            <?php if ($selectedRegId && $studentSelected): ?>
                <?php if ($topsisError): ?>
                    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs border border-rose-100 dark:border-rose-950/50 font-semibold">
                        Gagal memuat rekomendasi TOPSIS: <?= esc($topsisError) ?>
                    </div>
                <?php else: ?>
                    <!-- Student Selected Header -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                <?= strtoupper(substr($studentSelected['full_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm leading-none"><?= esc($studentSelected['full_name']) ?></h3>
                                <p class="text-slate-400 dark:text-slate-500 mt-1"><?= esc($studentSelected['npm']) ?> / <?= esc($studentSelected['prodi_name']) ?> (IPK: <?= esc($studentSelected['academic_gpa']) ?>)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="<?= base_url('koordinator/topsis/calculate/' . $studentSelected['id']) ?>" method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition flex items-center gap-1.5">
                                    <i class="ti ti-reload"></i> Hitung Ulang TOPSIS
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- TOPSIS Ranking Result -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                            <i class="ti ti-award text-base text-blue-500"></i>
                            Hasil Ranking Rekomendasi Instansi
                        </h3>
                        <div class="table-responsive">
                            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                                <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-center w-16">Rank</th>
                                        <th class="px-6 py-3 font-bold">Nama Instansi Mitra</th>
                                        <th class="px-6 py-3 font-bold text-center">Indeks Preferensi (Ci)</th>
                                        <th class="px-6 py-3 font-bold text-center">Jarak Ideal (D+)</th>
                                        <th class="px-6 py-3 font-bold text-center">Jarak Non-Ideal (D-)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <?php foreach ($topsisData['results'] as $res): ?>
                                        <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-850/20 <?= $res['rank_order'] == 1 ? 'bg-blue-50/20 dark:bg-blue-950/10 font-semibold' : '' ?>">
                                            <td class="px-6 py-3.5 text-center">
                                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full font-bold text-[10px] <?= $res['rank_order'] == 1 ? 'bg-amber-500 text-white shadow' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-450' ?>">
                                                    #<?= $res['rank_order'] ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 text-slate-800 dark:text-slate-200 font-bold">
                                                <?= esc($res['instansi_name']) ?>
                                            </td>
                                            <td class="px-6 py-3.5 text-center font-bold text-slate-900 dark:text-white">
                                                <?= number_format($res['preference_value'], 6) ?>
                                            </td>
                                            <td class="px-6 py-3.5 text-center text-slate-500 dark:text-slate-450">
                                                <?= number_format($res['d_plus'], 6) ?>
                                            </td>
                                            <td class="px-6 py-3.5 text-center text-slate-500 dark:text-slate-450">
                                                <?= number_format($res['d_minus'], 6) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Collapsible Decision Matrix Input -->
                    <div x-data="{ open: false }" class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                        <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between font-bold text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-850/30 transition border-b border-slate-100 dark:border-slate-800">
                            <span class="flex items-center gap-2">
                                <i class="ti ti-edit text-base text-blue-500"></i>
                                Penyesuaian Matriks Keputusan (Nilai Kriteria)
                            </span>
                            <i class="ti ti-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="p-6">
                            <form action="<?= base_url('koordinator/topsis/save-scores/' . $studentSelected['id']) ?>" method="POST" class="space-y-4 text-xs">
                                <?= csrf_field() ?>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">Sesuaikan nilai raw kriteria untuk setiap instansi di bawah ini. C1-C3, C5-C6 bertindak sebagai benefit (0 s/d 100), sedangkan C4 bertindak sebagai cost (jarak dalam km).</p>
                                <div class="table-responsive">
                                    <table class="w-full text-left text-slate-600 dark:text-slate-400">
                                        <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/40 text-slate-500 dark:text-slate-400 border-b border-slate-200/60">
                                            <tr>
                                                <th class="px-4 py-2.5">Instansi</th>
                                                <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                    <th class="px-4 py-2.5 text-center" title="<?= esc($c['description']) ?>"><?= esc($c['code']) ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            <?php 
                                            // Group raw scores list by instansi
                                            $groupedScores = [];
                                            foreach ($rawScoresList as $sc) {
                                                $groupedScores[$sc['institution_id']]['name'] = $sc['inst_name'];
                                                $groupedScores[$sc['institution_id']]['scores'][$sc['criteria_id']] = $sc['score'];
                                            }
                                            ?>
                                            <?php foreach ($groupedScores as $instId => $instGroup): ?>
                                                <tr>
                                                    <td class="px-4 py-3 font-bold text-slate-800 dark:text-slate-200"><?= esc($instGroup['name']) ?></td>
                                                    <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                        <td class="px-4 py-3 text-center">
                                                            <input type="number" 
                                                                   step="0.01" 
                                                                   name="scores[<?= $instId ?>][<?= $c['id'] ?>]" 
                                                                   value="<?= esc($instGroup['scores'][$c['id']] ?? 50) ?>" 
                                                                   class="w-16 px-1.5 py-1 text-center border border-slate-200 dark:border-slate-800 rounded bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none" />
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition">
                                        Simpan Nilai & Hitung Ulang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Collapsible Calculation Steps (Accordion) -->
                    <div x-data="{ open: false }" class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                        <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between font-bold text-sm text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-850/30 transition border-b border-slate-100 dark:border-slate-800">
                            <span class="flex items-center gap-2">
                                <i class="ti ti-chart-dots text-base text-blue-500"></i>
                                Detail Langkah Perhitungan TOPSIS (Laporan Skripsi)
                            </span>
                            <i class="ti ti-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        
                        <div x-show="open" x-collapse x-cloak class="p-6 space-y-6 text-xs text-slate-600 dark:text-slate-400">
                            <!-- 1. Weights and Criteria -->
                            <div class="space-y-2">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200">1. Bobot Kriteria ($W$)</h4>
                                <ul class="list-disc pl-5 space-y-1 text-[11px]">
                                    <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                        <li><strong><?= esc($c['code']) ?> (<?= esc($c['name']) ?>)</strong>: <?= esc($topsisData['snapshot']['weights'][$c['id']] ?? 0) ?>% - Tipe: <?= strtoupper($c['type']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- 2. Normalized Matrix -->
                            <div class="space-y-2">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200">2. Matriks Keputusan Ternormalisasi ($R$)</h4>
                                <div class="table-responsive">
                                    <table class="w-full text-left text-[11px]">
                                        <thead class="bg-slate-50 dark:bg-slate-800/40 text-[10px]">
                                            <tr>
                                                <th class="px-3 py-2">Instansi</th>
                                                <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                    <th class="px-3 py-2 text-center"><?= esc($c['code']) ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            <?php foreach ($topsisData['snapshot']['normalized'] as $altId => $critRow): ?>
                                                <tr>
                                                    <td class="px-3 py-2 font-semibold text-slate-800 dark:text-slate-350"><?= esc($groupedScores[$altId]['name'] ?? $altId) ?></td>
                                                    <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                        <td class="px-3 py-2 text-center"><?= number_format($critRow[$c['id']], 6) ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 3. Weighted Normalized Matrix -->
                            <div class="space-y-2">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200">3. Matriks Ternormalisasi Berbobot ($V$)</h4>
                                <div class="table-responsive">
                                    <table class="w-full text-left text-[11px]">
                                        <thead class="bg-slate-50 dark:bg-slate-800/40 text-[10px]">
                                            <tr>
                                                <th class="px-3 py-2">Instansi</th>
                                                <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                    <th class="px-3 py-2 text-center"><?= esc($c['code']) ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                            <?php foreach ($topsisData['snapshot']['weighted'] as $altId => $critRow): ?>
                                                <tr>
                                                    <td class="px-3 py-2 font-semibold text-slate-800 dark:text-slate-350"><?= esc($groupedScores[$altId]['name'] ?? $altId) ?></td>
                                                    <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                        <td class="px-3 py-2 text-center"><?= number_format($critRow[$c['id']], 6) ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 4. Ideal Positive and Negative -->
                            <div class="space-y-2">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200">4. Solusi Ideal Positif ($A^+$) & Negatif ($A^-$)</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/30 rounded-lg">
                                        <span class="font-bold text-slate-700 dark:text-slate-300 block mb-1">Ideal Positif (A+)</span>
                                        <div class="space-y-0.5 text-[11px]">
                                            <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                <div class="flex justify-between">
                                                    <span><?= esc($c['code']) ?>:</span>
                                                    <span class="font-bold text-slate-800 dark:text-slate-200"><?= number_format($topsisData['snapshot']['ideal_positive'][$c['id']], 6) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/30 rounded-lg">
                                        <span class="font-bold text-slate-700 dark:text-slate-300 block mb-1">Ideal Negatif (A-)</span>
                                        <div class="space-y-0.5 text-[11px]">
                                            <?php foreach ($topsisData['snapshot']['criteria'] as $c): ?>
                                                <div class="flex justify-between">
                                                    <span><?= esc($c['code']) ?>:</span>
                                                    <span class="font-bold text-slate-800 dark:text-slate-200"><?= number_format($topsisData['snapshot']['ideal_negative'][$c['id']], 6) ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-12 text-center shadow-sm space-y-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 mx-auto">
                        <i class="ti ti-award text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-slate-700 dark:text-slate-300">Pilih Mahasiswa</h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">Silakan pilih mahasiswa pada sidebar kiri untuk melakukan perhitungan rekomendasi penempatan menggunakan metode TOPSIS.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
