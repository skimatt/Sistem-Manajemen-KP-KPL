<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Registrasi KP/KPL</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Lengkapi berkas pendaftaran untuk divalidasi oleh Koordinator Program Studi.</p>
    </div>

    <!-- Error Alert Box -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/40 rounded-xl p-4 text-xs text-red-600 dark:text-red-400 space-y-1">
            <h4 class="font-bold flex items-center gap-1.5"><i class="ti ti-alert-triangle"></i> Terjadi Kesalahan:</h4>
            <ul class="list-disc list-inside pl-1 space-y-0.5">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!$period): ?>
        <!-- No Active Period -->
        <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/40 rounded-xl p-6 text-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 mx-auto shadow-sm">
                <i class="ti ti-calendar-off text-xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Tidak Ada Periode Aktif</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                    Saat ini tidak ada periode pendaftaran KP/KPL aktif yang dibuka untuk program studi Anda. Silakan hubungi Koordinator Prodi Anda untuk informasi lebih lanjut.
                </p>
            </div>
            <a href="<?= base_url('mahasiswa/dashboard') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-bold transition shadow-sm">
                <i class="ti ti-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    <?php elseif ($registration && $registration->current_status !== 'registrasi_ditolak' && $registration->current_status !== 'revisi_registrasi'): ?>
        <!-- Already registered and pending/verified -->
        <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/40 rounded-xl p-6 text-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 mx-auto shadow-sm">
                <i class="ti ti-circle-check text-xl"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Pendaftaran Telah Dikirim</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                    Anda sudah mengirim berkas pendaftaran KP/KPL pada periode <strong><?= esc($period->name) ?></strong>. Silakan pantau perkembangannya melalui menu Status Registrasi.
                </p>
            </div>
            <div class="flex items-center justify-center gap-3">
                <a href="<?= base_url('mahasiswa/status-registrasi') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                    Pantau Status <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Form Pendaftaran -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Formulir Pendaftaran KP/KPL</h2>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Periode Aktif: <?= esc($period->name) ?> (<?= esc($period->academic_year) ?> - Sem. <?= esc($period->semester) ?>)</p>
            </div>

            <form action="<?= base_url('mahasiswa/registrasi/submit') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Academic SKS -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Jumlah SKS Lulus</label>
                        <input type="number" name="academic_sks" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" placeholder="Contoh: 102" value="<?= old('academic_sks', $registration->academic_sks ?? '') ?>" required />
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Minimal 80 SKS.</span>
                    </div>

                    <!-- Academic GPA -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">IPK Terakhir</label>
                        <input type="text" name="academic_gpa" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" placeholder="Contoh: 3.25" value="<?= old('academic_gpa', $registration->academic_gpa ?? '') ?>" required />
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Minimal IPK 2,50.</span>
                    </div>
                </div>

                <!-- Prerequisite Checklist -->
                <div>
                    <h3 class="text-xs font-semibold text-slate-700 dark:text-slate-350 border-b border-slate-100 dark:border-slate-800/60 pb-1.5 mb-3">Kelulusan Mata Kuliah Prasyarat</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                            <label class="font-semibold text-slate-600 dark:text-slate-400">Pemrograman Dasar</label>
                            <select name="passed_basic_programming" class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded text-xs focus:outline-none">
                                <option value="1" <?= old('passed_basic_programming', $registration->passed_basic_programming ?? '1') == '1' ? 'selected' : '' ?>>Lulus</option>
                                <option value="0" <?= old('passed_basic_programming', $registration->passed_basic_programming ?? '1') == '0' ? 'selected' : '' ?>>Belum Lulus</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                            <label class="font-semibold text-slate-600 dark:text-slate-400">Struktur Data</label>
                            <select name="passed_data_structure" class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded text-xs focus:outline-none">
                                <option value="1" <?= old('passed_data_structure', $registration->passed_data_structure ?? '1') == '1' ? 'selected' : '' ?>>Lulus</option>
                                <option value="0" <?= old('passed_data_structure', $registration->passed_data_structure ?? '1') == '0' ? 'selected' : '' ?>>Belum Lulus</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                            <label class="font-semibold text-slate-600 dark:text-slate-400">Basis Data</label>
                            <select name="passed_database" class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded text-xs focus:outline-none">
                                <option value="1" <?= old('passed_database', $registration->passed_database ?? '1') == '1' ? 'selected' : '' ?>>Lulus</option>
                                <option value="0" <?= old('passed_database', $registration->passed_database ?? '1') == '0' ? 'selected' : '' ?>>Belum Lulus</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                            <label class="font-semibold text-slate-600 dark:text-slate-400">Analisis Sistem (APSI)</label>
                            <select name="passed_system_analysis" class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded text-xs focus:outline-none">
                                <option value="1" <?= old('passed_system_analysis', $registration->passed_system_analysis ?? '1') == '1' ? 'selected' : '' ?>>Lulus</option>
                                <option value="0" <?= old('passed_system_analysis', $registration->passed_system_analysis ?? '1') == '0' ? 'selected' : '' ?>>Belum Lulus</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                            <label class="font-semibold text-slate-600 dark:text-slate-400">Jaringan Komputer</label>
                            <select name="passed_networking" class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded text-xs focus:outline-none">
                                <option value="1" <?= old('passed_networking', $registration->passed_networking ?? '1') == '1' ? 'selected' : '' ?>>Lulus</option>
                                <option value="0" <?= old('passed_networking', $registration->passed_networking ?? '1') == '0' ? 'selected' : '' ?>>Belum Lulus</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                            <label class="font-semibold text-slate-600 dark:text-slate-400">Mata Kuliah Konsentrasi</label>
                            <select name="passed_concentration_course" class="px-2 py-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded text-xs focus:outline-none">
                                <option value="1" <?= old('passed_concentration_course', $registration->passed_concentration_course ?? '1') == '1' ? 'selected' : '' ?>>Lulus</option>
                                <option value="0" <?= old('passed_concentration_course', $registration->passed_concentration_course ?? '1') == '0' ? 'selected' : '' ?>>Belum Lulus</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Advisor Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nama Lengkap Dosen Pembimbing Akademik (PA)</label>
                    <input type="text" name="academic_advisor_name" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 focus:ring-1 focus:ring-blue-500/20" placeholder="Contoh: Bahrul Khair, M.Sc." value="<?= old('academic_advisor_name', $registration->academic_advisor_name ?? '') ?>" required />
                </div>

                <!-- File uploads -->
                <div>
                    <h3 class="text-xs font-semibold text-slate-700 dark:text-slate-350 border-b border-slate-100 dark:border-slate-800/60 pb-1.5 mb-3">Unggah Berkas Persyaratan</h3>
                    
                    <div class="space-y-4">
                        <!-- Bukti Pembayaran -->
                        <div class="bg-slate-50/50 dark:bg-slate-950/20 p-3 rounded-lg border border-slate-200/50 dark:border-slate-800/60 text-xs">
                            <label class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">Bukti Pembayaran KP/KPL</label>
                            <input type="file" name="payment_proof" class="block w-full text-slate-500 text-[11px] mt-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-850 dark:file:text-slate-200 cursor-pointer" required />
                            <span class="text-[9px] text-slate-400 dark:text-slate-500 block mt-1">Format: PDF, JPG, JPEG, atau PNG. Maksimal 10 MB.</span>
                        </div>

                        <!-- KHS Terbaru -->
                        <div class="bg-slate-50/50 dark:bg-slate-950/20 p-3 rounded-lg border border-slate-200/50 dark:border-slate-800/60 text-xs">
                            <label class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">KHS Terbaru (Menampilkan SKS & IPK)</label>
                            <input type="file" name="khs_file" class="block w-full text-slate-500 text-[11px] mt-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-850 dark:file:text-slate-200 cursor-pointer" required />
                            <span class="text-[9px] text-slate-400 dark:text-slate-500 block mt-1">Format: PDF saja. Maksimal 10 MB.</span>
                        </div>

                        <!-- Rekomendasi PA -->
                        <div class="bg-slate-50/50 dark:bg-slate-950/20 p-3 rounded-lg border border-slate-200/50 dark:border-slate-800/60 text-xs">
                            <label class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">Surat Rekomendasi Dosen PA</label>
                            <input type="file" name="recommendation_file" class="block w-full text-slate-500 text-[11px] mt-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-850 dark:file:text-slate-200 cursor-pointer" required />
                            <span class="text-[9px] text-slate-400 dark:text-slate-500 block mt-1">Format: PDF saja. Maksimal 10 MB.</span>
                        </div>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs shadow shadow-blue-500/10 transition">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
