<?php
include '../db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $jenis = $_POST['jenis'];
    $cawangan = $_POST['cawangan'];
    $penempatan = $_POST['penempatan'];
    $siri = $_POST['siri'];
    $pcname = $_POST['pcname'];
    $model = $_POST['model'];
    $status = $_POST['status'];
    $pengguna = $_POST['pengguna'];
    $date = $_POST['date'];

    // Validate required fields (for security and form validation)
    if (!empty($id) && !empty($name) && !empty($jenis) && !empty($cawangan) && !empty($penempatan) && !empty($siri) && !empty($status)) {

        // Prepare an SQL UPDATE statement
        $sql = "UPDATE ict-aset SET 
                    name = ?, 
                    jenis = ?, 
                    cawangan = ?, 
                    penempatan = ?, 
                    siri = ?, 
                    pcname = ?, 
                    model = ?, 
                    status = ?, 
                    pengguna = ?, 
                    date = ? 
                WHERE id = ?";

        // Initialize prepared statement
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters to the SQL query
        $stmt->bind_param(
            "ssssssssssi", 
            $name, 
            $jenis, 
            $cawangan, 
            $penempatan, 
            $siri, 
            $pcname, 
            $model, 
            $status, 
            $pengguna, 
            $date, 
            $id
        );

        // Execute the prepared statement
        if ($stmt->execute()) {
            // On success, redirect with a success alert and the id in the query string
            header("Location: kemaskini-aset-ict.php?id=$id&status=success");
            exit();
        } else {
            // On failure, redirect with an error alert and the id in the query string
            header("Location: kemaskini-aset-ict.php?id=$id&status=failed");
            exit();
        }

        // Close the statement
        $stmt->close();
    } else {
        // If required fields are missing, redirect with an error message
        header("Location: kemaskini-aset-ict.php?id=$id&status=validation_failed");
        exit();
    }
} else {
    // If the form wasn't submitted via POST, redirect to the asset form with the id
    header("Location: kemaskini-aset-ict.php?id=$id");
    exit();
}

// Close the database connection
$conn->close();
?>
