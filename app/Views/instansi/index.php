<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Kerja Praktek Lapangan (KPL) Mitra</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pantau daftar mahasiswa Universitas Almuslim yang ditempatkan di instansi Anda.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Mahasiswa Magang -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">
                <i class="ti ti-users text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Mahasiswa Aktif</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($stats['total_students']) ?></p>
            </div>
        </div>

        <!-- Card 2: Menunggu Konfirmasi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                <i class="ti ti-circle-check text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Menunggu Konfirmasi</p>
                <p class="text-lg font-bold text-slate-800 dark:text-slate-100 mt-0.5"><?= esc($stats['pending_placements']) ?></p>
            </div>
        </div>

        <!-- Card 3: Bidang Instansi -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400">
                <i class="ti ti-briefcase text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Bidang Usaha</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-0.5 truncate max-w-[150px]" title="<?= esc($stats['field_category']) ?>">
                    <?= esc($stats['field_category']) ?>
                </p>
            </div>
        </div>

        <!-- Card 4: Kemitraan -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                <i class="ti ti-award text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status Mitra</p>
                <span class="inline-flex items-center gap-1 font-semibold text-xs mt-1 text-emerald-700 dark:text-emerald-400 uppercase bg-emerald-50 dark:bg-emerald-950/40 px-1.5 py-0.5 rounded">
                    <?= esc($stats['partnership']) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Active Students placed -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-1.5">
                <i class="ti ti-users-group text-slate-400 dark:text-slate-500"></i> Mahasiswa Magang Aktif
            </h2>
            <a href="<?= base_url('instansi/mahasiswa') ?>" class="text-[11px] font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">Lihat Detail</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-300">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800 uppercase text-[9px] tracking-wider">
                        <th class="py-2.5 font-semibold">Nama Mahasiswa</th>
                        <th class="py-2.5 font-semibold">NPM</th>
                        <th class="py-2.5 font-semibold">Status Kegiatan</th>
                        <th class="py-2.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500">Tidak ada mahasiswa magang aktif saat ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="py-3 font-semibold text-slate-800 dark:text-slate-200"><?= esc($student['full_name']) ?></td>
                                <td class="py-3 text-slate-500 dark:text-slate-400 font-mono"><?= esc($student['npm']) ?></td>
                                <td class="py-3">
                                    <span class="inline-flex items-center gap-1 font-medium px-2 py-0.5 rounded text-[10px] bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50">
                                        Aktif Magang
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="<?= base_url('instansi/penilaian') ?>" class="inline-flex items-center justify-center px-2.5 py-1 bg-blue-600 text-white rounded text-[11px] font-semibold hover:bg-blue-700 shadow shadow-blue-500/10 transition">
                                        Beri Nilai
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
