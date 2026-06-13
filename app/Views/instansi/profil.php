<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-5">
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Profil Instansi</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Perbarui data narahubung dan informasi dasar instansi mitra.</p>
    </div>

    <form method="post" action="<?= base_url('instansi/profil/update') ?>" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 space-y-4">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Nama Instansi</span>
                <input name="name" value="<?= old('name', $instansi->name ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm" required>
            </label>
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Bidang Usaha</span>
                <input name="field_category" value="<?= old('field_category', $instansi->field_category ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
            </label>
            <label class="space-y-1 md:col-span-2">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Alamat</span>
                <textarea name="address" rows="3" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm" required><?= old('address', $instansi->address ?? '') ?></textarea>
            </label>
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Kota/Kabupaten</span>
                <input name="city" value="<?= old('city', $instansi->city ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
            </label>
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Provinsi</span>
                <input name="province" value="<?= old('province', $instansi->province ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
            </label>
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Narahubung</span>
                <input name="contact_person" value="<?= old('contact_person', $instansi->contact_person ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
            </label>
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Jabatan</span>
                <input name="contact_position" value="<?= old('contact_position', $instansi->contact_position ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
            </label>
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Nomor HP</span>
                <input name="contact_phone" value="<?= old('contact_phone', $instansi->contact_phone ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
            </label>
            <label class="space-y-1">
                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Email</span>
                <input name="contact_email" value="<?= old('contact_email', $instansi->contact_email ?? '') ?>" class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 text-sm">
            </label>
        </div>
        <div class="flex justify-end">
            <button class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                <i class="ti ti-device-floppy"></i> Simpan Profil
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
