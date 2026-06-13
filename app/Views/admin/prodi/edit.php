<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/prodi') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Program Studi</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ubah informasi program studi: <?= esc($prodi['name']) ?>.</p>
        </div>
    </div>

    <!-- Error Alert Banner -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl text-xs text-red-700 dark:text-red-400 space-y-1">
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
        <form action="<?= base_url('admin/prodi/update/' . $prodi['id']) ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Segment: Detail Program Studi -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Detail Program Studi</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kode Prodi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Kode Program Studi <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="code" 
                               value="<?= old('code', $prodi['code']) ?>" 
                               placeholder="Contoh: IF, TI, MI"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Nama Prodi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nama Program Studi <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="<?= old('name', $prodi['name']) ?>" 
                               placeholder="Contoh: Informatika"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Fakultas -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Fakultas <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="faculty" 
                               value="<?= old('faculty', $prodi['faculty']) ?>" 
                               placeholder="Contoh: Fakultas Ilmu Komputer"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Label Penugasan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Tipe Kegiatan / Penugasan <span class="text-red-550">*</span></label>
                        <select name="kp_label" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="KP" <?= old('kp_label', $prodi['kp_label']) === 'KP' ? 'selected' : '' ?>>KP (Kerja Praktek)</option>
                            <option value="KPL" <?= old('kp_label', $prodi['kp_label']) === 'KPL' ? 'selected' : '' ?>>KPL (Kerja Praktek Lapangan)</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Status Program Studi <span class="text-red-550">*</span></label>
                        <select name="status" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="active" <?= old('status', $prodi['status']) === 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= old('status', $prodi['status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button Wrapper -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <a href="<?= base_url('admin/prodi') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
