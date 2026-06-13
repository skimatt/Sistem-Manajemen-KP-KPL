<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('koordinator/pengajuan-penempatan') ?>" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-base"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail & Review Penempatan Mitra</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Review kesesuaian instansi dan kelayakan kuota penempatan sebelum persetujuan final.</p>
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
                        <span class="text-slate-400 dark:text-slate-500 block">IPK Terakhir</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['academic_gpa']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Periode Pelaksanaan</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['period_name']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Target Institution Details -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-building text-base text-blue-500"></i>
                    Detail Instansi Terpilih
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block">Nama Instansi Mitra</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-sm block mt-0.5"><?= esc($placement['instansi_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Bidang Pekerjaan</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200 block mt-0.5"><?= esc($placement['instansi_field']) ?: 'Umum' ?></span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-slate-500 block">Status Kemitraan</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400 uppercase inline-block mt-1"><?= esc($placement['partnership_status']) ?></span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 dark:text-slate-500 block">Alamat Instansi</span>
                        <span class="font-medium text-slate-700 dark:text-slate-350 block mt-0.5"><?= esc($placement['instansi_address']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Priority Choices -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <i class="ti ti-list-check text-base text-blue-500"></i>
                    Pilihan Prioritas Mahasiswa
                </h3>
                <div class="space-y-3">
                    <?php if (!empty($choices)): ?>
                        <?php foreach ($choices as $ch): ?>
                            <div class="flex items-center justify-between p-3 rounded-lg border <?= $ch['institution_id'] == $placement['institution_id'] ? 'border-blue-500 bg-blue-50/20 dark:bg-blue-950/20' : 'border-slate-200/60 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/10' ?> text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full <?= $ch['institution_id'] == $placement['institution_id'] ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400' ?> font-bold text-[10px]">
                                        <?= $ch['priority_order'] ?>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-800 dark:text-slate-200"><?= esc($ch['institution_name']) ?></h5>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5"><?= esc($ch['institution_address']) ?></p>
                                    </div>
                                </div>
                                <?php if ($ch['institution_id'] == $placement['institution_id']): ?>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400">PILIHAN AJUAN</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-slate-400 dark:text-slate-500 text-xs">
                            Tidak ada data prioritas pilihan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Validation Column -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl p-6 shadow-sm sticky top-20 space-y-6">
                <!-- TOPSIS Rank Reference -->
                <?php if (!empty($topsisRank)): ?>
                    <div class="p-3.5 bg-blue-50/40 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-950/50 rounded-xl text-xs space-y-2">
                        <div class="font-bold text-blue-800 dark:text-blue-400 flex items-center gap-1.5">
                            <i class="ti ti-award text-sm"></i>
                            Rekomendasi TOPSIS
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Instansi ini menempati ranking **#<?= $topsisRank['rank_order'] ?>** untuk mahasiswa ini dengan indeks preferensi **<?= number_format($topsisRank['preference_value'], 4) ?>**.</p>
                        <a href="<?= base_url('koordinator/topsis?registration_id=' . $placement['registration_id']) ?>" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline inline-block">Lihat matriks TOPSIS &rarr;</a>
                    </div>
                <?php endif; ?>

                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 pb-3 border-b border-slate-100 dark:border-slate-800/80 flex items-center gap-2">
                    <i class="ti ti-checklist text-base text-blue-500"></i>
                    Form Validasi
                </h3>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="p-3 rounded bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 text-xs font-semibold">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('koordinator/pengajuan-penempatan/submit/' . $placement['id']) ?>" method="POST" class="space-y-4 text-xs">
                    <?= csrf_field() ?>

                    <!-- Decision -->
                    <div class="space-y-2">
                        <label class="block font-bold text-slate-700 dark:text-slate-350">Keputusan Validasi</label>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="disetujui" <?= $placement['status'] === 'disetujui' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Setujui Penempatan</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Mahasiswa diterima pada instansi mitra ini.</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="perlu_revisi" <?= $placement['status'] === 'perlu_revisi' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Revisi Penempatan</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Minta mahasiswa memilih instansi lain.</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-800 rounded-xl cursor-pointer hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <input type="radio" name="status" value="ditolak" <?= $placement['status'] === 'ditolak' ? 'checked' : '' ?> class="text-blue-600" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">Tolak Penempatan</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Batalkan pengajuan penempatan instansi ini.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Quota Override Checkbox -->
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200/50 dark:border-slate-800/50 space-y-2">
                        <span class="font-bold text-slate-700 dark:text-slate-350 block">Status Kuota Mitra</span>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                            <span>Batas/Terpakai:</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">Kuota Terisi</span>
                        </div>
                        <label class="flex items-center gap-2 mt-2 pt-2 border-t border-slate-200/40 dark:border-slate-800/40 cursor-pointer">
                            <input type="checkbox" name="override_quota" value="1" class="text-blue-600 rounded" />
                            <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-400">Buka kuota khusus/override (Bypass kapasitas)</span>
                        </label>
                    </div>

                    <!-- Review Notes -->
                    <div class="space-y-2">
                        <label for="review_note" class="block font-bold text-slate-700 dark:text-slate-350">Catatan Review</label>
                        <textarea name="review_note" 
                                  id="review_note" 
                                  rows="3" 
                                  placeholder="Catatan persetujuan, instruksi revisi instansi..." 
                                  class="block w-full px-3 py-2 border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500"><?= esc($placement['review_note']) ?></textarea>
                    </div>

                    <!-- Submit -->
                    <button type="submit" 
                            class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition flex items-center justify-center gap-2">
                        <i class="ti ti-device-floppy text-base"></i> Simpan Penempatan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
