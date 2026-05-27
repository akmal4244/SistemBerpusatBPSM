<?php
// index.php
session_start();

// 1) Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName     = "bpsm";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 2) Helper: generate a new random token
function generateToken() {
    return bin2hex(random_bytes(32)); // 64 hex chars
}

// 3) If no token in the URL, show the “login via BPSM SSO” button
if (!isset($_GET['token'])) {
    $backUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?token=';
    $encoded = urlencode($backUrl);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <title>MBJ BPSM – Log Masuk</title>
      <style>
        body {
          display: flex;
          align-items: center;
          justify-content: center;
          height: 100vh;
          background: #f0f2f5;
          font-family: Arial, sans-serif;
        }
        .button {
          background: #007bff;
          color: #fff;
          padding: 12px 24px;
          border-radius: 4px;
          text-decoration: none;
          font-weight: bold;
        }
        .button:hover { background: #0056b3; }
      </style>
    </head>
    <body>
      <div style="text-align:center;">
        <h2>Log Masuk ke MBJ BPSM via SSO</h2>
        <p>Klik butang di bawah untuk log masuk melalui Sistem Pengurusan BPSM.</p>
        <a 
          href="http://10.22.28.183/bpsm/login?redirect=<?php echo $encoded; ?>" 
          class="button"
        >
          Log Masuk via BPSM SSO
        </a>
      </div>
    </body>
    </html>
    <?php
    exit();
}

// 4) Token exists → process it
$incomingToken = trim($_GET['token']);

// Try token lookup in DB
$sql  = "SELECT * FROM personal_access_tokens WHERE token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $incomingToken);
$stmt->execute();
$res = $stmt->get_result();

$user = null;
if ($res && $res->num_rows === 1) {
    $tokenRow = $res->fetch_assoc();
    $userId   = $tokenRow['tokenable_id'];

    $uSql  = "SELECT * FROM users WHERE id = ?";
    $uStmt = $conn->prepare($uSql);
    $uStmt->bind_param("i", $userId);
    $uStmt->execute();
    $uRes = $uStmt->get_result();
    if (! $uRes || $uRes->num_rows !== 1) {
        echo "User not found!";
        exit();
    }
    $user = $uRes->fetch_assoc();

    // Rotate the token
    $brandNew = generateToken();
    $updSql   = "UPDATE personal_access_tokens SET token = ?, updated_at = NOW() WHERE tokenable_id = ?";
    $updStmt  = $conn->prepare($updSql);
    $updStmt->bind_param("si", $brandNew, $userId);
    $updStmt->execute();
}
else {
    // Decode base64 fallback
    $decodedPayload = @json_decode(base64_decode($incomingToken), true);
    if (! $decodedPayload || ! isset($decodedPayload['id'])) {
        echo "Invalid token format!";
        exit();
    }
    $userId = intval($decodedPayload['id']);

    $uSql  = "SELECT * FROM users WHERE id = ?";
    $uStmt = $conn->prepare($uSql);
    $uStmt->bind_param("i", $userId);
    $uStmt->execute();
    $uRes = $uStmt->get_result();
    if (! $uRes || $uRes->num_rows !== 1) {
        echo "User not found!";
        exit();
    }
    $user = $uRes->fetch_assoc();

    // Create a new token
    $newTok  = generateToken();
    $insSql  = "INSERT INTO personal_access_tokens (tokenable_id, token, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
    $insStmt = $conn->prepare($insSql);
    $insStmt->bind_param("is", $userId, $newTok);
    $insStmt->execute();
}

// Extract “mbj” system role
$systems   = [];
$rawSystem = $user['system'];
if (!empty($rawSystem)) {
    $decoded = @json_decode($rawSystem, true);
    if (is_array($decoded)) {
        $systems = $decoded;
    }
}
$mbjRole = $systems['mbj'] ?? 'Pengguna';

// ✅ Enforce access: only Pentadbir allowed
if (strcasecmp($mbjRole, 'Pentadbir') !== 0) {
    // Not allowed → bounce
    header("Location: http://10.22.28.183/mbj/dashboard.php");
    exit();
}

// ✅ Store session
$_SESSION['loggedin'] = true;
$_SESSION['user_id']  = $user['id'];
$_SESSION['fullname'] = $user['Fullname'];
$_SESSION['email']    = $user['Email'];
$_SESSION['role']     = $mbjRole;

// ✅ Redirect to actual protected dashboard
header("Location: dashboard.php");
exit();

// Cleanup
if (isset($stmt))  { $stmt->close(); }
if (isset($uStmt)) { $uStmt->close(); }
$conn->close();
?>
