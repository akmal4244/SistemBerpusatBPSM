<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.html");  // Redirect to login page if not logged in
    exit();
}

// Check user type and redirect accordingly
if ($_SESSION['userType'] === 'PENGGUNA') {
    header("Location: ../user/index.php");  // Redirect to user page for PENGGUNA
    exit();
}

date_default_timezone_set('Asia/Kuala_Lumpur');

$currentDateTime = date('d/m/Y h:i:s A');

$departments = [
    'CAWANGAN PENTADBIRAN DAN KEWANGAN' => 
    ['UNIT TEKNIKAL ICT', 
    'UNIT PERKHIDMATAN', 
    'UNIT PENTADBIRAN', 
    'UNIT PENGURUSAN REKOD', 
    'UNIT KEWANGAN, 
    PEROLEHAN DAN ASET'],
    
    'CAWANGAN DASAR' => 
    ['UNIT PERANCANGAN STRATEGIK SUMBER MANUSIA', 
    'UNIT PEMBANGUNAN DASAR INSTITUSI PENDIDIKAN', 
    'UNIT PEMBANGUNAN DASAR INSTITUSI PENDIDIKAN 2, ELAUN DAN KEMUDAHAN', 
    'UNIT PEMBANGUNAN DASAR SUMBER MANUSIA BUKAN GURU', 
    'UNIT TADBIR URUS DASAR SUMBER MANUSIA', 
    'UNIT PENYELARASAN DAN PENGURUSAN MAKLUMAT'],
    
    'CAWANGAN NAIK PANGKAT' => 
    ['UNIT NAIK PANGKAT SISWAZAH HAKIKI', 
    'UNIT NAIK PANGKAT SISWAZAH TIME-BASED BERASASKAN KECEMERLANGAN', 
    'UNIT NAIK PANGKAT SISWAZAH LALUAN CEMERLANG', 
    'UNIT NAIK PANGKAT BUKAN GURU', 
    'UNIT NAIK PANGKAT BUKAN SISWAZAH', 
    'UNIT LANTIKAN KHAS DAN LALUAN KERJAYA', 
    'UNIT PEMBANGUNAN KEPIMPINAN PENDIDIKAN'],
    
    'CAWANGAN PERKHIDMATAN' => 
    ['UNIT AMBILAN GURU', 
    'UNIT GURU', 'UNIT SARAAN, 
    KEMUDAHAN DAN HUBUNGAN KESATUAN', 
    'UNIT BUKAN GURU', 
    'UNIT BUKU PERKHIDMATAN DAN PELARASAN GAJI'],
    
    'CAWANGAN LATIHAN DAN KOMPETENSI' => 
    ['UNIT PERANCANGAN DASAR LATIHAN DAN KOMPETENSI', 
    'UNIT LATIHAN DALAM PERKHIDMATAN', 
    'UNIT INDUKSI', 
    'UNIT PENILAIAN KOMPETENSI', 
    'UNIT PEPERIKSAAN PERKHIDMATAN', 
    'UNIT PEMBANGUNAN PRESTASI'],
    
    'CAWANGAN PEMBANGUNAN ORGANISASI' => 
    ['UNIT PERANCANGAN PERJAWATAN INSTITUSI', 
    'UNIT ANGGARAN BELANJA MENGURUS', 
    'UNIT PENGUKUHAN PERJAWATAN', 
    'UNIT PENGURUSAN WARAN PERJAWATAN DAN INSPEKTORAT'],
    
    'PENGURUSAN KANAN' => 
    ['SUB', 
    'PA SUB', 
    'TSUBK P', 
    'PA TSUBK P', 
    'TSUBK O', 
    'PA TSUBK O', 
    'TSUB D', 
    'TSUB O', 
    'TSUB L', 
    'TSUB K', 
    'TSUB P', 
    'TSUB TW'],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ICT BPSM - Tambah Pengguna</title>
    <link rel="icon" href="../ict1.jpg" type="image/x-icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-laptop-house"></i>
                </div>
                <div class="sidebar-brand-text mx-3">ICT BPSM</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="laman-pengguna.php">
                    <i class="fas fa-users"></i>
                    <span>Pengguna</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="laman-graf.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Carta</span></a>
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
                    <p class="ml-3 my-auto text-gray-600 small">Anda sekarang berperanan sebagai <?php echo htmlspecialchars($_SESSION['userType']); ?></p>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['userFullname']); ?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="kemaskini-profil.php?id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Tambah Pengguna Baru</h1>
                    <div class="card shadow mb-4">
                        <div class="card-body"> 
                            <form action="tambah-pengguna-proses.php" method="post">
                                <div class="form-group">
                                    <label for="userName" class="text-gray-900">Kad Pengenalan</label>
                                    <input type="text" class="form-control" id="userName" name="userName" required>
                                </div>
                                <div class="form-group">
                                    <label for="userFullname" class="text-gray-900">Nama Penuh</label>
                                    <input type="text" class="form-control" id="userFullname" name="userFullname" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="userPass" class="text-gray-900">Kata Laluan</label>
                                    <input type="password" class="form-control" id="userPass" name="userPass" required>
                                </div>
                                <div class="form-group">
                                    <label for="userEmail" class="text-gray-900">E-mel</label>
                                    <input type="email" class="form-control" id="userEmail" name="userEmail" required>
                                </div>
                                <div class="form-group">
                                    <label for="userPosition" class="text-gray-900">Jawatan, Skim & Gred</label>
                                    <input type="text" class="form-control" id="userPosition" name="userPosition" oninput="toUpperCase(this)" required>
                                </div>
                                <div class="form-group">
                                    <label for="userDepartment" class="text-gray-900">Cawangan</label>
                                    <select class="form-control" id="userDepartment" name="userDepartment" required>
                                        <option value="" disabled selected>--Pilih Cawangan--</option>
                                        <?php foreach ($departments as $department => $units): ?>
                                            <option value="<?php echo htmlspecialchars($department); ?>"><?php echo htmlspecialchars($department); ?></option>
                                        <?php endforeach; ?> 
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userUnit" class="text-gray-900">Unit</label>
                                    <select class="form-control" id="userUnit" name="userUnit" required>
                                        <option value="" disabled selected>--Pilih Unit--</option>
                                        <!-- Units will be populated based on department selection -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="userType" class="text-gray-900">Peranan</label>
                                    <select class="form-control" id="userType" name="userType" required>
                                        <option value="" disabled selected>--Pilih Jenis Pengguna--</option>
                                        <option value="PENGGUNA">PENGGUNA</option>
                                        <option value="PENTADBIR">PENTADBIR</option>
                                    </select>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="userStatus">Status</label>
                                    <select class="form-control" id="userStatus" name="userStatus" required>
                                        <option value="">--Pilih Status--</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div> -->
                                <!-- <div class="form-group">
                                    <label for="userCreated">Tarikh Dicipta</label>
                                    <input type="text" class="form-control" id="userCreated" name="userCreated" readonly value="<?php echo $currentDateTime; ?>">
                                </div> -->
                                <button type="submit" class="btn btn-primary">Daftar</button>
								<button type="button" class="btn btn-secondary" onclick="window.location.href='laman-pengguna.php'">Kembali</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Hak Cipta &copy; ICT BPSM 2024</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script>
        function toUpperCase(element) {
            const cursorPosition = element.selectionStart;
            element.value = element.value.toUpperCase();
            element.setSelectionRange(cursorPosition, cursorPosition);
        }

        document.getElementById('userDepartment').addEventListener('change', function() {
            var department = this.value;
            var units = <?php echo json_encode($departments); ?>;
            var unitSelect = document.getElementById('userUnit');
            
            unitSelect.innerHTML = '<option value="">--Pilih Unit--</option>';
            
            if (department && units[department]) {
                units[department].forEach(function(unit) {
                    var option = document.createElement('option');
                    option.value = unit;
                    option.textContent = unit;
                    unitSelect.appendChild(option);
                });
            }
        });
    </script>
</body>
</html>
