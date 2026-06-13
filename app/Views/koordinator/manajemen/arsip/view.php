<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('koordinator/arsip') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-base"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Arsip Periode</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Melihat data historis terkunci untuk kegiatan KP/KPL mahasiswa.</p>
        </div>
    </div>

    <!-- Metadata Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
            <i class="ti ti-info-circle text-base text-purple-500"></i>
            Informasi Periode Akademik Terkunci
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-400 dark:text-slate-500 block">Nama Periode</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($period['name']) ?></span>
            </div>
            <div>
                <span class="text-slate-400 dark:text-slate-500 block">Tipe Kegiatan / Semester</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5 uppercase"><?= esc($period['activity_type']) ?> (Semester <?= esc($period['semester']) ?> - <?= esc($period['academic_year']) ?>)</span>
            </div>
            <div>
                <span class="text-slate-400 dark:text-slate-500 block">Jadwal Pelaksanaan</span>
                <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= date('d M Y', strtotime($period['activity_start'])) ?> s/d <?= date('d M Y', strtotime($period['activity_end'])) ?></span>
            </div>
            <div>
                <span class="text-slate-400 dark:text-slate-500 block">Status Kontrol</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                    <i class="ti ti-archive text-xs"></i> Locked Archive
                </span>
            </div>
        </div>
    </div>

    <!-- Student List Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="detailArsipTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-4 py-3.5 font-bold text-center w-12">No</th>
                            <th class="px-4 py-3.5 font-bold">NPM</th>
                            <th class="px-4 py-3.5 font-bold">Nama Lengkap</th>
                            <th class="px-4 py-3.5 font-bold">Instansi Penempatan</th>
                            <th class="px-4 py-3.5 font-bold">Dosen Pembimbing</th>
                            <th class="px-4 py-3.5 font-bold text-center">Nilai Akhir</th>
                            <th class="px-4 py-3.5 font-bold text-center">Grade</th>
                            <th class="px-4 py-3.5 font-bold text-center">Berkas Laporan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($students)): ?>
                            <?php $no = 1; foreach ($students as $s): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-4 py-4 text-center font-semibold text-slate-400">
                                        <?= $no++ ?>
                                    </td>
                                    <td class="px-4 py-4 font-semibold text-slate-700 dark:text-slate-350">
                                        <?= esc($s['npm']) ?>
                                    </td>
                                    <td class="px-4 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($s['full_name']) ?>
                                    </td>
                                    <td class="px-4 py-4 text-slate-500 dark:text-slate-450 font-medium">
                                        <?= esc($s['instansi_name']) ?: '<span class="text-slate-300 dark:text-slate-650 italic">Tempat Mandiri / Tidak diplot</span>' ?>
                                    </td>
                                    <td class="px-4 py-4 text-slate-500 dark:text-slate-455 font-medium">
                                        <?= esc($s['dosen_name']) ?: '<span class="text-slate-300 dark:text-slate-650 italic">Belum Ditetapkan</span>' ?>
                                    </td>
                                    <td class="px-4 py-4 text-center font-bold text-blue-600 dark:text-blue-450">
                                        <?= $s['final_score'] !== null ? number_format($s['final_score'], 2) : '-' ?>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-2.5 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950/45 dark:text-blue-400 font-black">
                                            <?= esc($s['final_grade']) ?: '-' ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <?php if ($s['report_id']): ?>
                                            <a href="<?= base_url('admin/laporan/download/' . $s['report_id']) ?>" 
                                               class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded font-bold transition text-[10px]"
                                               target="_blank">
                                                <i class="ti ti-download text-xs"></i> Unduh PDF
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-300 dark:text-slate-600 italic">Tidak ada berkas</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#detailArsipTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            columnDefs: [
                { orderable: false, targets: 7 }
            ]
        });
    });
</script>
<?= $this->endSection() ?>
