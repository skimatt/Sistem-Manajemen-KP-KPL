<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Profil Saya</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola data profil akademik, bidang keahlian, nomor kontak, serta kata sandi akun Anda.</p>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-xs">
        <!-- Profile details card (2 Cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 space-y-6">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center gap-1.5">
                <i class="ti ti-user-edit text-blue-500"></i> Sunting Data Profil & Kontak
            </h3>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 p-3 rounded-lg border border-emerald-100/50 dark:border-emerald-900/50 font-bold">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 p-3 rounded-lg border border-rose-100/50 dark:border-rose-900/50 font-bold">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 p-3 rounded-lg border border-rose-100/50 dark:border-rose-900/50">
                    <ul class="list-disc pl-4 space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="profile_form" action="<?= base_url('dosen/profile/update') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Nama Lengkap Dosen <span class="text-rose-500">*</span></label>
                        <input type="text" id="profile_name" name="name" value="<?= old('name', $user['name']) ?>" class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500 font-bold" required />
                    </div>
                    <div>
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">NIDN / NIP</label>
                        <input type="text" value="<?= esc($lecturer->nidn ?? '-') ?>" class="block w-full p-2.5 bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-750 rounded-lg text-slate-500 dark:text-slate-400 font-mono focus:outline-none cursor-not-allowed" readonly disabled />
                    </div>
                    <div>
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" id="profile_email" name="email" value="<?= old('email', $user['email']) ?>" class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500" required />
                    </div>
                    <div>
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Nomor HP / Telepon <span class="text-rose-500">*</span></label>
                        <input type="text" id="profile_phone" name="phone" value="<?= old('phone', $user['phone']) ?>" class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500 font-mono" required />
                    </div>
                    <div class="md:col-span-2">
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Bidang Keahlian / Kepakaran</label>
                        <input type="text" id="profile_expertise" name="expertise" value="<?= old('expertise', $lecturer->expertise ?? '') ?>" placeholder="Misal: Data Mining, Rekayasa Perangkat Lunak, Jaringan..." class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                    </div>
                </div>

                <h4 class="font-bold text-slate-800 dark:text-slate-250 border-t border-slate-100 dark:border-slate-800 pt-4 flex items-center gap-1.5">
                    <i class="ti ti-key text-slate-400"></i> Ubah Kata Sandi Akun
                </h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Kosongkan jika Anda tidak ingin mengubah kata sandi login saat ini.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Kata Sandi Baru</label>
                        <input type="password" id="profile_password" name="password" placeholder="Minimal 8 karakter" class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="font-bold text-slate-600 dark:text-slate-400 block mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="profile_password_confirm" name="password_confirm" placeholder="Ulangi kata sandi baru" class="block w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" id="btn_save_profile" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow shadow-blue-500/10 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Lecturer Info Side Card (1 Col) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl p-5 shadow-sm transition-colors duration-300 space-y-4">
            <h3 class="font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2">Informasi Akademik</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Program Studi Homebase</label>
                    <?php 
                    $prodiName = 'Tidak terasosiasi';
                    if ($lecturer && !empty($prodis)) {
                        foreach ($prodis as $p) {
                            if ($p->id == $lecturer->study_program_id) {
                                $prodiName = $p->name;
                                break;
                            }
                        }
                    }
                    ?>
                    <p class="font-bold text-slate-850 dark:text-slate-200 mt-0.5"><?= esc($prodiName) ?></p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Kuota Maksimal Bimbingan</label>
                    <p class="font-bold text-slate-850 dark:text-slate-200 mt-0.5"><?= esc($lecturer->max_supervision_quota ?? 10) ?> Mahasiswa</p>
                </div>
                <div>
                    <label class="text-slate-400 dark:text-slate-505 uppercase tracking-wider text-[9px] block">Status Ketersediaan</label>
                    <span class="inline-flex items-center gap-1 font-bold px-2 py-0.5 rounded text-[10px] border mt-1.5 <?= ($lecturer->is_available ?? 1) ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50' : 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50' ?>">
                        <?= ($lecturer->is_available ?? 1) ? 'Menerima Bimbingan' : 'Kuota Penuh / Tidak Aktif' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
