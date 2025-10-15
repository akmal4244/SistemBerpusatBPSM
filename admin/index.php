<?php


session_start();

// 1) Must be logged in
if (! isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Not authenticated → send them back to the MBJ login entry point
    header("Location: /ictbpsm/index.php");
    exit();
}

// 2) Re‐fetch this user’s “system” JSON from the users table
//    (In case it changed since session start.)
$userId = intval($_SESSION['user_id']);
$conn   = new mysqli("localhost", "root", "", "bpsm");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT system FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if (! $result || $result->num_rows !== 1) {
    // User ID is bogus → force re‐login
    session_destroy();
    header("Location: /ictbpsm/index.php");
    exit();
}

$row       = $result->fetch_assoc();
$rawSystem = $row['system'];


// 3) Decode JSON (if invalid or empty, default to empty array)
$systems = [];
if (! empty($rawSystem)) {
    $decoded = @json_decode($rawSystem, true);
    if (is_array($decoded)) {
        $systems = $decoded;
    }
}

// 4) Check “MBJ” key. Only "Pentadbir" may proceed.
$ictbpsmRole = $systems['ICTBPSM'] ?? 'Pengguna';
if (strcasecmp($ictbpsmRole, 'Pentadbir') !== 0) {
    // Not a Pentadbir → deny access and halt
    echo "
      <h2 style='text-align:center; margin-top:50px;'>
        Akses Ditolak. Hanya Pentadbir SPAICT dibenarkan.
      </h2>
    ";
    exit();
}

// 7) Otherwise, render this dashboard (for Pengguna or any other role)
$total_komputer   = 0;
$total_laptop     = 0;
$total_printer    = 0;
$total_projektor  = 0;
$total_semua_aset = 0;
$total_lain_lain  = 0;

$sql = "
  SELECT
    `jenis`   AS jenis,
    COUNT(*)  AS total
  FROM
    `ict-aset`
  GROUP BY
    `jenis`
";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $count = (int)$row['total'];
        switch ($row['jenis']) {
            case 'KOMPUTER':
                $total_komputer = $count;
                break;
            case 'LAPTOP':
                $total_laptop = $count;
                break;
            case 'PRINTER':
                $total_printer = $count;
                break;
            case 'PROJEKTOR':
                $total_projektor = $count;
                break;
            default:
                $total_lain_lain += $count;
                break;
        }
        $total_semua_aset += $count;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ICT BPSM - Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
    rel="stylesheet"
  >
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css"
  >
  <style>
    table { font-size: 14px; }
    .wrap-text { max-width: 115px; word-wrap: break-word; white-space: normal; }
    .dashboard-link { margin-right: 1rem; }
  </style>
