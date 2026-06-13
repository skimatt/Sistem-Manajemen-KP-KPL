<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-3xl mx-auto">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Pengajuan Tempat Mandiri</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Lengkapi rincian instansi mandiri yang Anda tuju beserta berkas bukti korespondensi awal.</p>
    </div>

    <!-- Alert Box for Errors -->
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

    <!-- Proposed Form -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 mb-4">Informasi Instansi Mandiri</h2>
        
        <form action="<?= base_url('mahasiswa/tempat-mandiri/submit') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Proposed Institution Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nama Instansi / Perusahaan</label>
                    <input type="text" name="proposed_institution_name" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Contoh: PT. Studio Digital Kreatif" value="<?= old('proposed_institution_name') ?>" required />
                </div>
                
                <!-- Proposed Field Category -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Bidang Instansi</label>
                    <input type="text" name="proposed_field" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Contoh: Software House, Network Support" value="<?= old('proposed_field') ?>" required />
                </div>
            </div>

            <!-- Proposed Address -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Alamat Lengkap Instansi</label>
                <textarea name="proposed_address" rows="3" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Masukkan alamat lengkap beserta kota dan kode pos..." required><?= old('proposed_address') ?></textarea>
            </div>

            <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 pt-4 mb-4">Informasi Kontak Person (Narahubung Instansi)</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Contact Person -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nama Lengkap Narahubung</label>
                    <input type="text" name="contact_person" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Contoh: Ahmad Subardjo" value="<?= old('contact_person') ?>" required />
                </div>

                <!-- Contact Position -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Jabatan Narahubung</label>
                    <input type="text" name="contact_position" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Contoh: HRD Manager, Project Leader" value="<?= old('contact_position') ?>" required />
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Nomor HP/Telepon Narahubung</label>
                    <input type="text" name="contact_phone" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Contoh: 08123456789" value="<?= old('contact_phone') ?>" required />
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Email Narahubung</label>
                    <input type="email" name="contact_email" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Contoh: hr@studiodigital.id" value="<?= old('contact_email') ?>" required />
                </div>
            </div>

            <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2 pt-4 mb-4">Justifikasi & Bukti Komunikasi</h2>

            <!-- Reason -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400">Alasan Mengajukan Mandiri</label>
                <textarea name="reason" rows="3" class="block w-full mt-1.5 px-3 py-2 text-xs bg-white dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400" placeholder="Jelaskan alasan kecocokan topik/bidang Anda dengan instansi mandiri ini..." required><?= old('reason') ?></textarea>
            </div>

            <!-- Bukti Komunikasi -->
            <div class="bg-slate-50/50 dark:bg-slate-950/20 p-4 rounded-lg border border-slate-200/50 dark:border-slate-800 text-xs">
                <label class="font-semibold text-slate-700 dark:text-slate-350 block mb-1">Bukti Komunikasi Awal / Penjajakan Mandiri</label>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mb-2">Unggah tangkapan layar chat, email balasan awal, atau surat pengantar yang telah mendapat stempel basah/tanda tangan dari instansi.</p>
                <input type="file" name="mandiri_proof" class="block w-full text-slate-500 text-[11px] mt-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-850 dark:file:text-slate-200 cursor-pointer" required />
                <span class="text-[9px] text-slate-400 dark:text-slate-500 block mt-1">Format: PDF saja. Maksimal 10 MB.</span>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="<?= base_url('mahasiswa/penempatan') ?>" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-950 rounded-lg text-xs font-semibold transition">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs shadow shadow-blue-500/10 transition">
                    Ajukan Tempat Mandiri
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
