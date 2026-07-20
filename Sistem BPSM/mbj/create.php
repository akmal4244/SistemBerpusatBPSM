<?php
// create.php
session_start();

// Authentication guard — only a logged-in MBJ admin session may create records.
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
    

// Prepare and bind the form data
$stmt = $conn->prepare("INSERT INTO mbj (category, bil, isu, tindakan) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Set parameters and execute
$category = $_POST['category'];
$bil = $_POST['bil'];
$isu = $_POST['isu'];
$tindakan = $_POST['tindakan'];

// Bind parameters
if (!$stmt->bind_param("ssss", $category, $bil, $isu, $tindakan)) {
    die("Error binding parameters: " . $stmt->error);
}

// Execute the statement
if (!$stmt->execute()) {
    die("Error executing the statement: " . $stmt->error);
}

// Redirect to mbj.php after successful insertion
header("Location: dashboard.php");
exit;

$stmt->close();
$conn->close();
?>
