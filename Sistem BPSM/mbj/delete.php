<?php
// delete.php
session_start();

// Authentication guard — only a logged-in MBJ admin session may delete records.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit();
}

// Database connection (credentials live in gitignored db.local.php)
require __DIR__ . '/db.local.php';

// 3) Helper: generate a new random token
function generateToken() {
    return bin2hex(random_bytes(32)); // 64 hex chars
}

// 4) If no token in the URL, show the “login via BPSM SSO” button
if (! isset($_GET['token'])) {
    // Build the URL that BPSM must redirect back to, with “?token=” appended
    $backUrl   = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?token=';
    $encoded   = urlencode($backUrl);}
    

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("DELETE FROM mbj WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting record.";
}
$stmt->close();
?>
