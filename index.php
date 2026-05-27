<?php
// Filename: index.php
// Version: 4.1 - Removed Profil Menu

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] === 'admin') {
    header("Location: admin/index.php");
    exit();
}

$username = $_SESSION['username'];
$page = $_GET['page'] ?? 'ringkasan';

// Senarai halaman yang dibenarkan (tanpa 'profil')
$allowed_pages = [
    'ringkasan'     => ['label' => 'Ringkasan Eksekutif', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>'],
    'perjawatan'    => ['label' => 'Analisis Perjawatan', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'],
    'demografi'     => ['label' => 'Demografi Pegawai', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'],
    'latihan'       => ['label' => 'Prestasi Latihan', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.747h18"></path><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>'],
    'oku'           => ['label' => 'Status OKU & Orang Asli', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>']
];

if (!array_key_exists($page, $allowed_pages)) {
    $page = 'ringkasan';
}

$page_title = $allowed_pages[$page]['label'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Dashboard Sumber Manusia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .sidebar-active { background-color: #312e81; color: white; }
    </style>
</head>
<body class="flex h-screen">
     <aside class="flex-shrink-0 w-80 bg-slate-900 text-slate-300 flex flex-col">
        <div class="p-6 text-center border-b border-slate-700">
            <img src="assets/logokpm1.png" alt="Logo KPM" class="w-auto h-28 mx-auto">
            <h1 class="text-xl font-bold text-white mt-4">Dashboard<br>Sumber Manusia</h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php
            foreach ($allowed_pages as $key => $item) {
                $is_active = ($page === $key) ? 'sidebar-active' : '';
                echo "<a href='index.php?page={$key}' class='flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-slate-700 transition-colors {$is_active}'>{$item['icon']}<span>{$item['label']}</span></a>";
            }
            ?>
        </nav>
        <div class="p-4 border-t border-slate-700">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-rose-600 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span>Log Keluar</span>
            </a>
        </div>
    </aside>
    <main class="flex-1 p-8 overflow-y-auto">
        <?php
        $page_path = __DIR__ . "/pages/{$page}.php";
        if (file_exists($page_path)) {
            include $page_path;
        } else {
            echo "<p>Halaman tidak ditemui.</p>";
        }
        ?>
        <footer class="text-center text-sm text-slate-500 mt-8">
            Versi 4.1 | Laluan Fail: /index.php | Tarikh Kemas Kini: <?php echo date('Y-m-d'); ?>
        </footer>
    </main>
</body>
</html>

