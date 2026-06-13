<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Section -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Audit Log Aktivitas</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar riwayat aksi dan jejak audit aktivitas pengguna di dalam sistem.</p>
    </div>

    <!-- Filter Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-5 shadow-sm">
        <form method="GET" action="<?= base_url('admin/audit-log') ?>" class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
            <!-- Action filter -->
            <div>
                <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Aksi / Event</label>
                <select name="action" class="block w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-150 focus:outline-none">
                    <option value="">-- Semua Aksi --</option>
                    <?php foreach ($uniqueActions as $act): ?>
                        <option value="<?= esc($act) ?>" <?= $actionFilter === $act ? 'selected' : '' ?>><?= esc($act) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Email filter -->
            <div>
                <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1.5">User Email</label>
                <input type="text" name="email" value="<?= esc($emailFilter) ?>" placeholder="Cari email..." class="block w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-800 dark:text-slate-150 focus:outline-none" />
            </div>

            <!-- Date filter -->
            <div>
                <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Tanggal (YYYY-MM-DD)</label>
                <input type="date" name="date" value="<?= esc($dateFilter) ?>" class="block w-full px-3 py-2 bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-850 dark:text-slate-150 focus:outline-none" />
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2.5">
                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition">
                    Terapkan Filter
                </button>
                <a href="<?= base_url('admin/audit-log') ?>" class="py-2 px-3 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg text-slate-500 transition" title="Reset">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card Wrapper -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-slate-600 dark:text-slate-350">
                <thead>
                    <tr class="text-slate-400 dark:text-slate-500 border-b border-slate-200 dark:border-slate-800 uppercase text-[9px] tracking-wider font-semibold">
                        <th class="pb-3 w-40">Waktu</th>
                        <th class="pb-3">User</th>
                        <th class="pb-3">Role</th>
                        <th class="pb-3">Aksi</th>
                        <th class="pb-3">Tabel / ID</th>
                        <th class="pb-3">Catatan</th>
                        <th class="pb-3 text-right">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 dark:text-slate-500 italic">Jejak audit tidak ditemukan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="py-3.5 font-mono text-slate-500 dark:text-slate-450"><?= esc($log['created_at']) ?></td>
                                <td class="py-3.5">
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block"><?= esc($log['user_name'] ?? 'System') ?></span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono"><?= esc($log['email'] ?? 'system_process') ?></span>
                                </td>
                                <td class="py-3.5 font-semibold capitalize"><?= esc($log['role']) ?></td>
                                <td class="py-3.5 font-mono font-semibold text-blue-600 dark:text-blue-400"><?= esc($log['action']) ?></td>
                                <td class="py-3.5">
                                    <?php if ($log['table_name']): ?>
                                        <span class="font-medium text-slate-700 dark:text-slate-300"><?= esc($log['table_name']) ?></span>
                                        <span class="font-mono text-[10px] text-slate-400 block">ID: <?= esc($log['record_id']) ?></span>
                                    <?php else: ?>
                                        <span class="text-slate-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 text-slate-700 dark:text-slate-300"><?= esc($log['note']) ?></td>
                                <td class="py-3.5 text-right">
                                    <?php if ($log['old_values'] || $log['new_values']): ?>
                                        <button onclick='viewDetails(<?= json_encode($log['old_values']) ?>, <?= json_encode($log['new_values']) ?>, "<?= esc($log['action']) ?>")' class="px-2 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-md transition font-semibold text-[10px]">
                                            Lihat JSON
                                        </button>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-[10px]">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination Links -->
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-850/60">
            <div class="text-slate-400 dark:text-slate-500 text-[11px]">
                Menampilkan halaman <?= $pager->getCurrentPage('logs') ?> dari <?= $pager->getPageCount('logs') ?>
            </div>
            <div class="inline-flex gap-1.5">
                <?= $pager->links('logs', 'default_full') ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function viewDetails(oldVal, newVal, action) {
        let oldObj = oldVal ? JSON.parse(oldVal) : null;
        let newObj = newVal ? JSON.parse(newVal) : null;

        let content = `<div class="text-left font-mono text-[10px] space-y-4 max-h-96 overflow-y-auto">`;
        if (oldObj) {
            content += `<div>
                <strong class="text-red-500 block mb-1 uppercase tracking-wider">Nilai Lama (Sebelum):</strong>
                <pre class="bg-slate-50 dark:bg-slate-950 p-2.5 rounded border border-slate-200 dark:border-slate-800 overflow-x-auto text-slate-700 dark:text-slate-300">${JSON.stringify(oldObj, null, 2)}</pre>
            </div>`;
        }
        if (newObj) {
            content += `<div>
                <strong class="text-emerald-500 block mb-1 uppercase tracking-wider">Nilai Baru (Sesudah):</strong>
                <pre class="bg-slate-50 dark:bg-slate-950 p-2.5 rounded border border-slate-200 dark:border-slate-800 overflow-x-auto text-slate-700 dark:text-slate-300">${JSON.stringify(newObj, null, 2)}</pre>
            </div>`;
        }
        content += `</div>`;

        Swal.fire({
            title: `Jejak Data: ${action}`,
            html: content,
            width: '600px',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'dark:bg-slate-900 dark:border-slate-800 text-slate-800 dark:text-slate-100',
                title: 'text-slate-800 dark:text-slate-100'
            }
        });
    }
</script>
<?= $this->endSection() ?>
