<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbj_feedback";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetch_data($conn, $category) {
    $sql = "SELECT bil, isu, tindakan, id FROM mbj WHERE category='$category'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
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
        echo "<tr><td colspan='4'></td></tr>";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.html");
    exit();
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
    <style>
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
            margin-top: 20px;
        }

        .content h1 {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .content h2 {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 26px;
            margin-top: 30px;
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
			text-align: justify;
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

        .tambahan-cell {
            text-align: center;
        }

        .tambahan-cell a {
            display: inline-block;
            margin: 0 5px;
            color: #007bff;
            font-size: 18px;
        }

        .tambahan-cell a:hover {
            color: #0056b3;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#" class="sidebar-link active">Laman Utama</a>
        <a href="mbj-form.php" class="sidebar-link">Maklum Balas</a>
        <a href="?logout=true" class="sidebar-link">Log Keluar</a>
    </div>
    <div class="container">
        <div class="tabs">
            <button class="tab-link active" data-tab="pentadbiran">Pentadbiran</button>
            <button class="tab-link" data-tab="kebajikan">Kebajikan</button>
            <button class="tab-link" data-tab="sumbermanusia">Sumber Manusia</button>
            <button class="tab-link" data-tab="kewangan">Kewangan dan Kemudahan</button>
            <button class="tab-link" data-tab="inovasi">Inovasi dan Kreativiti</button>
            <button class="tab-link" data-tab="lainlain">Lain-lain</button>
        </div>
        <div class="content">
            <h1>MAKLUM BALAS MINIT MESYUARAT<br>
            MAJLIS BERSAMA JABATAN (MBJ)<br>
            BAHAGIAN PENGURUSAN SUMBER MANUSIA (BPSM)</h1>

            <div id="pentadbiran" class="tab-content active">
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

            <div id="kebajikan" class="tab-content">
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

            <div id="sumbermanusia" class="tab-content">
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

            <div id="kewangan" class="tab-content">
                <h2>Kewangan dan Kemudahan</h2>
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

            <div id="inovasi" class="tab-content">
                <h2>Inovasi dan Kreativiti</h2>
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

            <div id="lainlain" class="tab-content">
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
    </div>

    <div class="to-top">
        <i class="fas fa-chevron-up"></i>
    </div>

    <script>
        document.querySelectorAll('.tab-link').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                document.getElementById(button.dataset.tab).classList.add('active');

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

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
