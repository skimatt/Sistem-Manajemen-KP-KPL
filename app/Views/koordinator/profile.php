<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Profil Saya</h1>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Perbarui data profil diri dan kelola pengaturan kata sandi akun Koordinator Anda.</p>
    </div>

    <!-- Alert success/error -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-xs border border-emerald-100 dark:border-emerald-950/30 font-semibold">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 text-xs border border-rose-100 dark:border-rose-950/30 font-semibold">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 text-xs border border-rose-100 dark:border-rose-950/30 space-y-1">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <div><i class="ti ti-alert-circle text-xs mr-1"></i> <?= esc($err) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Profile Form Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <form action="<?= base_url('koordinator/profile/update') ?>" method="POST" class="space-y-4 text-xs" id="profileForm">
            <?= csrf_field() ?>

            <!-- Biodata section -->
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 mb-4">
                Informasi Akun
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block font-bold text-slate-700 dark:text-slate-350">Nama Lengkap</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="<?= esc(old('name', $user['name'])) ?>" 
                           placeholder="Nama Lengkap" 
                           class="block w-full mt-1.5 px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-950/30 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>

                <!-- Role -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-350">Peran Pengguna (Role)</label>
                    <input type="text" 
                           value="<?= strtoupper(esc($user['role'])) ?>" 
                           disabled 
                           class="block w-full mt-1.5 px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-lg bg-slate-50 dark:bg-slate-950/60 text-slate-400 dark:text-slate-500 font-semibold cursor-not-allowed focus:outline-none" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-bold text-slate-700 dark:text-slate-350">Alamat Email</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="<?= esc(old('email', $user['email'])) ?>" 
                           placeholder="email@domain.com" 
                           class="block w-full mt-1.5 px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-950/30 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block font-bold text-slate-700 dark:text-slate-350">Nomor Telepon / HP</label>
                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           value="<?= esc(old('phone', $user['phone'])) ?>" 
                           placeholder="08xxxxxxxx" 
                           class="block w-full mt-1.5 px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-950/30 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="pt-4" x-data="{ changePass: false }">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2 mb-4">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                        Ubah Kata Sandi (Password)
                    </h3>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" @change="changePass = !changePass" class="sr-only peer" />
                        <div class="w-7 h-4 bg-slate-200 dark:bg-slate-800 rounded-full peer peer-focus:ring-0 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-2 text-[10px] font-bold text-slate-500 dark:text-slate-400">Aktifkan</span>
                    </label>
                </div>

                <!-- Pass fields (Expandable) -->
                <div x-show="changePass" x-collapse style="display: none;" class="space-y-4 pt-1">
                    <div class="p-3.5 bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-950/30 rounded-xl leading-relaxed">
                        <i class="ti ti-info-circle text-sm mr-1"></i>
                        Isi form kata sandi baru di bawah ini hanya apabila Anda ingin mengubah kata sandi masuk untuk akun Anda. Minimal panjang kata sandi adalah 8 karakter.
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block font-bold text-slate-700 dark:text-slate-350">Kata Sandi Baru</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   placeholder="••••••••" 
                                   class="block w-full mt-1.5 px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-950/30 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label for="password_confirm" class="block font-bold text-slate-700 dark:text-slate-350">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" 
                                   name="password_confirm" 
                                   id="password_confirm" 
                                   placeholder="••••••••" 
                                   class="block w-full mt-1.5 px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-950/30 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-sm shadow-blue-500/10 transition flex items-center gap-1.5">
                    <i class="ti ti-device-floppy text-base"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Apakah Anda yakin ingin memperbarui data profil Anda?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>
