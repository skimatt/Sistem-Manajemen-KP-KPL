<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
        <a href="<?= base_url('dosen/penilaian') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200/60 dark:border-slate-700/60 transition">
            <i class="ti ti-chevron-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Input Nilai Akademik</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Input nilai kompetensi bimbingan mahasiswa.</p>
        </div>
    </div>

    <!-- Student details card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
        <div>
            <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] font-semibold block">Mahasiswa</label>
            <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($registration['full_name']) ?></p>
            <p class="font-mono text-slate-500 dark:text-slate-400 mt-0.5"><?= esc($registration['npm']) ?></p>
        </div>
        <div>
            <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] font-semibold block">Periode Kegiatan</label>
            <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($registration['period_name']) ?></p>
            <p class="text-slate-500 dark:text-slate-400 mt-0.5">Jenis: <?= esc(strtoupper($registration['activity_type'] ?? 'KP')) ?></p>
        </div>
        <div>
            <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] font-semibold block">Template Penilaian</label>
            <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($template->name) ?></p>
            <p class="text-slate-550 dark:text-slate-500 mt-0.5">Versi: v<?= esc($template->version) ?></p>
        </div>
    </div>

    <!-- Grading components form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Components Input Card (2 Cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-1.5">
                <i class="ti ti-checklist text-blue-500"></i> Komponen Nilai & Pengisian Skor
            </h3>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 p-2.5 rounded-lg text-xs border border-rose-100/50 dark:border-rose-900/50 mb-4">
                    <ul class="list-disc pl-4 space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="grading_form" action="<?= base_url('dosen/penilaian/input/submit/' . $registration['id']) ?>" method="POST" class="space-y-6 text-xs">
                <?= csrf_field() ?>
                <input type="hidden" name="template_id" value="<?= esc($template->id) ?>" />

                <div class="space-y-5">
                    <?php foreach ($components as $c): ?>
                        <?php 
                        $preVal = isset($scoresMap[$c['id']]) ? floatval($scoresMap[$c['id']]['score']) : '';
                        $preNote = isset($scoresMap[$c['id']]) ? $scoresMap[$c['id']]['note'] : '';
                        ?>
                        <div class="p-4 rounded-xl border border-slate-150 dark:border-slate-850 hover:border-slate-200 dark:hover:border-slate-700 transition space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 dark:border-slate-850 pb-2">
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-slate-200"><?= esc($c['component_name']) ?></h4>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Nilai Maksimal: <?= esc(number_format($c['max_score'], 0)) ?> | Bobot: <span class="component-weight" data-weight="<?= esc($c['weight']) ?>"><?= esc(number_format($c['weight'], 0)) ?>%</span></p>
                                </div>
                                <div class="w-full sm:w-28 flex items-center gap-2">
                                    <input type="number" 
                                           id="score_<?= esc($c['id']) ?>" 
                                           name="score_<?= esc($c['id']) ?>" 
                                           step="0.01" 
                                           min="0" 
                                           max="<?= esc($c['max_score']) ?>" 
                                           value="<?= old('score_' . $c['id'], $preVal) ?>" 
                                           placeholder="0.00" 
                                           class="score-input block w-full px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-750 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 font-bold focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" 
                                           data-max="<?= esc($c['max_score']) ?>"
                                           required />
                                    <span class="font-bold text-slate-400">/<?= esc(number_format($c['max_score'], 0)) ?></span>
                                </div>
                            </div>
                            <div>
                                <label class="text-slate-400 dark:text-slate-500 text-[10px] font-semibold block mb-1">Catatan Khusus Komponen (Opsional):</label>
                                <input type="text" 
                                       id="note_<?= esc($c['id']) ?>" 
                                       name="note_<?= esc($c['id']) ?>" 
                                       value="<?= old('note_' . $c['id'], $preNote) ?>" 
                                       placeholder="Tambahkan masukan mengenai kompetensi ini..." 
                                       class="block w-full px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-750 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-550 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="<?= base_url('dosen/penilaian') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-lg font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" id="btn_submit_grades" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow shadow-blue-500/10 transition">
                        Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>

        <!-- Grade Summary Calculator Card (1 Col) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 text-xs space-y-4">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-1.5">
                    <i class="ti ti-calculator text-blue-500"></i> Kalkulasi Nilai Dosen
                </h3>
                
                <div class="bg-slate-50 dark:bg-slate-800/40 p-4 border border-slate-200/50 dark:border-slate-800 rounded-xl flex flex-col items-center justify-center text-center py-6">
                    <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] font-bold block mb-1">Skor Dosen Terkalkulasi</label>
                    <p id="calculatedScore" class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 font-mono">0.00</p>
                    <p class="text-[10px] text-slate-400 mt-2 font-medium">Berdasarkan bobot sub-komponen penilaian yang diisi.</p>
                </div>

                <div class="space-y-3">
                    <h4 class="font-semibold text-slate-700 dark:text-slate-300">Rumus Kalkulasi Nilai Dosen:</h4>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-[11px]">Setiap skor sub-komponen dikalikan dengan bobot masing-masing, kemudian dijumlahkan untuk membentuk nilai total dosen pembimbing (maks 100).</p>
                    <div class="bg-slate-50 dark:bg-slate-850 p-2.5 rounded-lg border border-slate-150/60 dark:border-slate-800 font-mono text-[9px] text-slate-550 leading-relaxed">
                        Nilai Dosen = &Sigma; (Skor * Bobot / 100)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll(".score-input");
        const calculatedDisplay = document.getElementById("calculatedScore");

        function calculateTotal() {
            let total = 0;
            inputs.forEach(input => {
                const val = parseFloat(input.value) || 0;
                const parent = input.closest(".p-4");
                const weightSpan = parent.querySelector(".component-weight");
                const weight = parseFloat(weightSpan.getAttribute("data-weight")) || 0;
                
                // Score contribution = val * (weight / 100)
                total += val * (weight / 100);
            });
            
            calculatedDisplay.textContent = total.toFixed(2);
        }

        // Add input event listeners to recalculate dynamically as lecturer types
        inputs.forEach(input => {
            input.addEventListener("input", function() {
                // Ensure score is not above max_score
                const max = parseFloat(this.getAttribute("data-max")) || 100;
                if (parseFloat(this.value) > max) {
                    this.value = max;
                }
                calculateTotal();
            });
        });

        // Run initial calculation
        calculateTotal();
    });
</script>
<?= $this->endSection() ?>
