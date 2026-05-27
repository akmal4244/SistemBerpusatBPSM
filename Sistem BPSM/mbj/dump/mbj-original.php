<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbj_feedback";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetch_data($conn, $category) {
    $sql = "SELECT bil, isu, tindakan, maklum_balas FROM mbj WHERE category='$category'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['bil']}</td>
                    <td>{$row['isu']}</td>
                    <td>{$row['tindakan']}</td>
                    <td>{$row['maklum_balas']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'></td></tr>";
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
            margin: 0 20px;
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

        .hidden-btn {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
            color: #ffffff;
            cursor: pointer;
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

        .popup-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        .popup-form input, .popup-form select, .popup-form textarea {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.2s;
            font-family: 'Open Sans', Arial, sans-serif;
            font-size: 14px;
            box-sizing: border-box;
        }

        .popup-form input:focus, .popup-form select:focus, .popup-form textarea:focus {
            border-color: #80bdff;
            outline: none;
        }

        .popup-form button {
            width: 100%;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
            font-family: 'Open Sans', Arial, sans-serif;
            font-size: 16px;
        }

        .popup-form button:hover {
            background-color: #0056b3;
        }

        .popup-form .close-btn {
            background-color: #dc3545;
        }

        .popup-form .close-btn:hover {
            background-color: #c82333;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);  /* Slightly darker overlay */
            z-index: 500;
            transition: opacity 0.3s ease-in-out;  /* Smooth transition */
        }

        .overlay.show, .popup-form.show {
            display: block;
            opacity: 1;
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
            <h1>MAKLUM BALAS MINIT MESYUARAT<br>
            MAJLIS BERSAMA JABATAN (MBJ)<br>
            BAHAGIAN PENGURUSAN SUMBER MANUSIA (BPSM)<br>
            BILANGAN 2 TAHUN 2023 BERTARIKH 9 NOVEMBER 2023</h1>

            <div id="pentadbiran" class="tab-content active">
                <h2>Pentadbiran</h2>
                <table>
                    <tr>
                        <th>PARA</th>
                        <th>TAJUK / PERKARA</th>
                        <th>TINDAKAN</th>
                        <th>MAKLUM BALAS</th>
                    </tr>
                    <?php fetch_data($conn, 'pentadbiran'); ?>
                </table>
            </div>

            <div id="kebajikan" class="tab-content">
                <h2>Kebajikan</h2>
                <table>
                    <tr>
                        <th>PARA</th>
                        <th>TAJUK / PERKARA</th>
                        <th>TINDAKAN</th>
                        <th>MAKLUM BALAS</th>
                    </tr>
                    <?php fetch_data($conn, 'kebajikan'); ?>
                </table>
            </div>

            <div id="sumbermanusia" class="tab-content">
                <h2>Sumber Manusia</h2>
                <table>
                    <tr>
                        <th>PARA</th>
                        <th>TAJUK / PERKARA</th>
                        <th>TINDAKAN</th>
                        <th>MAKLUM BALAS</th>
                    </tr>
                    <?php fetch_data($conn, 'sumbermanusia'); ?>
                </table>
            </div>

            <div id="kewangan" class="tab-content">
                <h2>Kewangan dan Kemudahan</h2>
                <table>
                    <tr>
                        <th>PARA</th>
                        <th>TAJUK / PERKARA</th>
                        <th>TINDAKAN</th>
                        <th>MAKLUM BALAS</th>
                    </tr>
                    <?php fetch_data($conn, 'kewangan'); ?>
                </table>
            </div>

            <div id="inovasi" class="tab-content">
                <h2>Inovasi dan Kreativiti</h2>
                <table>
                    <tr>
                        <th>PARA</th>
                        <th>TAJUK / PERKARA</th>
                        <th>TINDAKAN</th>
                        <th>MAKLUM BALAS</th>
                    </tr>
                    <?php fetch_data($conn, 'inovasi'); ?>
                </table>
            </div>

            <div id="lainlain" class="tab-content">
                <h2>Lain-lain</h2>
                <table>
                    <tr>
                        <th>PARA</th>
                        <th>TAJUK / PERKARA</th>
                        <th>TINDAKAN</th>
                        <th>MAKLUM BALAS</th>
                    </tr>
                    <?php fetch_data($conn, 'lainlain'); ?>
                </table>
            </div>
        </div>
        <div class="hidden-btn" id="hidden-btn">Add Feedback</div>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="popup-form" id="popup-form">
        <form action="mbj_feedback.php" method="post">
            <select name="category" required>
                <option value="" disabled selected>Pilih Kategori</option>
                <option value="pentadbiran">Pentadbiran</option>
                <option value="kebajikan">Kebajikan</option>
                <option value="sumbermanusia">Sumber Manusia</option>
                <option value="kewangan">Kewangan dan Kemudahan</option>
                <option value="inovasi">Inovasi dan Kreativiti</option>
                <option value="lainlain">Lain-lain</option>
            </select><br>
            <input type="text" name="para" placeholder="Para" required><br>
            <input type="textarea" name="tajuk" placeholder="Tajuk" required><br>
            <input type="textarea" name="tindakan" placeholder="Tindakan" required><br>
            <input type="textarea" name="maklum" placeholder="Maklum Balas" required><br>
            <button type="submit">Hantar</button>
        </form>
        <button class="close-btn" id="close-btn">Tutup</button>
    </div>

    <div class="to-top" id="to-top"><i class="fa fa-arrow-up"></i></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let tabLinks = document.querySelectorAll('.tab-link');
            let tabContents = document.querySelectorAll('.tab-content');
            let toTopBtn = document.getElementById('to-top');

            tabLinks.forEach(function(tabLink) {
                tabLink.addEventListener('click', function() {
                    tabLinks.forEach(function(link) {
                        link.classList.remove('active');
                    });
                    tabLink.classList.add('active');

                    tabContents.forEach(function(content) {
                        content.classList.remove('active');
                    });
                    let tab = tabLink.getAttribute('data-tab');
                    document.getElementById(tab).classList.add('active');
                });
            });

            window.addEventListener('scroll', function() {
                if (window.scrollY > 200) {
                    toTopBtn.classList.add('show');
                } else {
                    toTopBtn.classList.remove('show');
                }
            });

            toTopBtn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            const overlay = document.getElementById('overlay');
            const popupForm = document.getElementById('popup-form');
            const closeBtn = document.getElementById('close-btn');
            const hiddenBtn = document.getElementById('hidden-btn');

            hiddenBtn.addEventListener('click', function() {
                overlay.style.display = 'block';
                popupForm.style.display = 'block';
            });

            closeBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
                popupForm.style.display = 'none';
            });

            overlay.addEventListener('click', function() {
                overlay.style.display = 'none';
                popupForm.style.display = 'none';
            });
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>