<?php
// update.php
session_start();

// Authentication guard — only a logged-in MBJ admin session may update records.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit();
}

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
    $encoded   = urlencode($backUrl);}
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Maklum Balas MBJ</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
        }


        h2 {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 26px;
            margin-top: 0px;
            margin-bottom: 20px;
            color: #2980b9;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            margin-top: 10px;
            margin-bottom: 10px;
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

        .cancel-button {
            background-color: gray;
        }

        .cancel-button:hover {
            background-color: #c82333;
        }

        form label {
            margin: 10px 0 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    // Reuse the single $conn (database `bpsm`) from db.local.php above.
    // Previously this block opened a SECOND connection to a DIFFERENT database
    // (`mbj_feedback`), so update.php wrote to a different table than
    // create.php / delete.php / dashboard.php read from — a data inconsistency.

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = (int) $_POST['id'];
        $bil = $_POST['bil'];
        $isu = $_POST['isu'];
        $tindakan = $_POST['tindakan'];
        $category = $_POST['category'];

        $stmt = $conn->prepare("UPDATE mbj SET bil = ?, isu = ?, tindakan = ?, category = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $bil, $isu, $tindakan, $category, $id);
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error updating record.";
        }
        $stmt->close();
    } else {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $stmt = $conn->prepare("SELECT bil, isu, tindakan, category FROM mbj WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            ?>
            <form method="post" action="update.php">
                <h2>Kemaskini Maklum Balas</h2>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="category">Kategori</label>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="pentadbiran" <?php if ($row['category'] == 'pentadbiran') echo 'selected'; ?>>Pentadbiran</option>
                    <option value="kebajikan" <?php if ($row['category'] == 'kebajikan') echo 'selected'; ?>>Kebajikan</option>
                    <option value="sumbermanusia" <?php if ($row['category'] == 'sumbermanusia') echo 'selected'; ?>>Sumber Manusia</option>
                    <option value="kewangan" <?php if ($row['category'] == 'kewangan') echo 'selected'; ?>>Kewangan dan Kemudahan</option>
                    <option value="inovasi" <?php if ($row['category'] == 'inovasi') echo 'selected'; ?>>Inovasi dan Kreativiti</option>
                    <option value="lainlain" <?php if ($row['category'] == 'lainlain') echo 'selected'; ?>>Lain-lain</option>
                </select><br>
                <label>Bil:</label>
                <input type="text" name="bil" value="<?php echo $row['bil']; ?>"><br>
                <label>Isu:</label>
                <textarea name="isu"><?php echo $row['isu']; ?></textarea><br>
                <label>Tindakan:</label>
                <textarea name="tindakan"><?php echo $row['tindakan']; ?></textarea><br>
                <button type="submit">Kemaskini</button>
                <button type="button" class="cancel-button" onclick="window.location.href='dashboard.php'">Batal</button>
            </form>
            <?php
        } else {
            echo "Tiada Rekod";
        }
    }
    ?>
    <script>
        CKEDITOR.replace('isu');
        CKEDITOR.replace('tindakan');
    </script>
</body>
</html>
