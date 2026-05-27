<?php
// Filename: admin/pages/pengguna.php
// Version: 1.2 - Added Error Reporting & Session Check

// --- PENAMBAHBAIKAN: Paparkan semua ralat untuk tujuan debugging ---
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- PENAMBAHBAIKAN: Pastikan sesi sentiasa dimulakan ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
$conn = connect_db();

// Semak notifikasi dari sesi
$notification = $_SESSION['notification'] ?? null;
unset($_SESSION['notification']);

// --- LOGIK PEMPROSESAN BORANG (CREATE, UPDATE, DELETE) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_success = false;
    $error_message = '';

    // Logik untuk Tambah Pengguna Baharu
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        
        // Semakan asas
        if (empty($username) || empty($password) || empty($role)) {
            $error_message = 'Semua medan diperlukan.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashed_password, $role);
            if ($stmt->execute()) {
                $action_success = true;
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Pengguna baharu berjaya ditambah.'];
            } else {
                // Ralat jika username sudah wujud atau isu pangkalan data lain
                $error_message = 'Gagal menambah pengguna. Nama pengguna mungkin sudah wujud.';
            }
        }
    }

    // Logik untuk Kemas Kini Pengguna
    if (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, role = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $role, $hashed_password, $user_id);
        } else {
            $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $role, $user_id);
        }

        if ($stmt->execute()) {
            $action_success = true;
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Maklumat pengguna berjaya dikemas kini.'];
        } else {
            $error_message = 'Gagal mengemas kini pengguna: ' . $stmt->error;
        }
    }

    // Logik untuk Padam Pengguna
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        if ($user_id == ($_SESSION['user_id'] ?? 0)) {
             $error_message = 'Anda tidak boleh memadam akaun anda sendiri.';
        } else {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $action_success = true;
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Pengguna berjaya dipadam.'];
            } else {
                $error_message = 'Gagal memadam pengguna: ' . $stmt->error;
            }
        }
    }
    
    if (!$action_success && !empty($error_message)) {
        $_SESSION['notification'] = ['type' => 'error', 'message' => $error_message];
    }

    // Redirect selepas selesai
    header("Location: index.php?page=pengguna");
    exit();
}

// Dapatkan senarai semua pengguna untuk paparan
$users_result = $conn->query("SELECT id, username, role FROM users ORDER BY username ASC");
$users = $users_result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-slate-800">Pengurusan Pengguna</h1>
        <button onclick="openAddModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span>Tambah Pengguna Baharu</span>
        </button>
    </div>

    <!-- Paparan Notifikasi -->
    <?php if ($notification): ?>
    <div id="notification-banner" class="rounded-md p-4 <?php echo $notification['type'] === 'success' ? 'bg-teal-50 text-teal-800' : 'bg-rose-50 text-rose-800'; ?>">
        <p class="text-sm font-medium"><?php echo htmlspecialchars($notification['message']); ?></p>
    </div>
    <?php endif; ?>

    <!-- Jadual Pengguna -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Peranan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <?php if (empty($users)): ?>
                        <tr><td colspan="3" class="px-6 py-4 text-center text-slate-500">Tiada pengguna ditemui.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['role'] === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800'; ?>">
                                    <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-4">
                                <button onclick='openEditModal(<?php echo json_encode($user); ?>)' class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <?php if (($_SESSION['user_id'] ?? 0) != $user['id']): // Jangan tunjuk butang padam untuk diri sendiri ?>
                                <button onclick="openDeleteModal(<?php echo $user['id']; ?>)" class="text-rose-600 hover:text-rose-900">Padam</button>
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

<!-- Modal Tambah Pengguna -->
<div id="add-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <form method="POST" action="index.php?page=pengguna">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Pengguna Baharu</h3>
            <div class="space-y-4">
                <div>
                    <label for="add-username" class="block text-sm font-medium text-gray-700">Nama Pengguna</label>
                    <input type="text" name="username" id="add-username" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="add-password" class="block text-sm font-medium text-gray-700">Kata Laluan</label>
                    <input type="password" name="password" id="add-password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="add-role" class="block text-sm font-medium text-gray-700">Peranan</label>
                    <select name="role" id="add-role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeAddModal()" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit" name="add_user" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Tambah Pengguna</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="edit-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <form method="POST" action="index.php?page=pengguna">
            <input type="hidden" name="user_id" id="edit-user-id">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Pengguna</h3>
            <div class="space-y-4">
                <div>
                    <label for="edit-username" class="block text-sm font-medium text-gray-700">Nama Pengguna</label>
                    <input type="text" name="username" id="edit-username" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="edit-password" class="block text-sm font-medium text-gray-700">Kata Laluan Baharu (biarkan kosong jika tidak mahu tukar)</label>
                    <input type="password" name="password" id="edit-password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="edit-role" class="block text-sm font-medium text-gray-700">Peranan</label>
                    <select name="role" id="edit-role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Batal</button>
                <button type="submit" name="edit_user" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Padam Pengguna -->
<div id="delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <form method="POST" action="index.php?page=pengguna">
        <input type="hidden" name="user_id" id="delete-user-id">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100">
            <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
          </div>
          <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Padam Pengguna</h3>
          <p class="text-sm text-gray-500 mt-2">Adakah anda pasti mahu memadam pengguna ini? Tindakan ini tidak boleh dibatalkan.</p>
        </div>
        <div class="mt-5 flex justify-center space-x-3">
            <button type="button" onclick="closeDeleteModal()" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Batal</button>
            <button type="submit" name="delete_user" class="bg-rose-600 text-white px-4 py-2 rounded-md hover:bg-rose-700">Ya, Padam</button>
        </div>
    </form>
  </div>
</div>

<script>
    // Fungsi untuk Modal Tambah
    function openAddModal() { document.getElementById('add-modal').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('add-modal').classList.add('hidden'); }

    // Fungsi untuk Modal Edit
    function openEditModal(user) {
        document.getElementById('edit-user-id').value = user.id;
        document.getElementById('edit-username').value = user.username;
        document.getElementById('edit-role').value = user.role;
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('edit-modal').classList.add('hidden'); }
    
    // Fungsi untuk Modal Padam
    function openDeleteModal(userId) {
        document.getElementById('delete-user-id').value = userId;
        document.getElementById('delete-modal').classList.remove('hidden');
    }
    function closeDeleteModal() { document.getElementById('delete-modal').classList.add('hidden'); }
</script>

