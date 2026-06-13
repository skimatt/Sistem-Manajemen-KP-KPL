<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto" x-data="{ openWeekId: null, addWeekModal: false, addEntryModal: false, activeWeekId: null }">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Logbook Kegiatan</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Catat aktivitas harian Anda dan kirimkan laporan mingguan untuk ditinjau oleh Dosen Pembimbing.</p>
        </div>
        
        <!-- Add Week Button -->
        <button @click="addWeekModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow shadow-blue-500/10">
            <i class="ti ti-plus"></i> Tambah Minggu Baru
        </button>
    </div>

    <!-- Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-xl p-4 text-xs text-emerald-600 dark:text-emerald-450">
            <span class="font-bold"><i class="ti ti-circle-check"></i> Berhasil:</span> <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl p-4 text-xs text-red-600 dark:text-red-400">
            <span class="font-bold"><i class="ti ti-alert-circle"></i> Galat:</span> <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <!-- Logbook Weeks Accordion List -->
    <div class="space-y-4">
        <?php if (empty($weeks)): ?>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-10 text-center space-y-4 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-450 dark:text-slate-500 mx-auto">
                    <i class="ti ti-book text-xl"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Logbook Masih Kosong</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                        Anda belum menambahkan minggu logbook pertama. Silakan tekan tombol "Tambah Minggu Baru" di atas untuk memulai.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($weeks as $w): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
                    <!-- Accordion Header -->
                    <div class="flex items-center justify-between p-4 cursor-pointer select-none bg-slate-50/40 dark:bg-slate-950/10 hover:bg-slate-50 dark:hover:bg-slate-950/20 transition"
                         @click="openWeekId = (openWeekId === <?= $w->id ?>) ? null : <?= $w->id ?>">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 font-bold text-xs">
                                W<?= esc($w->week_number) ?>
                            </div>
                            <div class="space-y-0.5">
                                <h3 class="font-bold text-slate-800 dark:text-slate-150 text-xs">Minggu Ke-<?= esc($w->week_number) ?></h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-550"><?= date('d M Y', strtotime($w->start_date)) ?> s.d. <?= date('d M Y', strtotime($w->end_date)) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-xs">
                            <?php 
                            $statusBadge = 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-950 dark:text-slate-450 dark:border-slate-800';
                            if ($w->status === 'disetujui') {
                                $statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/50';
                            } elseif ($w->status === 'dikirim') {
                                $statusBadge = 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/50';
                            } elseif ($w->status === 'perlu_revisi') {
                                $statusBadge = 'bg-orange-50 text-orange-700 border-orange-100 dark:bg-orange-950/40 dark:text-orange-400 dark:border-orange-900/50';
                            }
                            ?>
                            <span class="px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider <?= $statusBadge ?>"><?= esc($w->status) ?></span>
                            <i class="ti text-base text-slate-450" :class="openWeekId === <?= $w->id ?> ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
                        </div>
                    </div>

                    <!-- Accordion Content -->
                    <div x-show="openWeekId === <?= $w->id ?> block" class="p-5 border-t border-slate-100 dark:border-slate-800 space-y-5" x-cloak>
                        
                        <!-- Target Description -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="p-3 bg-slate-50 dark:bg-slate-950/40 rounded-lg border border-slate-150 dark:border-slate-850">
                                <span class="font-bold text-slate-700 dark:text-slate-300 block mb-1">Target Mingguan:</span>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed"><?= nl2br(esc($w->weekly_target ?? 'Belum ditentukan')) ?></p>
                            </div>
                            <?php if ($w->status === 'dikirim' || $w->status === 'disetujui'): ?>
                                <div class="p-3 bg-slate-50 dark:bg-slate-950/40 rounded-lg border border-slate-150 dark:border-slate-850">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 block mb-1">Hasil yang Dicapai:</span>
                                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed"><?= nl2br(esc($w->weekly_result ?? '-')) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Daily Entries Table -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Aktivitas Harian</h4>
                                <?php if ($w->status === 'draft' || $w->status === 'perlu_revisi'): ?>
                                    <button @click="activeWeekId = <?= $w->id ?>; addEntryModal = true" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded text-[10px] font-bold transition">
                                        <i class="ti ti-plus"></i> Catat Harian
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-150 dark:border-slate-850 text-slate-400 dark:text-slate-500 font-semibold">
                                            <th class="py-2">Tanggal</th>
                                            <th class="py-2">Waktu</th>
                                            <th class="py-2">Deskripsi Kegiatan</th>
                                            <th class="py-2">Hasil / Luaran</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-850 text-slate-700 dark:text-slate-300">
                                        <?php if (empty($entries[$w->id])): ?>
                                            <tr>
                                                <td colspan="4" class="py-3 text-center text-slate-500 dark:text-slate-400 text-[11px]">Belum ada kegiatan harian yang dicatat.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($entries[$w->id] as $entry): ?>
                                                <tr>
                                                    <td class="py-2.5 font-bold whitespace-nowrap"><?= date('d M Y', strtotime($entry->activity_date)) ?></td>
                                                    <td class="py-2.5 whitespace-nowrap"><?= date('H:i', strtotime($entry->start_time)) ?> - <?= date('H:i', strtotime($entry->end_time)) ?></td>
                                                    <td class="py-2.5 leading-relaxed pr-4"><?= esc($entry->activity_description) ?></td>
                                                    <td class="py-2.5 leading-relaxed"><?= esc($entry->result_description) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Weekly review submission forms -->
                        <?php if ($w->status === 'draft' || $w->status === 'perlu_revisi'): ?>
                            <form action="<?= base_url('mahasiswa/logbook/submit-week/' . $w->id) ?>" method="POST" class="border-t border-slate-100 dark:border-slate-800 pt-4 space-y-4 text-xs">
                                <?= csrf_field() ?>
                                <h4 class="font-bold text-slate-700 dark:text-slate-300">Submit Laporan Mingguan</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Hasil Akhir Mingguan</label>
                                        <textarea name="weekly_result" rows="2" class="block w-full mt-1 px-2.5 py-2 bg-slate-50 dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" placeholder="Hasil/pencapaian utama minggu ini..." required><?= esc($w->weekly_result) ?></textarea>
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Kendala (Jika Ada)</label>
                                        <textarea name="obstacle" rows="2" class="block w-full mt-1 px-2.5 py-2 bg-slate-50 dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" placeholder="Hambatan/kendala selama kegiatan..."><?= esc($w->obstacle) ?></textarea>
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Rencana Minggu Berikutnya</label>
                                        <textarea name="next_plan" rows="2" class="block w-full mt-1 px-2.5 py-2 bg-slate-50 dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" placeholder="Target rencana selanjutnya..." required><?= esc($w->next_plan) ?></textarea>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-[10px] uppercase tracking-wider transition shadow shadow-blue-500/10">
                                        Kirim Logbook Minggu Ini
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- MODAL: ADD WEEK -->
    <div x-show="addWeekModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 max-w-sm w-full shadow-lg space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Tambah Minggu Logbook</h3>
                <button @click="addWeekModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="ti ti-x text-sm"></i></button>
            </div>

            <form action="<?= base_url('mahasiswa/logbook/add-week') ?>" method="POST" class="space-y-3 text-xs">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block font-semibold text-slate-600 dark:text-slate-400">Minggu Ke-</label>
                    <input type="number" name="week_number" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" placeholder="Contoh: 1" required />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" required />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" required />
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 dark:text-slate-400">Target/Fokus Pekerjaan</label>
                    <textarea name="weekly_target" rows="3" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" placeholder="Fokus pekerjaan yang direncanakan minggu ini..." required></textarea>
                </div>

                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow shadow-blue-500/10">
                    Tambah Minggu
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL: ADD DAILY ENTRY -->
    <div x-show="addEntryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4" x-cloak>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 max-w-md w-full shadow-lg space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Catat Kegiatan Harian</h3>
                <button @click="addEntryModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><i class="ti ti-x text-sm"></i></button>
            </div>

            <form action="<?= base_url('mahasiswa/logbook/add-entry') ?>" method="POST" class="space-y-3 text-xs">
                <?= csrf_field() ?>
                
                <input type="hidden" name="logbook_week_id" :value="activeWeekId" />

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-1">
                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Tanggal</label>
                        <input type="date" name="activity_date" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" required />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Jam Mulai</label>
                        <input type="time" name="start_time" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" required />
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400">Jam Selesai</label>
                        <input type="time" name="end_time" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" required />
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 dark:text-slate-400">Uraian Detail Kegiatan</label>
                    <textarea name="activity_description" rows="3" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" placeholder="Menyelesaikan setup database, merancang model user, dll..." required></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-600 dark:text-slate-400">Hasil / Outcame Kegiatan</label>
                    <textarea name="result_description" rows="2" class="block w-full mt-1 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs" placeholder="Skema database selesai, tabel user terbuat." required></textarea>
                </div>

                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow shadow-blue-500/10">
                    Simpan Kegiatan
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
