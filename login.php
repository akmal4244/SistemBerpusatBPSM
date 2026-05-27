<?php
// Filename: login.php
// Version: 4.1 - Hardcoded Admin Fallback

// Mulakan sesi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika pengguna sudah log masuk, halakan mereka
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

require_once __DIR__ . '/config/db.php';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- LOGIK HARDCODED UNTUK ADMIN ---
    // Semak jika ini adalah percubaan log masuk admin dengan kata laluan khas
    if ($username === 'akmal4244' && $password === '123') {
        // Terus berikan akses tanpa menyemak pangkalan data
        $_SESSION['user_id'] = 1; // ID admin tetap
        $_SESSION['username'] = 'akmal4244';
        $_SESSION['role'] = 'admin';
        header("Location: admin/index.php");
        exit();
    }
    // --- TAMAT LOGIK HARDCODED ---

    // Jika bukan admin hardcoded, teruskan dengan logik pangkalan data
    $conn = connect_db();

    if (empty($username) || empty($password)) {
        $error_message = "Sila masukkan nama pengguna dan kata laluan.";
    } else {
        // Gunakan prepared statements untuk keselamatan
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Sahkan kata laluan yang di-hash
            if (password_verify($password, $user['password'])) {
                // Kata laluan betul, mulakan sesi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Halakan pengguna berdasarkan peranan
                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error_message = "Nama pengguna atau kata laluan tidak sah.";
            }
        } else {
            $error_message = "Nama pengguna atau kata laluan tidak sah.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk - Dashboard Sumber Manusia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-md rounded-lg p-8">
            <div class="text-center mb-6">
                <img src="assets/logokpm1.png" alt="Logo KPM" class="mx-auto h-20 w-auto">
                <h1 class="text-2xl font-bold text-slate-800 mt-4">Dashboard Sumber Manusia</h1>
                <p class="text-slate-500">Sila log masuk untuk meneruskan</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="bg-rose-100 border border-rose-200 text-rose-700 px-4 py-3 rounded-md mb-6" role="alert">
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700">Nama Pengguna</label>
                    <div class="mt-1">
                        <input id="username" name="username" type="text" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Kata Laluan</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Log Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

