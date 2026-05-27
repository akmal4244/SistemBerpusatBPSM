<?php
// Filename: admin/pages/tambah_perjawatan.php
// Version: 5.0 - Bulk Data Entry Table Interface

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
$conn = connect_db();

// Dapatkan senarai semua agensi dan kumpulan untuk borang
$agencies_result = $conn->query("SELECT agensi_id, nama_agensi FROM dim_agensi ORDER BY agensi_id ASC");
$agencies = $agencies_result->fetch_all(MYSQLI_ASSOC);

$kumpulan_result = $conn->query("SELECT kumpulan_id, nama_kumpulan FROM dim_kumpulan_perkhidmatan ORDER BY nama_kumpulan ASC");
$kumpulan_list = $kumpulan_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);
?>

<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Tambah Sesi Data Perjawatan Baharu</h1>
        <p class="text-slate-500 mt-1">Pilih satu tarikh dan isi maklumat untuk semua agensi di bawah.</p>
    </div>

    <?php if ($notification): ?>
    <div id="notification-banner" class="rounded-md p-4 <?php echo $notification['type'] === 'success' ? 'bg-teal-50 text-teal-800' : 'bg-rose-50 text-rose-800'; ?>">
        <p class="text-sm font-medium"><?php echo htmlspecialchars($notification['message']); ?></p>
    </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-lg shadow-sm border border-slate-200">
        <form action="actions/perjawatan_actions.php" method="POST">
            <!-- Pemilihan Tarikh -->
            <div class="mb-8">
                <label for="tarikh" class="block text-lg font-semibold text-slate-700">Langkah 1: Pilih Tarikh Sesi</label>
                <input type="date" name="tarikh" id="tarikh" required class="mt-2 block w-full md:w-1/3 rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="text-xs text-slate-500 mt-1">Semua rekod di bawah akan disimpan di bawah tarikh ini.</p>
            </div>
            
            <label class="block text-lg font-semibold text-slate-700 mb-4">Langkah 2: Isi Maklumat Perjawatan</label>
            <!-- Jadual Kemasukan Data -->
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Agensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kumpulan Perkhidmatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Bil. Perjawatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Bil. Pengisian</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php foreach ($agencies as $agency): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    <?php echo htmlspecialchars($agency['nama_agensi']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <select name="data[<?php echo $agency['agensi_id']; ?>][kumpulan_id]" required class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Sila Pilih --</option>
                                        <?php foreach ($kumpulan_list as $kumpulan): ?>
                                            <option value="<?php echo $kumpulan['kumpulan_id']; ?>"><?php echo htmlspecialchars($kumpulan['nama_kumpulan']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="data[<?php echo $agency['agensi_id']; ?>][bil_perjawatan]" required class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="0" value="0">
                                </td>
                                 <td class="px-6 py-4">
                                    <input type="number" name="data[<?php echo $agency['agensi_id']; ?>][bil_pengisian]" required class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="0" value="0">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Butang Hantar -->
            <div class="flex justify-end pt-6">
                <button type="submit" name="action" value="add_bulk" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Semua Sesi
                </button>
            </div>
        </form>
    </div>
</div>

