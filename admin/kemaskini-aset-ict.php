<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.html");  // Redirect to login page if not logged in
    exit();
}

// Check user type and redirect accordingly
if ($_SESSION['role'] === 'PENGGUNA') {
    header("Location: ../user/index.php");  // Redirect to user page for PENGGUNA
    exit();
}

include '../db-connect.php';

$asset_id = $_GET['id'] ?? 0;

// Fetch asset details from the database
$sql = "SELECT * FROM `ict-aset` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $asset_id);
$stmt->execute();
$result = $stmt->get_result();
$asset = $result->fetch_assoc();

// Include this at the top of kemaskini-aset-ict.php
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = '';
    $modalTitle = '';
    $modalType = '';

    if ($status == 'success') {
        $modalTitle = 'Berjaya';
        $message = 'Aset berjaya dikemaskini!!';
        $modalType = 'success';
    } elseif ($status == 'failed') {
        $modalTitle = 'Ralat';
        $message = 'Ralat mengemaskini aset, Sila cuba lagi.';
        $modalType = 'danger';
    } elseif ($status == 'validation_failed') {
        $modalTitle = 'Ralat Pengesahan';
        $message = 'Sila isi semua ruang yang diperlukan.';
        $modalType = 'warning';
    }
    
    echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modalMessage = '{$message}';
                var modalTitle = '{$modalTitle}';
                document.getElementById('modalBody').textContent = modalMessage;
                document.getElementById('modalTitle').textContent = modalTitle;
                $('#statusModal').modal('show');
            });
        </script>
    ";
}

date_default_timezone_set('Asia/Kuala_Lumpur');

$currentDateTime = date('d/m/Y h:i:s A');

$emp_id = isset($_SESSION['userFullname']) ? $_SESSION['userFullname'] : 'unknown';

$updatedInfo = "Tarikh: $currentDateTime  Dikemaskini oleh: $emp_id";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ICT BPSM - Kemaskini Aset</title>
    <link rel="icon" href="../ict1.jpg" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-laptop-house"></i>
                </div>
                <div class="sidebar-brand-text mx-3">ICT BPSM</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link" href="laman-graf.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Carta</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <p class="ml-3 my-auto text-gray-600 small">Anda sekarang berperanan sebagai <?php echo htmlspecialchars($_SESSION['role']); ?></p>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                          <div class="dashboard-link">
    <a href="http://10.22.28.183/bpsm/dashboard" style="text-decoration: none; color: inherit;">
        <i class="uil uil-estate"></i> Kembali ke Sistem Pengurusan BPSM
    </a>
