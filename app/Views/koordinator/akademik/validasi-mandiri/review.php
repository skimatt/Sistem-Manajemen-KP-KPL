<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('koordinator/validasi-mandiri') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-base"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Usulan Tempat Mandiri</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Periksa kelayakan perusahaan/instansi mandiri yang diajukan oleh mahasiswa.</p>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Profile Summary -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-user text-base text-blue-500"></i>
                    Informasi Akademik Mahasiswa
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Nama Lengkap</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['full_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">NPM / Program Studi</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['npm']) ?> / <?= esc($placement['prodi_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Periode Pelaksanaan</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['period_name']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Proposed Institution Details -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                        <i class="ti ti-building text-base text-blue-500"></i>
                        Data Instansi Mandiri yang Diusulkan
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block">Nama Instansi/Perusahaan</span>
                        <span class="font-bold text-slate-850 dark:text-slate-150 text-sm block mt-0.5"><?= esc($placement['proposed_institution_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Bidang Pekerjaan / Usaha</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['proposed_field']) ?: 'Lainnya' ?></span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block">Alamat Lengkap</span>
                        <span class="font-medium text-slate-700 dark:text-slate-350 block mt-0.5"><?= esc($placement['proposed_address']) ?></span>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                        <i class="ti ti-phone text-base text-blue-500"></i>
                        Informasi Kontak & Alasan Pemilihan
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Contact Person / Pimpinan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['contact_person']) ?> (<?= esc($placement['contact_position']) ?>)</span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Nomor Telepon & Email Kontak</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['contact_phone']) ?> / <?= esc($placement['contact_email']) ?: '-' ?></span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block">Alasan Pemilihan Instansi Mandiri</span>
                        <p class="font-medium text-slate-700 dark:text-slate-350 mt-1 leading-relaxed bg-slate-50 dark:bg-slate-800/30 p-3 rounded-lg border border-slate-150 dark:border-slate-850"><?= esc($placement['reason']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Uploaded Acceptance Letter Stempel -->
            <?php if (!empty($acceptanceDoc)): ?>
                <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                        <i class="ti ti-file-certificate text-base text-blue-500"></i>
                        Surat Balasan / Bukti Penerimaan Instansi
                    </h3>
                    <div class="flex items-center justify-between p-3.5 bg-emerald-50/20 dark:bg-emerald-950/10 border border-emerald-100 dark:border-emerald-950/40 rounded-xl text-xs gap-3">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                                <i class="ti ti-file-text text-lg"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-800 dark:text-slate-200">Surat Penerimaan Mandiri</h5>
                                <p class="text-[10px] text-slate-450 mt-0.5">Berkas: <?= esc($acceptanceDoc['original_name']) ?> (<?= number_format($acceptanceDoc['file_size_kb'], 0) ?> KB)</p>
                            </div>
                        </div>
                        <a href="<?= base_url('admin/laporan/download/' . $acceptanceDoc['id']) ?>" 
                           class="inline-flex h-7 px-3.5 items-center justify-center rounded bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold transition"
                           target="_blank">
                            <i class="ti ti-download text-xs mr-1"></i> Unduh Berkas
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Decision Box Column -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm sticky top-20">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 pb-3 border-b border-slate-100 dark:border-slate-800/80 mb-4 flex items-center gap-2">
                    <i class="ti ti-checklist text-base text-blue-500"></i>
                    Form Validasi Mandiri
                </h3>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="p-3 rounded bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs font-semibold mb-4">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('koordinator/validasi-mandiri/submit/' . $placement['id']) ?>" method="POST" class="space-y-4 text-xs">
                    <?= csrf_field() ?>

                    <!-- Keputusan -->
                    <div class="space-y-2">
                        <label class="block font-bold text-slate-700 dark:text-slate-350">Keputusan Validasi</label>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="disetujui" <?= $placement['status'] === 'disetujui' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Setujui Usulan</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Instansi mandiri layak dan disetujui.</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="perlu_revisi" <?= $placement['status'] === 'perlu_revisi' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Minta Revisi Usulan</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Data instansi kurang lengkap/kurang jelas.</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="ditolak" <?= $placement['status'] === 'ditolak' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Tolak Usulan</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Instansi tidak layak (tidak sesuai bidang studi).</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="review_note" class="block font-bold text-slate-700 dark:text-slate-350">Catatan Review</label>
                        <textarea name="review_note" 
                                  id="review_note" 
                                  rows="4" 
                                  placeholder="Catatan persetujuan kelayakan instansi mandiri..." 
                                  class="block w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500"><?= esc($placement['review_note']) ?></textarea>
                    </div>

                    <!-- Submit -->
                    <button type="submit" 
                            class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition flex items-center justify-center gap-2">
                        <i class="ti ti-device-floppy text-base"></i> Simpan Validasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
