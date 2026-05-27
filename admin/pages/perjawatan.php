<?php
// Filename: admin/pages/perjawatan.php
// Version: 4.1 - Displays historical data with "Latest" indicator

// Pastikan sesi dimulakan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan fail konfigurasi pangkalan data
require_once __DIR__ . '/../../config/db.php';
$conn = connect_db();

// Semak jika ada mesej notifikasi dari sesi sebelumnya
$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);

// --- DAPATKAN ID TARIKH TERKINI ---
$latest_date_result = $conn->query("SELECT MAX(tarikh_id) as latest_date FROM fact_perjawatan");
$latest_date_id = $latest_date_result->fetch_assoc()['latest_date'];

// --- LOGIK PENOMBORAN (PAGINATION) ---
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$records_per_page = 15;
$offset = ($page_num - 1) * $records_per_page;

$total_records_result = $conn->query("SELECT COUNT(*) as total FROM fact_perjawatan");
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// --- PENGAMBILAN DATA UNTUK PAPARAN ---
$sql_select = "SELECT
                    fp.fact_id,
                    fp.tarikh_id,
                    dt.tarikh,
                    da.nama_agensi,
                    dk.nama_kumpulan,
                    fp.bil_perjawatan,
                    fp.bil_pengisian
                FROM fact_perjawatan fp
                JOIN dim_agensi da ON fp.agensi_id = da.agensi_id
                JOIN dim_kumpulan_perkhidmatan dk ON fp.kumpulan_id = dk.kumpulan_id
                JOIN dim_tarikh dt ON fp.tarikh_id = dt.tarikh_id
                ORDER BY dt.tarikh DESC, da.nama_agensi ASC, dk.nama_kumpulan ASC
                LIMIT ? OFFSET ?";

$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("ii", $records_per_page, $offset);
$stmt_select->execute();
$result = $stmt_select->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
$stmt_select->close();
$conn->close();
?>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Urus Data Perjawatan</h1>
            <p class="text-slate-500 mt-1">Edit atau padam rekod perjawatan. Rekod bertanda "Terkini" sedang dipaparkan di papan pemuka.</p>
        </div>
        <a href="index.php?page=tambah_perjawatan" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 w-full md:w-auto justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span>Tambah Rekod Baharu</span>
        </a>
    </div>

    <!-- Paparan Notifikasi -->
    <?php if ($notification): ?>
    <div id="notification-banner" class="rounded-md p-4 <?php echo $notification['type'] === 'success' ? 'bg-teal-50 text-teal-800' : 'bg-rose-50 text-rose-800'; ?> transition-opacity duration-500">
        <p class="text-sm font-medium"><?php echo htmlspecialchars($notification['message']); ?></p>
    </div>
    <?php endif; ?>

    <!-- Jadual Data -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tarikh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Agensi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kumpulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Perjawatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pengisian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <?php if (empty($records)): ?>
                        <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500">Tiada rekod ditemui.</td></tr>
                    <?php else: ?>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <?php echo date("d/m/Y", strtotime($record['tarikh'])); ?>
                                    <?php if ($record['tarikh_id'] == $latest_date_id): ?>
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800">
                                            Terkini
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate" title="<?php echo htmlspecialchars($record['nama_agensi']); ?>"><?php echo htmlspecialchars($record['nama_agensi']); ?></td>
                                <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate" title="<?php echo htmlspecialchars($record['nama_kumpulan']); ?>"><?php echo htmlspecialchars($record['nama_kumpulan']); ?></td>
                                <form method="POST" action="actions/perjawatan_actions.php">
                                    <input type="hidden" name="fact_id" value="<?php echo $record['fact_id']; ?>">
                                    <td class="px-6 py-4">
                                        <input type="number" name="bil_perjawatan" value="<?php echo $record['bil_perjawatan']; ?>" class="w-24 p-1 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="bil_pengisian" value="<?php echo $record['bil_pengisian']; ?>" class="w-24 p-1 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <button type="submit" name="action" value="update" class="text-indigo-600 hover:text-indigo-900 transition-colors">Simpan</button>
                                        <button type="button" onclick="showDeleteModal(<?php echo $record['fact_id']; ?>)" class="text-rose-600 hover:text-rose-900 transition-colors">Padam</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Penomboran Halaman -->
    <?php if ($total_pages > 1): ?>
    <nav class="flex items-center justify-between border-t border-slate-200 px-4 sm:px-0 mt-6 pt-4">
        <div class="text-sm text-slate-600">
            Halaman <?php echo $page_num; ?> dari <?php echo $total_pages; ?>
        </div>
        <div class="flex-1 flex justify-between sm:justify-end">
            <?php if ($page_num > 1): ?>
                <a href="index.php?page=perjawatan&p=<?php echo $page_num - 1; ?>" class="relative inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">Sebelumnya</a>
            <?php endif; ?>
            <?php if ($page_num < $total_pages): ?>
                <a href="index.php?page=perjawatan&p=<?php echo $page_num + 1; ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">Seterusnya</a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>
</div>


<!-- Modal Pengesahan Padam -->
<div id="delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 transition-opacity">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <div class="mt-3 text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100">
        <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
      </div>
      <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Padam Rekod</h3>
      <div class="mt-2 px-7 py-3">
        <p class="text-sm text-gray-500">Adakah anda pasti mahu memadam rekod ini? Tindakan ini tidak boleh dibatalkan.</p>
      </div>
      <div class="items-center px-4 py-3">
        <form id="delete-form" method="POST" action="actions/perjawatan_actions.php" class="inline-block">
            <input type="hidden" name="fact_id" id="fact_id_to_delete">
            <button type="submit" name="action" value="delete" class="px-4 py-2 bg-rose-500 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-300">
              Ya, Padam
            </button>
        </form>
        <button onclick="hideDeleteModal()" class="ml-2 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-auto shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>

<script>
    function showDeleteModal(factId) {
        document.getElementById('fact_id_to_delete').value = factId;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    function hideDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
    // Tutup notifikasi selepas 5 saat
    setTimeout(function() {
        const notification = document.getElementById('notification-banner');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => notification.style.display = 'none', 500);
        }
    }, 5000);
</script>

