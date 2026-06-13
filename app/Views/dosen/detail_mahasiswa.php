<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
        <a href="<?= base_url('dosen/mahasiswa') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 border border-slate-200/60 dark:border-slate-700/60 transition">
            <i class="ti ti-chevron-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Mahasiswa Bimbingan</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review profil, status penempatan, berkas, dan tahapan workflow bimbingan.</p>
        </div>
    </div>

    <!-- Stepper Workflow -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-6 shadow-sm transition-colors duration-300">
        <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-6 flex items-center gap-2">
            <i class="ti ti-git-pull-request text-blue-500"></i> Alur Tahapan Workflow Mahasiswa
        </h3>
        
        <?php
        $statusRank = [
            'draft' => 1,
            'menunggu_verifikasi' => 1,
            'revisi_registrasi' => 1,
            'registrasi_ditolak' => 1,
            'registrasi_disetujui' => 2,
            'penempatan_diajukan' => 2,
            'penempatan_disetujui' => 3,
            'diterima_instansi' => 4,
            'dosen_ditetapkan' => 5,
            'sedang_berjalan' => 6,
            'logbook_berjalan' => 6,
            'laporan_akhir_dikirim' => 7,
            'menunggu_penilaian_instansi' => 8,
            'nilai_dosen_masuk' => 8,
            'menunggu_validasi_akhir' => 9,
            'selesai' => 10,
            'diarsipkan' => 10
        ];
        $currentRank = $statusRank[$registration['current_status']] ?? 1;
        
        $steps = [
            1 => ['Pendaftaran', 'Registrasi Berkas'],
            3 => ['Penempatan', 'Persetujuan Mitra'],
            5 => ['Pembimbing', 'Penetapan Dosen'],
            6 => ['Pelaksanaan', 'Isi Logbook Mingguan'],
            7 => ['Laporan', 'Review Laporan Akhir'],
            8 => ['Penilaian', 'Penilaian Akademik & Lapangan'],
            10 => ['Selesai', 'Selesai KP/KPL']
        ];
        ?>
        
        <div class="grid grid-cols-2 md:grid-cols-7 gap-4 relative">
            <?php foreach ($steps as $rank => $step): ?>
                <?php
                $isCompleted = $currentRank >= $rank;
                $isActive = $currentRank === $rank;
                ?>
                <div class="flex flex-col items-center text-center p-3 rounded-xl border <?= $isActive ? 'bg-blue-50/50 dark:bg-blue-950/20 border-blue-200 dark:border-blue-800/60' : 'bg-slate-50/30 dark:bg-slate-900/30 border-slate-100 dark:border-slate-800/60' ?>">
                    <div class="flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-bold <?= $isCompleted ? 'bg-blue-600 dark:bg-blue-500 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400' ?> mb-2">
                        <?php if ($isCompleted && $currentRank > $rank): ?>
                            <i class="ti ti-check text-xs"></i>
                        <?php else: ?>
                            <?= array_search($step, $steps) ?>
                        <?php endif; ?>
                    </div>
                    <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200 leading-none"><?= esc($step[0]) ?></p>
                    <p class="text-[8px] text-slate-400 dark:text-slate-500 mt-1 leading-tight hidden sm:block"><?= esc($step[1]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300 lg:col-span-2 space-y-4">
            <h2 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-1.5">
                <i class="ti ti-user-circle text-slate-400"></i> Biodata Mahasiswa
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Nama Lengkap</label>
                    <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($registration['full_name']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">NPM</label>
                    <p class="font-mono font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($registration['npm']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Program Studi</label>
                    <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($registration['prodi_name']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">No. HP</label>
                    <p class="text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($registration['phone']) ?: '-' ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Email</label>
                    <p class="text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($registration['email']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Jenis Kelamin</label>
                    <p class="text-slate-800 dark:text-slate-200 mt-0.5"><?= $registration['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                </div>
            </div>

            <h2 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pt-2 pb-2 flex items-center gap-1.5">
                <i class="ti ti-school text-slate-400"></i> Kelayakan Akademik
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">SKS Lulus</label>
                    <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($registration['academic_sks']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">IPK Terakhir</label>
                    <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($registration['academic_gpa']) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Dosen PA</label>
                    <p class="text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($registration['academic_advisor_name']) ?: '-' ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] font-semibold block">Rekomendasi Dosen PA</label>
                    <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5">
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-100/50 dark:border-emerald-900/50">
                            <?= esc($registration['advisor_recommendation_status']) ?: 'layak' ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Placement Info Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300 space-y-4">
            <h2 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2 flex items-center gap-1.5">
                <i class="ti ti-building text-slate-400"></i> Informasi Penempatan
            </h2>
            <?php if (empty($placement)): ?>
                <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs">
                    <p>Mahasiswa belum mengajukan penempatan instansi.</p>
                </div>
            <?php else: ?>
                <div class="text-xs space-y-3">
                    <div>
                        <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] block">Instansi Penempatan</label>
                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5"><?= esc($placement->instansi_name) ?></p>
                    </div>
                    <div>
                        <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] block">Alamat Instansi</label>
                        <p class="text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed"><?= esc($placement->instansi_address) ?></p>
                    </div>
                    <div>
                        <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] block">Status Penerimaan</label>
                        <?php 
                        $plStatus = $placement->status;
                        $plLabel = ucwords(str_replace('_', ' ', $plStatus));
                        $plColor = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-150/50 dark:border-slate-800/50';
                        if ($plStatus === 'disetujui') {
                            $plColor = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
                        } elseif ($plStatus === 'ditolak') {
                            $plColor = 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border-rose-100/50 dark:border-rose-900/50';
                        } elseif ($plStatus === 'diajukan') {
                            $plColor = 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border-amber-100/50 dark:border-amber-900/50';
                        }
                        ?>
                        <span class="inline-flex items-center gap-1 font-bold px-2 py-0.5 rounded text-[10px] border <?= $plColor ?> mt-1">
                            <?= esc($plLabel) ?>
                        </span>
                    </div>
                    <div>
                        <label class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[9px] block">Jenis Pengajuan</label>
                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 uppercase tracking-wide text-[10px]">
                            <?= esc($placement->placement_type) ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Student Documents Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 shadow-sm transition-colors duration-300">
        <h2 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-3 mb-4 flex items-center gap-1.5">
            <i class="ti ti-files text-slate-400"></i> Berkas Persyaratan & Dokumen Mahasiswa
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-2.5 font-semibold">Nama Dokumen</th>
                        <th class="py-2.5 font-semibold">Nama Berkas Asli</th>
                        <th class="py-2.5 font-semibold">Versi</th>
                        <th class="py-2.5 font-semibold">Mime Type</th>
                        <th class="py-2.5 font-semibold">Ukuran</th>
                        <th class="py-2.5 font-semibold">Status Berkas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 dark:text-slate-500">Mahasiswa belum mengunggah dokumen apapun.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td class="py-3 font-semibold text-slate-800 dark:text-slate-200"><?= esc($doc['document_name']) ?></td>
                                <td class="py-3 text-slate-500 dark:text-slate-400 font-mono"><?= esc($doc['original_name']) ?></td>
                                <td class="py-3 text-slate-500 dark:text-slate-400">v<?= esc($doc['version']) ?></td>
                                <td class="py-3 text-slate-500 dark:text-slate-400 font-mono"><?= esc($doc['mime_type']) ?></td>
                                <td class="py-3 text-slate-500 dark:text-slate-400"><?= esc($doc['file_size_kb']) ?> KB</td>
                                <td class="py-3">
                                    <?php 
                                    $docStatus = $doc['status'];
                                    $docLabel = ucwords(str_replace('_', ' ', $docStatus));
                                    $docColor = 'bg-slate-50 dark:bg-slate-800/40 text-slate-700 dark:text-slate-400 border-slate-150/50 dark:border-slate-800/50';
                                    if ($docStatus === 'valid') {
                                        $docColor = 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-100/50 dark:border-emerald-900/50';
                                    } elseif ($docStatus === 'perlu_revisi') {
                                        $docColor = 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border-amber-100/50 dark:border-amber-900/50';
                                    } elseif ($docStatus === 'ditolak') {
                                        $docColor = 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border-rose-100/50 dark:border-rose-900/50';
                                    }
                                    ?>
                                    <span class="inline-flex items-center gap-1 font-bold px-2 py-0.5 rounded text-[10px] border <?= $docColor ?>">
                                        <?= esc($docLabel) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
