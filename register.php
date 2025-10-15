<?php

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

    <title>ICT BPSM - Akaun Baharu</title>
    <link rel="icon" href="ict1.jpg" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        /* Ensure dropdowns have a visible text color and background */
        select.form-control-user {
            color: #000; /* Text color */
            background-color: #fff; /* Background color */
            height: calc(2.25rem + 2px); /* Match height of input fields */
        }

        /* Optional: Change the focus style */
        select.form-control-user:focus {
            border-color: #4e73df; /* Change border color on focus */
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25); /* Focus shadow */
        }
    </style>

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-4">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Daftar Akaun Baharu</h1>
                    </div>
                    <form action="register-process.php" method="post">
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
                        <button type="submit" class="btn btn-primary btn-user btn-block">Daftar</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="login.html">Telah mempunyai akaun? Log masuk</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- JavaScript -->
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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
