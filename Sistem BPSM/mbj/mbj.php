<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bpsm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetch_data($conn, $category, $order = 'created_at', $direction = 'DESC') {
    $sql = "SELECT bil, isu, tindakan, created_at FROM mbj WHERE category='$category' ORDER BY $order $direction";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr data-created_at='{$row['created_at']}'>
                    <td>{$row['bil']}</td>
                    <td>{$row['isu']}</td>
                    <td>{$row['tindakan']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'></td></tr>";
    }
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
        }

        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
/*            margin: 0 20px;*/
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
/*            margin-top: 20px;*/
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
            cursor: pointer;
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
            <!--<h1>MAKLUM BALAS MINIT MESYUARAT<br>
            MAJLIS BERSAMA JABATAN (MBJ)<br>
            BAHAGIAN PENGURUSAN SUMBER MANUSIA (BPSM)</h1>-->

            <div id="pentadbiran" class="tab-content active">
                <h2>Pentadbiran</h2>
                <table>
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Bil</th>
                            <th>Isu</th>
                            <th>Tindakan</th>
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
                            <th onclick="sortTable(0)">Bil</th>
                            <th>Isu</th>
                            <th>Tindakan</th>
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
                            <th onclick="sortTable(0)">Bil</th>
                            <th>Isu</th>
                            <th>Tindakan</th>
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
                            <th onclick="sortTable(0)">Bil</th>
                            <th>Isu</th>
                            <th>Tindakan</th>
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
                            <th onclick="sortTable(0)">Bil</th>
                            <th>Isu</th>
                            <th>Tindakan</th>
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
                            <th onclick="sortTable(0)">Bil</th>
                            <th>Isu</th>
                            <th>Tindakan</th>
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

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.querySelector('.tab-content.active table');
            switching = true;
            dir = "asc"; 
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</body>
</html>
