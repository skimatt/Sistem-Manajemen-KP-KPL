<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 md:p-12 text-center max-w-xl mx-auto shadow-sm mt-8">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 mb-4">
        <i class="ti ti-tool text-3xl"></i>
    </div>
    
    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Fitur Sedang Dikembangkan</h1>
    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto leading-relaxed">
        Menu <strong><?= esc($menuName) ?></strong> sedang dalam tahap integrasi backend. Halaman ini akan segera tersedia setelah tahapan alur utama selesai diuji.
    </p>
    
    <div class="mt-6 flex justify-center gap-3">
        <a href="<?= base_url(esc(session()->get('role') ?? 'mahasiswa') . '/dashboard') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 shadow-md shadow-blue-500/10 transition">
            <i class="ti ti-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
<?= $this->endSection() ?>
