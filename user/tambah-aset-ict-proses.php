<?php
include '../db-connect.php';

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

$sql = "INSERT INTO ict-aset (name, jenis, cawangan, penempatan, siri, pcname, model, status, pengguna, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssss", $name, $jenis, $cawangan, $penempatan, $siri, $pcname, $model, $status, $pengguna, $date);

if ($stmt->execute()) {
    header("Location: index.php?message=Aset berjaya ditambah");
} else {
    header("Location: index.php?error=Gagal untuk tambah aset");
}

$stmt->close();
$conn->close();
?>
