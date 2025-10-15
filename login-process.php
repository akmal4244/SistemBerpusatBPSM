<?php
session_start();
require_once 'dbConnect.php'; // your DB connection

// Generate a secure random token
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Must have a ?token=... in the URL
if (! isset($_GET['token'])) {
    echo "Token parameter missing!";
    exit;
}

$incomingToken = trim($_GET['token']);

// 1) See if that token already exists in personal_access_tokens
$query = "SELECT * 
          FROM personal_access_tokens 
          WHERE token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $incomingToken);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Token was found: log the user in and immediately rotate the token
    $tokens = $result->fetch_assoc();
    $tokenable_id = $tokens['tokenable_id']; // this is users.id

    // 1a) Fetch the user record
    $uquery = "SELECT * 
               FROM users 
               WHERE id = ?";
    $ustmt = $conn->prepare($uquery);
    $ustmt->bind_param("i", $tokenable_id);
    $ustmt->execute();
    $urow = $ustmt->get_result();
    if ($urow->num_rows !== 1) {
        echo "User not found!";
        exit;
    }
    $user = $urow->fetch_assoc();

    // 1b) Rotate the token (optional but recommended)
    $newToken = generateToken();
    $updateSQL = "UPDATE personal_access_tokens 
                  SET token = ?, updated_at = NOW() 
                  WHERE tokenable_id = ?";
    $updatestmt = $conn->prepare($updateSQL);
    $updatestmt->bind_param("si", $newToken, $tokenable_id);
    $updatestmt->execute();

    // 1c) Extract the `system` JSON column and decode it
    //     In Laravel you did: protected $casts = ['system'=>'array'];
    //     Here we just do it manually in plain PHP:
    $rawSystemJson = $user['system']; 
    // If that column was NULL or empty, assume an empty array:
    $systems = [];
    if (! empty($rawSystemJson)) {
        $decoded = json_decode($rawSystemJson, true);
        if (is_array($decoded)) {
            $systems = $decoded;
        }
    }

    // 1d) Look up the “AsetICT” subtree, if it exists
    //     We expect something like: { "BPSM": "Superadmin", "AsetICT": "Pentadbir", … }
    $AsetICTRole = null;
    if (isset($systems['AsetICT'])) {
        $AsetICTRole = $systems['AsetICT']; // e.g. “Pengguna” or “Pentadbir”
    }

    // 1e) Start the session, store basic info
    $_SESSION['loggedin']  = true;
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['fullname']  = $user['Fullname'];
    $_SESSION['email']     = $user['Email'];

    // 1f) If the user has a AsetICT entry, use it; otherwise treat them as “Pengguna”
    if ($AsetICTRole === null) {
        // no explicit “AsetICT” key in their JSON → default to “Pengguna”
        $AsetICTRole = 'Pengguna';
    }
    $_SESSION['role'] = $AsetICTRole;

    // 1g) Redirect them inside AsetICT based on that role
    if (strcasecmp($AsetICTRole, 'Pengguna') === 0) {
        header("Location: laman-pengguna.php");
    }
    elseif (strcasecmp($AsetICTRole, 'Pentadbir') === 0) {
        header("Location: index.php");
    }
    else {
        // Unexpected value in system→AsetICT
        $_SESSION['error'] = "Invalid AsetICT role: " . htmlspecialchars($AsetICTRole);
        header("Location: error.php");
    }
    exit;
}
else {
    // Token not found in personal_access_tokens → we assume it was a “base64‐encoded JSON payload”?
    echo "Token not found! Generating new token...<br>";

    $decodedToken = json_decode(base64_decode($incomingToken), true);
    if (! $decodedToken || ! isset($decodedToken['id'])) {
        echo "Invalid token format!";
        exit;
    }
    $userId = intval($decodedToken['id']);

    // Fetch user record
    $uquery = "SELECT * 
               FROM users 
               WHERE id = ?";
    $ustmt = $conn->prepare($uquery);
    $ustmt->bind_param("i", $userId);
    $ustmt->execute();
    $urow = $ustmt->get_result();
    if ($urow->num_rows !== 1) {
        echo "User not found!";
        exit;
    }
    $user = $urow->fetch_assoc();

    // Generate and insert a brand‐new personal_access_tokens row
    $newToken = generateToken();
    $insertSQL = "INSERT INTO personal_access_tokens 
                  (tokenable_id, token, created_at, updated_at) 
                  VALUES (?, ?, NOW(), NOW())";
    $insertStmt = $conn->prepare($insertSQL);
    $insertStmt->bind_param("is", $userId, $newToken);
    $insertStmt->execute();

    // Now repeat the same decoding‐of‐users.system logic:
    $rawSystemJson = $user['system'];
    $systems = [];
    if (! empty($rawSystemJson)) {
        $decoded = json_decode($rawSystemJson, true);
        if (is_array($decoded)) {
            $systems = $decoded;
        }
    }
    $AsetICTRole = $systems['AsetICT'] ?? 'Pengguna';

    $_SESSION['loggedin']  = true;
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['fullname']  = $user['Fullname'];
    $_SESSION['email']     = $user['Email'];
    $_SESSION['role']      = $AsetICTRole;

    if (strcasecmp($AsetICTRole, 'Pengguna') === 0) {
        header("Location: laman-pengguna.php");
    }
    elseif (strcasecmp($AsetICTRole, 'Pentadbir') === 0) {
        header("Location: index.php");
    }
    else {
        $_SESSION['error'] = "Invalid AsetICT role: " . htmlspecialchars($AsetICTRole);
        header("Location: error.php");
    }
    exit;
}

// Clean up
if (isset($stmt))  { $stmt->close(); }
if (isset($ustmt)) { $ustmt->close(); }
$conn->close();
