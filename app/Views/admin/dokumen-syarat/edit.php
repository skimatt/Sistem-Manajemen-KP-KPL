<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/dokumen-syarat') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Dokumen Persyaratan</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ubah definisi dokumen persyaratan dengan ID: <?= esc($requirement['id']) ?>.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <form action="<?= base_url('admin/dokumen-syarat/update/' . $requirement['id']) ?>" method="POST" class="space-y-5 text-xs">
            <?= csrf_field() ?>

            <!-- Field: period_id -->
            <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300">Periode Akademik <span class="text-red-500">*</span></label>
                <select name="period_id" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['period_id']) ? 'border-red-500' : '' ?>" required>
                    <option value="" disabled>-- Pilih Periode --</option>
                    <?php foreach ($periods as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= old('period_id', $requirement['period_id']) == $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= esc(str_replace('_', ' ', $p['status'])) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset(session('errors')['period_id'])): ?>
                    <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['period_id'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Field: document_name & document_code -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Nama Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="document_name" value="<?= old('document_name', $requirement['document_name']) ?>" placeholder="Contoh: Bukti Pembayaran Registrasi" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['document_name']) ? 'border-red-500' : '' ?>" required />
                    <?php if (isset(session('errors')['document_name'])): ?>
                        <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['document_name'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Kode Dokumen <span class="text-red-500">*</span></label>
                    <input type="text" name="document_code" value="<?= old('document_code', $requirement['document_code']) ?>" placeholder="Contoh: BUKTI_BAYAR" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['document_code']) ? 'border-red-500' : '' ?>" required />
                    <?php if (isset(session('errors')['document_code'])): ?>
                        <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['document_code'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Field: allowed_extensions & max_size_kb -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Ekstensi Diperbolehkan <span class="text-red-500">*</span></label>
                    <input type="text" name="allowed_extensions" value="<?= old('allowed_extensions', $requirement['allowed_extensions']) ?>" placeholder="Contoh: pdf,jpg,jpeg,png" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['allowed_extensions']) ? 'border-red-500' : '' ?>" required />
                    <span class="text-[10px] text-slate-400 mt-1 block">Pisahkan dengan koma tanpa spasi.</span>
                    <?php if (isset(session('errors')['allowed_extensions'])): ?>
                        <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['allowed_extensions'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Ukuran File Maksimal (KB) <span class="text-red-500">*</span></label>
                    <input type="number" name="max_size_kb" value="<?= old('max_size_kb', $requirement['max_size_kb']) ?>" placeholder="Contoh: 10240" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['max_size_kb']) ? 'border-red-500' : '' ?>" required />
                    <?php if (isset(session('errors')['max_size_kb'])): ?>
                        <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['max_size_kb'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Field: is_required, stage, sort_order -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Sifat Persyaratan <span class="text-red-500">*</span></label>
                    <select name="is_required" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                        <option value="1" <?= old('is_required', $requirement['is_required']) == 1 ? 'selected' : '' ?>>Wajib Diisi</option>
                        <option value="0" <?= old('is_required', $requirement['is_required']) == 0 ? 'selected' : '' ?>>Opsional</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Tahapan Alur <span class="text-red-500">*</span></label>
                    <select name="stage" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                        <option value="registrasi" <?= old('stage', $requirement['stage']) === 'registrasi' ? 'selected' : '' ?>>Registrasi Awal</option>
                        <option value="penempatan" <?= old('stage', $requirement['stage']) === 'penempatan' ? 'selected' : '' ?>>Penempatan</option>
                        <option value="penilaian" <?= old('stage', $requirement['stage']) === 'penilaian' ? 'selected' : '' ?>>Penilaian</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Urutan Tampil <span class="text-red-500">*</span></label>
                    <input type="number" name="sort_order" value="<?= old('sort_order', $requirement['sort_order']) ?>" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required />
                </div>
            </div>

            <!-- Field: status -->
            <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300">Status Keaktifan <span class="text-red-500">*</span></label>
                <select name="status" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                    <option value="active" <?= old('status', $requirement['status']) === 'active' ? 'selected' : '' ?>>Aktif (Tampil)</option>
                    <option value="inactive" <?= old('status', $requirement['status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <a href="<?= base_url('admin/dokumen-syarat') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-755 dark:hover:text-slate-200 rounded-lg font-semibold transition">
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
