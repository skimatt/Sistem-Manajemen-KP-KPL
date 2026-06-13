<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('koordinator/validasi-penilaian') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-base"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail & Validasi Penilaian Akhir</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Sahkan akumulasi nilai akhir dari dosen pembimbing, instansi/mitra lapangan, dan administrasi.</p>
        </div>
    </div>

    <!-- Alert success -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-xs border border-emerald-100 dark:border-emerald-950/30 font-semibold">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grades Detail & Student Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Information Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-user text-base text-blue-500"></i>
                    Identitas Mahasiswa & Program Kegiatan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Nama Lengkap</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($registration['full_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">NPM / Program Studi</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($registration['npm']) ?> / <?= esc($registration['prodi_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Periode Kegiatan</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($registration['period_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Status Alur Saat Ini</span>
                        <?php
                        $statusClass = 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400';
                        $statusLabel = esc($registration['current_status']);
                        if ($registration['current_status'] === 'sedang_berjalan') {
                            $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                            $statusLabel = 'Kegiatan Berjalan';
                        } elseif ($registration['current_status'] === 'selesai') {
                            $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
                            $statusLabel = 'Selesai';
                        }
                        ?>
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                            <?= $statusLabel ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Detailed Component Scores Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-table text-base text-blue-500"></i>
                    Rincian Nilai per Komponen
                </h3>

                <div class="table-responsive">
                    <table class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                        <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                            <tr>
                                <th class="px-4 py-2.5 font-bold">Komponen Penilaian</th>
                                <th class="px-4 py-2.5 font-bold text-center">Penilai (Assessor)</th>
                                <th class="px-4 py-2.5 font-bold text-center">Bobot</th>
                                <th class="px-4 py-2.5 font-bold text-center">Nilai Asli (Raw)</th>
                                <th class="px-4 py-2.5 font-bold text-center">Nilai Berbobot</th>
                                <th class="px-4 py-2.5 font-bold">Keterangan/Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <?php if (!empty($scoresList)): ?>
                                <?php foreach ($scoresList as $score): ?>
                                    <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-850/20 transition duration-150">
                                        <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-350">
                                            <?= esc($score['component_name']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-center uppercase font-bold text-[10px]">
                                            <?php
                                            $roleClass = 'text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400';
                                            if ($score['assessor_role'] === 'dosen') {
                                                $roleClass = 'text-blue-600 bg-blue-50 dark:bg-blue-950/35 dark:text-blue-400';
                                            } elseif ($score['assessor_role'] === 'instansi') {
                                                $roleClass = 'text-emerald-600 bg-emerald-50 dark:bg-emerald-950/35 dark:text-emerald-400';
                                            } elseif ($score['assessor_role'] === 'admin') {
                                                $roleClass = 'text-purple-600 bg-purple-50 dark:bg-purple-950/35 dark:text-purple-400';
                                            }
                                            ?>
                                            <span class="px-2 py-0.5 rounded-full <?= $roleClass ?>">
                                                <?= esc($score['assessor_role']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center font-semibold text-slate-500 dark:text-slate-400">
                                            <?= number_format($score['weight'], 0) ?>%
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-700 dark:text-slate-300">
                                            <?= number_format($score['score'], 2) ?>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold text-slate-900 dark:text-white">
                                            <?php
                                            $weightedScore = ($score['score'] * $score['weight']) / 100;
                                            echo number_format($weightedScore, 2);
                                            ?>
                                        </td>
                                        <td class="px-4 py-3 text-slate-500 dark:text-slate-400 italic">
                                            <?= esc($score['note']) ?: '<span class="text-slate-300 dark:text-slate-655">-</span>' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-400 italic">
                                        Belum ada rincian komponen nilai yang dimasukkan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Final Calculations Banner -->
                <?php if ($finalScore): ?>
                    <div class="mt-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/50 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs">
                        <div class="space-y-1">
                            <div class="font-bold text-slate-700 dark:text-slate-300">Skor Gabungan (Summary):</div>
                            <div class="grid grid-cols-3 gap-x-4 text-slate-500 dark:text-slate-400">
                                <div>Dosen (50%): <span class="font-bold text-slate-700 dark:text-slate-350"><?= number_format($finalScore['lecturer_score'], 2) ?></span></div>
                                <div>Instansi (40%): <span class="font-bold text-slate-700 dark:text-slate-350"><?= number_format($finalScore['institution_score'], 2) ?></span></div>
                                <div>Logbook/Adm (10%): <span class="font-bold text-slate-700 dark:text-slate-350"><?= number_format($finalScore['admin_score'], 2) ?></span></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 border-t md:border-t-0 pt-2.5 md:pt-0 border-slate-200 dark:border-slate-800">
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 block uppercase font-bold tracking-wider">Nilai Total Akhir</span>
                                <span class="text-lg font-black text-blue-600 dark:text-blue-400"><?= number_format($finalScore['final_score'], 2) ?></span>
                            </div>
                            <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-800"></div>
                            <div class="text-center">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 block uppercase font-bold tracking-wider">Grade</span>
                                <span class="px-2.5 py-0.5 rounded font-black bg-blue-100 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400 text-sm"><?= esc($finalScore['final_grade']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Decision Side panel -->
        <div class="space-y-6">
            <!-- Form Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-4 flex items-center gap-2">
                    <i class="ti ti-checklist text-base text-blue-500"></i>
                    Sahkan Nilai Akhir
                </h3>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="p-3 rounded bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs font-semibold">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="p-3 rounded bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <div><?= esc($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($finalScore && $finalScore['status'] === 'divalidasi'): ?>
                    <!-- If already validated -->
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-950/40 space-y-3 text-xs">
                        <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-400 font-bold">
                            <i class="ti ti-circle-check text-base"></i>
                            Nilai Telah Disahkan
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
                            Nilai akhir untuk mahasiswa ini telah disahkan oleh Koordinator KP/KPL. Status pendaftaran/kegiatan saat ini adalah <strong>SELESAI</strong>.
                        </p>
                        <?php if ($finalScore['validation_note']): ?>
                            <div class="bg-white/60 dark:bg-slate-900/60 p-2.5 rounded border border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 italic">
                                &ldquo;<?= esc($finalScore['validation_note']) ?>&rdquo;
                            </div>
                        <?php endif; ?>
                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-2">
                            Tanggal Sah: <?= date('d M Y, H:i', strtotime($finalScore['validated_at'])) ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- If pending validation -->
                    <form action="<?= base_url('koordinator/validasi-penilaian/submit/' . $registration['id']) ?>" method="POST" class="space-y-4 text-xs" id="formValidateScore">
                        <?= csrf_field() ?>

                        <div class="p-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-950/40 rounded-xl text-slate-600 dark:text-slate-400 text-xs">
                            <p class="font-medium text-amber-800 dark:text-amber-400 flex items-center gap-1.5 mb-1.5">
                                <i class="ti ti-alert-triangle text-base"></i>
                                Perhatian Akademik
                            </p>
                            Menyetujui validasi ini akan mengubah status alur proses mahasiswa menjadi <strong>SELESAI</strong> secara permanen dan merekam rekap nilai akhir ini ke dalam basis data akademik utama.
                        </div>

                        <!-- Catatan Validasi -->
                        <div class="space-y-2">
                            <label for="validation_note" class="block font-bold text-slate-700 dark:text-slate-350">Catatan Validasi (Opsional)</label>
                            <textarea name="validation_note" 
                                      id="validation_note" 
                                      rows="4" 
                                      placeholder="Tulis catatan penyerahan nilai atau keterangan tambahan..." 
                                      class="block w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full mt-4 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition flex items-center justify-center gap-2">
                            <i class="ti ti-circle-check text-base"></i> Sahkan Nilai Sekarang
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('formValidateScore')?.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Nilai akhir mahasiswa akan disahkan secara resmi dan statusnya akan diselesaikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Sahkan Nilai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>
