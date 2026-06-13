<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/verifikasi-administrasi') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Dokumen Mahasiswa</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Periksa keabsahan berkas fisik yang diupload oleh <?= esc($registration['full_name']) ?>.</p>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <span class="block text-slate-400 dark:text-slate-550">Mahasiswa</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block"><?= esc($registration['full_name']) ?> (<?= esc($registration['npm']) ?>)</span>
            </div>
            <div>
                <span class="block text-slate-400 dark:text-slate-550">Program Studi / Tipe</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block"><?= esc($registration['prodi_name']) ?> (<?= esc($registration['prodi_code']) ?>)</span>
            </div>
            <div>
                <span class="block text-slate-400 dark:text-slate-550">Periode Akademik / Status</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block"><?= esc($registration['period_name']) ?> / <span class="capitalize"><?= str_replace('_', ' ', $registration['current_status']) ?></span></span>
            </div>
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

    <!-- Document Loop Section -->
    <div class="space-y-4">
        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-3">Daftar Dokumen</h3>

        <?php if (empty($documents)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center shadow-sm">
                <span class="text-xs text-slate-400 dark:text-slate-500 italic block">Mahasiswa belum mengunggah dokumen persyaratan apapun.</span>
            </div>
        <?php else: ?>
            <?php foreach ($documents as $doc): ?>
                <div x-data="{ showVerifyForm: false }" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="h-9 w-9 rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg flex-shrink-0">
                                <i class="ti ti-file-text"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-850 dark:text-slate-200 leading-normal"><?= esc($doc['document_name']) ?></h4>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Tipe: <?= strtoupper($doc['file_ext']) ?> | Ukuran: <?= esc($doc['file_size_kb']) ?> KB | Versi: <?= esc($doc['version']) ?></span>
                            </div>
                        </div>

                        <!-- Action Buttons and Badges -->
                        <div class="flex items-center gap-3">
                            <?php
                            $docColors = [
                                'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border-amber-100 dark:border-amber-900/40',
                                'valid' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/40',
                                'perlu_revisi' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/30 dark:text-amber-450 border-amber-100 dark:border-amber-900/30',
                                'ditolak' => 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 border-red-100 dark:border-red-900/40',
                            ];
                            $docColor = $docColors[$doc['status']] ?? 'bg-slate-50 text-slate-650';
                            ?>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold border capitalize <?= $docColor ?>">
                                <?= esc(str_replace('_', ' ', $doc['status'])) ?>
                            </span>

                            <div class="flex items-center gap-1.5">
                                <a href="<?= base_url('admin/verifikasi-administrasi/download-dokumen/' . $doc['id']) ?>" target="_blank" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-[10px] font-bold transition flex items-center gap-1">
                                    <i class="ti ti-download text-xs"></i> Unduh File
                                </a>
                                <button @click="showVerifyForm = !showVerifyForm" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold transition flex items-center gap-1">
                                    <i class="ti ti-checklist text-xs"></i> Verifikasi
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Verification logs notes -->
                    <?php if ($doc['verification_note']): ?>
                        <div class="p-3 bg-slate-50 dark:bg-slate-950/40 border border-slate-200/50 dark:border-slate-800 rounded-lg text-xs leading-relaxed">
                            <span class="font-bold text-slate-500 dark:text-slate-400 block mb-0.5 text-[10px] uppercase">Catatan Verifikator:</span>
                            <p class="text-slate-700 dark:text-slate-350 italic">"<?= esc($doc['verification_note']) ?>"</p>
                        </div>
                    <?php endif; ?>

                    <!-- Inline Verification Form -->
                    <div x-show="showVerifyForm" x-collapse x-cloak class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <form action="<?= base_url('admin/verifikasi-administrasi/update-document/' . $doc['id']) ?>" method="POST" class="space-y-4">
                            <?= csrf_field() ?>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Status Toggle -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Status Verifikasi <span class="text-red-550">*</span></label>
                                    <select name="status" 
                                            class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                            required>
                                        <option value="valid" <?= $doc['status'] === 'valid' ? 'selected' : '' ?>>Valid (Sesuai Syarat)</option>
                                        <option value="perlu_revisi" <?= $doc['status'] === 'perlu_revisi' ? 'selected' : '' ?>>Perlu Revisi (Kembalikan)</option>
                                        <option value="ditolak" <?= $doc['status'] === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                    </select>
                                </div>

                                <!-- Verification Notes -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Catatan / Alasan Perbaikan</label>
                                    <input type="text" 
                                           name="verification_note" 
                                           value="<?= esc($doc['verification_note']) ?>" 
                                           placeholder="Wajib diisi jika Perlu Revisi / Ditolak (misal: tanda tangan buram)"
                                           class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2.5">
                                <button type="button" @click="showVerifyForm = false" class="px-3.5 py-1.5 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg text-[10px] font-semibold transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-semibold shadow-sm transition">
                                    Simpan Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
