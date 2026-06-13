<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/periode') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Tambah Periode</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Buat periode akademik baru untuk pelaksanaan Kerja Praktek (KP) atau Kerja Praktek Lapangan (KPL).</p>
        </div>
    </div>

    <!-- Error Alert Banner -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl text-xs text-red-700 dark:text-red-450 space-y-1">
            <h4 class="font-bold flex items-center gap-1.5 mb-1.5"><i class="ti ti-alert-circle"></i> Perbaiki kesalahan berikut:</h4>
            <ul class="list-disc list-inside space-y-1">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <form action="<?= base_url('admin/periode/store') ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Segment: Metadata Periode -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Informasi Utama</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Periode -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nama Periode Akademik <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="<?= old('name') ?>" 
                               placeholder="Contoh: KP Teknik Informatika Genap 2025/2026"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Program Studi <span class="text-red-550">*</span></label>
                        <select name="study_program_id" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="">-- Pilih Program Studi --</option>
                            <?php foreach ($prodis as $prodi): ?>
                                <option value="<?= $prodi['id'] ?>" <?= old('study_program_id') == $prodi['id'] ? 'selected' : '' ?>>
                                    <?= esc($prodi['name']) ?> (<?= esc($prodi['kp_label']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tipe Kegiatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Tipe Kegiatan / Penugasan <span class="text-red-550">*</span></label>
                        <select name="activity_type" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="KP" <?= old('activity_type') === 'KP' ? 'selected' : '' ?>>KP (Kerja Praktek)</option>
                            <option value="KPL" <?= old('activity_type') === 'KPL' ? 'selected' : '' ?>>KPL (Kerja Praktek Lapangan)</option>
                        </select>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Harus cocok dengan label penugasan Program Studi yang dipilih.</span>
                    </div>

                    <!-- Tahun Akademik -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Tahun Akademik <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="academic_year" 
                               value="<?= old('academic_year', date('Y') . '/' . (date('Y') + 1)) ?>" 
                               placeholder="Contoh: 2025/2026"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Semester -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Semester <span class="text-red-550">*</span></label>
                        <select name="semester" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="Ganjil" <?= old('semester') === 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                            <option value="Genap" <?= old('semester') === 'Genap' ? 'selected' : '' ?>>Genap</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Segment: Penjadwalan -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Penjadwalan Periode</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Pendaftaran Mulai -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Mulai Pendaftaran <span class="text-red-550">*</span></label>
                        <input type="date" 
                               name="registration_start" 
                               value="<?= old('registration_start') ?>" 
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Pendaftaran Selesai -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Selesai Pendaftaran <span class="text-red-550">*</span></label>
                        <input type="date" 
                               name="registration_end" 
                               value="<?= old('registration_end') ?>" 
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Pelaksanaan Mulai -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Mulai Kegiatan <span class="text-red-550">*</span></label>
                        <input type="date" 
                               name="activity_start" 
                               value="<?= old('activity_start') ?>" 
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Pelaksanaan Selesai -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Selesai Kegiatan <span class="text-red-550">*</span></label>
                        <input type="date" 
                               name="activity_end" 
                               value="<?= old('activity_end') ?>" 
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>
                </div>
            </div>

            <!-- Segment: Status -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Status Awal</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Status Periode <span class="text-red-550">*</span></label>
                        <select name="status" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="draft" <?= old('status') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="aktif" <?= old('status', 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="ditutup" <?= old('status') === 'ditutup' ? 'selected' : '' ?>>Ditutup</option>
                            <option value="diarsipkan" <?= old('status') === 'diarsipkan' ? 'selected' : '' ?>>Diarsipkan</option>
                        </select>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Catatan: Mengaktifkan periode baru akan otomatis menonaktifkan periode aktif lain pada program studi & tipe kegiatan yang sama.</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button Wrapper -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <a href="<?= base_url('admin/periode') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                    Simpan Periode
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
