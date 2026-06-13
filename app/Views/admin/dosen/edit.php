<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/dosen') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Dosen</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi profil akademik dan kredensial akun dosen bimbingan.</p>
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
        <form action="<?= base_url('admin/dosen/update/' . $lecturer['id']) ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Segment: Akademik -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Informasi Akademik</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- NIDN -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">NIDN / NIP <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="nidn" 
                               value="<?= old('nidn', $lecturer['nidn']) ?>" 
                               placeholder="Contoh: 0102030405"
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
                                <option value="<?= $prodi['id'] ?>" <?= old('study_program_id', $lecturer['study_program_id']) == $prodi['id'] ? 'selected' : '' ?>>
                                    <?= esc($prodi['name']) ?> (<?= esc($prodi['kp_label']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Bidang Keahlian -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Bidang Keahlian</label>
                        <input type="text" 
                               name="expertise" 
                               value="<?= old('expertise', $lecturer['expertise']) ?>" 
                               placeholder="Contoh: Software Engineering"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- Kuota Bimbingan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Kuota Maksimal Bimbingan <span class="text-red-550">*</span></label>
                        <input type="number" 
                               name="max_supervision_quota" 
                               value="<?= old('max_supervision_quota', $lecturer['max_supervision_quota']) ?>" 
                               placeholder="Contoh: 10"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Status Ketersediaan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Status Ketersediaan <span class="text-red-550">*</span></label>
                        <select name="is_available" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="1" <?= old('is_available', $lecturer['is_available']) == '1' ? 'selected' : '' ?>>Tersedia (Bisa Menerima Bimbingan)</option>
                            <option value="0" <?= old('is_available', $lecturer['is_available']) == '0' ? 'selected' : '' ?>>Penuh (Tidak Menerima Bimbingan)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Segment: Identitas & Akun -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Biodata & Akun Login</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Lengkap -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nama Lengkap Dosen (Beserta Gelar) <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="full_name" 
                               value="<?= old('full_name', $lecturer['full_name']) ?>" 
                               placeholder="Contoh: Rahmat, S.T., M.T."
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Email Login <span class="text-red-550">*</span></label>
                        <input type="email" 
                               name="email" 
                               value="<?= old('email', $user['email']) ?>" 
                               placeholder="Contoh: dosen@unimus.ac.id"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Password Akun (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" 
                               name="password" 
                               placeholder="Masukkan password baru untuk merubah"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nomor HP</label>
                        <input type="text" 
                               name="phone" 
                               value="<?= old('phone', $user['phone']) ?>" 
                               placeholder="Contoh: 08123456789"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>
                </div>
            </div>

            <!-- Submit Button Wrapper -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <a href="<?= base_url('admin/dosen') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg text-xs font-semibold transition">
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
