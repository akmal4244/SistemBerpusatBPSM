<?php
// Filename: admin/actions/perjawatan_actions.php
// Fail ini mengendalikan semua logik untuk mengemas kini dan memadam data perjawatan.

// Pastikan sesi dimulakan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Keselamatan: Pastikan hanya admin yang log masuk boleh akses fail ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses tidak dibenarkan.");
}

require_once __DIR__ . '/../../config/db.php';

// Semak jika ada permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = connect_db();
    $action = $_POST['action'] ?? '';

    // Logik untuk KEMAS KINI rekod
    if ($action === 'update' && isset($_POST['fact_id'])) {
        $fact_id = $_POST['fact_id'];
        $bil_perjawatan = $_POST['bil_perjawatan'];
        $bil_pengisian = $_POST['bil_pengisian'];

        $sql = "UPDATE fact_perjawatan SET bil_perjawatan = ?, bil_pengisian = ? WHERE fact_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $bil_perjawatan, $bil_pengisian, $fact_id);

        if ($stmt->execute()) {
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Rekod berjaya dikemas kini.'];
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Gagal mengemas kini rekod: ' . $stmt->error];
        }
        $stmt->close();
    } 
    // Logik untuk PADAM rekod
    elseif ($action === 'delete' && isset($_POST['fact_id'])) {
        $fact_id = $_POST['fact_id'];

        $sql = "DELETE FROM fact_perjawatan WHERE fact_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $fact_id);

        if ($stmt->execute()) {
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Rekod berjaya dipadam.'];
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Gagal memadam rekod: ' . $stmt->error];
        }
        $stmt->close();
    }

    $conn->close();
}

// Selepas selesai, halakan admin kembali ke halaman urus data
header("Location: ../index.php?page=perjawatan");
exit();
?>
