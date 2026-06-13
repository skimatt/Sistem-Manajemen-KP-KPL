<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin/form-builder') ?>" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            <i class="ti ti-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Kelola Field Formulir</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Definisikan elemen input untuk formulir: <strong><?= esc($template['name']) ?></strong>.</p>
        </div>
    </div>

    <!-- Main Content Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 columns: List of Fields -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Struktur Input Saat Ini</h3>

            <?php if (empty($fields)): ?>
                <div class="p-8 text-center text-slate-400 dark:text-slate-500 italic text-xs">
                    Belum ada field didefinisikan. Gunakan form di sebelah kanan untuk menambahkan field baru.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                        <thead>
                            <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider font-semibold">
                                <th class="pb-3">Urutan</th>
                                <th class="pb-3">Label UI</th>
                                <th class="pb-3">Nama Field</th>
                                <th class="pb-3">Tipe Input</th>
                                <th class="pb-3">Pilihan (Select)</th>
                                <th class="pb-3">Wajib</th>
                                <th class="pb-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            <?php foreach ($fields as $f): ?>
                                <tr>
                                    <td class="py-3.5 font-mono text-slate-500"><?= esc($f['sort_order']) ?></td>
                                    <td class="py-3.5 font-semibold text-slate-850 dark:text-slate-200"><?= esc($f['label']) ?></td>
                                    <td class="py-3.5 font-mono text-slate-500"><?= esc($f['field_name']) ?></td>
                                    <td class="py-3.5 capitalize font-medium text-blue-600 dark:text-blue-400"><?= esc($f['field_type']) ?></td>
                                    <td class="py-3.5">
                                        <?php if ($f['options_json']): ?>
                                            <?php 
                                            $opts = json_decode($f['options_json'], true); 
                                            if (is_array($opts)) {
                                                echo '<span class="inline-flex flex-wrap gap-1">';
                                                foreach ($opts as $opt) {
                                                    echo '<span class="px-1.5 py-0.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded text-[9px]">' . esc($opt) . '</span>';
                                                }
                                                echo '</span>';
                                            }
                                            ?>
                                        <?php else: ?>
                                            <span class="text-slate-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5">
                                        <?php if ($f['is_required'] == 1): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-900/40">
                                                Ya
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                Tidak
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 text-right">
                                        <button onclick="confirmDelete(<?= $f['id'] ?>, '<?= esc($f['label']) ?>')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-700 dark:text-red-400 rounded-md transition text-[10px] font-bold" title="Hapus Field">
                                            <i class="ti ti-trash text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right column: Add Field Form -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm h-fit">
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80 pb-2 mb-4">Tambah Field Baru</h3>
            
            <form action="<?= base_url('admin/form-builder/fields/' . $template['id'] . '/add') ?>" method="POST" class="space-y-4 text-xs">
                <?= csrf_field() ?>

                <!-- Field: label -->
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Label Input (UI) <span class="text-red-500">*</span></label>
                    <input type="text" name="label" placeholder="Contoh: Nomor HP Instansi" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required />
                </div>

                <!-- Field: field_name -->
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Nama Field Internal (Db) <span class="text-red-500">*</span></label>
                    <input type="text" name="field_name" placeholder="Contoh: phone_number" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required />
                </div>

                <!-- Field: field_type -->
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Tipe Input <span class="text-red-500">*</span></label>
                    <select name="field_type" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" required>
                        <option value="text">Teks Pendek (Text)</option>
                        <option value="textarea">Teks Panjang (Textarea)</option>
                        <option value="number">Angka (Number)</option>
                        <option value="date">Tanggal (Date)</option>
                        <option value="select">Pilihan Dropdown (Select)</option>
                        <option value="file">Berkas File Upload (File)</option>
                    </select>
                </div>

                <!-- Field: options_json -->
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Pilihan Dropdown (Select Only)</label>
                    <input type="text" name="options_json" placeholder="Contoh: Sangat Baik, Baik, Cukup" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                    <span class="text-[10px] text-slate-400 mt-1 block">Pisahkan dengan koma jika menggunakan tipe input dropdown.</span>
                </div>

                <!-- Field: validation_rules -->
                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300">Validation Rules (Opsional)</label>
                    <input type="text" name="validation_rules" placeholder="Contoh: min_length[5]|numeric" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20" />
                </div>

                <!-- Field: is_required & sort_order -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Wajib Diisi <span class="text-red-500">*</span></label>
                        <select name="is_required" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-100 focus:outline-none focus:border-blue-500" required>
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300">Urutan Tampil <span class="text-red-500">*</span></label>
                        <input type="number" name="sort_order" value="0" class="block w-full mt-1.5 px-3 py-2 bg-white dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850" required />
                    </div>
                </div>

                <!-- Buttons -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-sm transition">
                        Tambahkan Field
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function confirmDelete(fieldId, label) {
        Swal.fire({
            title: 'Hapus field input?',
            text: "Anda akan menghapus field input: " + label,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'dark:bg-slate-900 dark:border-slate-800 text-slate-800 dark:text-slate-100',
                title: 'text-slate-800 dark:text-slate-100',
                htmlContainer: 'text-slate-600 dark:text-slate-300'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('admin/form-builder/fields/' . $template['id'] . '/delete') ?>/" + fieldId;
            }
        });
    }
</script>
<?= $this->endSection() ?>
