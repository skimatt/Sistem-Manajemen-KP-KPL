<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto" x-data="penetapanPembimbing()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Penetapan Dosen Pembimbing</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Tetapkan dosen pembimbing akademik untuk membimbing mahasiswa selama kegiatan KP/KPL berlangsung.</p>
        </div>
        <!-- Period Filter -->
        <form method="GET" action="<?= base_url('koordinator/penetapan-pembimbing') ?>" id="periodForm" class="flex items-center gap-2 text-xs">
            <span class="font-semibold text-slate-500 dark:text-slate-400">Periode:</span>
            <select name="period_id" onchange="document.getElementById('periodForm').submit()" class="px-3 py-1.5 border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none">
                <?php foreach ($periods as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodId ? 'selected' : '' ?>><?= esc($p['name']) ?> (<?= strtoupper($p['activity_type']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Alert Success/Error -->
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

    <!-- Main List Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="pembimbingTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Mahasiswa</th>
                            <th class="px-6 py-3.5 font-bold">NPM / Prodi</th>
                            <th class="px-6 py-3.5 font-bold">Instansi Penempatan</th>
                            <th class="px-6 py-3.5 font-bold">Dosen Pembimbing</th>
                            <th class="px-6 py-3.5 font-bold">Status</th>
                            <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $stu): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                        <?= esc($stu['full_name']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-500 dark:text-slate-350"><?= esc($stu['npm']) ?></div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($stu['prodi_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                        <?= esc($stu['instansi_name']) ?: '<span class="text-rose-500">Belum Penempatan</span>' ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if (!empty($stu['dosen_name'])): ?>
                                            <div class="font-bold text-slate-800 dark:text-slate-200"><?= esc($stu['dosen_name']) ?></div>
                                        <?php else: ?>
                                            <span class="inline-flex items-center text-rose-500 font-bold gap-1">
                                                <i class="ti ti-alert-circle"></i> Belum Ditetapkan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusClass = 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400';
                                        $statusLabel = esc($stu['current_status']);
                                        if ($stu['current_status'] === 'diterima_instansi') {
                                            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                            $statusLabel = 'Diterima Instansi';
                                        } elseif ($stu['current_status'] === 'sedang_berjalan') {
                                            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                                            $statusLabel = 'Sedang Berjalan';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button @click="openAssignModal(<?= $stu['registration_id'] ?>, '<?= esc($stu['full_name']) ?>', '<?= esc($stu['npm']) ?>', <?= $stu['lecturer_id'] ?: 'null' ?>)" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-950/75 rounded-lg font-semibold transition">
                                            <i class="ti ti-user-plus text-sm"></i>
                                            <?= !empty($stu['dosen_name']) ? 'Ganti Dosen' : 'Tetapkan Dosen' ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assignment Modal (Alpine.js overlay) -->
    <div x-show="isModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition
         x-cloak>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl w-full max-w-md overflow-hidden text-xs" @click.away="closeAssignModal()">
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-150 dark:border-slate-800 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Tetapkan Dosen Pembimbing</h3>
                <button @click="closeAssignModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-250"><i class="ti ti-x text-sm"></i></button>
            </div>
            
            <form action="<?= base_url('koordinator/penetapan-pembimbing/assign') ?>" method="POST" class="p-6 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="registration_id" :value="selectedRegId" />

                <!-- Student Info in Modal -->
                <div class="p-3 bg-slate-50 dark:bg-slate-800/45 border border-slate-200/40 dark:border-slate-800 rounded-lg space-y-1">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nama Mahasiswa:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedStudentName"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">NPM:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-350" x-text="selectedStudentNpm"></span>
                    </div>
                </div>

                <!-- Select Dosen -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-350">Pilih Dosen Pembimbing</label>
                    <select name="lecturer_id" x-model="selectedLecturerId" class="block w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">-- Pilih Dosen Pembimbing --</option>
                        <?php foreach ($lecturers as $lec): ?>
                            <option value="<?= $lec['id'] ?>">
                                <?= esc($lec['full_name']) ?> (Bimbingan: <?= esc($lec['active_bimbingan']) ?>/<?= esc($lec['max_supervision_quota'] ?? 5) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Override Checkbox -->
                <label class="flex items-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-800/20 rounded-lg cursor-pointer">
                    <input type="checkbox" name="override_quota" value="1" class="text-blue-600 rounded" />
                    <span class="text-[10px] text-slate-500 dark:text-slate-450">Bypass kuota maksimum dosen (Centang jika ingin override)</span>
                </label>

                <!-- Note -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 dark:text-slate-350">Catatan/Alasan Pergantian</label>
                    <textarea name="note" rows="3" placeholder="Opsional. Berikan catatan instruksi bimbingan..." class="block w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="closeAssignModal()" class="px-4 py-2 border border-slate-200 dark:border-slate-850 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg font-bold text-slate-600 dark:text-slate-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold">Simpan Penetapan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#pembimbingTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });
    });

    function penetapanPembimbing() {
        return {
            isModalOpen: false,
            selectedRegId: '',
            selectedStudentName: '',
            selectedStudentNpm: '',
            selectedLecturerId: '',

            openAssignModal(regId, name, npm, currentLecId) {
                this.selectedRegId = regId;
                this.selectedStudentName = name;
                this.selectedStudentNpm = npm;
                this.selectedLecturerId = currentLecId !== null ? currentLecId : '';
                this.isModalOpen = true;
            },

            closeAssignModal() {
                this.isModalOpen = false;
            }
        }
    }
</script>
<?= $this->endSection() ?>
