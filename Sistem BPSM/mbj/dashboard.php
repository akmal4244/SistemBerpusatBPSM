<?php
// dashboard.php
session_start();

// 1) If not logged in, send back to index.php (the SSO entry point)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// 2) Connect to DB
$servername  = "localhost";
$dbUsername  = "root";
$dbPassword  = "";
$dbName      = "bpsm";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// 3) Re‐fetch this user’s “system” JSON from the users table
$userId = intval($_SESSION['user_id']);
$stmt   = $conn->prepare("SELECT system FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Something is wrong (user not found), force re‐login
    session_destroy();
    header("Location: index.php");
    exit();
}

$row       = $result->fetch_assoc();
$rawSystem = $row['system'];
$stmt->close();

// 4) Decode JSON into an array; if it isn’t valid JSON, default to empty array
$systems = [];
if (!empty($rawSystem)) {
    $decoded = @json_decode($rawSystem, true);
    if (is_array($decoded)) {
        $systems = $decoded;
    }
}

// 5) Check the “MBJ” key. If it’s not exactly “Pentadbir”, show access denied and exit.
$mbjRole = $systems['MBJ'] ?? 'Pengguna';

if (strcasecmp($mbjRole, 'Pentadbir') !== 0) {
    // Not a Pentadbir → deny access right here
    echo "
      <h2 style='text-align:center; margin-top:50px;'>
        Akses Ditolak. Hanya Pentadbir MBJ dibenarkan.
      </h2>
    ";
    exit();
}

function fetch_data($conn, $category) {
    $sql  = "SELECT bil, isu, tindakan, id FROM MBJ WHERE category = ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['bil']}</td>
                    <td>{$row['isu']}</td>
                    <td>{$row['tindakan']}</td>
                    <td class='tambahan-cell'>
                        <a href='update.php?id={$row['id']}'><i class='fas fa-edit'></i></a>
                        <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Anda yakin untuk hapus?\")'><i class='fas fa-trash-alt'></i></a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4' style='text-align:center;'>Tiada data.</td></tr>";
    }
    $stmt->close();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$loggedInUser = htmlspecialchars($_SESSION['fullname']);
