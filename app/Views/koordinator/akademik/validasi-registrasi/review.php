<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('koordinator/validasi-registrasi') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-base"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Registrasi Mahasiswa</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Periksa berkas persyaratan akademik dan buat keputusan persetujuan registrasi.</p>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Information Card -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Diri & Akademik Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                        <i class="ti ti-user text-base text-blue-500"></i>
                        Data Profil Mahasiswa
                    </h3>
                </div>
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
                        <span class="text-slate-400 dark:text-slate-500 block">Semester / Angkatan</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 block mt-0.5">Semester <?= esc($registration['profile_semester']) ?> / Angkatan <?= esc($registration['generation_year']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Email Pengguna</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($registration['email']) ?></span>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                        <i class="ti ti-school text-base text-blue-500"></i>
                        Verifikasi Kelayakan Akademik
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                        <span class="text-slate-400 dark:text-slate-500 block">Jumlah SKS Kumulatif</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-sm mt-1 block"><?= esc($registration['academic_sks']) ?> SKS</span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                        <span class="text-slate-400 dark:text-slate-500 block">IPK Terakhir</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-sm mt-1 block"><?= esc($registration['academic_gpa']) ?></span>
                        <span class="inline-flex mt-1 text-[10px] font-bold <?= $registration['is_gpa_eligible'] ? 'text-emerald-600 dark:text-emerald-500' : 'text-rose-600' ?>">
                            <?= $registration['is_gpa_eligible'] ? 'Lulus Syarat (>= 2.50)' : 'Tidak Lulus Syarat' ?>
                        </span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                        <span class="text-slate-400 dark:text-slate-500 block">Rekomendasi Dosen PA</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-sm mt-1 block uppercase"><?= esc($registration['advisor_recommendation_status']) ?></span>
                    </div>
                </div>

                <!-- Kelulusan Mata Kuliah Wajib -->
                <div>
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-3">Kelulusan Mata Kuliah Wajib</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <!-- MK 1 -->
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 rounded-lg">
                            <span class="font-medium text-slate-700 dark:text-slate-350">Pemrograman Dasar</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $registration['passed_basic_programming'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400' ?>">
                                <?= $registration['passed_basic_programming'] ? 'Lulus' : 'Belum Lulus' ?>
                            </span>
                        </div>
                        <!-- MK 2 -->
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 rounded-lg">
                            <span class="font-medium text-slate-700 dark:text-slate-350">Struktur Data</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $registration['passed_data_structure'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400' ?>">
                                <?= $registration['passed_data_structure'] ? 'Lulus' : 'Belum Lulus' ?>
                            </span>
                        </div>
                        <!-- MK 3 -->
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 rounded-lg">
                            <span class="font-medium text-slate-700 dark:text-slate-350">Basis Data</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $registration['passed_database'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400' ?>">
                                <?= $registration['passed_database'] ? 'Lulus' : 'Belum Lulus' ?>
                            </span>
                        </div>
                        <!-- MK 4 -->
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 rounded-lg">
                            <span class="font-medium text-slate-700 dark:text-slate-350">Analisis Perancangan Sistem</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $registration['passed_system_analysis'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400' ?>">
                                <?= $registration['passed_system_analysis'] ? 'Lulus' : 'Belum Lulus' ?>
                            </span>
                        </div>
                        <!-- MK 5 -->
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 rounded-lg col-span-1 md:col-span-2">
                            <span class="font-medium text-slate-700 dark:text-slate-350">Jaringan Komputer / Komunikasi Data</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $registration['passed_networking'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400' ?>">
                                <?= $registration['passed_networking'] ? 'Lulus' : 'Belum Lulus' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uploaded Documents Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-folder text-base text-blue-500"></i>
                    Lampiran Dokumen Unggahan
                </h3>

                <div class="space-y-3">
                    <?php if (!empty($documents)): ?>
                        <?php foreach ($documents as $doc): ?>
                            <div class="flex flex-col md:flex-row md:items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-800/20 border border-slate-200/40 dark:border-slate-850 rounded-xl gap-3 text-xs">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex-shrink-0">
                                        <i class="ti ti-file-text text-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-800 dark:text-slate-200"><?= esc($doc['document_name']) ?></h5>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Berkas: <?= esc($doc['original_name']) ?> (<?= number_format($doc['file_size_kb'], 0) ?> KB)</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2.5 self-end md:self-auto">
                                    <!-- Document Status Badge -->
                                    <?php
                                    $badge = '';
                                    if ($doc['status'] === 'valid') {
                                        $badge = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400';
                                    } elseif ($doc['status'] === 'perlu_revisi') {
                                        $badge = 'bg-blue-100 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400';
                                    } elseif ($doc['status'] === 'ditolak') {
                                        $badge = 'bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400';
                                    } else {
                                        $badge = 'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400';
                                    }
                                    ?>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase <?= $badge ?>"><?= esc($doc['status']) ?></span>
                                    
                                    <!-- View/Download -->
                                    <a href="<?= base_url('koordinator/validasi-registrasi/download-dokumen/' . $doc['id']) ?>" 
                                       class="inline-flex h-7 px-3 items-center justify-center rounded bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold transition"
                                       target="_blank">
                                        <i class="ti ti-download text-xs mr-1"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-6 text-slate-400 dark:text-slate-500">
                            Tidak ada dokumen yang diunggah.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Decision Card -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm sticky top-20">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-4 flex items-center gap-2">
                    <i class="ti ti-checklist text-base text-blue-500"></i>
                    Form Validasi
                </h3>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="p-3 mb-4 rounded bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs font-semibold">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="p-3 mb-4 rounded bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <div><?= esc($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('koordinator/validasi-registrasi/submit/' . $registration['id']) ?>" method="POST" class="space-y-4 text-xs">
                    <?= csrf_field() ?>

                    <!-- Keputusan -->
                    <div class="space-y-2">
                        <label class="block font-bold text-slate-700 dark:text-slate-350">Keputusan Validasi</label>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="registrasi_disetujui" <?= $registration['current_status'] === 'registrasi_disetujui' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Setujui Registrasi</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Lolos berkas dan berhak memilih penempatan.</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="revisi_registrasi" <?= $registration['current_status'] === 'revisi_registrasi' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Kembalikan untuk Revisi</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Terdapat data/berkas salah. Mahasiswa wajib memperbaiki.</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="registrasi_ditolak" <?= $registration['current_status'] === 'registrasi_ditolak' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Tolak Registrasi</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Mahasiswa tidak memenuhi syarat akademik minimum.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Catatan/Catatan -->
                    <div class="space-y-2">
                        <label for="final_note" class="block font-bold text-slate-700 dark:text-slate-350">Catatan Koordinator</label>
                        <textarea name="final_note" 
                                  id="final_note" 
                                  rows="4" 
                                  placeholder="Tulis alasan jika menolak atau instruksi revisi berkas..." 
                                  class="block w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500"><?= esc($registration['final_note']) ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full mt-4 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition flex items-center justify-center gap-2">
                        <i class="ti ti-device-floppy text-base"></i> Simpan Validasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
