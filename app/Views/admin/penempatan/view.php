<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/penempatan') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Pengajuan Penempatan</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Peninjauan detail pengisian pengajuan penempatan Kerja Praktek.</p>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Student details and Institution details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Biodata -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Pengusul</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Nama Lengkap</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block"><?= esc($placement['full_name']) ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">NPM / Prodi</span>
                        <span class="font-semibold text-slate-805 dark:text-slate-105 font-mono mt-0.5 block"><?= esc($placement['npm']) ?> / <?= esc($placement['prodi_name'] ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Periode Akademik</span>
                        <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block"><?= esc($placement['period_name']) ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Jalur Penempatan</span>
                        <div class="mt-1">
                            <?php if ($placement['placement_type'] === 'mitra'): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/40">
                                    Mitra Kampus
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/40">
                                    Tempat Mandiri
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institution details -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2">Informasi Instansi Target</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                    <div class="sm:col-span-2">
                        <span class="block text-slate-400 dark:text-slate-550">Nama Instansi</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">
                            <?= $placement['placement_type'] === 'mitra' ? esc($placement['partner_institution_name'] ?? '-') : esc($placement['proposed_institution_name'] ?? '-') ?>
                        </span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="block text-slate-400 dark:text-slate-550">Alamat Instansi</span>
                        <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block">
                            <?= $placement['placement_type'] === 'mitra' ? '-' : esc($placement['proposed_address'] ?: '-') ?>
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Kategori Bidang</span>
                        <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block">
                            <?= $placement['placement_type'] === 'mitra' ? '-' : esc($placement['proposed_field'] ?: '-') ?>
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Hubungan Kontak (CP)</span>
                        <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block">
                            <?= esc($placement['contact_person'] ?: '-') ?> (<?= esc($placement['contact_position'] ?: '-') ?>)
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Nomor CP / Telepon</span>
                        <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block">
                            <?= esc($placement['contact_phone'] ?: '-') ?>
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Email Kontak</span>
                        <span class="font-medium text-slate-750 dark:text-slate-250 mt-0.5 block">
                            <?= esc($placement['contact_email'] ?: '-') ?>
                        </span>
                    </div>
                </div>

                <div class="pt-2 text-xs">
                    <span class="block text-slate-400 dark:text-slate-550">Alasan Pemilihan</span>
                    <p class="mt-1.5 p-3 bg-slate-50 dark:bg-slate-950/40 rounded-lg border border-slate-200/50 dark:border-slate-800/80 text-slate-700 dark:text-slate-300 italic leading-relaxed">
                        "<?= esc($placement['reason'] ?: 'Tidak ada alasan yang disematkan.') ?>"
                    </p>
                </div>
            </div>

            <!-- Priority choices for Mitra -->
            <?php if ($placement['placement_type'] === 'mitra'): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Urutan Pilihan Prioritas</h3>
                    
                    <div class="space-y-3">
                        <?php if (empty($choices)): ?>
                            <p class="text-xs text-slate-450 dark:text-slate-500 italic">Tidak ada list pilihan alternatif.</p>
                        <?php else: ?>
                            <?php foreach ($choices as $choice): ?>
                                <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800/60 rounded-xl text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-6 w-6 rounded bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 flex items-center justify-center font-bold text-[10px]">
                                            #<?= esc($choice['priority_order']) ?>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 dark:text-slate-200"><?= esc($choice['institution_name']) ?></h4>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 block"><?= esc($choice['institution_address']) ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <?php if ($choice['is_selected'] == 1): ?>
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/40">
                                                Penempatan Akhir
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Col 3: Reviewer and Decision Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2">Keputusan Review</h3>
                
                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="block text-slate-400 dark:text-slate-550">Status Pengajuan</span>
                        <div class="mt-1.5">
                            <?php
                            $statusColors = [
                                'draft' => 'bg-slate-50 text-slate-650 dark:bg-slate-800/40 dark:text-slate-405 border-slate-200 dark:border-slate-800',
                                'diajukan' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/40',
                                'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
                                'perlu_revisi' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/30 dark:text-amber-450 dark:border-amber-900/30',
                                'ditolak' => 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/40',
                                'dibatalkan' => 'bg-slate-100 text-slate-600 border-slate-250 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                            ];
                            $color = $statusColors[$placement['status']] ?? 'bg-slate-50 text-slate-650';
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-semibold border capitalize <?= $color ?>">
                                <?= esc(str_replace('_', ' ', $placement['status'])) ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($placement['reviewed_at']): ?>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Reviewer (Koordinator)</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200 mt-1 block"><?= esc($placement['reviewer_name'] ?? 'System') ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Tanggal Ditinjau</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-250 mt-1 block font-mono"><?= date('d M Y H:i', strtotime($placement['reviewed_at'])) ?></span>
                        </div>
                        <div>
                            <span class="block text-slate-400 dark:text-slate-550">Catatan Review</span>
                            <p class="mt-1.5 p-2.5 bg-slate-50 dark:bg-slate-950/40 rounded-lg border border-slate-200/50 dark:border-slate-800 text-slate-600 dark:text-slate-400 italic leading-relaxed">
                                "<?= esc($placement['review_note'] ?: 'Tidak ada catatan review.') ?>"
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="p-3 bg-slate-50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800/60 rounded-xl text-center">
                            <span class="text-slate-400 dark:text-slate-500 italic block">Menunggu peninjauan dari Koordinator KP/KPL.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
