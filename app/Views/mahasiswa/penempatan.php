<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Jalur Penempatan KP/KPL</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pilih salah satu metode penempatan instansi pelaksanaan Kerja Praktek / Kuliah Kerja Lapangan Anda.</p>
    </div>

    <!-- Pathway Chooser Grid -->
    <form action="<?= base_url('mahasiswa/penempatan/choose-type') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Mitra Kampus Card -->
            <label class="relative block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:border-blue-500 dark:hover:border-blue-400 cursor-pointer transition group">
                <input type="radio" name="placement_type" value="mitra" class="sr-only peer" checked />
                
                <!-- Border styling on selection -->
                <div class="absolute inset-0 border-2 border-transparent rounded-xl peer-checked:border-blue-500 dark:peer-checked:border-blue-400 pointer-events-none transition"></div>
                
                <div class="space-y-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 transition group-hover:scale-105 shadow-sm">
                        <i class="ti ti-chart-bar text-xl"></i>
                    </div>
                    
                    <div class="space-y-1">
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm flex items-center gap-1.5">
                            Mitra Kampus 
                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 text-[8px] font-bold uppercase tracking-wide">TOPSIS</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Pilih instansi dari daftar kerja sama resmi Fakultas. Sistem membantu menyusun rekomendasi kecocokan berdasarkan kriteria keahlian dan kuota dengan metode TOPSIS.
                        </p>
                    </div>
                </div>
            </label>

            <!-- Tempat Mandiri Card -->
            <label class="relative block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:border-emerald-500 dark:hover:border-emerald-450 cursor-pointer transition group">
                <input type="radio" name="placement_type" value="mandiri" class="sr-only peer" />
                
                <!-- Border styling on selection -->
                <div class="absolute inset-0 border-2 border-transparent rounded-xl peer-checked:border-emerald-500 dark:peer-checked:border-emerald-450 pointer-events-none transition"></div>
                
                <div class="space-y-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 transition group-hover:scale-105 shadow-sm">
                        <i class="ti ti-map-pin text-xl"></i>
                    </div>
                    
                    <div class="space-y-1">
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Tempat Mandiri</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Ajukan sendiri instansi/mitra target di luar daftar kerja sama kampus. Anda wajib mengunggah bukti komunikasi awal, dan memvalidasi kelayakan instansi ke Koordinator.
                        </p>
                    </div>
                </div>
            </label>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow shadow-blue-500/10">
                Lanjutkan Tahap Penempatan <i class="ti ti-arrow-right text-xs"></i>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
