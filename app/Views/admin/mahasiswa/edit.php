<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/mahasiswa') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Mahasiswa</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi biodata akademik dan akun login mahasiswa.</p>
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
        <form action="<?= base_url('admin/mahasiswa/update/' . $student['id']) ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Segment: Akademik -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Informasi Akademik</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- NPM -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">NPM (Nomor Pokok Mahasiswa) <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="npm" 
                               value="<?= old('npm', $student['npm']) ?>" 
                               placeholder="Contoh: 180102034"
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
                                <option value="<?= $prodi['id'] ?>" <?= old('study_program_id', $student['study_program_id']) == $prodi['id'] ? 'selected' : '' ?>>
                                    <?= esc($prodi['name']) ?> (<?= esc($prodi['kp_label']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Angkatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Tahun Angkatan <span class="text-red-550">*</span></label>
                        <input type="number" 
                               name="generation_year" 
                               value="<?= old('generation_year', $student['generation_year']) ?>" 
                               placeholder="Contoh: 2023"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Semester -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Semester Saat Ini <span class="text-red-550">*</span></label>
                        <input type="number" 
                               name="current_semester" 
                               value="<?= old('current_semester', $student['current_semester']) ?>" 
                               placeholder="Contoh: 6"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>
                </div>
            </div>

            <!-- Segment: Identitas Diri -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Biodata Personal</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Lengkap -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nama Lengkap <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="full_name" 
                               value="<?= old('full_name', $student['full_name']) ?>" 
                               placeholder="Nama lengkap sesuai KTP"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Jenis Kelamin</label>
                        <select name="gender" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20">
                            <option value="">-- Pilih Gender --</option>
                            <option value="L" <?= old('gender', $student['gender']) === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= old('gender', $student['gender']) === 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nomor HP</label>
                        <input type="text" 
                               name="phone" 
                               value="<?= old('phone', $student['phone']) ?>" 
                               placeholder="Contoh: 08123456789"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>
                </div>
            </div>

            <!-- Segment: Akun Login -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Informasi Akun Login</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Email Login <span class="text-red-550">*</span></label>
                        <input type="email" 
                               name="email" 
                               value="<?= old('email', $user['email']) ?>" 
                               placeholder="Contoh: budi@gmail.com"
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
                </div>
            </div>

            <!-- Submit Button Wrapper -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <a href="<?= base_url('admin/mahasiswa') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg text-xs font-semibold transition">
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
