<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto" x-data="topsisWeightsCalculator()">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Kriteria & Bobot TOPSIS</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Konfigurasikan pembobotan kriteria pendukung rekomendasi penempatan instansi mitra mahasiswa.</p>
        </div>
        <div>
            <!-- Period Selector -->
            <form method="GET" action="<?= base_url('admin/topsis') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
                <label class="font-semibold text-slate-600 dark:text-slate-400">Pilih Periode:</label>
                <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none">
                    <?php foreach ($periods as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $selectedPeriodId == $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-xl text-xs text-emerald-700 dark:text-emerald-450 flex items-center gap-2">
            <i class="ti ti-circle-check text-base"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl text-xs text-red-700 dark:text-red-400 flex items-center gap-2">
            <i class="ti ti-alert-circle text-base"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Config Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <?php if (!$selectedPeriodId): ?>
            <div class="text-center py-8 italic text-xs text-slate-400">
                Silakan buat periode akademik aktif terlebih dahulu untuk mengatur bobot kriteria.
            </div>
        <?php else: ?>
            <form action="<?= base_url('admin/topsis/update-weights') ?>" method="POST" class="space-y-6 text-xs">
                <?= csrf_field() ?>
                <input type="hidden" name="period_id" value="<?= $selectedPeriodId ?>" />

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-350 divide-y divide-slate-100 dark:divide-slate-800">
                        <thead>
                            <tr class="text-slate-400 dark:text-slate-500 uppercase text-[9px] tracking-wider font-semibold">
                                <th class="pb-3 w-16">Kode</th>
                                <th class="pb-3">Nama Kriteria</th>
                                <th class="pb-3 w-28">Tipe</th>
                                <th class="pb-3">Deskripsi Kriteria</th>
                                <th class="pb-3 w-28 text-right">Bobot Kriteria (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                            <?php foreach ($weights as $index => $w): ?>
                                <tr>
                                    <td class="py-4 font-mono font-bold text-slate-800 dark:text-slate-100"><?= esc($w['code']) ?></td>
                                    <td class="py-4 font-semibold text-slate-850 dark:text-slate-250"><?= esc($w['name']) ?></td>
                                    <td class="py-4">
                                        <?php if ($w['type'] === 'benefit'): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/40 capitalize">
                                                Benefit (Keuntungan)
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-900/40 capitalize">
                                                Cost (Biaya/Jarak)
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 text-slate-500 dark:text-slate-400 leading-relaxed max-w-xs"><?= esc($w['description']) ?></td>
                                    <td class="py-4 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="weights[<?= $w['criteria_id'] ?>]" 
                                                   x-model.number="weights[<?= $index ?>]" 
                                                   @input="calculateTotal()"
                                                   class="block w-20 px-2.5 py-1.5 font-mono text-right bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                                   required />
                                            <span class="font-bold text-slate-400">%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Live Total Sum & Submit Button -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-5 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-slate-500 dark:text-slate-400">Total Akumulasi Bobot:</span>
                        <span class="font-mono font-bold text-sm px-2.5 py-1 rounded-lg"
                              :class="totalWeight === 100 ? 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-450'">
                            <span x-text="totalWeight.toFixed(2)"></span>%
                        </span>
                        <p x-show="totalWeight !== 100" class="text-[10px] text-red-600 dark:text-red-400 italic">Total bobot kriteria harus tepat bernilai 100%.</p>
                    </div>
                    <div>
                        <button type="submit" 
                                :disabled="totalWeight !== 100"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition">
                            Simpan Konfigurasi Bobot
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function topsisWeightsCalculator() {
        return {
            weights: [
                <?php foreach ($weights as $w): ?>
                    <?= floatval($w['weight']) ?>,
                <?php endforeach; ?>
            ],
            totalWeight: 0,

            init() {
                this.calculateTotal();
            },

            calculateTotal() {
                this.totalWeight = this.weights.reduce((sum, current) => sum + (parseFloat(current) || 0), 0);
            }
        };
    }
</script>
<?= $this->endSection() ?>