</div>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Kemaskini Aset ICT</h1>
                    
                    <!-- Update Asset Form -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form action="kemaskini-aset-ict-proses.php" method="post">
                                <!-- Hidden input for asset ID -->
                                <input type="hidden" name="id" value="<?php echo $asset['id']; ?>">

                                <div class="form-group">
                                    <label for="name" class="text-gray-900">Nama Pengguna</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $asset['name']; ?>" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis" class="text-gray-900">Jenis Aset ICT</label>
                                    <select class="form-control" id="jenis" name="jenis" required>
                                        <option value="" disabled selected>--Pilih Jenis Aset--</option>
                                        <option value="KOMPUTER" <?php echo $asset['jenis'] == 'KOMPUTER' ? 'selected' : ''; ?>>KOMPUTER</option>
                                        <option value="LAPTOP" <?php echo $asset['jenis'] == 'LAPTOP' ? 'selected' : ''; ?>>LAPTOP</option>
                                        <option value="PRINTER" <?php echo $asset['jenis'] == 'PRINTER' ? 'selected' : ''; ?>>PRINTER</option>
                                        <option value="PROJEKTOR" <?php echo $asset['jenis'] == 'PROJEKTOR' ? 'selected' : ''; ?>>PROJEKTOR</option>
                                        <option value="BROADBAND" <?php echo $asset['jenis'] == 'BROADBAND' ? 'selected' : ''; ?>>BROADBAND</option>
                                        <option value="LAIN-LAIN" <?php echo $asset['jenis'] == 'LAIN-LAIN' ? 'selected' : ''; ?>>LAIN-LAIN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="cawangan" class="text-gray-900">Cawangan</label>
                                    <select class="form-control" id="cawangan" name="cawangan" required>
                                        <option value="" disabled selected>--Pilih Cawangan--</option>
                                        <option value="CAWANGAN PENTADBIRAN DAN KEWANGAN" <?php echo $asset['cawangan'] == 'CAWANGAN PENTADBIRAN DAN KEWANGAN' ? 'selected' : ''; ?>>CAWANGAN PENTADBIRAN DAN KEWANGAN</option>
                                        <option value="CAWANGAN DASAR" <?php echo $asset['cawangan'] == 'CAWANGAN DASAR' ? 'selected' : ''; ?>>CAWANGAN DASAR</option>
                                        <option value="CAWANGAN NAIK PANGKAT" <?php echo $asset['cawangan'] == 'CAWANGAN NAIK PANGKAT' ? 'selected' : ''; ?>>CAWANGAN NAIK PANGKAT</option>
                                        <option value="CAWANGAN PERKHIDMATAN" <?php echo $asset['cawangan'] == 'CAWANGAN PERKHIDMATAN' ? 'selected' : ''; ?>>CAWANGAN PERKHIDMATAN</option>
                                        <option value="CAWANGAN LATIHAN DAN KOMPETENSI" <?php echo $asset['cawangan'] == 'CAWANGAN LATIHAN DAN KOMPETENSI' ? 'selected' : ''; ?>>CAWANGAN LATIHAN DAN KOMPETENSI</option>
                                        <option value="CAWANGAN PEMBANGUNAN ORGANISASI" <?php echo $asset['cawangan'] == 'CAWANGAN PEMBANGUNAN ORGANISASI' ? 'selected' : ''; ?>>CAWANGAN PEMBANGUNAN ORGANISASI</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="penempatan" class="text-gray-900">Nombor Penempatan</label>
                                    <input type="text" class="form-control" id="penempatan" name="penempatan" value="<?php echo $asset['penempatan']; ?>" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="siri" class="text-gray-900">Nombor Siri</label>
                                    <input type="text" class="form-control" id="siri" name="siri" value="<?php echo $asset['siri']; ?>" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="pcname" class="text-gray-900">Nama PC</label>
                                    <input type="text" class="form-control" id="pcname" name="pcname" value="<?php echo $asset['pcname']; ?>" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="model" class="text-gray-900">Model</label>
                                    <input type="text" class="form-control" id="model" name="model" value="<?php echo $asset['model']; ?>" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="text-gray-900">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="" disabled selected>--Pilih Status--</option>
                                        <option value="BAHARU" <?php echo $asset['status'] == 'BAHARU' ? 'selected' : ''; ?>>BAHARU</option>
                                        <option value="DALAM KEADAAN BAIK" <?php echo $asset['status'] == 'DALAM KEADAAN BAIK' ? 'selected' : ''; ?>>DALAM KEADAAN BAIK</option>
                                        <option value="PERLU DIBAIKI" <?php echo $asset['status'] == 'PERLU DIBAIKI' ? 'selected' : ''; ?>>PERLU DIBAIKI</option>
                                        <!-- Add other statuses as needed -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pengguna" class="text-gray-900">Pengguna Sebelum</label>
                                    <input type="text" class="form-control" id="pengguna" name="pengguna" value="<?php echo $asset['pengguna']; ?>" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="date" class="text-gray-900">Tarikh Kemaskini</label>
                                    <input type="text" class="form-control" id="date" name="date" readonly value="<?php echo $updatedInfo; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Kemaskini Aset</button>
								<button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Kembali</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Hak Cipta &copy; ICT BPSM 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                    <a class="btn btn-primary" href="../login.html">Log Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Modal - Lepas Proses -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Content will be injected by PHP -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../js/script.js"></script>

</body>

</html>