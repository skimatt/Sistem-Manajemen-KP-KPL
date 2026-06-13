<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Rekomendasi Penempatan Mitra (TOPSIS)</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan rekomendasi sistem berbasis TOPSIS sebagai acuan untuk memilih 3 prioritas instansi mitra.</p>
    </div>

    <!-- Alert Box for Error feedback -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl p-4 text-xs text-red-600 dark:text-red-400">
            <span class="font-bold"><i class="ti ti-alert-circle"></i> Galat:</span> <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Ranked Recommendations List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Rekomendasi Instansi Urutan TOPSIS</h3>
                
                <div class="space-y-3">
                    <?php if (empty($rekomendasi)): ?>
                        <p class="text-xs text-slate-500 dark:text-slate-400 text-center py-4">Belum ada alternatif mitra yang dikonfigurasi untuk periode ini.</p>
                    <?php else: ?>
                        <?php foreach ($rekomendasi as $index => $item): ?>
                            <div class="flex items-start justify-between p-3.5 border border-slate-150 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-950/10 rounded-lg text-xs hover:border-slate-350 dark:hover:border-slate-700 transition">
                                <div class="flex items-start gap-3">
                                    <!-- Rank Circle Badge -->
                                    <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 font-bold text-xs shadow-sm">
                                        #<?= esc($item->rank_order) ?>
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-slate-800 dark:text-slate-150"><?= esc($item->instansi_name) ?></h4>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider"><?= esc($item->field_category ?? 'Umum') ?></p>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1"><i class="ti ti-map-pin"></i> <?= esc($item->address) ?></p>
                                    </div>
                                </div>
                                
                                <div class="text-right space-y-1">
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 text-[10px] font-bold">
                                        S: <?= number_format($item->preference_value, 4) ?>
                                    </span>
                                    <span class="block text-[10px] text-slate-400 dark:text-slate-550 mt-1 font-semibold">
                                        Kuota: <?= esc($item->quota_used) ?> / <?= esc($item->quota_total) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Choices Input Form -->
        <div>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-4 sticky top-6">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-150 dark:border-slate-800/60 pb-2">Pilih 3 Prioritas</h3>
                
                <form action="<?= base_url('mahasiswa/rekomendasi-mitra/submit') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    
                    <!-- Choice 1 -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Prioritas 1 (Utama)</label>
                        <select name="institution_id_1" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" required>
                            <option value="">-- Pilih Instansi --</option>
                            <?php foreach ($rekomendasi as $item): ?>
                                <option value="<?= $item->institution_id ?>" <?= old('institution_id_1') == $item->institution_id ? 'selected' : '' ?>>
                                    #<?= esc($item->rank_order) ?> - <?= esc($item->instansi_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Choice 2 -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Prioritas 2</label>
                        <select name="institution_id_2" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" required>
                            <option value="">-- Pilih Instansi --</option>
                            <?php foreach ($rekomendasi as $item): ?>
                                <option value="<?= $item->institution_id ?>" <?= old('institution_id_2') == $item->institution_id ? 'selected' : '' ?>>
                                    #<?= esc($item->rank_order) ?> - <?= esc($item->instansi_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Choice 3 -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Prioritas 3</label>
                        <select name="institution_id_3" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" required>
                            <option value="">-- Pilih Instansi --</option>
                            <?php foreach ($rekomendasi as $item): ?>
                                <option value="<?= $item->institution_id ?>" <?= old('institution_id_3') == $item->institution_id ? 'selected' : '' ?>>
                                    #<?= esc($item->rank_order) ?> - <?= esc($item->instansi_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Alasan Pemilihan</label>
                        <textarea name="reason" rows="3" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Jelaskan alasan kecocokan bidang atau ketertarikan Anda..." required><?= old('reason') ?></textarea>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs shadow shadow-blue-500/10 transition">
                        Ajukan Pilihan Mitra
                    </button>
                    
                    <a href="<?= base_url('mahasiswa/penempatan') ?>" class="w-full block py-2 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-950 text-center font-semibold rounded-lg text-xs transition">
                        Ganti Jalur Penempatan
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
