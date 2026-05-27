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
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #ffffff;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            z-index: 1;
        }

        .sidebar .tabs {
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar .tabs button, .sidebar .tabs .dropdown-btn {
            padding: 15px;
            background-color: #ffffff;
            color: #333;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-align: left;
            transition: color 0.3s, background-color 0.3s;
            margin-bottom: 10px;
        }

        .sidebar .tabs button:hover, .sidebar .tabs button.active,
        .sidebar .tabs .dropdown-btn:hover {
            color: #007bff;
            background-color: #f0f2f5;
            border-left: 4px solid #007bff;
        }

        .sidebar .tabs .dropdown-container {
            display: none;
            padding-left: 20px;
            white-space: pre-line;
        }

        .sidebar .tabs .dropdown-container .dropdown-item {
            padding: 10px;
            font-size: 14px;
            background-color: #f0f2f5;
        }

        .sidebar .tabs .dropdown-container .dropdown-item:hover {
            background-color: #e9ecef;
        }

        .container {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
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
            .container {
                margin-left: 0;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar .tabs {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .sidebar .tabs button {
                flex: 1 1 auto;
                margin-bottom: 0;
            }

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
            background-color: rgba(0, 0, 0, 0.6);  /* Slightly less dark overlay */
            z-index: 500;
        }

        .overlay.show {
            display: block;
        }

        .popup-form.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <!-- <h2>Kategori / Isu</h2> -->
        <div class="tabs">
            <button class="dropdown-btn" data-tab="pentadbiran">Pendtadbiran <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-container">
                <button class="tab-link dropdown-item" data-tab="bil-1-2024">BIL 1/2024</button>
                <button class="tab-link dropdown-item" data-tab="2023">BIL 2/2024</button>
                <button class="tab-link dropdown-item" data-tab="2022">BIL 3/2024</button>
            </div>
            <button class="dropdown-btn" data-tab="kebajikan">Kebajikan <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-container">
                <button class="tab-link dropdown-item" data-tab="2024">2024</button>
                <button class="tab-link dropdown-item" data-tab="2023">2023</button>
                <button class="tab-link dropdown-item" data-tab="2022">2022</button>
            </div>
            <button class="dropdown-btn" data-tab="sumber-manusia">Sumber Manusia <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-container">
                <button class="tab-link dropdown-item" data-tab="2024">2024</button>
                <button class="tab-link dropdown-item" data-tab="2023">2023</button>
                <button class="tab-link dropdown-item" data-tab="2022">2022</button>
            </div>
            <button class="dropdown-btn" data-tab="kewangan">Kewangan dan Kemudahan <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-container">
                <button class="tab-link dropdown-item" data-tab="2024">2024</button>
                <button class="tab-link dropdown-item" data-tab="2023">2023</button>
                <button class="tab-link dropdown-item" data-tab="2022">2022</button>
            </div>
            <button class="dropdown-btn" data-tab="inovasi">Inovasi dan Kretiviti <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-container">
                <button class="tab-link dropdown-item" data-tab="2024">2024</button>
                <button class="tab-link dropdown-item" data-tab="2023">2023</button>
                <button class="tab-link dropdown-item" data-tab="2022">2022</button>
            </div>
            <button class="dropdown-btn" data-tab="lainlain">Lain-lain <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-container">
                <button class="tab-link dropdown-item" data-tab="2024">2024</button>
                <button class="tab-link dropdown-item" data-tab="2023">2023</button>
                <button class="tab-link dropdown-item" data-tab="2022">2022</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content">
            <h1>MAKLUM BALAS MESYUARAT MBJ BPSM BIL 1/2024</h1>

            <div id="pentadbiran" class="tab-content active">
                <h2>PENTADBIRAN</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'pentadbiran'); ?>
                    </tbody>
                </table>
            </div>

            <div id="bil-1-2024" class="tab-content">
                <h2>PENGAMBILAN</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'pentadbiran'); ?>
                    </tbody>
                </table>
            </div>

            <div id="latihan" class="tab-content">
                <h2>LATIHAN</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'latihan'); ?>
                    </tbody>
                </table>
            </div>

            <div id="penilaian" class="tab-content">
                <h2>PENILAIAN</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'penilaian'); ?>
                    </tbody>
                </table>
            </div>

            <div id="struktur" class="tab-content">
                <h2>STRUKTUR</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'struktur'); ?>
                    </tbody>
                </table>
            </div>

            <div id="fungsi" class="tab-content">
                <h2>FUNGSI</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'fungsi'); ?>
                    </tbody>
                </table>
            </div>

            <div id="keselamatan-fizikal" class="tab-content">
                <h2>KESELAMATAN FIZIKAL</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'keselamatan-fizikal'); ?>
                    </tbody>
                </table>
            </div>

            <div id="keselamatan-data" class="tab-content">
                <h2>KESELAMATAN DATA</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Para</th>
                            <th>Tajuk</th>
                            <th>Tindakan</th>
                            <th>Maklum Balas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php fetch_data($conn, 'keselamatan-data'); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="hidden-btn" id="hidden-btn">Add Feedback</div>
    </div>

    <div class="to-top" id="toTopBtn">
        <i class="fas fa-chevron-up"></i>
    </div>

    <div class="overlay"></div>

    <div class="popup-form" id="popupForm">
        <form action="your_php_file.php" method="post">
            <h2>Form Title</h2>
            <select name="category" required>
                <option value="" disabled selected>Select Category</option>
                <option value="pentadbiran">Pentadbiran</option>
                <option value="sumber-manusia">Sumber Manusia</option>
                <option value="pengurusan-organisasi">Pengurusan Organisasi</option>
                <option value="pengurusan-keselamatan">Pengurusan Keselamatan</option>
            </select>
            <input type="text" name="para" placeholder="Para" required>
            <textarea name="tajuk" rows="5" placeholder="Tajuk" required></textarea>
            <input type="text" name="tindakan" placeholder="Tindakan" required>
            <textarea name="maklum" rows="5" placeholder="Maklum" required></textarea>
            <button type="submit">Submit</button>
            <button type="button" class="close-btn" id="closePopup">Close</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.tab-link').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                let tab = button.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                document.getElementById(tab).classList.add('active');
            });
        });

        let toTopBtn = document.getElementById('toTopBtn');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 100) {
                toTopBtn.classList.add('show');
            } else {
                toTopBtn.classList.remove('show');
            }
        });

        toTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        const openPopup = document.querySelectorAll('.hidden-btn');
        const closePopup = document.getElementById('closePopup');
        const popupForm = document.getElementById('popupForm');
        const overlay = document.querySelector('.overlay');

        openPopup.forEach(button => {
            button.addEventListener('click', () => {
                popupForm.classList.add('show');
                overlay.classList.add('show');
            });
        });

        closePopup.addEventListener('click', () => {
            popupForm.classList.remove('show');
            overlay.classList.remove('show');
        });

        overlay.addEventListener('click', () => {
            popupForm.classList.remove('show');
            overlay.classList.remove('show');
        });

        document.querySelectorAll('.dropdown-btn').forEach(button => {
            button.addEventListener('click', () => {
                button.classList.toggle('active');
                const dropdownContent = button.nextElementSibling;
                if (dropdownContent.style.display === 'block') {
                    dropdownContent.style.display = 'none';
                } else {
                    dropdownContent.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