</head>
<body id="page-top">

  <div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon">
          <i class="fas fa-laptop-house"></i>
        </div>
        <div class="sidebar-brand-text mx-3">ICT BPSM</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="laman-graf.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Carta</span>
        </a>
      </li>
      <hr class="sidebar-divider d-none d-md-block">
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <div class="dashboard-link">
              <a href="http://10.22.28.183/bpsm/dashboard" style="text-decoration: none; color: inherit;">
                <i class="uil uil-estate"></i>
                Kembali ke Sistem Pengurusan BPSM
              </a>
            </div>
          </ul>
        </nav>

        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Senarai Aset ICT BPSM</h1>
            <a href="tambah-aset-ict.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fa fa-plus fa-sm text-white-50"></i> Tambah Aset
            </a>
          </div>

          <div class="row">
            <div class="col-xl-2 col-md-4 mb-4">
              <div class="card border-left-danger shadow h-100 py-2" onclick="filterTable('')" style="cursor:pointer;">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Semua Aset</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_semua_aset; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-th fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
              <div class="card border-left-primary shadow h-100 py-2" onclick="filterTable('KOMPUTER')" style="cursor:pointer;">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Komputer</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_komputer; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-desktop fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
              <div class="card border-left-success shadow h-100 py-2" onclick="filterTable('LAPTOP')" style="cursor:pointer;">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Laptop</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_laptop; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-laptop fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
              <div class="card border-left-info shadow h-100 py-2" onclick="filterTable('PRINTER')" style="cursor:pointer;">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Printer</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_printer; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-print fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
              <div class="card border-left-warning shadow h-100 py-2" onclick="filterTable('PROJEKTOR')" style="cursor:pointer;">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Projektor</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_projektor; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-hdd fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-4">
              <div class="card border-left-dark shadow h-100 py-2" onclick="filterTable('LAIN_LAIN')" style="cursor:pointer;">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Lain-lain</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_lain_lain; ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-box fa-2x text-gray-300"></i></div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <!-- End of Cards Row -->

          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered text-gray-900" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Bil</th>
                      <th>Nama Pengguna</th>
                      <th>Jenis Aset ICT</th>
                      <th>Cawangan</th>
                      <th class="wrap-text">Nombor Penempatan</th>
                      <th>Nombor Siri</th>
                      <th>Nama PC</th>
                      <th>Model</th>
                      <th>Status</th>
                      <th>Pengguna Sebelum</th>
                      <th>Tindakan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    include '../db-connect.php';
                    $sql2 = "SELECT * FROM `ict-aset`";
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows > 0) {
                        $bil = 1;
                        while ($row = $result2->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$bil}</td>";
                            echo "<td><a href='lihat-aset-ict.php?id={$row['id']}'>{$row['name']}</a></td>";
                            echo "<td>{$row['jenis']}</td>";
                            echo "<td>{$row['cawangan']}</td>";
                            echo "<td class='wrap-text'>{$row['penempatan']}</td>";
                            echo "<td>{$row['siri']}</td>";
                            echo "<td>{$row['pcname']}</td>";
                            echo "<td>{$row['model']}</td>";
                            echo "<td>{$row['status']}</td>";
                            echo "<td>{$row['pengguna']}</td>";
                            echo "<td class='text-center'>
                                    <a href='kemaskini-aset-ict.php?id={$row['id']}' class='btn btn-primary btn-sm'>
                                      <i class='fas fa-edit'></i>
                                    </a>
                                    <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal' data-id='{$row['id']}'><i class='fas fa-trash-alt'></i></button>
                                  </td>";
                            echo "</tr>";
                            $bil++;
                        }
                    } else {
                        echo "<tr><td colspan='11' class='text-center'>No data found</td></tr>";
                    }
                    $conn->close();
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Hak Cipta &copy; ICT BPSM 2024</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
       aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Sahkan Log Keluar</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Anda pasti untuk log keluar?</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <a class="btn btn-primary" href="../logout.php">Log Keluar</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
       aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Sahkan Hapus Aset</h5>
          <button type="button" class="close" data-dismiss="modal"
                  aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Anda pasti untuk hapus aset ini?</div>
        <div class="modal-footer">
          <form action="hapus-aset-ict.php" method="POST">
            <input type="hidden" name="id" id="delete-id">
            <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/sb-admin-2.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

  <script>
    function filterTable(jenis) {
      var table = $('#dataTable').DataTable();

      if (jenis === 'LAIN_LAIN') {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
          var jenisCell = data[2];
          return (
            jenisCell !== 'KOMPUTER' &&
            jenisCell !== 'LAPTOP' &&
            jenisCell !== 'PRINTER' &&
            jenisCell !== 'PROJEKTOR'
          );
        });
      } else {
        $.fn.dataTable.ext.search.pop();
        table.column(2).search(jenis).draw();
        return;
      }

      table.draw();
    }

    $(document).ready(function() {
      $('#dataTable').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100],
        dom: 'lBfrtip',
        buttons: ['copy', 'excel', 'print']
      });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var id     = button.data('id');
      var modal  = $(this);
      modal.find('#delete-id').val(id);
    });
  </script>

</body>
</html>
