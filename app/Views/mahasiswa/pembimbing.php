<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Dosen Pembimbing Saya</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Informasi lengkap dosen pembimbing akademik yang ditugaskan membimbing KP/KPL Anda.</p>
    </div>

    <!-- Supervisor Details Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-6">
        <?php if (!$supervisor): ?>
            <div class="text-center py-8 space-y-2">
                <div class="h-12 w-12 bg-slate-100 dark:bg-slate-800/60 rounded-full flex items-center justify-center text-slate-400 dark:text-slate-500 mx-auto">
                    <i class="ti ti-school text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Pembimbing Belum Ditetapkan</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                    Dosen pembimbing akademik belum ditunjuk oleh Koordinator Program Studi. Penetapan dilakukan setelah surat balasan instansi selesai diverifikasi.
                </p>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
                <!-- Lecturer Initial Icon -->
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 dark:bg-blue-500 text-white font-bold text-lg shadow">
                    <i class="ti ti-school"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="font-bold text-slate-800 dark:text-slate-100 text-sm"><?= esc($supervisor->lecturer_name) ?></h2>
                    <p class="text-[11px] text-slate-400 dark:text-slate-550">NIDN/NIP. <?= esc($supervisor->nidn ?? '-') ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <!-- Expertise -->
                <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-lg p-3">
                    <span class="text-slate-400 dark:text-slate-500 block mb-0.5">Bidang Keahlian:</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-250"><?= esc($supervisor->expertise ?? 'Ilmu Komputer / Umum') ?></span>
                </div>

                <!-- Assigned Time -->
                <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-lg p-3">
                    <span class="text-slate-400 dark:text-slate-500 block mb-0.5">Tanggal Penetapan:</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-250"><?= date('d M Y', strtotime($supervisor->assigned_at)) ?></span>
                </div>

                <!-- Email Contact -->
                <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-lg p-3">
                    <span class="text-slate-400 dark:text-slate-500 block mb-0.5">Alamat Email:</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-250"><?= esc($supervisor->email ?? '-') ?></span>
                </div>

                <!-- Phone Contact -->
                <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-lg p-3">
                    <span class="text-slate-400 dark:text-slate-500 block mb-0.5">Nomor HP/WhatsApp:</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-250"><?= esc($supervisor->phone ?? '-') ?></span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
