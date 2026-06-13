<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/form-builder') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Template Formulir</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ubah definisi template form ID: <?= esc($template['id']) ?>.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <form action="<?= base_url('admin/form-builder/update/' . $template['id']) ?>" method="POST" class="space-y-5 text-xs">
            <?= csrf_field() ?>

            <!-- Field: name -->
            <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300">Nama Formulir <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?= old('name', $template['name']) ?>" placeholder="Contoh: Form Evaluasi Kinerja Mahasiswa" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['name']) ? 'border-red-500' : '' ?>" required />
                <?php if (isset(session('errors')['name'])): ?>
                    <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['name'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Field: form_type & version -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Tipe Formulir <span class="text-red-500">*</span></label>
                    <select name="form_type" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                        <option value="registration" <?= old('form_type', $template['form_type']) === 'registration' ? 'selected' : '' ?>>Pendaftaran (Registrasi)</option>
                        <option value="logbook" <?= old('form_type', $template['form_type']) === 'logbook' ? 'selected' : '' ?>>Logbook Mingguan</option>
                        <option value="assessment" <?= old('form_type', $template['form_type']) === 'assessment' ? 'selected' : '' ?>>Penilaian Akhir</option>
                        <option value="general" <?= old('form_type', $template['form_type']) === 'general' ? 'selected' : '' ?>>Umum / Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Versi Formulir <span class="text-red-500">*</span></label>
                    <input type="number" name="version" value="<?= old('version', $template['version']) ?>" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required />
                </div>
            </div>

            <!-- Field: period_id & status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Terikat Periode Akademik</label>
                    <select name="period_id" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20">
                        <option value="">-- Semua / Global (Tidak Terikat) --</option>
                        <?php foreach ($periods as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= old('period_id', $template['period_id']) == $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                        <option value="draft" <?= old('status', $template['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="active" <?= old('status', $template['status']) === 'active' ? 'selected' : '' ?>>Aktif (Tampil)</option>
                        <option value="inactive" <?= old('status', $template['status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        <option value="archived" <?= old('status', $template['status']) === 'archived' ? 'selected' : '' ?>>Diarsipkan</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <a href="<?= base_url('admin/form-builder') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-755 dark:hover:text-slate-200 rounded-lg font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
