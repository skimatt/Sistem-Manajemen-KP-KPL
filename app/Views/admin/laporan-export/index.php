<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Laporan & Export Data</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Unduh rekapitulasi nilai akhir, status penempatan, dan program studi mahasiswa Kerja Praktek (KP) / KPL dalam bentuk berkas Excel dan PDF.</p>
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

    <!-- Export Action Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-6" x-data="{ selectedPeriod: '' }">
        <div class="space-y-4">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 border-b border-slate-100 dark:border-slate-800 pb-2">
                <i class="ti ti-file-export text-base text-slate-400"></i>
                Pilih Parameter Export Laporan
            </h3>

            <div class="text-xs">
                <label class="block font-semibold text-slate-700 dark:text-slate-350">Periode Akademik <span class="text-red-500">*</span></label>
                <select x-model="selectedPeriod" class="block w-full mt-1.5 px-3 py-2 bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20">
                    <option value="">-- Pilih Periode Akademik --</option>
                    <?php foreach ($periods as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?> (<?= esc(str_replace('_', ' ', $p['status'])) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Download Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 dark:border-slate-800/80">
            <!-- Export Excel Button -->
            <button :disabled="!selectedPeriod"
                    @click="window.location.href = '<?= base_url('admin/laporan-export/excel') ?>?period_id=' + selectedPeriod"
                    class="flex items-center justify-center gap-2 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition">
                <i class="ti ti-file-spreadsheet text-base"></i>
                Export Rekapitulasi (.xlsx)
            </button>

            <!-- Export PDF Button -->
            <button :disabled="!selectedPeriod"
                    @click="window.location.href = '<?= base_url('admin/laporan-export/pdf') ?>?period_id=' + selectedPeriod"
                    class="flex items-center justify-center gap-2 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-xs disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition">
                <i class="ti ti-file-text text-base"></i>
                Export Rekapitulasi (.pdf)
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
