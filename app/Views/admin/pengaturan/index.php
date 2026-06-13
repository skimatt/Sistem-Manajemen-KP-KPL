<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Pengaturan Sistem</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Konfigurasikan preferensi sistem informasi, limitasi nilai akademik, dan konfigurasi email server.</p>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-xl text-xs text-emerald-700 dark:text-emerald-450 flex items-center gap-2">
            <i class="ti ti-circle-check text-base"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl text-xs text-red-700 dark:text-red-400 flex items-center gap-2">
            <i class="ti ti-alert-circle text-base"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Config Form -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <form action="<?= base_url('admin/pengaturan/save') ?>" method="POST" class="space-y-6 text-xs">
            <?= csrf_field() ?>

            <!-- Section 1: General Preferences -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <i class="ti ti-settings text-base text-slate-400"></i>
                    Pengaturan Umum Aplikasi
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">Nama Instansi / Aplikasi <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="app_name" 
                               value="<?= old('app_name', $settings['app_name']) ?>" 
                               class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['app_name']) ? 'border-red-500' : '' ?>" 
                               required />
                        <?php if (isset(session('errors')['app_name'])): ?>
                            <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['app_name'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">Status Operasional <span class="text-red-500">*</span></label>
                        <select name="system_status" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500" required>
                            <option value="open" <?= old('system_status', $settings['system_status']) === 'open' ? 'selected' : '' ?>>Aktif (Terbuka)</option>
                            <option value="maintenance" <?= old('system_status', $settings['system_status']) === 'maintenance' ? 'selected' : '' ?>>Pemeliharaan (Maintenance)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Academic Thresholds -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <i class="ti ti-school text-base text-slate-400"></i>
                    Batas Persyaratan Akademik Mahasiswa
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">IPK Minimal Kelayakan KP/KPL <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01" 
                               name="gpa_minimum" 
                               value="<?= old('gpa_minimum', $settings['gpa_minimum']) ?>" 
                               class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['gpa_minimum']) ? 'border-red-500' : '' ?>" 
                               required />
                        <?php if (isset(session('errors')['gpa_minimum'])): ?>
                            <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['gpa_minimum'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">Jumlah SKS Minimal Kelayakan <span class="text-red-500">*</span></label>
                        <input type="number" 
                               name="sks_minimum" 
                               value="<?= old('sks_minimum', $settings['sks_minimum']) ?>" 
                               class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['sks_minimum']) ? 'border-red-500' : '' ?>" 
                               required />
                        <?php if (isset(session('errors')['sks_minimum'])): ?>
                            <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['sks_minimum'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Section 3: SMTP Email Config -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <i class="ti ti-mail text-base text-slate-400"></i>
                    Konfigurasi Server SMTP (Pengiriman Notifikasi)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">SMTP Host</label>
                        <input type="text" 
                               name="smtp_host" 
                               value="<?= old('smtp_host', $settings['smtp_host']) ?>" 
                               placeholder="Contoh: smtp.gmail.com"
                               class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">SMTP Port</label>
                        <input type="number" 
                               name="smtp_port" 
                               value="<?= old('smtp_port', $settings['smtp_port']) ?>" 
                               placeholder="Contoh: 587"
                               class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">SMTP Username (Email Address)</label>
                        <input type="email" 
                               name="smtp_user" 
                               value="<?= old('smtp_user', $settings['smtp_user']) ?>" 
                               placeholder="alamatemail@gmail.com"
                               class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 <?= isset(session('errors')['smtp_user']) ? 'border-red-500' : '' ?>" />
                        <?php if (isset(session('errors')['smtp_user'])): ?>
                            <p class="text-red-500 mt-1 text-[10px]"><?= session('errors')['smtp_user'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-350">SMTP Password</label>
                        <input type="password" 
                               name="smtp_pass" 
                               value="<?= old('smtp_pass', $settings['smtp_pass']) ?>" 
                               placeholder="••••••••••••••"
                               class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition">
                    Simpan Pengaturan Sistem
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
