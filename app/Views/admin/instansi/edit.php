<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto" x-data="{ hasAccount: <?= old('has_account', $instansi['has_account']) == 1 ? 'true' : 'false' ?> }">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/instansi') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Instansi</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui profil instansi mitra/mandiri dan pengelolaan akun sistem.</p>
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
    </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <form action="<?= base_url('admin/instansi/update/' . $instansi['id']) ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Segment: Detail Instansi -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Profil Instansi</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Instansi -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nama Instansi / Perusahaan <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="<?= old('name', $instansi['name']) ?>" 
                               placeholder="Contoh: PT. Teknologi Nusantara"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Tipe -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Tipe Instansi <span class="text-red-550">*</span></label>
                        <select name="type" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="mitra" <?= old('type', $instansi['type']) === 'mitra' ? 'selected' : '' ?>>Mitra Kampus (Resmi)</option>
                            <option value="mandiri" <?= old('type', $instansi['type']) === 'mandiri' ? 'selected' : '' ?>>Mandiri (Usulan Mahasiswa)</option>
                        </select>
                    </div>

                    <!-- Bidang Usaha -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Bidang Usaha</label>
                        <input type="text" 
                               name="field_category" 
                               value="<?= old('field_category', $instansi['field_category']) ?>" 
                               placeholder="Contoh: IT Consultant, BUMN, Perbankan"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- Status Kemitraan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Status Kemitraan <span class="text-red-550">*</span></label>
                        <select name="partnership_status" 
                                class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                                required>
                            <option value="active" <?= old('partnership_status', $instansi['partnership_status']) === 'active' ? 'selected' : '' ?>>Aktif (Kerja Sama Terjalin)</option>
                            <option value="pending" <?= old('partnership_status', $instansi['partnership_status']) === 'pending' ? 'selected' : '' ?>>Pending (Dalam Proses Pengajuan)</option>
                            <option value="inactive" <?= old('partnership_status', $instansi['partnership_status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Segment: Alamat -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Lokasi & Alamat</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Kabupaten/Kota -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Kabupaten / Kota <span class="text-red-550">*</span></label>
                        <input type="text" 
                               name="city" 
                               value="<?= old('city', $instansi['city']) ?>" 
                               placeholder="Contoh: Bireuen"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               required />
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Kecamatan</label>
                        <input type="text" 
                               name="district" 
                               value="<?= old('district', $instansi['district']) ?>" 
                               placeholder="Contoh: Kota Juang"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Provinsi</label>
                        <input type="text" 
                               name="province" 
                               value="<?= old('province', $instansi['province']) ?>" 
                               placeholder="Contoh: Aceh"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Alamat Lengkap <span class="text-red-550">*</span></label>
                        <textarea name="address" 
                                  rows="2" 
                                  placeholder="Nama Jalan, Nomor, Gedung/Blok"
                                  class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20"
                                  required><?= old('address', $instansi['address']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Segment: Kontak Person -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Detail Kontak Person / HRD</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Kontak -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Nama Kontak</label>
                        <input type="text" 
                               name="contact_person" 
                               value="<?= old('contact_person', $instansi['contact_person']) ?>" 
                               placeholder="Contoh: Hendra Saputra"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- Jabatan Kontak -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Jabatan Kontak</label>
                        <input type="text" 
                               name="contact_position" 
                               value="<?= old('contact_position', $instansi['contact_position']) ?>" 
                               placeholder="Contoh: HRD / Supervisor"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- No HP Kontak -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">No. HP Kontak</label>
                        <input type="text" 
                               name="contact_phone" 
                               value="<?= old('contact_phone', $instansi['contact_phone']) ?>" 
                               placeholder="Contoh: 0811223344"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <!-- Email Kontak -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Email Kontak</label>
                        <input type="email" 
                               name="contact_email" 
                               value="<?= old('contact_email', $instansi['contact_email']) ?>" 
                               placeholder="Contoh: hrd@perusahaan.com"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>
                </div>
            </div>

            <!-- Segment: Akun Login -->
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Akun Login Sistem</h3>
                    <div>
                        <select name="has_account" 
                                x-on:change="hasAccount = ($event.target.value === '1')"
                                class="px-2 py-0.5 text-[10px] font-semibold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-750 rounded-lg text-slate-700 dark:text-slate-300 focus:outline-none">
                            <option value="0" <?= old('has_account', $instansi['has_account']) == 0 ? 'selected' : '' ?>>Nonaktifkan Akun</option>
                            <option value="1" <?= old('has_account', $instansi['has_account']) == 1 ? 'selected' : '' ?>>Aktifkan Akun</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="hasAccount" x-transition x-cloak>
                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-350">Email Login Instansi <span class="text-red-550">*</span></label>
                        <input type="email" 
                               name="email" 
                               value="<?= old('email', $user ? $user['email'] : '') ?>" 
                               placeholder="Contoh: login@perusahaan.com"
                               class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" 
                               :required="hasAccount" />
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
                <a href="<?= base_url('admin/instansi') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg text-xs font-semibold transition">
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
