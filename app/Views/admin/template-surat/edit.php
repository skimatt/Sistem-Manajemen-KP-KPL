<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/template-surat') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Template Surat</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ubah definisi template surat ID: <?= esc($template['id']) ?>.</p>
        </div>
    </div>

    <!-- Alert / Guide Card -->
    <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/40 rounded-xl p-4 text-xs text-slate-700 dark:text-slate-300">
        <div class="flex gap-2">
            <i class="ti ti-info-circle text-base text-blue-500 mt-0.5 flex-shrink-0"></i>
            <div>
                <h5 class="font-bold text-slate-800 dark:text-slate-200 mb-1">Panduan Penggunaan Placeholder</h5>
                <p class="leading-relaxed mb-2">Gunakan penanda kurung kurawal berikut dalam konten HTML agar digantikan otomatis oleh sistem saat generate PDF:</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 font-mono text-[10px] text-blue-700 dark:text-blue-400 bg-white dark:bg-slate-950/40 p-2.5 rounded-lg border border-blue-100 dark:border-blue-900/20">
                    <div>{npm}</div>
                    <div>{nama_mahasiswa}</div>
                    <div>{prodi}</div>
                    <div>{periode}</div>
                    <div>{dosen_pembimbing}</div>
                    <div>{nama_instansi}</div>
                    <div>{nilai_akhir}</div>
                    <div>{grade}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <form action="<?= base_url('admin/template-surat/update/' . $template['id']) ?>" method="POST" class="space-y-5 text-xs">
            <?= csrf_field() ?>

            <!-- Field: name & code -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Nama Template <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?= old('name', $template['name']) ?>" placeholder="Contoh: Surat Rekomendasi Pembimbing Akademik" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['name']) ? 'border-red-500' : '' ?>" required />
                    <?php if (isset(session('errors')['name'])): ?>
                        <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['name'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Kode Template (Unique) <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="<?= old('code', $template['code']) ?>" placeholder="Contoh: SURAT_REK_PA" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['code']) ? 'border-red-500' : '' ?>" required />
                    <?php if (isset(session('errors')['code'])): ?>
                        <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['code'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Field: document_type, version, status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Jenis Dokumen <span class="text-red-500">*</span></label>
                    <select name="document_type" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                        <option value="rekomendasi_pa" <?= old('document_type', $template['document_type']) === 'rekomendasi_pa' ? 'selected' : '' ?>>Surat Rekomendasi PA</option>
                        <option value="permohonan_mitra" <?= old('document_type', $template['document_type']) === 'permohonan_mitra' ? 'selected' : '' ?>>Surat Permohonan Mitra</option>
                        <option value="tugas_pembimbing" <?= old('document_type', $template['document_type']) === 'tugas_pembimbing' ? 'selected' : '' ?>>Surat Tugas Pembimbing</option>
                        <option value="rekap_nilai" <?= old('document_type', $template['document_type']) === 'rekap_nilai' ? 'selected' : '' ?>>Rekap Nilai Akhir</option>
                        <option value="lainnya" <?= old('document_type', $template['document_type']) === 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Versi Template <span class="text-red-500">*</span></label>
                    <input type="number" name="version" value="<?= old('version', $template['version']) ?>" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required />
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                        <option value="draft" <?= old('status', $template['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="active" <?= old('status', $template['status']) === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= old('status', $template['status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        <option value="archived" <?= old('status', $template['status']) === 'archived' ? 'selected' : '' ?>>Diarsipkan</option>
                    </select>
                </div>
            </div>

            <!-- Field: content_html -->
            <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300">Konten HTML Template <span class="text-red-500">*</span></label>
                <textarea name="content_html" rows="12" placeholder="Tuliskan struktur HTML..." class="block w-full mt-1.5 px-3 py-2 font-mono bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['content_html']) ? 'border-red-500' : '' ?>" required><?= old('content_html', $template['content_html']) ?></textarea>
                <?php if (isset(session('errors')['content_html'])): ?>
                    <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['content_html'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <a href="<?= base_url('admin/template-surat') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-755 dark:hover:text-slate-200 rounded-lg font-semibold transition">
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
