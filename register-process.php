<?php
include 'db-connect.php';

$userName = $_POST['userName'];
$userFullname = $_POST['userFullname'];
$userPass = $_POST['userPass'];
$userEmail = $_POST['userEmail'];
$userPosition = $_POST['userPosition'];
$userDepartment = $_POST['userDepartment'];
$userUnit = $_POST['userUnit'];

$sql = "INSERT INTO users (userName, userFullname, userPass, userEmail, userPosition, userDepartment, userUnit) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $userName, $userFullname, $userPass, $userEmail, $userPosition, $userDepartment, $userUnit);

if ($stmt->execute()) {
    header("Location: login.html?message=Aset berjaya ditambah");
} else {
    header("Location: login.html?error=Gagal untuk tambah aset");
}

$stmt->close();
$conn->close();
?>
