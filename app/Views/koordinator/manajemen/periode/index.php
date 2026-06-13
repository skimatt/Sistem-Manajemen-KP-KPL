<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Manajemen Periode Akademik</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Kelola siklus status kegiatan KP/KPL prodi (aktif, ditutup, diarsipkan) untuk periode berjalan.</p>
        </div>
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

    <!-- Main Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="periodeKoorTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Nama Periode</th>
                            <th class="px-6 py-3.5 font-bold">Program Studi / Tipe</th>
                            <th class="px-6 py-3.5 font-bold text-center">Registrasi Pendaftaran</th>
                            <th class="px-6 py-3.5 font-bold text-center">Jadwal Pelaksanaan</th>
                            <th class="px-6 py-3.5 font-bold text-center">Mahasiswa Terdaftar</th>
                            <th class="px-6 py-3.5 font-bold text-center">Mahasiswa Aktif</th>
                            <th class="px-6 py-3.5 font-bold">Status</th>
                            <th class="px-6 py-3.5 font-bold text-center">Aksi Siklus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($periods)): ?>
                            <?php foreach ($periods as $p): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($p['name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-500 dark:text-slate-350"><?= esc($p['prodi_name']) ?></div>
                                        <span class="inline-flex mt-1 px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-blue-50 text-blue-700 dark:bg-blue-950/45 dark:text-blue-400">
                                            <?= esc($p['activity_type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                        <?= date('d/m/y', strtotime($p['registration_start'])) ?> s/d <?= date('d/m/y', strtotime($p['registration_end'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                        <?= date('d/m/y', strtotime($p['activity_start'])) ?> s/d <?= date('d/m/y', strtotime($p['activity_end'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-800 dark:text-slate-300">
                                        <?= $p['total_students'] ?> Orang
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold">
                                        <span class="text-blue-600 dark:text-blue-400"><?= $p['active_students'] ?></span> / <?= $p['total_students'] ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClass = 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400';
                                        $statusLabel = esc($p['status']);
                                        if ($p['status'] === 'aktif') {
                                            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                                        } elseif ($p['status'] === 'ditutup') {
                                            $statusClass = 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400';
                                        } elseif ($p['status'] === 'diarsipkan') {
                                            $statusClass = 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 font-bold';
                                            $statusLabel = 'Diarsipkan';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $statusClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($p['status'] === 'draft'): ?>
                                            <form action="<?= base_url('koordinator/periode/update-status/' . $p['id']) ?>" method="POST" class="inline form-status-change" data-title="Aktifkan Periode" data-text="Aktifkan periode ini? Periode lain yang aktif untuk prodi ini akan otomatis ditutup.">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="status" value="aktif">
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded font-bold transition text-[10px]">
                                                    <i class="ti ti-player-play"></i> Aktifkan
                                                </button>
                                            </form>
                                        <?php elseif ($p['status'] === 'aktif'): ?>
                                            <form action="<?= base_url('koordinator/periode/update-status/' . $p['id']) ?>" method="POST" class="inline form-status-change" data-title="Tutup Periode" data-text="Menutup periode akan mematikan registrasi baru, namun mahasiswa terdaftar tetap melanjutkan workflow. Anda yakin?">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="status" value="ditutup">
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded font-bold transition text-[10px]">
                                                    <i class="ti ti-lock"></i> Tutup Periode
                                                </button>
                                            </form>
                                        <?php elseif ($p['status'] === 'ditutup'): ?>
                                            <div class="flex items-center justify-center gap-1.5">
                                                <form action="<?= base_url('koordinator/periode/update-status/' . $p['id']) ?>" method="POST" class="inline form-status-change" data-title="Buka Kembali Periode" data-text="Membuka kembali periode akan mengaktifkannya kembali.">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="status" value="aktif">
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded font-semibold transition text-[10px]">
                                                        <i class="ti ti-rotate-clockwise-2"></i> Buka Kembali
                                                    </button>
                                                </form>
                                                
                                                <form action="<?= base_url('koordinator/periode/update-status/' . $p['id']) ?>" method="POST" class="inline form-status-change" data-title="Arsipkan & Kunci" data-text="PERHATIAN! Mengarsipkan periode akan mengunci seluruh data mahasiswa dan statusnya secara PERMANEN menjadi Read-Only. Tindakan ini tidak dapat dibatalkan!">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="status" value="diarsipkan">
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded font-bold transition text-[10px]">
                                                        <i class="ti ti-archive"></i> Arsipkan & Kunci
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic text-[10px]"><i class="ti ti-circle-check"></i> Arsip Terkunci</span>
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
        $('#periodeKoorTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            columnDefs: [
                { orderable: false, targets: 7 }
            ]
        });

        // Setup confirm prompts on status forms
        $('.form-status-change').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const title = $(form).data('title');
            const text = $(form).data('text');
            const status = $(form).find('input[name="status"]').val();
            
            Swal.fire({
                title: title,
                text: text,
                icon: status === 'diarsipkan' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: status === 'diarsipkan' ? '#9333ea' : '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
