<?php
// Filename: admin/index.php
// Version: 5.0 - Integrated User Dashboard Preview

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user']) || isset($_POST['edit_user']) || isset($_POST['delete_user'])) {
        require __DIR__ . '/actions/user_actions.php';
    }
}

$username = $_SESSION['username'];
$page = $_GET['page'] ?? 'dashboard';

// Senarai halaman yang dibenarkan: admin pages + user view pages
$allowed_admin_pages = ['dashboard', 'perjawatan', 'tambah_perjawatan', 'pengguna'];
$allowed_view_pages = ['view_ringkasan', 'view_perjawatan', 'view_demografi', 'view_latihan', 'view_profil', 'view_oku'];
$all_pages = array_merge($allowed_admin_pages, $allowed_view_pages);

if (!in_array($page, $all_pages)) {
    $page = 'dashboard';
}

// Tentukan tajuk halaman
if (strpos($page, 'view_') === 0) {
    $page_title = 'Pratonton: ' . ucfirst(str_replace('view_', '', $page));
} else {
    $page_title = ucfirst(str_replace('_', ' ', $page));
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Panel Pentadbir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .sidebar-active { background-color: #4338ca; color: white; }
    </style>
</head>
<body class="flex h-screen bg-slate-100">
    <aside class="w-72 bg-slate-800 text-white flex flex-col flex-shrink-0">
        <div class="p-6 text-center border-b border-slate-700">
            <h1 class="text-xl font-bold">Panel Pentadbir</h1>
            <p class="text-sm text-slate-400 mt-1">Selamat Datang, <?php echo htmlspecialchars($username); ?></p>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="index.php?page=dashboard" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors <?php echo ($page === 'dashboard') ? 'sidebar-active' : ''; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Papan Utama Admin</span>
            </a>
             <!-- Bahagian Pratonton -->
            <div class="pt-4">
                <h3 class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pratonton Papan Pemuka</h3>
                <div class="mt-2 space-y-2">
                    <a href="index.php?page=view_ringkasan" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors <?php echo ($page === 'view_ringkasan') ? 'sidebar-active' : ''; ?>"><span>Ringkasan Eksekutif</span></a>
                    <a href="index.php?page=view_perjawatan" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors <?php echo ($page === 'view_perjawatan') ? 'sidebar-active' : ''; ?>"><span>Analisis Perjawatan</span></a>
                    <a href="index.php?page=view_demografi" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors <?php echo ($page === 'view_demografi') ? 'sidebar-active' : ''; ?>"><span>Demografi Pegawai</span></a>
                </div>
            </div>
            <!-- Bahagian Pengurusan Data -->
            <div class="pt-4">
                <h3 class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengurusan Data</h3>
                <div class="mt-2 space-y-2">
                     <a href="index.php?page=perjawatan" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors <?php echo ($page === 'perjawatan') ? 'sidebar-active' : ''; ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Urus Data Perjawatan</span>
                    </a>
                    <a href="index.php?page=tambah_perjawatan" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors <?php echo ($page === 'tambah_perjawatan') ? 'sidebar-active' : ''; ?>">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Tambah Data Perjawatan</span>
                    </a>
                </div>
            </div>
             <div class="pt-4">
                <h3 class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pentadbiran Sistem</h3>
                <div class="mt-2 space-y-2">
                     <a href="index.php?page=pengguna" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-700 transition-colors <?php echo ($page === 'pengguna') ? 'sidebar-active' : ''; ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"></path></svg>
                        <span>Pengurusan Pengguna</span>
                    </a>
                </div>
            </div>
        </nav>
        <div class="p-4 border-t border-slate-700">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-rose-600 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Log Keluar</span>
            </a>
        </div>
    </aside>
    <main class="flex-1 p-8 overflow-y-auto">
        <?php
        // Logik untuk memuatkan fail halaman yang betul
        if (strpos($page, 'view_') === 0) {
            // Jika ia adalah halaman pratonton, muatkan dari folder ../pages/
            $user_page = str_replace('view_', '', $page);
            include __DIR__ . "/../pages/{$user_page}.php";
        } else {
            // Jika ia adalah halaman admin, muatkan dari folder ./pages/
            include __DIR__ . "/pages/{$page}.php";
        }
        ?>
    </main>
</body>
</html>

