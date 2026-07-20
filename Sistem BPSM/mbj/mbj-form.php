<?php
// index.php
session_start();

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
    $encoded   = urlencode($backUrl);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklum Balas MBJ BPSM</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <style>
        .dashboard-link {
            text-align: right;
            margin-bottom: 15px;
            }
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #007bff;
            color: #ffffff;
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        /* “Kembali ke...” link styling */
        .dashboard-link {
            margin-bottom: 15px;
        }
        .dashboard-link a {
            text-decoration: none;
            color: inherit;
            font-weight: 600;
        }
        .dashboard-link i {
            margin-right: 6px;
        }

        .tabs {
            display: flex;
            justify-content: space-around;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .tabs button {
            flex: 1;
            padding: 15px 0;
            background-color: #ffffff;
            color: #333;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: color 0.3s, border-bottom 0.3s;
        }

        .tabs button:hover, .tabs button.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
            border-radius: 0px;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 26px;
            margin-top: 0px;
            margin-bottom: 20px;
            color: #2980b9;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
        }

        th {
            background-color: #2980b9;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        td {
            background-color: #f9f9fb;
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.3s;
            vertical-align: top;
        }

        tr:hover td {
            background-color: #e3f2fd;
        }

        tr:last-child td {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }

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
                top: 0;
                position: sticky;
            }
        }

        .to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-size: 20px;
            transition: opacity 0.3s;
            opacity: 0;
        }

        .to-top.show {
            opacity: 1;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form select, form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form select:focus, form input:focus, form textarea:focus, form button:focus {
            border-color: #007bff;
            outline: none;
        }

        form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #0056b3;
        }

        form label {
            margin: 10px 0 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php" class="sidebar-link">Laman Utama</a>
        <a href="#" class="sidebar-link active">Maklum Balas</a>
    </div>
    <div class="container">
        <!-- “Kembali ke Sistem Pengurusan BPSM” on the right side -->
        <div class="dashboard-link">
            <a href="http://10.22.28.183/bpsm/dashboard" target="_self">
                <i class="uil uil-estate"></i> Kembali ke Sistem Pengurusan BPSM
            </a>
        </div>

        <div class="content">
            <h2>Maklum Balas</h2>
            <form action="create.php" method="post">
                <label for="category">Kategori</label>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="pentadbiran">Pentadbiran</option>
                    <option value="kebajikan">Kebajikan</option>
                    <option value="sumbermanusia">Sumber Manusia</option>
                    <option value="kewangan">Kewangan dan Kemudahan</option>
                    <option value="inovasi">Inovasi dan Kreativiti</option>
                    <option value="lainlain">Lain-lain</option>
                </select>
                
                <label for="bil">Bil Mesyuarat</label>
                <input type="text" name="bil" id="bil" placeholder="" required>
                
                <label for="isu">Isu</label>
                <textarea name="isu" id="isu" rows="5" placeholder="" required></textarea>
                
                <label for="tindakan">Tindakan</label>
                <textarea name="tindakan" id="tindakan" rows="5" placeholder="" required></textarea>
                
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="to-top">
        <i class="fas fa-chevron-up"></i>
    </div>

    <script>
        CKEDITOR.replace('isu');
        CKEDITOR.replace('tindakan');

        window.addEventListener('scroll', () => {
            const toTop = document.querySelector('.to-top');
            if (window.pageYOffset > 100) {
                toTop.classList.add('show');
            } else {
                toTop.classList.remove('show');
            }
        });

        document.querySelector('.to-top').addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>
