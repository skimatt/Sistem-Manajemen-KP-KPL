<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ openModal: false, activeLog: null }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Riwayat Keputusan Saya</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Daftar tindakan audit dan keputusan akademik resmi yang telah Anda lakukan di dalam sistem.</p>
        </div>
    </div>

    <!-- Audit Logs List Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="p-6">
            <div class="table-responsive">
                <table id="keputusanKoorTable" class="w-full text-xs text-left text-slate-600 dark:text-slate-400">
                    <thead class="text-[10px] uppercase bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 border-b border-slate-200/60 dark:border-slate-850">
                        <tr>
                            <th class="px-6 py-3.5 font-bold">Waktu Tindakan</th>
                            <th class="px-6 py-3.5 font-bold">Aksi / Fitur</th>
                            <th class="px-6 py-3.5 font-bold">Target Data</th>
                            <th class="px-6 py-3.5 font-bold">Keterangan Aktivitas</th>
                            <th class="px-6 py-3.5 font-bold text-center">IP Address</th>
                            <th class="px-6 py-3.5 font-bold text-center">Detail Perubahan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                        <?= date('d M Y, H:i:s', strtotime($log['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 dark:bg-blue-950/45 dark:text-blue-400">
                                            <?= esc($log['action']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 dark:text-slate-350 text-[10px]"><?= esc($log['table_name']) ?></div>
                                        <div class="text-[9px] text-slate-400 dark:text-slate-500 mt-0.5">ID Record: <?= esc($log['record_id']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-200 max-w-xs truncate" title="<?= esc($log['note']) ?>">
                                        <?= esc($log['note']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center text-slate-500 dark:text-slate-400 font-mono text-[10px]">
                                        <?= esc($log['ip_address']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if (!empty($log['old_values']) || !empty($log['new_values'])): ?>
                                            <button @click="activeLog = <?= htmlspecialchars(json_encode([
                                                'id' => $log['id'],
                                                'action' => $log['action'],
                                                'note' => $log['note'],
                                                'old' => $log['old_values'] ? json_decode($log['old_values'], true) : null,
                                                'new' => $log['new_values'] ? json_decode($log['new_values'], true) : null
                                            ])) ?>; openModal = true" 
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded font-semibold transition text-[10px]">
                                                <i class="ti ti-zoom-code text-xs"></i> Inspect
                                            </button>
                                        <?php else: ?>
                                            <span class="text-slate-300 dark:text-slate-600 italic">No Diff</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JSON Inspector Modal (Alpine) -->
    <div x-show="openModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="openModal = false"
         style="display: none;">
        
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-3xl overflow-hidden shadow-2xl flex flex-col max-h-[85vh]"
             @click.away="openModal = false">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                <div>
                    <span class="text-[9px] uppercase font-black tracking-wider px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-400" x-text="activeLog?.action"></span>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1" x-text="activeLog?.note"></h3>
                </div>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                    <i class="ti ti-x text-lg"></i>
                </button>
            </div>

            <!-- Modal Content (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-4 text-xs font-mono">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Old values -->
                    <div class="space-y-2">
                        <span class="font-sans font-bold text-slate-500 dark:text-slate-400 block border-b pb-1">Sebelum Perubahan (Old)</span>
                        <pre class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-4 rounded-xl max-h-72 overflow-auto text-[10px] text-slate-700 dark:text-slate-300" x-text="activeLog?.old ? JSON.stringify(activeLog.old, null, 2) : 'NULL'"></pre>
                    </div>

                    <!-- New values -->
                    <div class="space-y-2">
                        <span class="font-sans font-bold text-slate-500 dark:text-slate-400 block border-b pb-1">Sesudah Perubahan (New)</span>
                        <pre class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850 p-4 rounded-xl max-h-72 overflow-auto text-[10px] text-slate-700 dark:text-slate-300" x-text="activeLog?.new ? JSON.stringify(activeLog.new, null, 2) : 'NULL'"></pre>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50 dark:bg-slate-850/20 flex justify-end">
                <button @click="openModal = false" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold rounded-xl transition text-xs">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#keputusanKoorTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
            },
            order: [[0, 'desc']]
        });
    });
</script>
<?= $this->endSection() ?>
