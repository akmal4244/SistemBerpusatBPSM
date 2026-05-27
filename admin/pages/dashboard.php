<?php
// Filename: admin/pages/dashboard.php
// Version: 3.0 - Comprehensive & Data-Driven

require_once __DIR__ . '/../../config/db.php';
$conn = connect_db();

// --- PENGAMBILAN DATA DINAMIK UNTUK PAPAN UTAMA ---

// 1. Dapatkan Statistik Sistem
$total_users = $conn->query("SELECT COUNT(id) as count FROM users")->fetch_assoc()['count'];
$total_perjawatan_records = $conn->query("SELECT COUNT(fact_id) as count FROM fact_perjawatan")->fetch_assoc()['count'];
$latest_date_result = $conn->query("SELECT MAX(tarikh) as date FROM dim_tarikh");
$latest_update = ($latest_date_result && $latest_date_result->num_rows > 0) ? date("d M Y", strtotime($latest_date_result->fetch_assoc()['date'])) : 'Tiada Data';

// 2. Dapatkan Metrik Kesihatan Data (Kadar Pengisian Keseluruhan)
$kadar_pengisian_peratus = 0;
$kpi_query = "SELECT SUM(bil_perjawatan) as total_perjawatan, SUM(bil_pengisian) as total_pengisian 
              FROM fact_perjawatan 
              WHERE tarikh_id = (SELECT MAX(tarikh_id) FROM fact_perjawatan)";
$kpi_result = $conn->query($kpi_query);
if ($kpi_result && $kpi_result->num_rows > 0) {
    $kpi_row = $kpi_result->fetch_assoc();
    if ($kpi_row['total_perjawatan'] > 0) {
        $kadar_pengisian_peratus = ($kpi_row['total_pengisian'] / $kpi_row['total_perjawatan']) * 100;
    }
}

$conn->close();
?>

<div class="space-y-10">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Papan Utama Pentadbir</h1>
        <p class="text-slate-500 mt-1">Ringkasan status sistem, kesihatan data, dan pautan pantas untuk pengurusan.</p>
    </div>

    <!-- Kad KPI Sistem -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h3 class="text-sm font-medium text-slate-500">Jumlah Pengguna Aktif</h3>
            <p class="text-4xl font-bold text-slate-800 mt-2"><?php echo $total_users; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h3 class="text-sm font-medium text-slate-500">Rekod Data Perjawatan</h3>
            <p class="text-4xl font-bold text-slate-800 mt-2"><?php echo number_format($total_perjawatan_records); ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h3 class="text-sm font-medium text-slate-500">Kemas Kini Data Terakhir</h3>
            <p class="text-4xl font-bold text-slate-800 mt-2"><?php echo $latest_update; ?></p>
        </div>
        <!-- Kad Kesihatan Data -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h3 class="text-sm font-medium text-slate-500">Kadar Pengisian Keseluruhan</h3>
            <p class="text-4xl font-bold text-teal-600 mt-2"><?php echo number_format($kadar_pengisian_peratus, 2); ?>%</p>
            <div class="w-full bg-slate-200 rounded-full h-2.5 mt-2">
                <div class="bg-teal-500 h-2.5 rounded-full" style="width: <?php echo $kadar_pengisian_peratus; ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Pautan Tindakan Pantas -->
    <div>
        <h2 class="text-xl font-semibold text-slate-700">Tindakan Pantas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
            <a href="index.php?page=tambah_perjawatan" class="block p-6 bg-white rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-100 rounded-lg">
                         <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Tambah Data Baharu</h3>
                        <p class="text-sm text-slate-500">Masukkan rekod perjawatan.</p>
                    </div>
                </div>
            </a>
             <a href="index.php?page=pengguna" class="block p-6 bg-white rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-rose-100 rounded-lg">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Urus Pengguna Sistem</h3>
                        <p class="text-sm text-slate-500">Tambah, edit, atau padam akaun.</p>
                    </div>
                </div>
            </a>
            <a href="index.php?page=view_ringkasan" class="block p-6 bg-white rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:-translate-y-1 transition-all">
                 <div class="flex items-center gap-4">
                    <div class="p-3 bg-sky-100 rounded-lg">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Pratonton Papan Pemuka</h3>
                        <p class="text-sm text-slate-500">Lihat paparan pengguna penuh.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

