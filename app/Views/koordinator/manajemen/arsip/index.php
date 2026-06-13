<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Arsip KP/KPL</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Daftar periode kegiatan akademik yang telah ditutup dan diarsipkan secara permanen.</p>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="arsipKoorTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Nama Periode</th>
                            <th class="px-6 py-3.5 font-bold">Program Studi</th>
                            <th class="px-6 py-3.5 font-bold text-center">Tipe Kegiatan</th>
                            <th class="px-6 py-3.5 font-bold text-center">Jadwal Pelaksanaan</th>
                            <th class="px-6 py-3.5 font-bold text-center">Total Mahasiswa</th>
                            <th class="px-6 py-3.5 font-bold text-center">Status</th>
                            <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($periods)): ?>
                            <?php foreach ($periods as $p): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($p['name']) ?>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-600 dark:text-slate-350">
                                        <?= esc($p['prodi_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-blue-50 text-blue-700 dark:bg-blue-950/45 dark:text-blue-400">
                                            <?= esc($p['activity_type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                        <?= date('d/m/Y', strtotime($p['activity_start'])) ?> s/d <?= date('d/m/Y', strtotime($p['activity_end'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-800 dark:text-slate-300">
                                        <?= $p['total_students'] ?> Mahasiswa
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                            Terkunci
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= base_url('koordinator/arsip/view/' . $p['id']) ?>" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded font-bold transition text-[10px]">
                                            <i class="ti ti-folder-open text-xs"></i> Lihat Detail Arsip
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#arsipKoorTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            columnDefs: [
                { orderable: false, targets: 6 }
            ]
        });
    });
</script>
<?= $this->endSection() ?>
