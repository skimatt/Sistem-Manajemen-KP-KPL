<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/penilaian') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Nilai Mahasiswa</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Rincian nilai akademik lengkap, nilai pembimbing lapangan, serta administrasi untuk <?= esc($registration['full_name']) ?>.</p>
        </div>
    </div>

    <!-- Student Info and Validation Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Biodata Akademik</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="block text-slate-400 dark:text-slate-550">Mahasiswa</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 block text-sm mt-0.5"><?= esc($registration['full_name']) ?></span>
                    <span class="font-mono text-slate-500 dark:text-slate-400 block"><?= esc($registration['npm']) ?></span>
                </div>
                <div>
                    <span class="block text-slate-400 dark:text-slate-550">Program Studi / Tipe</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($registration['prodi_name']) ?></span>
                    <span class="text-slate-500 dark:text-slate-400 block"><?= esc($registration['period_name']) ?></span>
                </div>
                <div>
                    <span class="block text-slate-400 dark:text-slate-550">Dosen Pembimbing</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($registration['supervisor_name'] ?: 'Belum Diplot') ?></span>
                </div>
                <div>
                    <span class="block text-slate-400 dark:text-slate-550">Instansi KP/KPL</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($registration['proposed_institution_name'] ?? ($registration['partner_institution_name'] ?? 'Mandiri / Belum Diplot')) ?></span>
                </div>
            </div>
        </div>

        <!-- Validation Status Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Status Kelulusan</h3>
                
                <?php
                $statusText = 'Draft / Belum Divalidasi';
                $statusBadge = 'bg-slate-50 text-slate-650 dark:bg-slate-800/45 dark:text-slate-400 border-slate-200 dark:border-slate-800';
                if ($finalScore['status'] === 'menunggu_validasi') {
                    $statusText = 'Menunggu Validasi Koordinator';
                    $statusBadge = 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border-amber-100 dark:border-amber-900/40';
                } elseif ($finalScore['status'] === 'selesai') {
                    $statusText = 'Selesai / Dinyatakan Lulus';
                    $statusBadge = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/40';
                } elseif ($finalScore['status'] === 'diarsipkan') {
                    $statusText = 'Diarsipkan (Read-Only)';
                    $statusBadge = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 border-indigo-100 dark:border-indigo-900/40';
                }
                ?>
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold border capitalize <?= $statusBadge ?>">
                        <?= $statusText ?>
                    </span>
                </div>

                <?php if ($finalScore['validated_by']): ?>
                    <p class="text-[10px] text-slate-500 dark:text-slate-450">
                        Disetujui oleh: <strong class="text-slate-700 dark:text-slate-300"><?= esc($finalScore['validator_name']) ?></strong><br>
                        Tanggal: <?= date('d M Y H:i', strtotime($finalScore['validated_at'])) ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if ($finalScore['validation_note']): ?>
                <div class="mt-4 p-3 bg-slate-50 dark:bg-slate-950/40 border border-slate-200/60 dark:border-slate-800 rounded-lg text-[11px] text-slate-600 dark:text-slate-400">
                    <span class="font-semibold block text-[10px] text-slate-400 dark:text-slate-500 uppercase">Catatan Koordinator:</span>
                    <span class="italic">"<?= esc($finalScore['validation_note']) ?>"</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Weight snapshot breakdown -->
    <?php
    // Decode weight snapshot (or fallback to defaults)
    $weights = [];
    if (!empty($finalScore['weight_snapshot'])) {
        $weights = json_decode($finalScore['weight_snapshot'], true);
    }
    $wInstansi = $weights['institution_weight'] ?? 40;
    $wDosen = $weights['lecturer_weight'] ?? 50;
    $wAdmin = $weights['admin_weight'] ?? 10;
    ?>

    <!-- Big Score Panel -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Institution Score -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-1.5">
            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nilai Instansi (<?= $wInstansi ?>%)</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono"><?= number_format($finalScore['institution_score'], 2) ?></span>
                <span class="text-[10px] text-slate-500 dark:text-slate-400">kontribusi: <?= number_format($finalScore['institution_score'] * $wInstansi / 100, 2) ?></span>
            </div>
            <p class="text-[9px] text-slate-400 dark:text-slate-500">Berdasarkan evaluasi & form instansi pembimbing lapangan.</p>
        </div>

        <!-- Lecturer Score -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-1.5">
            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nilai Dosen (<?= $wDosen ?>%)</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono"><?= number_format($finalScore['lecturer_score'], 2) ?></span>
                <span class="text-[10px] text-slate-500 dark:text-slate-400">kontribusi: <?= number_format($finalScore['lecturer_score'] * $wDosen / 100, 2) ?></span>
            </div>
            <p class="text-[9px] text-slate-400 dark:text-slate-500">Berdasarkan ujian, laporan akhir, bimbingan berkala.</p>
        </div>

        <!-- Admin Score -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm space-y-1.5">
            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nilai Admin/Logbook (<?= $wAdmin ?>%)</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono"><?= number_format($finalScore['admin_score'], 2) ?></span>
                <span class="text-[10px] text-slate-500 dark:text-slate-400">kontribusi: <?= number_format($finalScore['admin_score'] * $wAdmin / 100, 2) ?></span>
            </div>
            <p class="text-[9px] text-slate-400 dark:text-slate-500">Berdasarkan kedisiplinan pengumpulan logbook dan berkas.</p>
        </div>

        <!-- Final Score / Grade Highlight -->
        <div class="bg-blue-600 dark:bg-blue-700 text-white rounded-xl p-5 shadow-sm space-y-1.5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-100">Nilai Akhir Kumulatif</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-white/20 text-white border border-white/10 uppercase font-mono">
                    Grade <?= esc($finalScore['final_grade']) ?>
                </span>
            </div>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-3xl font-black font-mono"><?= number_format($finalScore['final_score'], 2) ?></span>
                <span class="text-[11px] text-blue-100">skala 100</span>
            </div>
        </div>
    </div>

    <!-- Components Table Section -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Rincian Skor per Komponen Penilaian</h3>
        
        <?php if (empty($componentScores)): ?>
            <span class="text-xs text-slate-400 dark:text-slate-550 italic block text-center py-4">Data komponen penilaian belum dimasukkan oleh penilai terkait.</span>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-350 divide-y divide-slate-100 dark:divide-slate-800">
                    <thead class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="pb-3">Komponen Penilaian</th>
                            <th class="pb-3">Asal Penilai (Role)</th>
                            <th class="pb-3 text-center">Batas Maksimal</th>
                            <th class="pb-3 text-center">Skor Diperoleh</th>
                            <th class="pb-3">Catatan Evaluasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php foreach ($componentScores as $comp): ?>
                            <tr>
                                <td class="py-3 font-semibold text-slate-800 dark:text-slate-250"><?= esc($comp['component_name']) ?></td>
                                <td class="py-3">
                                    <?php if ($comp['assessor_role'] === 'instansi'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/40 capitalize">
                                            Mitra Instansi
                                        </span>
                                    <?php elseif ($comp['assessor_role'] === 'dosen'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/40 capitalize">
                                            Dosen Pembimbing
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-400 border border-slate-200 dark:border-slate-700 capitalize">
                                            Administrasi
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-center font-mono text-slate-500 dark:text-slate-450"><?= number_format($comp['max_score'], 0) ?></td>
                                <td class="py-3 text-center font-mono font-bold text-slate-800 dark:text-slate-100"><?= number_format($comp['score'], 2) ?></td>
                                <td class="py-3 text-slate-600 dark:text-slate-400 italic max-w-xs truncate" title="<?= esc($comp['note']) ?>">
                                    <?= esc($comp['note'] ?: '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
