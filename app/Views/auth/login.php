<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200/60 dark:border-slate-800 overflow-hidden transition-all duration-300 transform hover:shadow-2xl">
    <!-- Top Branding Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8 text-center text-white relative">
        <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-white/10 blur-xl"></div>
        <div class="absolute -left-6 -bottom-6 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
        
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 backdrop-blur-md mb-3">
            <i class="ti ti-activity text-3xl text-white"></i>
        </div>
        <h2 class="text-xl font-bold tracking-tight">Sistem Manajemen KP/KPL</h2>
        <p class="text-xs text-blue-100 mt-1 font-medium">Fakultas Ilmu Komputer - Universitas Almuslim</p>
    </div>

    <!-- Login Form Area -->
    <div class="p-6 md:p-8">
        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Masuk Akun</h3>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Silakan masuk menggunakan surel dan kata sandi Anda.</p>

        <!-- Dynamic Flash Message Banner (Optional if toast fails) -->
        <?php if (session()->getFlashdata('error') && strpos(session()->getFlashdata('error'), '<li>') !== false): ?>
            <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-400 text-xs rounded-lg p-3.5 mt-4">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login/auth') ?>" method="POST" class="mt-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Alamat Surel / Email</label>
                <div class="mt-1.5 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500">
                        <i class="ti ti-mail"></i>
                    </span>
                    <input type="email" name="email" id="email" 
                           value="<?= old('email') ?>"
                           placeholder="nama@unmuslim.ac.id" 
                           class="block w-full pl-9 pr-3 py-2 text-sm bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150" 
                           required />
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Kata Sandi / Password</label>
                    <a href="#" class="text-[11px] font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">Lupa sandi?</a>
                </div>
                <div class="mt-1.5 relative" x-data="{ show: false }">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500">
                        <i class="ti ti-lock"></i>
                    </span>
                    <input :type="show ? 'text' : 'password'" name="password" id="password" 
                           placeholder="••••••••" 
                           class="block w-full pl-9 pr-10 py-2 text-sm bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150" 
                           required />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none">
                        <i class="ti" :class="show ? 'ti-eye-off' : 'ti-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- Keep Logged In Checkbox -->
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-805 text-blue-600 focus:ring-blue-500/30 transition" />
                <label for="remember" class="ml-2 block text-xs text-slate-500 dark:text-slate-400 font-medium select-none cursor-pointer">Ingat saya di perangkat ini</label>
            </div>

            <!-- Action Button -->
            <button type="submit" class="w-full flex justify-center items-center gap-2 py-2 px-4 border border-transparent rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 active:bg-blue-800 transition duration-150 mt-6 shadow-md shadow-blue-500/10">
                <span>Masuk Sekarang</span>
                <i class="ti ti-arrow-right"></i>
            </button>
        </form>
    </div>

    <!-- Demo Accounts Toggle Drawer -->
    <div x-data="{ open: false }" class="border-t border-slate-100 dark:border-slate-800/60 bg-slate-50 dark:bg-slate-900/60 p-4">
        <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-semibold hover:text-slate-800 dark:hover:text-slate-200 focus:outline-none transition">
            <span class="flex items-center gap-1.5">
                <i class="ti ti-help-circle text-sm"></i> Akun Demo Pengujian (Copy-Paste)
            </span>
            <i class="ti text-sm transition-transform duration-200" :class="open ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
        </button>
        
        <div x-show="open" x-collapse class="mt-3 space-y-2.5 text-xs text-slate-600 dark:text-slate-400 border-t border-slate-200/50 dark:border-slate-800/50 pt-3" x-cloak>
            <div class="grid grid-cols-1 gap-2">
                <!-- Admin -->
                <div class="flex items-center justify-between p-2 bg-white dark:bg-slate-850 rounded-lg border border-slate-200/60 dark:border-slate-800 shadow-sm">
                    <div>
                        <span class="font-bold text-slate-700 dark:text-slate-350 capitalize">Admin:</span>
                        <code class="ml-1 text-[11px] bg-slate-100 dark:bg-slate-800 px-1 rounded text-blue-600 dark:text-blue-400">admin@unmuslim.ac.id</code>
                    </div>
                    <button onclick="copyToClipboard('admin@unmuslim.ac.id')" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 text-[10px] font-semibold hover:underline">Salin</button>
                </div>
                <!-- Koordinator -->
                <div class="flex items-center justify-between p-2 bg-white dark:bg-slate-850 rounded-lg border border-slate-200/60 dark:border-slate-800 shadow-sm">
                    <div>
                        <span class="font-bold text-slate-700 dark:text-slate-350 capitalize">Koordinator:</span>
                        <code class="ml-1 text-[11px] bg-slate-100 dark:bg-slate-800 px-1 rounded text-blue-600 dark:text-blue-400">koordinator@unmuslim.ac.id</code>
                    </div>
                    <button onclick="copyToClipboard('koordinator@unmuslim.ac.id')" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 text-[10px] font-semibold hover:underline">Salin</button>
                </div>
                <!-- Mahasiswa -->
                <div class="flex items-center justify-between p-2 bg-white dark:bg-slate-850 rounded-lg border border-slate-200/60 dark:border-slate-800 shadow-sm">
                    <div>
                        <span class="font-bold text-slate-700 dark:text-slate-350 capitalize">Mahasiswa:</span>
                        <code class="ml-1 text-[11px] bg-slate-100 dark:bg-slate-800 px-1 rounded text-blue-600 dark:text-blue-400">mahasiswa@unmuslim.ac.id</code>
                    </div>
                    <button onclick="copyToClipboard('mahasiswa@unmuslim.ac.id')" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 text-[10px] font-semibold hover:underline">Salin</button>
                </div>
                <!-- Dosen -->
                <div class="flex items-center justify-between p-2 bg-white dark:bg-slate-850 rounded-lg border border-slate-200/60 dark:border-slate-800 shadow-sm">
                    <div>
                        <span class="font-bold text-slate-700 dark:text-slate-350 capitalize">Dosen:</span>
                        <code class="ml-1 text-[11px] bg-slate-100 dark:bg-slate-800 px-1 rounded text-blue-600 dark:text-blue-400">dosen@unmuslim.ac.id</code>
                    </div>
                    <button onclick="copyToClipboard('dosen@unmuslim.ac.id')" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 text-[10px] font-semibold hover:underline">Salin</button>
                </div>
                <!-- Instansi -->
                <div class="flex items-center justify-between p-2 bg-white dark:bg-slate-850 rounded-lg border border-slate-200/60 dark:border-slate-800 shadow-sm">
                    <div>
                        <span class="font-bold text-slate-700 dark:text-slate-350 capitalize">Instansi:</span>
                        <code class="ml-1 text-[11px] bg-slate-100 dark:bg-slate-800 px-1 rounded text-blue-600 dark:text-blue-400">instansi@technology.com</code>
                    </div>
                    <button onclick="copyToClipboard('instansi@technology.com')" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 text-[10px] font-semibold hover:underline">Salin</button>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 italic text-center mt-1">Kata sandi default untuk seluruh akun di atas adalah: <strong class="text-slate-500">password</strong></p>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Surel Berhasil Disalin',
                text: text,
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            document.getElementById('email').value = text;
            document.getElementById('password').value = 'password';
        }).catch(err => {
            console.error('Gagal menyalin: ', err);
        });
    }
</script>
<?= $this->endSection() ?>
