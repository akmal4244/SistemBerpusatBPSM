<?php
// Filename: admin/actions/user_actions.php
// Version: 1.0 - Handles all user management form submissions

// Pastikan sesi dimulakan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan fail konfigurasi pangkalan data
require_once __DIR__ . '/../../config/db.php';
$conn = connect_db();

// Semak jika ia adalah permintaan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Jika bukan, hentikan skrip
    exit('Invalid request method.');
}

$action_success = false;
$error_message = '';

// Logik untuk Tambah Pengguna Baharu
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
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

$conn->close();

// Redirect kembali ke halaman pengurusan pengguna selepas selesai
header("Location: ../index.php?page=pengguna");
exit();
?>
