<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Profil Saya</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Lengkapi informasi biodata dan berkas akademik Anda agar memenuhi syarat pendaftaran KP/KPL.</p>
    </div>

    <!-- Profile Form -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 mb-4">Informasi Biodata</h2>
        
        <form class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nama Lengkap</label>
                    <input type="text" class="block w-full mt-1.5 px-3 py-2 text-xs bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed focus:outline-none" value="<?= esc($profile->full_name ?? session()->get('name')) ?>" disabled />
                </div>
                
                <!-- NPM -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">NPM (Nomor Pokok Mahasiswa)</label>
                    <input type="text" class="block w-full mt-1.5 px-3 py-2 text-xs bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed focus:outline-none" value="<?= esc($profile->npm ?? '') ?>" disabled />
                </div>
                
                <!-- Birth Place -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Tempat Lahir</label>
                    <input type="text" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" value="<?= esc($profile->birth_place ?? '') ?>" />
                </div>

                <!-- Birth Date -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Tanggal Lahir</label>
                    <input type="date" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" value="<?= esc($profile->birth_date ?? '') ?>" />
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Jenis Kelamin</label>
                    <select class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20">
                        <option class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200" value="L" <?= ($profile && $profile->gender === 'L') ? 'selected' : '' ?>>Laki-laki</option>
                        <option class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200" value="P" <?= ($profile && $profile->gender === 'P') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <!-- Religion -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Agama</label>
                    <input type="text" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" value="<?= esc($profile->religion ?? '') ?>" />
                </div>
            </div>

            <!-- Address Textarea -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Alamat Lengkap (KTP)</label>
                <textarea rows="3" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20"><?= esc($profile->address ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Phone -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nomor HP Aktif</label>
                    <input type="text" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" value="<?= esc($profile->phone ?? '') ?>" />
                </div>

                <!-- Parent name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nama Orang Tua / Wali</label>
                    <input type="text" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" value="<?= esc($profile->parent_name ?? '') ?>" />
                </div>
            </div>

            <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 pt-4 mb-4">Informasi Akademik</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Year of generation -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Tahun Angkatan</label>
                    <input type="number" class="block w-full mt-1.5 px-3 py-2 text-xs bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed focus:outline-none" value="<?= esc($profile->generation_year ?? 2023) ?>" disabled />
                </div>
                <!-- Semester -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Semester Saat Ini</label>
                    <input type="number" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" value="<?= esc($profile->current_semester ?? 6) ?>" />
                </div>
                <!-- Profile Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Kelengkapan Profil</label>
                    <?php 
                    $badgeColor = ($profileStatus === 'complete') 
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50' 
                        : 'bg-red-50 text-red-700 border-red-100 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/50';
                    ?>
                    <span class="inline-flex items-center gap-1 font-semibold px-2 py-1 rounded text-[10px] border mt-2.5 <?= $badgeColor ?>">
                        <?= ($profileStatus === 'complete') ? 'Sudah Lengkap' : 'Belum Lengkap' ?>
                    </span>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="Swal.fire({icon: 'success', title: 'Data Disimpan', text: 'Data profil berhasil diperbarui secara lokal.', confirmButtonColor: '#3b82f6'})" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs shadow shadow-blue-500/10 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