$loggedInRole = htmlspecialchars($mbjRole);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Maklum Balas MBJ BPSM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Google Fonts + Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        crossorigin="anonymous" />
  <style>
    * { box-sizing: border-box; margin:0; padding:0; }
    body {
      font-family: 'Open Sans', Arial, sans-serif;
      background: #f0f2f5;
      color: #333;
      display: flex;
    }

    /* SIDEBAR */
    .sidebar {
      width: 250px;
      background: #fff;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      top:0; left:0;
    }
    .sidebar a {
      padding: 15px 20px;
      text-decoration: none;
      color: #333;
      font-weight: 600;
      transition: background 0.3s, color 0.3s;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background: #007bff;
      color: #fff;
    }

    /* MAIN CONTAINER */
    .container {
      margin-left: 250px;
      flex: 1;
      padding: 20px;
      min-height: 100vh;
    }

    /* HEADER: flex with space-between */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    .header-left h3 {
      font-size: 24px;
      color: #2980b9;
    }
    .header-left small {
      font-size: 14px;
      color: #555;
    }
    .dashboard-link a {
      text-decoration: none;
      color: inherit;
      font-weight: 600;
    }
    .dashboard-link i {
      margin-right: 6px;
    }

    /* TABS */
    .tabs {
      display: flex;
      justify-content: space-around;
      background: #fff;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .tabs button {
      flex: 1;
      padding: 15px 0;
      background: #fff;
      border: none;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      transition: color 0.3s, border-bottom 0.3s;
    }
    .tabs button:hover,
    .tabs button.active {
      color: #007bff;
      border-bottom: 3px solid #007bff;
    }

    /* CONTENT PANES */
    .content-pane {
      display: none;
      margin-top: 20px;
      background: #fff;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .content-pane.active {
      display: block;
    }
    .content-pane h2 {
      margin-bottom: 15px;
      color: #2980b9;
      text-align: center;
    }

    /* TABLE STYLING */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      padding: 12px 15px;
    }
    th {
      background: #2980b9;
      color: #fff;
      text-align: center;
    }
    td {
      background: #f9f9fb;
      border-bottom: 1px solid #e0e0e0;
      vertical-align: top;
      transition: background 0.3s;
    }
    tr:hover td {
      background: #e3f2fd;
    }
    tr:last-child td {
      border-bottom: none;
    }
    .tambahan-cell { text-align: center; }
    .tambahan-cell a {
      margin: 0 6px;
      color: #007bff;
      font-size: 18px;
    }
    .tambahan-cell a:hover {
      color: #0056b3;
    }

    /* TO-TOP BUTTON */
    .to-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 40px; height: 40px;
      background: #007bff;
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      cursor: pointer;
      font-size: 20px;
      opacity: 0;
      transition: opacity 0.3s;
    }
    .to-top.show {
      opacity: 1;
    }

    /* MOBILE (RESPONSIVE) */
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        box-shadow: none;
      }
      .container {
        margin-left: 0;
      }
      table, th, td {
        display: block;
        width: 100%;
      }
      th, td {
        box-sizing: border-box;
      }
      th {
        position: sticky;
        top: 0;
      }
    }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <a href="dashboard.php" class="active">Laman Utama</a>
    <a href="MBJ-form.php">Maklum Balas</a>
  </div>

  <!-- MAIN CONTENT -->
  <div class="container">

    <!-- HEADER: welcome text on left, dashboard link on right -->
    <div class="header">
      <div class="header-left">
        <h3>Selamat Datang, <?php echo $loggedInUser; ?></h3>
        <small>Peranan: <?php echo $loggedInRole; ?></small>
      </div>
      <div class="dashboard-link">
        <a href="http://10.22.28.183/bpsm/dashboard" target="_self">
          <i class="uil uil-estate"></i> Kembali ke Sistem Pengurusan BPSM
        </a>
      </div>
    </div>

    <!-- TABS NAV -->
    <div class="tabs">
      <button class="tab-btn active" data-tab="pentadbiran">Pentadbiran</button>
      <button class="tab-btn" data-tab="kebajikan">Kebajikan</button>
      <button class="tab-btn" data-tab="sumbermanusia">Sumber Manusia</button>
      <button class="tab-btn" data-tab="kewangan">Kewangan &amp; Kemudahan</button>
      <button class="tab-btn" data-tab="inovasi">Inovasi &amp; Kreativiti</button>
      <button class="tab-btn" data-tab="lainlain">Lain-lain</button>
    </div>

    <!-- TAB CONTENT: Pentadbiran -->
    <div id="pentadbiran" class="content-pane active">
      <h2>Pentadbiran</h2>
      <table>
        <thead>
          <tr>
            <th>Bil</th>
            <th>Isu</th>
            <th>Tindakan</th>
            <th>Tambahan</th>
          </tr>
        </thead>
        <tbody>
          <?php fetch_data($conn, 'pentadbiran'); ?>
        </tbody>
      </table>
    </div>

    <!-- TAB CONTENT: Kebajikan -->
    <div id="kebajikan" class="content-pane">
      <h2>Kebajikan</h2>
      <table>
        <thead>
          <tr>
            <th>Bil</th>
            <th>Isu</th>
            <th>Tindakan</th>
            <th>Tambahan</th>
          </tr>
        </thead>
        <tbody>
          <?php fetch_data($conn, 'kebajikan'); ?>
        </tbody>
      </table>
    </div>

    <!-- TAB CONTENT: Sumber Manusia -->
    <div id="sumbermanusia" class="content-pane">
      <h2>Sumber Manusia</h2>
      <table>
        <thead>
          <tr>
            <th>Bil</th>
            <th>Isu</th>
            <th>Tindakan</th>
            <th>Tambahan</th>
          </tr>
        </thead>
        <tbody>
          <?php fetch_data($conn, 'sumbermanusia'); ?>
        </tbody>
      </table>
    </div>

    <!-- TAB CONTENT: Kewangan -->
    <div id="kewangan" class="content-pane">
      <h2>Kewangan &amp; Kemudahan</h2>
      <table>
        <thead>
          <tr>
            <th>Bil</th>
            <th>Isu</th>
            <th>Tindakan</th>
            <th>Tambahan</th>
          </tr>
        </thead>
        <tbody>
          <?php fetch_data($conn, 'kewangan'); ?>
        </tbody>
      </table>
    </div>

    <!-- TAB CONTENT: Inovasi -->
    <div id="inovasi" class="content-pane">
      <h2>Inovasi &amp; Kreativiti</h2>
      <table>
        <thead>
          <tr>
            <th>Bil</th>
            <th>Isu</th>
            <th>Tindakan</th>
            <th>Tambahan</th>
          </tr>
        </thead>
        <tbody>
          <?php fetch_data($conn, 'inovasi'); ?>
        </tbody>
      </table>
    </div>

    <!-- TAB CONTENT: Lain-lain -->
    <div id="lainlain" class="content-pane">
      <h2>Lain-lain</h2>
      <table>
        <thead>
          <tr>
            <th>Bil</th>
            <th>Isu</th>
            <th>Tindakan</th>
            <th>Tambahan</th>
          </tr>
        </thead>
        <tbody>
          <?php fetch_data($conn, 'lainlain'); ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- TO-TOP BUTTON -->
  <div class="to-top"><i class="fas fa-chevron-up"></i></div>

  <script>
    // TAB SWITCHING LOGIC
    const tabButtons = document.querySelectorAll(".tab-btn");
    const contentPanes = document.querySelectorAll(".content-pane");
    tabButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        tabButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        const tabId = btn.dataset.tab;
        contentPanes.forEach(pane => {
          pane.classList.remove("active");
          if (pane.id === tabId) pane.classList.add("active");
        });
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    });

    // SCROLL-TO-TOP BUTTON
    const toTop = document.querySelector(".to-top");
    window.addEventListener("scroll", () => {
      if (window.pageYOffset > 100) {
        toTop.classList.add("show");
      } else {
        toTop.classList.remove("show");
      }
    });
    toTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  </script>

</body>
</html>
<?php
$conn->close();
?>
