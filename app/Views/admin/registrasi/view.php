<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/registrasi') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Registrasi Mahasiswa</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Peninjauan berkas pendaftaran dan biodata mahasiswa.</p>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Biodata and Academic Stats -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card: Biodata -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-5">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Biodata Mahasiswa</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Nama Lengkap</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block"><?= esc($registration['full_name']) ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">NPM (Nomor Pokok Mahasiswa)</span>
                            <span class="font-semibold text-slate-850 dark:text-slate-100 font-mono mt-0.5 block"><?= esc($registration['npm']) ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Program Studi</span>
                            <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= esc($registration['prodi_name'] ?? '-') ?> (<?= esc($registration['prodi_code'] ?? '-') ?>)</span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Email / No HP</span>
                            <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= esc($registration['email']) ?> / <?= esc($registration['phone'] ?: '-') ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Tempat, Tanggal Lahir</span>
                            <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= esc($registration['birth_place'] ?: '-') ?>, <?= $registration['birth_date'] ? date('d F Y', strtotime($registration['birth_date'])) : '-' ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Jenis Kelamin / Agama</span>
                            <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= $registration['gender'] === 'L' ? 'Laki-laki' : ($registration['gender'] === 'P' ? 'Perempuan' : '-') ?> / <?= esc($registration['religion'] ?: '-') ?></span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="block text-slate-400 dark:text-slate-550">Alamat Rumah</span>
                            <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= esc($registration['address'] ?: '-') ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Nama Orang Tua/Wali</span>
                            <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= esc($registration['parent_name'] ?: '-') ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Nomor HP Orang Tua/Wali</span>
                            <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= esc($registration['parent_phone'] ?: '-') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Data Akademik -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-5">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Syarat Kelayakan Akademik</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mb-5">
                        <div class="p-3 bg-slate-50 dark:bg-slate-950/40 rounded-lg border border-slate-200/50 dark:border-slate-800">
                            <span class="block text-slate-400 dark:text-slate-550 text-[10px] uppercase font-bold tracking-wider">Jumlah SKS Terlaksana</span>
                            <span class="font-mono text-lg font-bold text-slate-800 dark:text-slate-100 mt-1 block"><?= esc($registration['academic_sks']) ?> SKS</span>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-950/40 rounded-lg border border-slate-200/50 dark:border-slate-800">
                            <span class="block text-slate-400 dark:text-slate-550 text-[10px] uppercase font-bold tracking-wider">IPK Terakhir</span>
                            <span class="font-mono text-lg font-bold text-slate-800 dark:text-slate-100 mt-1 block"><?= esc($registration['academic_gpa']) ?></span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <span class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2">Checklist Mata Kuliah Wajib:</span>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= $registration['is_gpa_eligible'] ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">IPK Minimal (>= 2.50)</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= $registration['passed_basic_programming'] ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">Lulus Pemrograman Dasar</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= $registration['passed_data_structure'] ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">Lulus Struktur Data</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= $registration['passed_database'] ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">Lulus Basis Data</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= $registration['passed_system_analysis'] ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">Lulus Analisis Sistem (APSI)</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= $registration['passed_networking'] ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">Lulus Jaringan Komputer</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= $registration['passed_concentration_course'] ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">Lulus Mata Kuliah Konsentrasi</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <i class="ti <?= !empty($registration['education_payment_status']) ? 'ti-circle-check text-emerald-600' : 'ti-circle-x text-rose-500' ?> text-lg"></i>
                                <span class="text-slate-700 dark:text-slate-300">Status Pembayaran Pendidikan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Uploaded Documents -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Berkas yang Diunggah</h3>
                
                <div class="space-y-3.5">
                    <?php if (empty($documents)): ?>
                        <p class="text-xs text-slate-400 dark:text-slate-500 italic py-2">Belum ada dokumen yang diunggah mahasiswa.</p>
                    <?php else: ?>
                        <?php foreach ($documents as $doc): ?>
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800/60 rounded-xl text-xs">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-base">
                                        <i class="ti ti-file-text"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-800 dark:text-slate-200 leading-none"><?= esc($doc['document_name']) ?></h4>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Tipe: <?= strtoupper($doc['file_ext']) ?> | Ukuran: <?= esc($doc['file_size_kb']) ?> KB</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <?php
                                    $docColors = [
                                        'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border-amber-100 dark:border-amber-900/40',
                                        'valid' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/40',
                                        'perlu_revisi' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-450 border-amber-100 dark:border-amber-900/30',
                                        'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 border-red-100 dark:border-red-900/40',
                                    ];
                                    $docColor = $docColors[$doc['status']] ?? 'bg-slate-50 text-slate-650';
                                    ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border capitalize <?= $docColor ?>">
                                        <?= esc(str_replace('_', ' ', $doc['status'])) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Col 3: Status details and workflow timeline -->
        <div class="space-y-6">
            <!-- Card: Status Registrasi -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2">Status Proses</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Tahap Saat Ini</span>
                        <div class="mt-1.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-semibold border capitalize <?= $color ?>">
                                <?= esc(str_replace('_', ' ', $registration['current_status'])) ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Tahun Akademik / Semester</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-250 mt-1 block"><?= esc($registration['academic_year']) ?> - <?= esc($registration['semester']) ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Dosen Wali (PA)</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-250 mt-1 block"><?= esc($registration['academic_advisor_name'] ?: '-') ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Rekomendasi Dosen PA</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-250 mt-1 block capitalize"><?= esc($registration['advisor_recommendation_status'] ?: '-') ?></span>
                    </div>
                </div>
            </div>

            <!-- Card: Timeline Logs -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2">Riwayat Status</h3>
                
                <div class="relative border-l border-slate-200 dark:border-slate-800 pl-4 space-y-4 text-xs">
                    <?php if (empty($statusLogs)): ?>
                        <p class="text-slate-400 dark:text-slate-500 italic py-1">Belum ada riwayat perubahan status.</p>
                    <?php else: ?>
                        <?php foreach ($statusLogs as $log): ?>
                            <div class="relative">
                                <!-- Marker Bullet -->
                                <div class="absolute -left-[22px] top-1 h-2 w-2 rounded-full bg-blue-500 border border-white dark:border-slate-900"></div>
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 capitalize"><?= esc(str_replace('_', ' ', $log['new_status'])) ?></span>
                                        <span class="text-[9px] text-slate-400 dark:text-slate-500 font-mono"><?= date('d M H:i', strtotime($log['created_at'])) ?></span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-550"><?= esc($log['note'] ?: 'Perubahan status sistem.') ?></p>
                                    <span class="text-[9px] font-semibold text-slate-450 dark:text-slate-500 block">Oleh: <?= esc($log['user_name'] ?? 'System') ?> (<?= esc($log['changed_by_role']) ?>)</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
